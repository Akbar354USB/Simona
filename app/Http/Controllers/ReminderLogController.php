<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReminderLog;

class ReminderLogController extends Controller
{
    // public function index()
    // {
    //     $logs = ReminderLog::with('employee')
    //         ->orderBy('event_date', 'desc')
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(15);

    //     return view('reminder_logs.index', compact('logs'));
    // }

    // /**
    //  * Menghapus reminder log
    //  */
    // public function destroy(ReminderLog $reminderLog)
    // {
    //     $reminderLog->delete();

    //     return redirect()
    //         ->route('reminder-logs.index')
    //         ->with('success', 'Reminder log berhasil dihapus');
    // }

    public function index(Request $request)
    {
        $logs = ReminderLog::with('employee')

            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('employee', function ($sub) use ($request) {
                    $sub->where('employee_name', 'like', '%' . $request->search . '%');
                })
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            })

            ->when($request->event_type, function ($q) use ($request) {
                $q->where('event_type', $request->event_type);
            })

            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            ->when($request->event_date, function ($q) use ($request) {
                $q->whereDate('event_date', $request->event_date);
            })

            ->orderBy('event_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reminder_logs.index', compact('logs'));
    }

    public function destroy(ReminderLog $reminderLog)
    {
        $reminderLog->delete();

        return redirect()
            ->route('reminder-logs.index')
            ->with('success', 'Data berhasil dihapus');
    }

    /**
     * HAPUS SEMUA DATA
     */
    public function truncate()
    {
        ReminderLog::truncate();

        return redirect()
            ->route('reminder-logs.index')
            ->with('success', 'Semua data reminder log berhasil dihapus');
    }
}
