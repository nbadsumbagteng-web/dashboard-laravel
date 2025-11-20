<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Seed tabel statis dulu
            NopBaseSeeder::class,
            CityProgramSeeder::class,
            
            // 2. Seed tabel master dari detail1.csv
            MasterDataSeeder::class,
            
            // 3. Seed tabel SOW utama (harus terakhir)
            SowDataSeeder::class,
        ]);
    }
}