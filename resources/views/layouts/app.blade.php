<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RAN Performance Dashboard - Telkomsel')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root {
            --telkomsel-red: #E1000F;
            --telkomsel-green: #00A650;
            --telkomsel-orange: #FF6B00;
            --telkomsel-blue: #0056A3;
            --telkomsel-purple: #6C63FF;
            --telkomsel-dark: #1A1A1A;
            --telkomsel-light: #F8F9FA;
        }

        .bg-telkomsel-red { background-color: var(--telkomsel-red) !important; }
        .bg-telkomsel-green { background-color: var(--telkomsel-green) !important; }
        .bg-telkomsel-orange { background-color: var(--telkomsel-orange) !important; }
        .bg-telkomsel-blue { background-color: var(--telkomsel-blue) !important; }
        .bg-telkomsel-purple { background-color: var(--telkomsel-purple) !important; }
        .bg-telkomsel-dark { background-color: var(--telkomsel-dark) !important; }
        .bg-telkomsel-light { background-color: var(--telkomsel-light) !important; }

        .text-telkomsel-red { color: var(--telkomsel-red) !important; }
        .text-telkomsel-green { color: var(--telkomsel-green) !important; }
        .text-telkomsel-orange { color: var(--telkomsel-orange) !important; }
        .text-telkomsel-blue { color: var(--telkomsel-blue) !important; }
        .text-telkomsel-purple { color: var(--telkomsel-purple) !important; }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
        }

        .telkomsel-logo {
            width: 32px;
            height: 32px;
            background: var(--telkomsel-red);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .stat-card { 
            transition: transform 0.2s; 
        }
        .stat-card:hover { 
            transform: translateY(-2px); 
        }
        
        .table-responsive { 
            max-height: 500px; 
        }
        .sticky-top { 
            position: sticky; 
            top: 0; 
        }
        .sticky-bottom { 
            position: sticky; 
            bottom: 0; 
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--telkomsel-red);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #c1000c;
        }

        /* Loading Animation */
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--telkomsel-red);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Blink Animation */
        .blink {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.3; }
            100% { opacity: 1; }
        }

        /* Footer Styling */
        footer {
            border-top: 3px solid var(--telkomsel-red);
        }
    </style>
</head>
<body class="bg-telkomsel-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-telkomsel-dark sticky-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/">
                <div class="d-flex align-items-center">
                    <div class="telkomsel-logo me-2">
                        <i class="fas fa-satellite-dish text-white"></i>
                    </div>
                    <div>
                        <span class="text-white">TELKOMSEL</span>
                        <small class="d-block text-telkomsel-red" style="font-size: 0.7rem; line-height: 1;">RAN MONITORING SYSTEM</small>
                    </div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#network-health">
                            <i class="fas fa-heartbeat me-1"></i> Network Health
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#map-visualization">
                            <i class="fas fa-map-marked-alt me-1"></i> Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#regional-performance">
                            <i class="fas fa-map me-1"></i> Regional
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#partner-detail">
                            <i class="fas fa-handshake me-1"></i> Partner
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#capex">
                            <i class="fas fa-money-bill-wave me-1"></i> CAPEX
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#qos-metrics">
                            <i class="fas fa-star me-1"></i> QoS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#data-management">
                            <i class="fas fa-database me-1"></i> Data
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5 py-4 bg-white shadow">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 text-start">
                    <p class="text-muted mb-0">
                        <i class="fas fa-shield-alt text-telkomsel-red me-1"></i>
                        <strong>Secure Connection</strong> - All data is encrypted and secured
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted mb-0">
                        &copy; {{ date('Y') }} <strong class="text-telkomsel-red">Telkomsel</strong> Regional Sumbagteng. 
                        <span class="d-none d-md-inline">Dibuat dengan Laravel & Bootstrap 5.</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    
    @stack('scripts')
</body>
</html>