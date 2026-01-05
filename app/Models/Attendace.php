<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendace extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'type',
        'work_shift_id',
        'latitude',
        'longitude',
        'distance_meter',
        'photo_path',
        'attendance_time',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class);
    }
}
