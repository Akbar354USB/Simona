<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'employee_name' => 'Muhammad Akbar',
            'email'         => 'akbarmajenesmk5@gmail.com',
            'status'        => 'PNS',
            'is_active'     => true,
        ]);
    }
}
