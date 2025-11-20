<?php
// database/seeders/NopBaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NopBaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Memulai Seeding NOP Base (Hardcoded)...");

        // Hapus data lama
        DB::table('tbl_nop_base')->truncate();

        // Data statis dari db.php lama Anda
        $nop_data = [
            ['nop_name' => 'BATAM', 'sow_done' => 548, 'sow_ny_done' => 113, 'created_at' => now(), 'updated_at' => now()],
            ['nop_name' => 'DUMAI', 'sow_done' => 538, 'sow_ny_done' => 86, 'created_at' => now(), 'updated_at' => now()],
            ['nop_name' => 'PADANG', 'sow_done' => 472, 'sow_ny_done' => 56, 'created_at' => now(), 'updated_at' => now()],
            ['nop_name' => 'PEKANBARU', 'sow_done' => 679, 'sow_ny_done' => 124, 'created_at' => now(), 'updated_at' => now()],
            ['nop_name' => 'BUKITTINGGI', 'sow_done' => 469, 'sow_ny_done' => 62, 'created_at' => now(), 'updated_at' => now()],
        ];
        
        // Masukkan data baru
        DB::table('tbl_nop_base')->insert($nop_data);

        $this->command->info(count($nop_data) . " data NOP Base berhasil di-seed.");
    }
}