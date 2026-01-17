<?php

namespace App\Http\Controllers;

use App\Models\WorkUnit;
use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    // READ
    public function index()
    {
        $workUnits = WorkUnit::orderBy('id', 'desc')->get();
        return view('work_units.index', compact('workUnits'));
    }

    // CREATE FORM
    public function create()
    {
        return view('work_units.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'work_unit' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'leader_nip' => 'required|string|max:50',
        ]);

        WorkUnit::create($request->all());

        return redirect()
            ->route('work-units.index')
            ->with('success', 'Unit Kerja Berhasil di tambahkan.');
    }

    // DELETE
    public function destroy($id)
    {
        $workUnit = WorkUnit::findOrFail($id);
        $workUnit->delete();

        return redirect()
            ->route('work-units.index')
            ->with('success', 'Unit Kerja Berhasil di hapus.');
    }
}
