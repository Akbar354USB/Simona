<?php

namespace App\Http\Controllers;

use App\Models\WorkSchedule;
use App\Models\Employee;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $schedules = WorkSchedule::with('employee')->latest()->get();
        return view('work_schedules.index', compact('schedules'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('work_schedules.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'time_in' => 'required',
            'time_out' => 'required',
            'timezone' => 'required'
        ]);

        WorkSchedule::create($request->all());

        return redirect()->route('work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil ditambahkan');
    }

    public function edit(WorkSchedule $workSchedule)
    {
        $employees = Employee::all();
        return view('work_schedules.edit', compact('workSchedule', 'employees'));
    }

    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'time_in' => 'required',
            'time_out' => 'required',
            'timezone' => 'required'
        ]);

        $workSchedule->update($request->all());

        return redirect()->route('work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil diperbarui');
    }

    public function destroy(WorkSchedule $workSchedule)
    {
        $workSchedule->delete();

        return redirect()->route('work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil dihapus');
    }
}
