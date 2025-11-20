@extends('layouts.app')

@section('title', 'RAN Performance Dashboard - Telkomsel')

@section('content')
<!-- Telkomsel Brand Header -->
<div class="bg-telkomsel-red text-white py-3 mb-4 shadow">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded p-2 me-3">
                        <i class="fas fa-satellite-dish fa-2x text-telkomsel-red"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">TELKOMSEL NETWORK OPERATIONS</h4>
                        <small class="opacity-75">RAN Performance Monitoring System - Sumbagteng Region</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <div class="me-4">
                        <small class="opacity-75">Last Updated:</small>
                        <div id="currentTime" class="fw-bold">{{ now()->format('d M Y H:i:s') }}</div>
                    </div>
                    <div class="bg-white text-telkomsel-red px-3 py-2 rounded-pill shadow">
                        <i class="fas fa-signal me-1"></i>
                        <span id="networkStatus">LIVE</span>
                        <i class="fas fa-circle text-success blink ms-1" style="font-size: 0.7rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Network Health Overview -->
<div class="row mb-4" id="network-health">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-telkomsel-red text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-heartbeat me-2"></i>Network Health Overview - Sumbagteng Region</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="text-telkomsel-red mb-3">
                                    <i class="fas fa-broadcast-tower fa-3x"></i>
                                </div>
                                <h2 class="text-telkomsel-red fw-bold" id="activeSites">-</h2>
                                <p class="mb-2 text-muted">Active Sites</p>
                                <small class="text-success fw-bold" id="siteTrend"><i class="fas fa-arrow-up me-1"></i> 2.3%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="text-telkomsel-green mb-3">
                                    <i class="fas fa-network-wired fa-3x"></i>
                                </div>
                                <h2 class="text-telkomsel-green fw-bold" id="networkHealth">-</h2>
                                <p class="mb-2 text-muted">Network Health</p>
                                <small class="text-success fw-bold" id="healthTrend"><i class="fas fa-arrow-up me-1"></i> 1.5%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="text-telkomsel-blue mb-3">
                                    <i class="fas fa-tachometer-alt fa-3x"></i>
                                </div>
                                <h2 class="text-telkomsel-blue fw-bold" id="dataThroughput">-</h2>
                                <p class="mb-2 text-muted">Data Throughput (Mbps)</p>
                                <small class="text-success fw-bold" id="throughputTrend"><i class="fas fa-arrow-up me-1"></i> 5.2%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="text-telkomsel-purple mb-3">
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                                <h2 class="text-telkomsel-purple fw-bold" id="subscriberGrowth">-</h2>
                                <p class="mb-2 text-muted">Subscriber Growth</p>
                                <small class="text-success fw-bold" id="subscriberTrend"><i class="fas fa-arrow-up me-1"></i> 3.8%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KPI Performance Cards dengan warna Telkomsel -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card performance-card bg-telkomsel-red text-white shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title opacity-75">TOTAL SOW</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($total_sow) }}</h2>
                        <div class="mt-2">
                            <span class="badge bg-white text-telkomsel-red">
                                <i class="fas fa-arrow-up me-1"></i>4.5%
                            </span>
                            <small class="opacity-75 ms-1">vs last month</small>
                        </div>
                    </div>
                    <div class="performance-icon">
                        <i class="fas fa-bullseye fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card performance-card bg-telkomsel-green text-white shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title opacity-75">COMPLETED</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($total_done) }}</h2>
                        <div class="mt-2">
                            <span class="badge bg-white text-telkomsel-green">
                                <i class="fas fa-arrow-up me-1"></i>2.3%
                            </span>
                            <small class="opacity-75 ms-1">vs last month</small>
                        </div>
                    </div>
                    <div class="performance-icon">
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card performance-card bg-telkomsel-orange text-white shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title opacity-75">IN PROGRESS</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($total_not_done) }}</h2>
                        <div class="mt-2">
                            <span class="badge bg-white text-telkomsel-orange">
                                <i class="fas fa-arrow-down me-1"></i>12.8%
                            </span>
                            <small class="opacity-75 ms-1">vs last month</small>
                        </div>
                    </div>
                    <div class="performance-icon">
                        <i class="fas fa-sync-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card performance-card bg-telkomsel-blue text-white shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title opacity-75">ACHIEVEMENT RATE</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($total_percentage, 1) }}%</h2>
                        <div class="progress mt-2 bg-white bg-opacity-25" style="height: 6px;">
                            <div class="progress-bar bg-white" style="width: {{ $total_percentage }}%"></div>
                        </div>
                        <small class="opacity-75">{{ $total_percentage >= 95 ? 'Exceeding Target' : 'On Track' }}</small>
                    </div>
                    <div class="performance-icon">
                        <i class="fas fa-trophy fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Network Performance Metrics -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-telkomsel-red text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-chart-line me-2"></i>Network Performance Trend</h6>
            </div>
            <div class="card-body">
                <canvas id="networkTrendChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-telkomsel-blue text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-mobile-alt me-2"></i>Technology Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="techDistributionChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Regional Performance -->
