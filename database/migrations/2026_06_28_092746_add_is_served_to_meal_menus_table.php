<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meal_menus', function (Blueprint $table) {
            $table->boolean('is_served')->default(false)->after('dinner');
        });
    }

    public function down(): void
    {
        Schema::table('meal_menus', function (Blueprint $table) {
            $table->dropColumn('is_served');
        });
    }
};
