<?php
// app/Http/Controllers/DataController.php
// --- VERSI FINAL DENGAN PERBAIKAN EXPORT (TITIK KOMA) ---

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use League\Csv\Writer; // Pastikan ini di-import
use Exception;
use SplTempFileObject; // Pastikan ini di-import

class DataController extends Controller
{
    /**
     * Ini adalah fungsi import yang SUDAH BENAR,
     * menggunakan header yang kita temukan (KAB-KOTA, Prog.Name)
     * dan metode 'upsert' yang cepat.
     */
    public function import(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'sow_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $file = $request->file('sow_file');
            
            // 2. Baca CSV
            $csv = Reader::createFromPath($file->getRealPath(), 'r');
            $csv->setDelimiter(';');
            
            // Cek checkbox. Jika dicentang, kita asumsikan header di baris 3 (index 2)
            // Jika tidak dicentang, kita asumsikan baris 1 (index 0)
            $headerOffset = $request->has('skip_header') ? 2 : 0; 
            $csv->setHeaderOffset($headerOffset);

            $records = $csv->getRecords();

            // 3. Ambil data master ke memori (Lookup Map)
            $locationsMap = DB::table('tbl_locations')->pluck('id', 'city_name');
            $programsMap = DB::table('tbl_programs')->pluck('id', 'program_detail');
            $partnersMap = DB::table('tbl_partners')->pluck('id', 'partner_name');

            $sowData = [];
            $processedCount = 0;

            // 4. Loop melalui data CSV
            foreach ($records as $row) {
                // Lewati baris jika site_id kosong (data tidak valid)
                if (empty($row['Site ID Act'])) {
                    continue;
                }

                // --- Pemetaan Kolom ---
                $city = $row['KAB-KOTA'] ?? null;
                $program = $row['Prog.Name'] ?? null;
                $partner = $row['Vendor'] ?? null;

                // Cari ID dari lookup map
                $location_id = $locationsMap->get($city);
                $program_id = $programsMap->get($program);
                $partner_id = $partnersMap->get($partner);

                // --- Pembersihan Data Lat/Long ---
                $lat = (float)($row['Latitude'] ?? null);
                $lon = (float)($row['Longitude'] ?? null);

                if ( ($lat > 90 || $lat < -90) && ($lon <= 90 && $lon >= -90) ) {
                    $temp = $lat; $lat = $lon; $lon = $temp;
                }
                if ($lat > 90 || $lat < -90) $lat = null;
                if ($lon > 180 || $lon < -180) $lon = null;

                $capex = 0; // Masih 0
                $on_air_date = $this->formatDate($row['OA Date'] ?? null);
                
                // Siapkan data untuk di-insert atau di-update
                $sowData[] = [
                    'sow_id' => $row['sowID'] ?? null,
                    'site_id' => $row['Site ID Act'],
                    'site_name' => $row['Site Name Act'] ?? null,
                    'status' => $row['General Status'] ?? 'Not Done',
                    'capex_value' => $capex,
                    'on_air_date' => $on_air_date,
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'location_id' => $location_id,
                    'program_id' => $program_id,
                    'partner_id' => $partner_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $processedCount++;
            }

            // 5. Jalankan Upsert (Proses dalam 'chunks' 500 baris)
            if (!empty($sowData)) {
                foreach(array_chunk($sowData, 500) as $chunk) {
                    DB::table('sow_data')->upsert(
                        $chunk,
                        ['sow_id'], // Kolom unik untuk dicari
                        [ // Kolom-kolom yang boleh di-update
                          'site_id', 'site_name', 'status', 'capex_value', 'on_air_date', 
                          'latitude', 'longitude', 'location_id', 'program_id', 'partner_id', 'updated_at'
                        ]
                    );
                }
            }

            // 6. Kembalikan ke halaman dashboard dengan pesan sukses
            return redirect()->route('dashboard.index')
                             ->with('success', "Berhasil! $processedCount data telah di-import/update.");

        } catch (Exception $e) {
            // 7. Jika ada error, kembalikan dengan pesan error
            return redirect()->route('dashboard.index')
                             ->with('error', 'Gagal Import: ' . $e->getMessage());
        }
    }

    /**
     * Ini adalah fungsi export Anda, dengan perbaikan titik koma (;)
     */
    public function export(Request $request)
    {
        $request->validate([
            'export_data_type' => 'required|string'
        ]);

        $dataType = $request->export_data_type;
        $fileName = "export_{$dataType}_" . date('Y-m-d_His') . ".csv";
        $data = []; // Inisialisasi

        try {
            switch ($dataType) {
                case 'sow_joined':
                    $data = DB::table('sow_data as s')
                        ->leftJoin('tbl_programs as p', 's.program_id', '=', 'p.id')
                        ->leftJoin('tbl_partners as pt', 's.partner_id', '=', 'pt.id')
                        ->leftJoin('tbl_locations as l', 's.location_id', '=', 'l.id')
                        ->select(
                            's.sow_id', 's.site_id', 's.site_name', 's.status',
                            'p.program_detail as program',
                            'pt.partner_name as partner',
                            'l.city_name as city',
                            's.latitude', 's.longitude', 's.on_air_date', 's.capex_value'
                        )
                        ->get();
                    break;

                case 'sow_normalized':
                    $data = DB::table('sow_data')->get();
                    break;
                    
                case 'partner_detail':
                    $data = DB::select("
                        SELECT 
                            COALESCE(pt.vendor_category, 'Unknown Vendor') AS vendor_category,
                            COALESCE(p.program_detail, 'Unknown Program') AS program_detail,
                            COUNT(s.id) AS grand_total,
                            SUM(CASE WHEN s.status = 'Done' THEN 1 ELSE 0 END) AS done,
                            SUM(CASE WHEN s.status = 'Not Done' THEN 1 ELSE 0 END) AS not_done
                        FROM sow_data s 
                        LEFT JOIN tbl_partners pt ON s.partner_id = pt.id 
                        LEFT JOIN tbl_programs p ON s.program_id = p.id 
                        GROUP BY vendor_category, program_detail 
                        ORDER BY vendor_category, program_detail
                    ");
                    break;

                case 'locations':
                    $data = DB::table('tbl_locations')->get();
                    break;
                    
                case 'programs':
                    $data = DB::table('tbl_programs')->get();
                    break;

                case 'partners':
                    $data = DB::table('tbl_partners')->get();
                    break;

                default:
                    return redirect()->route('dashboard.index')
                        ->with('error', 'Tipe export tidak valid.');
            }

            // Konversi Collection/Array ke Array murni
            $data = json_decode(json_encode($data), true);

            if (empty($data)) {
                 return redirect()->route('dashboard.index')
                       ->with('error', 'Tidak ada data untuk diexport.');
            }

            // Create CSV
            $csv = Writer::createFromFileObject(new SplTempFileObject());
            
            // --- INI PERBAIKANNYA ---
            $csv->setDelimiter(';'); // Paksa pakai titik koma untuk Excel Indonesia
            // --- SELESAI PERBAIKAN ---

            $csv->insertOne(array_keys($data[0])); // Masukkan Header
            $csv->insertAll($data); // Masukkan semua data

            return response((string) $csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);

        } catch (\Exception $e) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Error mengexport data: ' . $e->getMessage());
        }
    }

    /**
     * Fungsi helper untuk memformat tanggal
     */
    private function formatDate($dateString)
    {
        if (empty($dateString) || $dateString === '?') {
            return null;
        }
        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
            } catch (\Exception $e2) {
                return null;
            }
        }
    }
}