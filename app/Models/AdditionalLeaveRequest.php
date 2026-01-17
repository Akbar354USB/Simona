<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalLeaveRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'employee_name',
        'nip',
        'position',
        'length_of_service',
        'work_unit_id',
        'leave_reason',
        'phone',
        'leave_address',
        'letter_number',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function periods()
    {
        return $this->hasMany(AdditionalLeavePeriod::class);
    }
}
