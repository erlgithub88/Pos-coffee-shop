<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->integer('monthly_wage')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropColumn('monthly_wage');
        });
    }
};
