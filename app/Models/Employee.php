<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_name',
        'email',
        'status',
        'is_active',
    ];

    public function workSchedule(): HasOne
    {
        return $this->hasOne(WorkSchedule::class);
    }

    public function googleAccount(): HasOne
    {
        return $this->hasOne(GoogleAccount::class);
    }

    public function reminderLogs(): HasMany
    {
        return $this->hasMany(ReminderLog::class);
    }

    public function guest_books()
    {
        return $this->belongsToMany(GuestBook::class, 'employee_guest_books');
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
