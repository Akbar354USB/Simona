<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'status' => 'required|in:PNS,PPNPN',
        ]);

        Employee::create([
            'employee_name' => $request->employee_name,
            'email' => $request->email,
            'status' => $request->status,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Data pegawai berhasil ditambahkan');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'status' => 'required|in:PNS,PPNPN',
        ]);

        $employee->update([
            'employee_name' => $request->employee_name,
            'email' => $request->email,
            'status' => $request->status,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Data pegawai berhasil diperbarui');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Data pegawai berhasil dihapus');
    }
}
