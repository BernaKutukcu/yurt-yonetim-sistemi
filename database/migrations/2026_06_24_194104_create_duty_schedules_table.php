<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duty_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->date('duty_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable(); // A Blok kapı, B Blok kapı vs.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duty_schedules');
    }
};
