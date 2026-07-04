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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tc_no', 11)->unique();
            $table->string('phone', 15);
            $table->string('email')->unique();
            $table->enum('department', ['security', 'kitchen', 'cleaning', 'management', 'student_affairs', 'duty_officer']);
            $table->time('shift_start');
            $table->time('shift_end');
            $table->date('start_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