<div class="row mb-4" id="regional-performance">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-telkomsel-green text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-map-marked-alt me-2"></i>Regional Performance - Sumbagteng</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($city_labels as $index => $city)
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                        <div class="card border-0 h-100 shadow-sm regional-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title text-telkomsel-red fw-bold">{{ $city }}</h6>
                                    <span class="badge {{ $city_data_done[$index] >= 80 ? 'bg-telkomsel-green' : 'bg-telkomsel-orange' }}">
                                        {{ $city_data_done[$index] >= 80 ? 'Good' : 'Attention' }}
                                    </span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-telkomsel-red" 
                                         style="width: {{ ($city_data_done[$index] / max($city_data_done[$index] + $city_data_not_done[$index], 1)) * 100 }}%">
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted">Completed</small>
                                        <div class="fw-bold text-telkomsel-green">{{ $city_data_done[$index] }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">In Progress</small>
                                        <div class="fw-bold text-telkomsel-orange">{{ $city_data_not_done[$index] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-lg-4">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-telkomsel-purple text-white py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-pie me-2"></i>Program Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="kpiSowChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-telkomsel-orange text-white py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar me-2"></i>Partner Performance</h6>
            </div>
            <div class="card-body">
                <canvas id="partnerPerformanceChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Data Management Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-gradient-teal text-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-database me-2"></i>Real-time Data Management</h6>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addDataModal">
                    <i class="fas fa-plus me-1"></i>Add New Record
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Site Name</th>
                                <th>Program</th>
                                <th>Partner</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody">
                            <!-- Data akan di-load via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Data Modal -->
<div class="modal fade" id="addDataModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-telkomsel-red text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New SOW Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addDataForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Site Name *</label>
                                <input type="text" class="form-control" name="site_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Program *</label>
                                <select class="form-select" name="program_id" required>
                                    <option value="">Select Program</option>
                                    @foreach($capex_table_data as $program)
                                    <option value="{{ $loop->index + 1 }}">{{ $program->program_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Partner *</label>
                                <select class="form-select" name="partner_id" required>
                                    <option value="">Select Partner</option>
                                    @foreach($partner_labels as $index => $partner)
                                    <option value="{{ $index + 1 }}">{{ $partner }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">City *</label>
                                <select class="form-select" name="city" required>
                                    <option value="">Select City</option>
                                    @foreach($city_labels as $city)
                                    <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" required>
                                    <option value="Done">Done</option>
                                    <option value="Not Done">Not Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Progress (%)</label>
                                <input type="number" class="form-control" name="progress" min="0" max="100" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" class="form-control" name="latitude" placeholder="e.g., -2.5489">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" class="form-control" name="longitude" placeholder="e.g., 118.0149">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Save Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Data Modal -->
<div class="modal fade" id="editDataModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-telkomsel-orange text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit SOW Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDataForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Site Name *</label>
                                <input type="text" class="form-control" name="site_name" id="edit_site_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" id="edit_status" required>
                                    <option value="Done">Done</option>
                                    <option value="Not Done">Not Done</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Progress (%)</label>
                                <input type="number" class="form-control" name="progress" id="edit_progress" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Updated</label>
                                <input type="text" class="form-control" id="edit_updated_at" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync me-1"></i>Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CAPEX Table -->
<div class="row" id="capex">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-telkomsel-blue text-white py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-money-bill-wave me-2"></i>CAPEX Performance Overview</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Program Type</th>
                                <th class="text-end">Total SOW</th>
                                <th class="text-end">Completed</th>
                                <th class="text-end">In Progress</th>
                                <th class="text-end">Completion Rate</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($capex_table_data as $row)
                            <tr>
                                <td class="fw-bold">{{ $row->program_type }}</td>
                                <td class="text-end">{{ number_format($row->total) }}</td>
                                <td class="text-end text-success fw-bold">{{ number_format($row->done) }}</td>
                                <td class="text-end text-warning">{{ number_format($row->not_done) }}</td>
                                <td class="text-end">
                                    <span class="fw-bold {{ $row->percentage >= 85 ? 'text-success' : ($row->percentage >= 70 ? 'text-warning' : 'text-danger') }}">
                                        {{ number_format($row->percentage, 1) }}%
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($row->percentage >= 85)
                                    <span class="badge bg-success">On Track</span>
                                    @elseif($row->percentage >= 70)
                                    <span class="badge bg-warning">Needs Attention</span>
                                    @else
                                    <span class="badge bg-danger">At Risk</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="row" id="map-visualization">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-telkomsel-green text-white py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-map-marked-alt me-2"></i>Geographical Distribution</h6>
            </div>
            <div class="card-body">
                <div id="map" style="height: 500px; border-radius: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Quality of Service Metrics -->
<div class="row mb-4" id="qos-metrics">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-telkomsel-purple text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-star me-2"></i>Quality of Service (QoS) Metrics</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="qos-metric">
                                <div class="qos-value text-telkomsel-red fw-bold">99.2%</div>
                                <div class="qos-label">Network Availability</div>
                                <div class="qos-trend text-success">
                                    <i class="fas fa-arrow-up me-1"></i>0.3%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="qos-metric">
                                <div class="qos-value text-telkomsel-red fw-bold">98.7%</div>
                                <div class="qos-label">Service Reliability</div>
                                <div class="qos-trend text-success">
                                    <i class="fas fa-arrow-up me-1"></i>0.5%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="qos-metric">
                                <div class="qos-value text-telkomsel-red fw-bold">96.8%</div>
                                <div class="qos-label">Customer Satisfaction</div>
                                <div class="qos-trend text-warning">
                                    <i class="fas fa-minus me-1"></i>0.1%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Management Section -->
<div class="row" id="data-management">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-telkomsel-blue text-white py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-upload me-2"></i>Import Data SOW</h6>
            </div>
            <div class="card-body">
                <p>Import data SOW utama dari file CSV (format `detail1.csv`).</p>
                <form action="{{ route('data.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="mb-3">
                        <label for="sow_file" class="form-label">Pilih file SOW (CSV):</label>
                        <input class="form-control" type="file" id="sow_file" name="sow_file" accept=".csv" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="skip_header" name="skip_header" checked>
                        <label class="form-check-label" for="skip_header">
                            Lewati baris pertama (header)
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Mulai Import
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-telkomsel-green text-white py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-download me-2"></i>Export Data</h6>
            </div>
            <div class="card-body">
                <p>Export data SOW yang sudah bersih dan ter-normalisasi ke CSV.</p>
                <form action="{{ route('data.export') }}" method="POST" id="exportForm">
                    @csrf
                    <div class="mb-3">
                        <label for="export_data_type" class="form-label">Pilih Tipe Data:</label>
                        <select class="form-select" id="export_data_type" name="export_data_type">
                            <option value="sow_joined">SOW Data (Joined)</option>
                            <option value="partner_detail">Partner Detail</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Download CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-telkomsel-red me-2" id="shareBtn">
                        <i class="fas fa-share-alt me-1"></i>Share Dashboard
                    </button>
                    <button type="button" class="btn btn-telkomsel-orange me-2" id="exportBtn">
                        <i class="fas fa-file-export me-1"></i>Export Report
                    </button>
                    <button type="button" class="btn btn-telkomsel-green me-2" id="addDataBtn" data-bs-toggle="modal" data-bs-target="#addDataModal">
                        <i class="fas fa-plus me-1"></i>Add Data
                    </button>
                    <button type="button" class="btn btn-telkomsel-blue" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// CSRF Token untuk AJAX
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Real-time data update
function updateRealTimeData() {
    $.ajax({
        url: '{{ route("realtime.data") }}',
        method: 'GET',
        success: function(response) {
            $('#activeSites').text(response.active_sites);
            $('#networkHealth').text(response.network_health + '%');
            $('#dataThroughput').text(response.data_throughput);
            $('#subscriberGrowth').text(response.subscriber_growth.toLocaleString());
            
            // Update network status dengan animasi
            $('#networkStatus').html('LIVE <i class="fas fa-circle text-success blink ms-1" style="font-size: 0.7rem;"></i>');
        },
        error: function(xhr) {
            console.error('Error fetching real-time data:', xhr);
            $('#networkStatus').html('OFFLINE <i class="fas fa-circle text-danger ms-1" style="font-size: 0.7rem;"></i>');
        }
    });
}

// Update waktu real-time
function updateCurrentTime() {
    const now = new Date();
    $('#currentTime').text(now.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    }));
}

// Initialize semua fungsi ketika DOM ready
document.addEventListener("DOMContentLoaded", function() {
    // Setup AJAX untuk semua request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    // Load data pertama kali
    loadTableData();
    
    // Update data pertama kali
    updateRealTimeData();
    updateCurrentTime();
    
    // Set interval untuk update real-time
    setInterval(updateRealTimeData, 30000); // 30 detik
    setInterval(updateCurrentTime, 1000); // 1 detik
    
    // Initialize charts
    initializeTelkomselCharts();
    initializeCharts();

    // Add Data Form Submission
    $('#addDataForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("sow-data.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addDataModal').modal('hide');
                $('#addDataForm')[0].reset();
                
                // Reload data dan update dashboard
                loadTableData();
                showNotification('Data berhasil ditambahkan!', 'success');
                
                // Update charts setelah delay singkat
                setTimeout(updateDashboard, 1000);
            },
            error: function(xhr) {
                showNotification('Error: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'), 'error');
            }
        });
    });

    // Edit Data Form Submission
    $('#editDataForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();
        
        $.ajax({
            url: `/sow-data/${id}`,
            method: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            success: function(response) {
                $('#editDataModal').modal('hide');
                
                // Reload data dan update dashboard
                loadTableData();
                showNotification('Data berhasil diupdate!', 'success');
                
                // Update charts
                setTimeout(updateDashboard, 1000);
            },
            error: function(xhr) {
                showNotification('Error: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'), 'error');
            }
        });
    });

    // Share Functionality
    document.getElementById('shareBtn').addEventListener('click', function() {
        if (navigator.share) {
            navigator.share({
                title: 'Telkomsel RAN Dashboard',
                text: 'Check out this RAN Performance Dashboard from Telkomsel',
                url: window.location.href
            })
            .then(() => console.log('Successful share'))
            .catch((error) => console.log('Error sharing:', error));
        } else {
            // Fallback for browsers that don't support Web Share API
            const shareUrl = window.location.href;
            navigator.clipboard.writeText(shareUrl).then(() => {
                showNotification('Dashboard link copied to clipboard!', 'success');
            });
        }
    });

    // Export Functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        showNotification('Generating comprehensive report...', 'success');
        // Simulate export process
        setTimeout(() => {
            showNotification('Report generated successfully!', 'success');
        }, 2000);
    });
});

