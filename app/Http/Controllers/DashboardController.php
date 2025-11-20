<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Query 1: KPI Utama
        $kpi = DB::table('sow_data')
            ->selectRaw("
                COUNT(*) AS total_sow,
                SUM(CASE WHEN status = 'Done' THEN 1 ELSE 0 END) AS total_done
            ")
            ->first();

        $total_sow = $kpi->total_sow ?? 0;
        $total_done = $kpi->total_done ?? 0;
        $total_not_done = $total_sow - $total_done;
        $total_percentage = ($total_sow > 0) ? round(($total_done * 100.0) / $total_sow, 2) : 0;

        // Query 2: KPI SOW Program (Doughnut Chart)
        $kpi_sow_program = DB::table('sow_data as s')
            ->leftJoin('tbl_programs as p', 's.program_id', '=', 'p.id')
            ->select(
                DB::raw("COALESCE(p.program_detail, 'Uncategorized') AS program_type"),
                DB::raw('COUNT(s.id) AS total')
            )
            ->groupBy('program_type')
            ->orderBy('total', 'desc')
            ->get();

        $kpi_sow_labels = $kpi_sow_program->pluck('program_type');
        $kpi_sow_data = $kpi_sow_program->pluck('total');

        // Query 3: Partner Performance (Bar Chart)
        $partner_summary = DB::table('sow_data as s')
            ->leftJoin('tbl_partners as p', 's.partner_id', '=', 'p.id')
            ->select(
                DB::raw("COALESCE(p.partner_name, 'No Partner') AS partner_name"),
                DB::raw("SUM(CASE WHEN s.status = 'Done' THEN 1 ELSE 0 END) AS done"),
                DB::raw("SUM(CASE WHEN s.status = 'Not Done' THEN 1 ELSE 0 END) AS not_done")
            )
            ->groupBy('partner_name')
            ->orderByRaw('COUNT(s.id) DESC')
            ->limit(10)
            ->get();

        $partner_labels = $partner_summary->pluck('partner_name');
        $partner_data_done = $partner_summary->pluck('done');
        $partner_data_not_done = $partner_summary->pluck('not_done');

        // Query 4: City Performance
        $city_program = DB::table('tbl_city_program')
            ->select('city_name AS city', 'sow_done AS done', 'sow_ny_done AS not_done')
            ->orderBy('city_name', 'asc')
            ->get();

        $city_labels = $city_program->pluck('city');
        $city_data_done = $city_program->pluck('done');
        $city_data_not_done = $city_program->pluck('not_done');

        // Query 5: CAPEX Table
        $capex_table_data = DB::table('sow_data as s')
            ->leftJoin('tbl_programs as p', 's.program_id', '=', 'p.id')
            ->select(
                DB::raw("COALESCE(p.program_detail, 'Uncategorized') AS program_type"),
                DB::raw("COUNT(s.id) AS total"),
                DB::raw("SUM(CASE WHEN s.status = 'Done' THEN 1 ELSE 0 END) AS done"),
                DB::raw("SUM(CASE WHEN s.status = 'Not Done' THEN 1 ELSE 0 END) AS not_done"),
                DB::raw("ROUND((SUM(CASE WHEN s.status = 'Done' THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(s.id), 0)), 2) AS percentage")
            )
            ->groupBy('program_type')
            ->orderBy('program_type')
            ->get();

        // Query 6: Map Data
        $sites_data = DB::table('sow_data as s')
            ->leftJoin('tbl_locations as l', 's.location_id', '=', 'l.id')
            ->leftJoin('tbl_programs as p', 's.program_id', '=', 'p.id')
            ->leftJoin('tbl_partners as pt', 's.partner_id', '=', 'pt.id')
            ->select(
                's.site_name', 
                's.status', 
                's.latitude', 
                's.longitude',
                DB::raw("COALESCE(p.program_detail, 'N/A') AS program_type"),
                DB::raw("COALESCE(l.city_name, 'N/A') AS city"),
                DB::raw("COALESCE(pt.partner_name, 'N/A') AS partner_name")
            )
            ->whereNotNull('s.latitude')
            ->whereNotNull('s.longitude')
            ->limit(1000)
            ->get();

        $total_sites = $sites_data->count();
        $done_sites = $sites_data->where('status', 'Done')->count();
        $not_done_sites = $total_sites - $done_sites;

        // Query 7: NOP Base Chart
        $nop_base = DB::table('tbl_nop_base')
            ->select('nop_name', 'sow_done', 'sow_ny_done')
            ->orderBy('nop_name', 'asc')
            ->get();

        $nop_labels = $nop_base->pluck('nop_name');
        $nop_data_done = $nop_base->pluck('sow_done');
        $nop_data_not_done = $nop_base->pluck('sow_ny_done');

        // Query 8: Partner Detail Table
        $partner_detail_result = DB::select("
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
            ORDER BY 
                CASE COALESCE(pt.vendor_category, 'ZZZ') 
                    WHEN 'EID' THEN 1 
                    WHEN 'HWI' THEN 2 
                    WHEN 'TI' THEN 3 
                    ELSE 4 
                END, 
                vendor_category, 
                program_detail
        ");

        // Process partner detail data
        $partner_detail_data = [];
        $grand_total_all = ['done' => 0, 'not_done' => 0, 'total' => 0];

        foreach ($partner_detail_result as $row) {
            $vendor = $row->vendor_category;
            $done = (int)$row->done;
            $not_done = (int)$row->not_done;
            $total = (int)$row->grand_total;
            $achieve = ($total > 0) ? round(($done * 100.0) / $total, 2) : 0;

            if (!isset($partner_detail_data[$vendor])) {
                $partner_detail_data[$vendor] = [];
            }

            $partner_detail_data[$vendor][] = [
                'program_detail' => $row->program_detail,
                'done' => $done,
                'not_done' => $not_done,
                'grand_total' => $total,
                'achieve' => $achieve
            ];

            $grand_total_all['done'] += $done;
            $grand_total_all['not_done'] += $not_done;
            $grand_total_all['total'] += $total;
        }

        $grand_achieve = ($grand_total_all['total'] > 0) ? 
            round(($grand_total_all['done'] * 100.0) / $grand_total_all['total'], 2) : 0;

        return view('dashboard', compact(
            'total_sow',
            'total_done',
            'total_not_done', 
            'total_percentage',
            'kpi_sow_labels',
            'kpi_sow_data',
            'partner_labels',
            'partner_data_done',
            'partner_data_not_done',
            'city_labels',
            'city_data_done',
            'city_data_not_done',
            'capex_table_data',
            'sites_data',
            'total_sites',
            'done_sites',
            'not_done_sites',
            'nop_labels',
            'nop_data_done',
            'nop_data_not_done',
            'partner_detail_data',
            'grand_total_all',
            'grand_achieve'
        ));
    }

    // Method untuk AJAX get data
    public function getSowData() {
        $data = DB::table('sow_data')
            ->leftJoin('tbl_programs', 'sow_data.program_id', '=', 'tbl_programs.id')
            ->leftJoin('tbl_partners', 'sow_data.partner_id', '=', 'tbl_partners.id')
            ->select('sow_data.*', 'tbl_programs.program_detail as program_type', 'tbl_partners.partner_name')
            ->get();
        
        return response()->json(['data' => $data]);
    }

    // Method untuk edit data
    public function editSowData($id) {
        $data = DB::table('sow_data')
            ->leftJoin('tbl_programs', 'sow_data.program_id', '=', 'tbl_programs.id')
            ->leftJoin('tbl_partners', 'sow_data.partner_id', '=', 'tbl_partners.id')
            ->select('sow_data.*', 'tbl_programs.program_detail as program_type', 'tbl_partners.partner_name')
            ->where('sow_data.id', $id)
            ->first();
        
        return response()->json($data);
    }

    // Method untuk store data baru
    public function storeSowData(Request $request) {
        // Validasi dan simpan data
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'program_id' => 'required|integer',
            'partner_id' => 'required|integer',
            'city' => 'required|string|max:100',
            'status' => 'required|in:Done,Not Done',
            'progress' => 'nullable|integer|min:0|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);
        
        // Set progress otomatis jika status Done
        if ($data['status'] === 'Done' && empty($data['progress'])) {
            $data['progress'] = 100;
        } elseif ($data['status'] === 'Not Done' && empty($data['progress'])) {
            $data['progress'] = 50;
        }
        
        $data['created_at'] = now();
        $data['updated_at'] = now();
        
        DB::table('sow_data')->insert($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    // Method untuk update data
    public function updateSowData(Request $request, $id) {
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'status' => 'required|in:Done,Not Done',
            'progress' => 'nullable|integer|min:0|max:100'
        ]);
        
        // Set progress otomatis jika status Done
        if ($data['status'] === 'Done' && empty($data['progress'])) {
            $data['progress'] = 100;
        } elseif ($data['status'] === 'Not Done' && empty($data['progress'])) {
            $data['progress'] = 50;
        }
        
        $data['updated_at'] = now();
        
        DB::table('sow_data')
            ->where('id', $id)
            ->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate'
        ]);
    }

    // Method untuk delete data
    public function deleteSowData($id) {
        DB::table('sow_data')
            ->where('id', $id)
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    // Method untuk export data
    public function exportData(Request $request) {
        $data = DB::table('sow_data')
            ->leftJoin('tbl_programs', 'sow_data.program_id', '=', 'tbl_programs.id')
            ->leftJoin('tbl_partners', 'sow_data.partner_id', '=', 'tbl_partners.id')
            ->select('sow_data.*', 'tbl_programs.program_detail as program_type', 'tbl_partners.partner_name')
            ->get();

        // Logic untuk export CSV/Excel bisa ditambahkan di sini
        // Untuk sekarang return success message saja
        
        return response()->json([
            'success' => true,
            'message' => 'Export berhasil',
            'data' => $data
        ]);
    }

    // Method untuk import data
    public function importData(Request $request) {
        $request->validate([
            'sow_file' => 'required|file|mimes:csv,txt'
        ]);

        // Logic untuk import CSV bisa ditambahkan di sini
        
        return response()->json([
            'success' => true,
            'message' => 'Import berhasil'
        ]);
    }

    // Method untuk data real-time (simulasi)
public function getRealTimeData()
{
    $realtimeData = [
        'active_sites' => rand(80, 120),
        'network_health' => rand(85, 99),
        'data_throughput' => rand(500, 800),
        'subscriber_growth' => rand(1000, 5000)
    ];

    return response()->json($realtimeData);
}

// Method untuk network performance
public function getNetworkPerformance()
{
    $performance = DB::table('sow_data')
        ->selectRaw("
            AVG(CASE WHEN status = 'Done' THEN 1 ELSE 0 END) * 100 as availability,
            COUNT(*) as total_sites,
            SUM(CASE WHEN status = 'Done' THEN 1 ELSE 0 END) as active_sites
        ")
        ->first();

    return response()->json([
        'availability' => round($performance->availability, 2),
        'total_sites' => $performance->total_sites,
        'active_sites' => $performance->active_sites
    ]);
}
}