<?php
// database/seeders/MasterDataSeeder.php
// --- VERSI SUDAH DIPERBAIKI ---

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader; // Library CSV yang kuat, sudah ada di Laravel

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Memulai Seeding Master Data (Locations, Programs, Partners)...");

        $csvPath = database_path('data/detail1.csv');
        if (!file_exists($csvPath)) {
            $this->command->error("File detail1.csv tidak ditemukan di database/data/");
            return;
        }

        // Hapus data master lama (kecuali yg statis)
        // Kita gunakan truncate() untuk mereset ID auto-increment
        DB::statement('TRUNCATE tbl_locations RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE tbl_programs RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE tbl_partners RESTART IDENTITY CASCADE');

        // Gunakan set/map untuk menyimpan nilai unik
        $uniqueLocations = [];
        $uniquePrograms = [];
        $uniquePartners = [];

        // Baca CSV
        $csv = Reader::createFromPath($csvPath, 'r');
        // --- PERBAIKAN DARI DEBUGGING ---
        $csv->setDelimiter(';'); 
        $csv->setHeaderOffset(2); // Header ada di baris ke-3 (index 2)
        // --- SELESAI PERBAIKAN ---

        $records = $csv->getRecords();

        $this->command->info("Membaca detail1.csv untuk data unik...");

        foreach ($records as $row) {
            // --- INI ADALAH PEMETAAN KOLOM BARU KITA ---
            $city = $row['KAB-KOTA'] ?? null;
            $region = 'SUMBAGTENG'; // Asumsi default, karena tidak ada kolom region
            $program = $row['Prog.Name'] ?? null;
            $partner = $row['Vendor'] ?? null;
            // --- SELESAI PEMETAAN ---

            // 1. Kumpulkan Lokasi
            if (!empty($city) && !isset($uniqueLocations[$city])) {
                $uniqueLocations[$city] = ['city_name' => $city, 'region' => $region];
            }

            // 2. Kumpulkan Program
            if (!empty($program) && !isset($uniquePrograms[$program])) {
                $uniquePrograms[$program] = ['program_detail' => $program];
            }

            // 3. Kumpulkan Partner
            if (!empty($partner) && !isset($uniquePartners[$partner])) {
                $uniquePartners[$partner] = ['partner_name' => $partner];
            }
        }

        // --- SEEDING DATA MASTER ---

        // 1. Seed tbl_locations
        if (!empty($uniqueLocations)) {
            $this->command->info("Memasukkan " . count($uniqueLocations) . " lokasi unik...");
            $data = $this->addTimestamps(array_values($uniqueLocations));
            foreach (array_chunk($data, 200) as $chunk) {
                DB::table('tbl_locations')->insert($chunk);
            }
        }

        // 2. Seed tbl_programs
        if (!empty($uniquePrograms)) {
            $this->command->info("Memasukkan " . count($uniquePrograms) . " program unik...");
            $data = $this->addTimestamps(array_values($uniquePrograms));
            DB::table('tbl_programs')->insert($data);
        }

        // 3. Seed tbl_partners
        if (!empty($uniquePartners)) {
            $this->command->info("Memasukkan " . count($uniquePartners) . " partner unik...");
            $data = $this->addTimestamps(array_values($uniquePartners));
            DB::table('tbl_partners')->insert($data);
        }

        $this->command->info("Seeding Master Data selesai.");
    }

    // Fungsi helper untuk menambah created_at/updated_at
    private function addTimestamps(array $data): array
    {
        return array_map(function ($row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
            return $row;
        }, $data);
    }
}