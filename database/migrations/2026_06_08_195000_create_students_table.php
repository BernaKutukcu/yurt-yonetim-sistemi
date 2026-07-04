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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tc_no', 11)->unique();
            $table->string('phone', 15)->nullable();
            $table->string('department');
            $table->string('address');
            $table->string('city')->nullable();
            $table->date('birth_date');
            $table->string('iban', 34);
            $table->string('mother_name');
            $table->string('father_name');
            $table->string('parent_phone', 15);
            $table->date('registration_date');
            $table->integer('bed_number')->nullable();
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
