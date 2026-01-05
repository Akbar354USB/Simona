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
        Schema::create('attendaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->enum('type', ['DATANG', 'PULANG']);

            $table->foreignId('work_shift_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('distance_meter');

            $table->string('photo_path');

            $table->timestamp('attendance_time');

            $table->timestamps();

            $table->unique([
                'employee_id',
                'attendance_date',
                'type',
                'work_shift_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendaces');
    }
};
