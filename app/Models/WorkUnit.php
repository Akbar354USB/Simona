<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_unit',
        'leader_name',
        'leader_nip',
    ];

    public function additionalLeaveRequests()
    {
        return $this->hasMany(AdditionalLeaveRequest::class);
    }
}
