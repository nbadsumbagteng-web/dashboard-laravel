<?php
// database/migrations/...._create_sow_data_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sow_data', function (Blueprint $table) {
            $table->id();
            $table->string('sow_id', 100)->unique()->nullable()->comment('SOW-ID dari file');
            $table->string('site_id', 100)->nullable()->index();
            $table->string('site_name', 255)->nullable();
            
            // Foreign key (penghubung) ke tabel lain
            $table->foreignId('location_id')->nullable()->constrained('tbl_locations')->onDelete('set null');
            $table->foreignId('program_id')->nullable()->constrained('tbl_programs')->onDelete('set null');
            $table->foreignId('partner_id')->nullable()->constrained('tbl_partners')->onDelete('set null');
            
            $table->string('status', 50)->nullable(); // cth: Done, Not Done, On Air
            $table->decimal('capex_value', 15, 2)->default(0);
            $table->date('on_air_date')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sow_data');
    }
};