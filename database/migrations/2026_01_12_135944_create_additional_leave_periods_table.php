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
        Schema::create('additional_leave_periods', function (Blueprint $table) {
            $table->id();

            $table->foreignId('additional_leave_request_id')
                ->constrained('additional_leave_requests')
                ->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_leave_periods');
    }
};
