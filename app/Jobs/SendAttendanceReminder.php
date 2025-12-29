<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use App\Models\ReminderLog;
use Illuminate\Support\Facades\Log;

class SendAttendanceReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }


    // public function handle(GoogleCalendarService $calendar)
    // {
    //     $employees = Employee::with(['workSchedule', 'googleAccount'])
    //         ->where('is_active', true)
    //         ->get();

    //     foreach ($employees as $employee) {

    //         // Lewati jika tidak memiliki akun Google atau jadwal kerja
    //         if (!$employee->googleAccount || !$employee->workSchedule) {
    //             continue;
    //         }

    //         $today = Carbon::today('Asia/Makassar');

    //         // =======================
    //         // EVENT ABSEN MASUK
    //         // =======================
    //         $startIn = (clone $today)
    //             ->setTimeFromTimeString($employee->workSchedule->time_in);

    //         $endIn = (clone $startIn)->addMinutes(10);

    //         try {
    //             $calendar->createReminder(
    //                 $employee->googleAccount,
    //                 'Reminder Absensi Datang',
    //                 $startIn,
    //                 $endIn
    //             );
    //         } catch (\Exception $e) {
    //             // Sengaja dikosongkan
    //         }

    //         // =======================
    //         // EVENT ABSEN PULANG
    //         // =======================
    //         $startOut = (clone $today)
    //             ->setTimeFromTimeString($employee->workSchedule->time_out);

    //         $endOut = (clone $startOut)->addMinutes(10);

    //         try {
    //             $calendar->createReminder(
    //                 $employee->googleAccount,
    //                 'Reminder Absensi Pulang',
    //                 $startOut,
    //                 $endOut
    //             );
    //         } catch (\Exception $e) {
    //             // Sengaja dikosongkan
    //         }
    //     }
    // }

    public function handle(GoogleCalendarService $calendar)
    {
        $employees = Employee::with(['workSchedule', 'googleAccount'])
            ->where('is_active', true)
            ->get();

        $today = Carbon::today('Asia/Makassar');

        foreach ($employees as $employee) {

            // ============================
            // VALIDASI DATA PEGAWAI
            // ============================
            if (!$employee->googleAccount || !$employee->workSchedule) {
                continue;
            }

            // ============================
            // EVENT ABSEN MASUK (IN)
            // ============================
            $startIn = (clone $today)
                ->setTimeFromTimeString($employee->workSchedule->time_in);

            $endIn = (clone $startIn)->addMinutes(10);

            try {
                $calendar->createReminder(
                    $employee->googleAccount,
                    'Reminder Absensi Datang',
                    $startIn,
                    $endIn
                );

                ReminderLog::create([
                    'employee_id' => $employee->id,
                    'event_type'  => 'IN',
                    'event_date'  => $today->toDateString(),
                    'status'      => 'success',
                    'message'     => 'Event absensi masuk berhasil dibuat',
                ]);
            } catch (\Exception $e) {

                ReminderLog::create([
                    'employee_id' => $employee->id,
                    'event_type'  => 'IN',
                    'event_date'  => $today->toDateString(),
                    'status'      => 'failed',
                    'message'     => $e->getMessage(),
                ]);
            }

            // ============================
            // EVENT ABSEN PULANG (OUT)
            // ============================
            $startOut = (clone $today)
                ->setTimeFromTimeString($employee->workSchedule->time_out);

            $endOut = (clone $startOut)->addMinutes(10);

            try {
                $calendar->createReminder(
                    $employee->googleAccount,
                    'Reminder Absensi Pulang',
                    $startOut,
                    $endOut
                );

                ReminderLog::create([
                    'employee_id' => $employee->id,
                    'event_type'  => 'OUT',
                    'event_date'  => $today->toDateString(),
                    'status'      => 'success',
                    'message'     => 'Event absensi pulang berhasil dibuat',
                ]);
            } catch (\Exception $e) {

                ReminderLog::create([
                    'employee_id' => $employee->id,
                    'event_type'  => 'OUT',
                    'event_date'  => $today->toDateString(),
                    'status'      => 'failed',
                    'message'     => $e->getMessage(),
                ]);
            }
        }
    }
}
