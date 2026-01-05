<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendace::class);
    }
}
