<?php
// database/migrations/...._create_tbl_programs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_detail', 255)->unique()->comment('Nama program dari file SOW');
            $table->string('program_category', 100)->nullable()->comment('cth: CO2024, CPIB 2025');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_programs');
    }
};