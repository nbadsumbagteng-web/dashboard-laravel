<?php
// database/migrations/...._create_tbl_nop_base_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_nop_base', function (Blueprint $table) {
            $table->id();
            $table->string('nop_name', 100)->unique();
            $table->integer('sow_done')->default(0);
            $table->integer('sow_ny_done')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_nop_base');
    }
};