<?php
// database/migrations/...._create_tbl_locations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_locations', function (Blueprint $table) {
            $table->id(); // id SERIAL PRIMARY KEY
            $table->string('city_name', 100)->unique()->comment('Nama Kota/Kabupaten');
            $table->string('nop_area', 100)->nullable()->comment('Nama NOP, cth: NOP PEKANBARU');
            $table->string('region', 100)->default('SUMBAGTENG');
            $table->timestamps(); // Menambah created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_locations');
    }
};