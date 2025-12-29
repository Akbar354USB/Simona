<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil 1 pegawai (pastikan sudah ada)
        $employee = Employee::where('email', 'jors@gmail.com')->first();

        // Jika pegawai belum ada, hentikan seeder
        if (!$employee) {
            $this->command->warn('Employee belum ada, UserSeeder dibatalkan.');
            return;
        }

        // Cegah user dobel
        if (User::where('employee_id', $employee->id)->exists()) {
            $this->command->warn('User untuk employee ini sudah ada.');
            return;
        }

        User::create([
            'employee_id' => $employee->id,
            'password'    => Hash::make('password123'),
        ]);

        $this->command->info('User berhasil dibuat untuk employee: ' . $employee->employee_name);
    }
}
