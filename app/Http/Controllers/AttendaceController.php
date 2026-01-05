<?php

namespace App\Http\Controllers;

use App\Models\Attendace;
use App\Models\OfficeLocation;
use App\Models\WorkShift;
use App\Services\GeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttendaceController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required|in:DATANG,PULANG',
    //         'work_shift_id' => 'required',
    //         'latitude' => 'required',
    //         'longitude' => 'required',
    //         'photo' => 'required|image'
    //     ]);

    //     $office = OfficeLocation::first();

    //     $distance = GeoService::distanceMeter(
    //         $request->latitude,
    //         $request->longitude,
    //         $office->latitude,
    //         $office->longitude
    //     );

    //     if ($distance > $office->radius_meter) {
    //         return response()->json([
    //             'message' => 'Anda berada di luar radius kantor'
    //         ], 403);
    //     }

    //     $path = $request->file('photo')->store('attendance_photos', 'public');

    //     Attendace::create([
    //         'employee_id' => auth()->user()->employee_id,
    //         'attendance_date' => now()->toDateString(),
    //         'attendance_time' => now(),
    //         'type' => $request->type,
    //         'work_shift_id' => $request->work_shift_id,
    //         'latitude' => $request->latitude,
    //         'longitude' => $request->longitude,
    //         'distance_meter' => $distance,
    //         'photo_path' => $path
    //     ]);

    //     return response()->json(['message' => 'Absensi berhasil']);
    // }
    public function index()
    {
        $user = auth()->user();

        // ❌ User non-aktif tidak bisa absen
        if (!$user->employee->is_active) {
            abort(403, 'Akun Anda tidak aktif');
        }

        return view('attendance.index', [
            'shifts' => WorkShift::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:DATANG,PULANG',
            'work_shift_id' => 'required|exists:work_shifts,id',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        $user = auth()->user();

        // ❌ User non-aktif
        if (!$user->employee->is_active) {
            return response()->json(['message' => 'Akun tidak aktif'], 403);
        }

        // ❌ Cegah absensi ganda
        $exists = Attendace::where([
            'employee_id' => $user->employee_id,
            'attendance_date' => now()->toDateString(),
            'type' => $request->type,
            'work_shift_id' => $request->work_shift_id
        ])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Absensi sudah dilakukan'
            ], 422);
        }

        // 📍 Validasi radius
        $office = OfficeLocation::first();

        $distance = GeoService::distanceMeter(
            $request->latitude,
            $request->longitude,
            $office->latitude,
            $office->longitude
        );

        // ✅ DEBUG LOG (TEMPATKAN DI SINI)
        // Log::info('DEBUG ABSENSI RADIUS', [
        //     'employee_id' => $user->employee_id,
        //     'jarak_meter' => round($distance, 2),
        //     'radius_kantor' => $office->radius_meter,
        //     'user_lat' => $request->latitude,
        //     'user_lng' => $request->longitude,
        //     'office_lat' => $office->latitude,
        //     'office_lng' => $office->longitude,
        // ]);

        if ($distance > $office->radius_meter) {
            return response()->json([
                'message' => 'Di luar radius kantor'
            ], 403);
        }

        // 📷 Simpan foto
        $photoPath = $request->file('photo')
            ->store('attendance_photos', 'public');

        Attendace::create([
            'employee_id' => $user->employee_id,
            'attendance_date' => now()->toDateString(),
            'attendance_time' => now(),
            'type' => $request->type,
            'work_shift_id' => $request->work_shift_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'distance_meter' => $distance,
            'photo_path' => $photoPath
        ]);

        return response()->json([
            'message' => 'Absensi berhasil'
        ]);
    }

    public function dataindex()
    {
        $attendances = Attendace::with(['employee', 'workShift'])
            ->latest('attendance_time')
            ->paginate(10);

        return view('attendance.dataindex', compact('attendances'));
    }

    public function destroy(Attendace $attendance)
    {
        // hapus file foto jika ada
        if ($attendance->photo_path && Storage::disk('public')->exists($attendance->photo_path)) {
            Storage::disk('public')->delete($attendance->photo_path);
        }

        $attendance->delete();

        return redirect()
            ->route('attendances.data')
            ->with('success', 'Data absensi dan foto berhasil dihapus');
    }
}
