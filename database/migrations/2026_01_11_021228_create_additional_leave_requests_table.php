<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('additional_leave_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');

            $table->string('employee_name');
            $table->string('nip');
            $table->string('position');
            $table->string('length_of_service');

            // RELATION TO WORK UNITS
            $table->foreignId('work_unit_id')
                ->constrained('work_units')
                ->onDelete('cascade');

            $table->text('leave_reason');

            $table->string('phone');
            $table->text('leave_address');

            $table->string('letter_number')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_leave_requests');
    }
};
