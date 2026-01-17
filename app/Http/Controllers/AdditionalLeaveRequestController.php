<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLeave;
use App\Models\AdditionalLeaveRequest;
use App\Models\Employee;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class AdditionalLeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = AdditionalLeaveRequest::with('periods')->latest();

        // 🔍 PENCARIAN NAMA / NIP / NO SURAT
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('employee_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('nip', 'like', '%' . $request->keyword . '%')
                    ->orWhere('letter_number', 'like', '%' . $request->keyword . '%');
            });
        }

        // 📅 FILTER PERIODE CUTI
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereHas('periods', function ($q) use ($request) {
                $q->whereDate('start_date', '>=', $request->start_date)
                    ->whereDate('end_date', '<=', $request->end_date);
            });
        }

        $requests = $query->get();

        return view('additional_leave_requests.index', compact('requests'));
    }


    public function create()
    {
        $employees = Employee::where('is_active', true)->get();
        $workUnits = WorkUnit::orderBy('work_unit')->get();

        return view(
            'additional_leave_requests.create',
            compact('employees', 'workUnits')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required',
            'nip'           => 'required',
            'position'      => 'required',
            'length_of_service' => 'required',
            'work_unit_id' => 'required|exists:work_units,id',
            'leave_reason'  => 'required',
            'phone'         => 'required',
            'leave_address' => 'required',

            'periods'                  => 'required|array',
            'periods.*.start_date'     => 'nullable|date',
            'periods.*.end_date'       => 'nullable|date|after_or_equal:periods.*.start_date',
        ]);

        $employeeId = auth()->user()->employee_id;
        $year       = date('Y');

        // 1️⃣ Ambil data kuota cuti
        $additionalLeave = AdditionalLeave::where('employee_id', $employeeId)
            ->where('year', $year)
            ->first();

        // ❌ BELUM PUNYA KUOTA
        if (!$additionalLeave) {
            return back()
                ->withInput()
                ->withErrors([
                    'quota' => 'Anda belum memiliki kuota cuti tambahan. Silakan hubungi admin.'
                ]);
        }

        // 2️⃣ Hitung total hari cuti (tanpa simpan dulu)
        $totalDays = 0;
        $validPeriodCount = 0;

        foreach ($request->periods as $period) {
            if (empty($period['start_date']) || empty($period['end_date'])) {
                continue;
            }

            $start = Carbon::parse($period['start_date']);
            $end   = Carbon::parse($period['end_date']);
            $days  = $start->diffInDays($end) + 1;

            $totalDays += $days;
            $validPeriodCount++;
        }

        if ($validPeriodCount === 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'periods' => 'Minimal harus mengisi satu periode cuti.'
                ]);
        }

        // ❌ KUOTA TIDAK CUKUP
        if ($additionalLeave->remaining_quota < $totalDays) {
            return back()
                ->withInput()
                ->withErrors([
                    'quota' => "Kuota cuti tidak mencukupi. Sisa kuota: {$additionalLeave->remaining_quota} hari."
                ]);
        }

        // 3️⃣ SEMUA LOLOS → SIMPAN DATA
        DB::transaction(function () use ($request, $employeeId, $year, $totalDays) {

            // Simpan pengajuan cuti
            $leaveRequest = AdditionalLeaveRequest::create([
                'employee_id'       => $employeeId,
                'employee_name'     => $request->employee_name,
                'nip'               => $request->nip,
                'position'          => $request->position,
                'length_of_service' => $request->length_of_service,
                'work_unit_id'      => $request->work_unit_id,
                'leave_reason'      => $request->leave_reason,
                'phone'             => $request->phone,
                'leave_address'     => $request->leave_address,
                'letter_number'     => 'CUTI-' . date('Y') . '-' . Str::upper(Str::random(6)),
            ]);

            // Simpan periode cuti
            foreach ($request->periods as $period) {
                if (empty($period['start_date']) || empty($period['end_date'])) {
                    continue;
                }

                $start = Carbon::parse($period['start_date']);
                $end   = Carbon::parse($period['end_date']);
                $days  = $start->diffInDays($end) + 1;

                $leaveRequest->periods()->create([
                    'start_date' => $start,
                    'end_date'   => $end,
                    'total_days' => $days,
                ]);
            }

            // Potong kuota cuti
            AdditionalLeave::where('employee_id', $employeeId)
                ->where('year', $year)
                ->decrement('remaining_quota', $totalDays);
        });

        return redirect()
            ->route('additional-leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil disimpan');
    }

    public function show(AdditionalLeaveRequest $additionalLeaveRequest)
    {
        $additionalLeaveRequest->load('periods');

        return view('additional_leave_requests.show', compact('additionalLeaveRequest'));
    }

    public function edit(AdditionalLeaveRequest $additionalLeaveRequest)
    {
        return view('additional_leave_requests.edit', compact('additionalLeaveRequest'));
    }

    public function update(Request $request, AdditionalLeaveRequest $additionalLeaveRequest)
    {
        $request->validate([
            'leave_reason' => 'required',
            'phone'        => 'required',
            'leave_address' => 'required',

            'periods'                  => 'required|array|min:1',
            'periods.*.start_date'     => 'required|date',
            'periods.*.end_date'       => 'required|date|after_or_equal:periods.*.start_date',
        ]);

        DB::transaction(function () use ($request, $additionalLeaveRequest) {

            // 1. Update data utama
            $additionalLeaveRequest->update([
                'leave_reason'  => $request->leave_reason,
                'phone'         => $request->phone,
                'leave_address' => $request->leave_address,
            ]);

            $totalDays = 0;

            // 2. Update periode cuti
            foreach ($request->periods as $periodData) {

                $start = Carbon::parse($periodData['start_date']);
                $end   = Carbon::parse($periodData['end_date']);
                $days  = $start->diffInDays($end) + 1;

                $totalDays += $days;

                // update periode lama
                $additionalLeaveRequest->periods()
                    ->where('id', $periodData['id'])
                    ->update([
                        'start_date' => $start,
                        'end_date'   => $end,
                        'total_days' => $days,
                    ]);
            }

            // 3. Update total hari cuti (jika kolom ada)
            if (Schema::hasColumn('additional_leave_requests', 'total_leave_days')) {
                $additionalLeaveRequest->update([
                    'total_leave_days' => $totalDays
                ]);
            }
        });

        return redirect()
            ->route('additional-leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui');
    }

    public function destroy(AdditionalLeaveRequest $additionalLeaveRequest)
    {
        DB::transaction(function () use ($additionalLeaveRequest) {

            // 1. Total hari cuti yang harus dikembalikan
            $totalDays = $additionalLeaveRequest->periods->sum('total_days');

            // 2. Ambil kuota cuti pegawai (berdasarkan tahun berjalan)
            $year = now()->year;

            $additionalLeave = AdditionalLeave::where('employee_id', $additionalLeaveRequest->employee_id)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            // 3. Kembalikan kuota cuti
            if ($additionalLeave) {
                $additionalLeave->remaining_quota += $totalDays;
                $additionalLeave->save();
            }

            // 4. Hapus periode cuti
            $additionalLeaveRequest->periods()->delete();

            // 5. Hapus pengajuan cuti
            $additionalLeaveRequest->delete();
        });

        return redirect()
            ->route('additional-leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus dan kuota cuti dikembalikan');
    }

    // public function print(AdditionalLeaveRequest $additionalLeaveRequest)
    // {
    //     // eager load biar aman
    //     $additionalLeaveRequest->load('periods');

    //     $pdf = Pdf::loadView(
    //         'additional_leave_requests.pdf',
    //         [
    //             'request' => $additionalLeaveRequest
    //         ]
    //     )->setPaper('A4', 'portrait');

    //     return $pdf->stream(
    //         'Pengajuan-Cuti-' . $additionalLeaveRequest->letter_number . '.pdf'
    //     );
    // }

    public function print(AdditionalLeaveRequest $additionalLeaveRequest)
    {
        $additionalLeaveRequest->load([
            'periods',
            'employee.additionalLeaves' => function ($q) {
                $q->where('year', date('Y'));
            }
        ]);

        $additionalLeave = $additionalLeaveRequest
            ->employee
            ->additionalLeaves
            ->first(); // ambil kuota tahun aktif

        $pdf = Pdf::loadView(
            'additional_leave_requests.pdf',
            [
                'request' => $additionalLeaveRequest,
                'additionalLeave' => $additionalLeave
            ]
        )->setPaper('A4', 'portrait');

        return $pdf->stream(
            'Pengajuan-Cuti-' . $additionalLeaveRequest->letter_number . '.pdf'
        );
    }
}
