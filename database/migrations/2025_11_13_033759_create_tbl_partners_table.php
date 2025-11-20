<?php
// database/migrations/...._create_tbl_partners_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_partners', function (Blueprint $table) {
            $table->id();
            $table->string('partner_name', 255)->unique();
            $table->string('vendor_category', 50)->nullable()->comment('cth: EID, HWI, TI');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_partners');
    }
};