// Initialize Telkomsel charts
function initializeTelkomselCharts() {
    // Network Trend Chart
    const trendCtx = document.getElementById('networkTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Network Availability %',
                        data: [98.5, 98.7, 99.1, 99.2, 99.0, 99.3, 99.1, 99.4, 99.2, 99.5, 99.3, 99.2],
                        borderColor: '#E1000F',
                        backgroundColor: 'rgba(225, 0, 15, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Data Throughput (Mbps)',
                        data: [450, 480, 520, 550, 580, 600, 620, 650, 680, 700, 720, 750],
                        borderColor: '#00A650',
                        backgroundColor: 'rgba(0, 166, 80, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    // Technology Distribution Chart
    const techCtx = document.getElementById('techDistributionChart');
    if (techCtx) {
        new Chart(techCtx, {
            type: 'doughnut',
            data: {
                labels: ['4G LTE', '5G', '3G', '2G'],
                datasets: [{
                    data: [65, 20, 10, 5],
                    backgroundColor: [
                        '#E1000F',
                        '#00A650',
                        '#FF6B00',
                        '#6C63FF'
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });
    }
}

// Function untuk load table data via AJAX
function loadTableData() {
    $.ajax({
        url: '{{ route("sow-data.index") }}',
        method: 'GET',
        success: function(response) {
            const tbody = $('#dataTableBody');
            tbody.empty();
            
            if (response.data && response.data.length > 0) {
                response.data.forEach(item => {
                    const statusBadge = item.status === 'Done' ? 
                        '<span class="badge bg-success">Done</span>' : 
                        '<span class="badge bg-warning">In Progress</span>';
                    
                    const progressValue = item.progress || (item.status === 'Done' ? 100 : 50);
                    const progressBar = `
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar ${item.status === 'Done' ? 'bg-success' : 'bg-warning'}" 
                                 style="width: ${progressValue}%">
                            </div>
                        </div>
                        <small class="text-muted">${progressValue}%</small>
                    `;
                    
                    const row = `
                        <tr>
                            <td>${item.site_name || 'N/A'}</td>
                            <td>${item.program_type || 'N/A'}</td>
                            <td>${item.partner_name || 'N/A'}</td>
                            <td>${item.city || 'N/A'}</td>
                            <td>${statusBadge}</td>
                            <td>${progressBar}</td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1 edit-btn" data-id="${item.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            } else {
                tbody.append(`
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-database fa-2x mb-2"></i>
                            <p>No data available</p>
                        </td>
                    </tr>
                `);
            }
            
            // Attach event listeners untuk edit dan delete buttons
            attachEventListeners();
        },
        error: function(xhr) {
            console.error('Error loading data:', xhr);
            showNotification('Error loading data', 'error');
        }
    });
}

// Attach event listeners untuk action buttons
function attachEventListeners() {
    // Edit buttons
    $('.edit-btn').on('click', function() {
        const id = $(this).data('id');
        editData(id);
    });
    
    // Delete buttons
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        deleteData(id);
    });
}

// Edit data function
function editData(id) {
    $.ajax({
        url: `/sow-data/${id}/edit`,
        method: 'GET',
        success: function(response) {
            $('#edit_id').val(response.id);
            $('#edit_site_name').val(response.site_name);
            $('#edit_status').val(response.status);
            $('#edit_progress').val(response.progress || (response.status === 'Done' ? 100 : 50));
            $('#edit_updated_at').val(new Date(response.updated_at).toLocaleString());
            
            $('#editDataModal').modal('show');
        },
        error: function(xhr) {
            showNotification('Error loading data for edit', 'error');
        }
    });
}

// Delete data function
function deleteData(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            url: `/sow-data/${id}`,
            method: 'POST',
            data: { _method: 'DELETE' },
            success: function(response) {
                loadTableData();
                showNotification('Data berhasil dihapus!', 'success');
                setTimeout(updateDashboard, 1000);
            },
            error: function(xhr) {
                showNotification('Error deleting data', 'error');
            }
        });
    }
}

// Update dashboard data
function updateDashboard() {
    // Untuk sekarang reload page, bisa dioptimasi dengan AJAX call specific
    location.reload();
}

// Notification function
function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${icon} me-2"></i>
            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('body').append(alert);
    
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
}

// Initialize charts dengan warna vibrant
function initializeCharts() {
    // KPI SOW Chart dengan warna vibrant
    const kpiSowCtx = document.getElementById('kpiSowChart');
    if (kpiSowCtx) {
        new Chart(kpiSowCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($kpi_sow_labels) !!},
                datasets: [{
                    data: {!! json_encode($kpi_sow_data) !!},
                    backgroundColor: [
                        '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4',
                        '#FFEAA7', '#DDA0DD', '#98D8C8', '#F7DC6F',
                        '#BB8FCE', '#85C1E9', '#F8C471', '#82E0AA'
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Partner Performance Chart
    const partnerCtx = document.getElementById('partnerPerformanceChart');
    if (partnerCtx) {
        new Chart(partnerCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($partner_labels) !!},
                datasets: [
                    {
                        label: 'Completed',
                        data: {!! json_encode($partner_data_done) !!},
                        backgroundColor: 'rgba(46, 204, 113, 0.8)',
                        borderColor: 'rgba(46, 204, 113, 1)',
                        borderWidth: 2,
                        borderRadius: 5
                    },
                    {
                        label: 'In Progress',
                        data: {!! json_encode($partner_data_not_done) !!},
                        backgroundColor: 'rgba(241, 196, 15, 0.8)',
                        borderColor: 'rgba(241, 196, 15, 1)',
                        borderWidth: 2,
                        borderRadius: 5
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Map Implementation dengan marker berwarna
    const map = L.map('map').setView([-2.5489, 118.0149], 6);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const sitesData = {!! json_encode($sites_data) !!};
    
    sitesData.forEach(site => {
        if (site.latitude && site.longitude) {
            const markerColor = site.status === 'Done' ? '#2ecc71' : '#f39c12';
            const icon = L.divIcon({
                className: 'custom-marker',
                html: `<div style="background-color: ${markerColor}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
                iconSize: [26, 26],
                iconAnchor: [13, 13]
            });

            L.marker([site.latitude, site.longitude], {icon: icon})
                .addTo(map)
                .bindPopup(`
                    <div class="map-popup" style="min-width: 200px;">
                        <h6 class="fw-bold text-primary">${site.site_name}</h6>
                        <div class="row small">
                            <div class="col-6">
                                <strong>Status:</strong><br>
                                <span class="badge ${site.status === 'Done' ? 'bg-success' : 'bg-warning'}">
                                    ${site.status}
                                </span>
                            </div>
                            <div class="col-6">
                                <strong>Program:</strong><br>
                                ${site.program_type}
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Partner:</strong> ${site.partner_name}<br>
                            <strong>City:</strong> ${site.city}
                        </div>
                    </div>
                `);
        }
    });
}
</script>

<style>
/* Performance Cards */
.performance-card {
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.performance-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: rgba(255,255,255,0.3);
}

.performance-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
}

.performance-icon {
    opacity: 0.8;
    transition: all 0.3s ease;
}

.performance-card:hover .performance-icon {
    transform: scale(1.1);
    opacity: 1;
}

/* QoS Metrics */
.qos-metric {
    padding: 20px;
    border-radius: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.qos-metric:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    border-color: var(--telkomsel-red);
}

.qos-value {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    font-weight: 800;
}

.qos-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.qos-trend {
    font-size: 0.8rem;
    font-weight: 600;
}

/* Regional Cards */
.regional-card {
    transition: all 0.3s ease;
    border-left: 4px solid var(--telkomsel-red);
}

.regional-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Telkomsel Buttons */
.btn-telkomsel-red {
    background-color: var(--telkomsel-red);
    color: white;
    border: none;
}

.btn-telkomsel-red:hover {
    background-color: #c1000c;
    color: white;
    transform: translateY(-1px);
}

.btn-telkomsel-green {
    background-color: var(--telkomsel-green);
    color: white;
    border: none;
}

.btn-telkomsel-green:hover {
    background-color: #008a47;
    color: white;
    transform: translateY(-1px);
}

.btn-telkomsel-orange {
    background-color: var(--telkomsel-orange);
    color: white;
    border: none;
}

.btn-telkomsel-orange:hover {
    background-color: #e05a00;
    color: white;
    transform: translateY(-1px);
}

.btn-telkomsel-blue {
    background-color: var(--telkomsel-blue);
    color: white;
    border: none;
}

.btn-telkomsel-blue:hover {
    background-color: #004586;
    color: white;
    transform: translateY(-1px);
}

/* Gradient Backgrounds */
.bg-gradient-teal {
    background: linear-gradient(135deg, #0cebeb 0%, #20e3b2 50%, #29ffc6 100%) !important;
}

/* Card Hover Effects */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15) !important;
}

/* Custom Badges */
.badge {
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
}

/* Network Status */
.network-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .qos-value {
        font-size: 2rem;
    }
    
    .performance-card .card-body {
        padding: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.5rem;
        width: 100%;
    }
}

/* Map Styling */
#map {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.custom-marker {
    background: transparent;
    border: none;
}

/* Table Styling */
.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,0.02);
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Progress Bar */
.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
}

/* Modal Styling */
.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    border-radius: 15px 15px 0 0;
}

/* Chart Container */
.card-body canvas {
    border-radius: 10px;
}
</style>
@endpush