<?php
// database/seeders/SowDataSeeder.php
// --- VERSI FINAL DENGAN VALIDASI LAT/LONG ---

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon; // Pastikan Carbon di-import

class SowDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Memulai Seeding SOW Data (Tabel Utama)...");

        $csvPath = database_path('data/detail1.csv');
        if (!file_exists($csvPath)) {
            $this->command->error("File detail1.csv tidak ditemukan.");
            return;
        }

        // Hapus data SOW lama
        DB::statement('TRUNCATE sow_data RESTART IDENTITY');

        // 1. Ambil data master ke memori (Lookup Map)
        $this->command->info("Memuat master data ke memori...");
        $locationsMap = DB::table('tbl_locations')->pluck('id', 'city_name');
        $programsMap = DB::table('tbl_programs')->pluck('id', 'program_detail');
        $partnersMap = DB::table('tbl_partners')->pluck('id', 'partner_name');

        // 2. Baca CSV
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(2); // Header ada di baris ke-3 (index 2)
        
        $records = $csv->getRecords();

        $sowData = [];
        $totalRows = 0;
        $processedRows = 0;
        $bar = $this->command->getOutput()->createProgressBar(); // Buat progress bar

        foreach ($records as $row) {
            $totalRows++;
            
            // Periksa jika site_id ada, jika tidak, lewati baris
            if (empty($row['Site ID Act'])) {
                continue;
            }

            // --- PEMETAAN KOLOM ---
            $city = $row['KAB-KOTA'] ?? null;
            $program = $row['Prog.Name'] ?? null;
            $partner = $row['Vendor'] ?? null;

            // Cari ID dari lookup map
            $location_id = $locationsMap[$city] ?? null;
            $program_id = $programsMap[$program] ?? null;
            $partner_id = $partnersMap[$partner] ?? null;

            // --- LOGIKA PEMBERSIHAN DATA BARU ---
            $lat = (float)($row['Latitude'] ?? null);
            $lon = (float)($row['Longitude'] ?? null);

            // 1. Cek jika data terbalik (lat > 90 DAN lon < 90)
            if ( ($lat > 90 || $lat < -90) && ($lon <= 90 && $lon >= -90) ) {
                $temp = $lat;
                $lat = $lon;
                $lon = $temp; // Tukar datanya
            }

            // 2. Validasi Final. Jika masih di luar jangkauan, set NULL
            if ($lat > 90 || $lat < -90) {
                $lat = null;
            }
            if ($lon > 180 || $lon < -180) {
                $lon = null;
            }
            // --- SELESAI LOGIKA PEMBERSIHAN ---

            // Bersihkan data lain
            $capex = 0; // 'capex_value' tidak ditemukan, di-set ke 0
            $on_air_date = $this->formatDate($row['OA Date'] ?? null);

            $sowData[] = [
                'sow_id' => $row['sowID'] ?? null,
                'site_id' => $row['Site ID Act'], // Wajib ada
                'site_name' => $row['Site Name Act'] ?? null,
                'status' => $row['General Status'] ?? 'Not Done',
                'capex_value' => $capex,
                'on_air_date' => $on_air_date,
                'latitude' => $lat, // Data yang sudah bersih
                'longitude' => $lon, // Data yang sudah bersih
                'location_id' => $location_id,
                'program_id' => $program_id,
                'partner_id' => $partner_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $processedRows++;

            // Masukkan data dalam 'chunks' 500 baris
            if (count($sowData) >= 500) {
                DB::table('sow_data')->insert($sowData);
                $sowData = []; // Kosongkan array
                $bar->advance(500);
            }
        }

        // Masukkan sisa data
        if (!empty($sowData)) {
            DB::table('sow_data')->insert($sowData);
            $bar->advance(count($sowData));
        }

        $bar->finish();
        $this->command->info("\nSeeding SOW Data selesai. Total " . $processedRows . " baris dimasukkan.");
    }
    
    // Fungsi helper untuk format tanggal
    private function formatDate($dateString)
    {
        if (empty($dateString) || $dateString === '?') {
            return null;
        }
        // Coba format 'd/m/Y' (cth: 29/12/2024)
        try {
            return Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            // Coba format lain jika gagal (cth: 2024-12-29)
            try {
                return Carbon::parse($dateString)->format('Y-m-d');
            } catch (\Exception $e2) {
                return null; // Gagal format, kembalikan null
            }
        }
    }
}