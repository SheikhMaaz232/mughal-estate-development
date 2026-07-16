@extends('layouts.backend')

@section('title', 'Land Registry Report - Land Registration System')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .report-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }
    .summary-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .summary-card:hover {
        transform: translateY(-5px);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.1);
    }
    .badge-status {
        font-size: 0.8em;
        padding: 0.5em 0.8em;
    }
    .export-btn {
        border-radius: 25px;
        padding: 0.5rem 2rem;
    }
    .filter-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .area-badge {
        background: #17a2b8;
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    .transfer-details {
        background: #f8f9fa;
        border-radius: 5px;
        padding: 1rem;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="report-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold">
                        <i class="fas fa-landmark me-3"></i>
                        Land Registry Report
                    </h1>
                    <p class="lead mb-0">Land Registration System - Comprehensive Land Transfer Overview</p>
                </div>
                <div class="col-md-4 text-end">
                    <small>Generated on: {{ \Carbon\Carbon::now()->format('M d, Y h:i A') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filters Section -->
        <div class="filter-section">
            <form action="{{ route('land-report.registry') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $filters['date_from'] }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $filters['date_to'] }}">
                    </div>
                    <div class="col-md-3">
                        <label for="project_id" class="form-label">Project</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">All Projects</option>
                            @foreach($dropdownData['projects'] as $project)
                                <option value="{{ $project->id }}" {{ $filters['project_id'] == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="registry_type_id" class="form-label">Registry Type</label>
                        <select class="form-select" id="registry_type_id" name="registry_type_id">
                            <option value="">All Types</option>
                            @foreach($dropdownData['registryTypes'] as $type)
                                <option value="{{ $type->id }}" {{ $filters['registry_type_id'] == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="seller_account_id" class="form-label">Seller</label>
                        <select class="form-select" id="seller_account_id" name="seller_account_id">
                            <option value="">All Sellers</option>
                            @foreach($dropdownData['sellers'] as $seller)
                                <option value="{{ $seller->id }}" {{ $filters['seller_account_id'] == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->account_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="buyer_account_id" class="form-label">Buyer</label>
                        <select class="form-select" id="buyer_account_id" name="buyer_account_id">
                            <option value="">All Buyers</option>
                            @foreach($dropdownData['buyers'] as $buyer)
                                <option value="{{ $buyer->id }}" {{ $filters['buyer_account_id'] == $buyer->id ? 'selected' : '' }}>
                                    {{ $buyer->account_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="khawat_no" class="form-label">Khawat Number</label>
                        <input type="text" class="form-control" id="khawat_no" name="khawat_no" value="{{ $filters['khawat_no'] }}" placeholder="Enter Khawat No">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card summary-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Lands</h6>
                                <h2 class="mb-0">{{ $summary['total_lands'] }}</h2>
                            </div>
                            <i class="fas fa-map-marked-alt fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Area</h6>
                                <h4 class="mb-0">
                                    {{ $summary['total_area_converted']['kanal'] }}-{{ $summary['total_area_converted']['marla'] }}
                                    <small>(K-M)</small>
                                </h4>
                            </div>
                            <i class="fas fa-ruler-combined fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Value</h6>
                                <h4 class="mb-0">Rs. {{ number_format($summary['total_value'], 2) }}</h4>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Commission</h6>
                                <h4 class="mb-0">Rs. {{ number_format($summary['total_commission'], 2) }}</h4>
                            </div>
                            <i class="fas fa-hand-holding-usd fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>
                        <i class="fas fa-list me-2"></i>
                        Land Transfer Records
                    </h3>
                    <div>
                        <a href="{{ route('land-report.export-pdf', $filters) }}" class="btn btn-success export-btn me-2" target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                        <a href="{{ route('land-report.export-excel', $filters) }}" class="btn btn-success export-btn">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Land ID</th>
                                <th>Seller</th>
                                <th>Buyer</th>
                                <th>Area Details</th>
                                <th>Land Amount</th>
                                <th>Commission</th>
                                <th>Project</th>
                                <th>Registration Date</th>
                                <th>Transfers</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lands as $index => $land)
                                @php
                                    $areaConverted = $summary['total_area_converted'];
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>LND-{{ $land->id }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $land->seller->account_name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $land->seller_account_id }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $land->buyer->account_name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $land->buyer_account_id }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="area-badge">
                                                {{ $areaConverted['kanal'] }}-{{ $areaConverted['marla'] }} (K-M)
                                            </span>
                                            <small class="text-muted mt-1">
                                                Marla: {{ $land->total_marla }} | 
                                                Acre: {{ $land->total_acre ?? '0' }} |
                                                SqFt: {{ $land->total_square_feet ?? '0' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>Rs. {{ number_format($land->land_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        Rs. {{ number_format($land->commission_amount ?? 0, 2) }}
                                    </td>
                                    <td>
                                        {{ $land->project->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $land->created_at->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $land->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $land->transfers->count() }} Transfer(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="View Transfer Details" 
                                                    onclick="viewTransferDetails({{ $land->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-info" title="Print Details" 
                                                    onclick="printLandDetails({{ $land->id }})">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5>No land records found</h5>
                                        <p class="text-muted">No data available for the selected criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($lands->count() > 0)
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $lands->count() }} records
                        </div>
                        <!-- Add pagination links if needed -->
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Transfer Details Modal -->
<div class="modal fade" id="transferDetailsModal" tabindex="-1" aria-labelledby="transferDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferDetailsModalLabel">Land Transfer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="transferDetailsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<script>
    function viewTransferDetails(landId) {
        // Show loading
        $('#transferDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading details...</p></div>');
        
        // Fetch transfer details via AJAX
        fetch(`/land-reports/transfer-details/${landId}`)
            .then(response => response.text())
            .then(data => {
                $('#transferDetailsContent').html(data);
                $('#transferDetailsModal').modal('show');
            })
            .catch(error => {
                $('#transferDetailsContent').html('<div class="alert alert-danger">Error loading details</div>');
            });
    }

    function printLandDetails(landId) {
        // Implement print functionality
        alert('Print functionality for land ID: ' + landId);
        // You can open a new window with print-friendly content
    }

    function exportToPDF() {
        // This will use the server-side PDF export
        window.location.href = '{{ route("land-report.export-pdf", $filters) }}';
    }

    function exportToExcel() {
        // This will use the server-side Excel export
        window.location.href = '{{ route("land-report.export-excel", $filters) }}';
    }
</script>
@endsection