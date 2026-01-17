<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLeave;
use App\Models\Employee;
use Illuminate\Http\Request;

class AdditionalLeaveController extends Controller
{
    public function index()
    {
        $additionalLeaves = AdditionalLeave::with('employee')->latest()->get();
        return view('additional_leaves.index', compact('additionalLeaves'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('additional_leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'year'            => 'required|digits:4',
            'remaining_quota' => 'required|numeric|min:0',
        ]);

        AdditionalLeave::create($request->all());

        return redirect()
            ->route('additional-leaves.index')
            ->with('success', 'Data cuti tambahan berhasil ditambahkan');
    }

    public function destroy(AdditionalLeave $additionalLeave)
    {
        $additionalLeave->delete();

        return redirect()
            ->route('additional-leaves.index')
            ->with('success', 'Data cuti tambahan berhasil dihapus');
    }
}
