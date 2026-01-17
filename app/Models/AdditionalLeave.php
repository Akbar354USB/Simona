<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalLeave extends Model
{
    protected $fillable = [
        'employee_id',
        'year',
        'remaining_quota'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
