<?php
// database/seeders/CityProgramSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CityProgramSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Memulai Seeding City Program (Hardcoded)...");
        
        // Hapus data lama
        DB::table('tbl_city_program')->truncate();

        // Data statis dari db.php lama Anda
        $cityData = [
            ['city_name' => 'AGAM', 'sow_done' => 21, 'sow_ny_done' => 5, 'sow_total' => 26],
            ['city_name' => 'BENGKALIS', 'sow_done' => 100, 'sow_ny_done' => 26, 'sow_total' => 126],
            ['city_name' => 'BINTAN', 'sow_done' => 55, 'sow_ny_done' => 3, 'sow_total' => 58],
            ['city_name' => 'DHARMASRAYA', 'sow_done' => 60, 'sow_ny_done' => 107, 'sow_total' => 167],
            ['city_name' => 'INDRAGIRI HILIR', 'sow_done' => 128, 'sow_ny_done' => 100, 'sow_total' => 228],
            ['city_name' => 'INDRAGIRI HULU', 'sow_done' => 128, 'sow_ny_done' => 36, 'sow_total' => 164],
            ['city_name' => 'KAMPAR', 'sow_done' => 25, 'sow_ny_done' => 2, 'sow_total' => 27],
            ['city_name' => 'KARIMUN', 'sow_done' => 4, 'sow_ny_done' => 0, 'sow_total' => 4],
            ['city_name' => 'KEPULAUAN ANAMBAS', 'sow_done' => 30, 'sow_ny_done' => 9, 'sow_total' => 39],
            ['city_name' => 'KEPULAUAN MERANTI', 'sow_done' => 39, 'sow_ny_done' => 4, 'sow_total' => 43],
            ['city_name' => 'KOTA BATAM', 'sow_done' => 12, 'sow_ny_done' => 298, 'sow_total' => 310],
            ['city_name' => 'KOTA BUKITTINGGI', 'sow_done' => 11, 'sow_ny_done' => 1, 'sow_total' => 12],
            ['city_name' => 'KOTA DUMAI', 'sow_done' => 39, 'sow_ny_done' => 58, 'sow_total' => 97],
            ['city_name' => 'KOTA PADANG', 'sow_done' => 6, 'sow_ny_done' => 3, 'sow_total' => 9],
            ['city_name' => 'KOTA PADANG PANJANG', 'sow_done' => 21, 'sow_ny_done' => 2, 'sow_total' => 23],
            ['city_name' => 'KOTA PARIAMAN', 'sow_done' => 16, 'sow_ny_done' => 1, 'sow_total' => 17],
            ['city_name' => 'KOTA PAYAKUMBUH', 'sow_done' => 254, 'sow_ny_done' => 50, 'sow_total' => 304],
            ['city_name' => 'KOTA PEKANBARU', 'sow_done' => 15, 'sow_ny_done' => 0, 'sow_total' => 15],
            ['city_name' => 'KOTA SAWAHLUNTO', 'sow_done' => 3, 'sow_ny_done' => 3, 'sow_total' => 6],
            ['city_name' => 'KOTA SOLOK', 'sow_done' => 84, 'sow_ny_done' => 1, 'sow_total' => 85],
            ['city_name' => 'KOTA TANJUNG PINANG', 'sow_done' => 66, 'sow_ny_done' => 0, 'sow_total' => 66],
            ['city_name' => 'KUANTAN SINGINGI', 'sow_done' => 15, 'sow_ny_done' => 5, 'sow_total' => 20],
            ['city_name' => 'LIMA PULUH KOTA', 'sow_done' => 13, 'sow_ny_done' => 2, 'sow_total' => 15],
            ['city_name' => 'LINGGA', 'sow_done' => 74, 'sow_ny_done' => 2, 'sow_total' => 76],
            ['city_name' => 'NATUNA', 'sow_done' => 40, 'sow_ny_done' => 1, 'sow_total' => 41],
            ['city_name' => 'PADANG PARIAMAN', 'sow_done' => 93, 'sow_ny_done' => 6, 'sow_total' => 99],
            ['city_name' => 'PASAMAN', 'sow_done' => 156, 'sow_ny_done' => 1, 'sow_total' => 157],
            ['city_name' => 'PASAMAN BARAT', 'sow_done' => 98, 'sow_ny_done' => 32, 'sow_total' => 130],
            ['city_name' => 'PELALAWAN', 'sow_done' => 90, 'sow_ny_done' => 1, 'sow_total' => 91],
            ['city_name' => 'ROKAN HILIR', 'sow_done' => 121, 'sow_ny_done' => 1, 'sow_total' => 122],
            ['city_name' => 'ROKAN HULU', 'sow_done' => 63, 'sow_ny_done' => 58, 'sow_total' => 121],
            ['city_name' => 'SIAK', 'sow_done' => 99, 'sow_ny_done' => 12, 'sow_total' => 111],
            ['city_name' => 'SIJUNJUNG', 'sow_done' => 60, 'sow_ny_done' => 8, 'sow_total' => 68],
            ['city_name' => 'SOLOK', 'sow_done' => 72, 'sow_ny_done' => 16, 'sow_total' => 88],
            ['city_name' => 'SOLOK SELATAN', 'sow_done' => 8, 'sow_ny_done' => 0, 'sow_total' => 8],
            ['city_name' => 'TANAH DATAR', 'sow_done' => 66, 'sow_ny_done' => 4, 'sow_total' => 70],
        ];

        // Tambahkan timestamp
        foreach ($cityData as &$row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
        }

        // Masukkan data baru (dalam chunks)
        foreach (array_chunk($cityData, 50) as $chunk) {
            DB::table('tbl_city_program')->insert($chunk);
        }

        $this->command->info(count($cityData) . " data City Program berhasil di-seed.");
    }
}