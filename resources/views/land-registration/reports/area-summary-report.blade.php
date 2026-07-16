@extends('layouts.backend')

@section('title', 'Area Summary Report - Land Registration')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<style>
    .chosen-container {
        width: 135px !important;
    }

    tr.border_bottom th {
        border-bottom: 1px solid black !important;
        border-width: 2px;
    }

    tr.border_bottom_content td {
        border-bottom: 1px solid grey !important;
        border-width: 2px;
    }

    .table-content td {
        vertical-align: top;
    }

    .other-container {
        width: 199px !important;
    }

    .print-font-size {
        font-size: 8px !important;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }

    .scroll-table {
        float: left;
        width: 73%;
    }

    .divformCell {
        width: 100%;
    }

    .print-style {
        direction: rtl;
        text-align: right;
    }

    .report-table {
        width: 100%;
        direction: rtl;
        text-align: right;
    }

    .area-summary-section {
        margin-bottom: 2rem;
    }
</style>
@endsection

@section('content')

<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.area_summary_report')</h3>
        <div class="block-options">
            <button type="button" class="btn btn-sm btn-alt-primary me-2 no-print" id="print">
                <i class="fa fa-print me-1"></i> @lang('messages.print_report')
            </button>
            <button type="button" class="btn btn-sm btn-alt-success no-print" id="all-print">
                <i class="fa fa-file-pdf me-1"></i>@lang('messages.print_all')
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="block-content block-content-full">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('land-report.area-summary') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="seller_id" class="form-label">@lang('messages.party')</label>
                    <select class="form-control chosen-select" id="seller_id" name="seller_id">
                        <option value="">@lang('messages.select-an-option')</option>
                        @foreach($dropdownData['sellers'] as $seller)
                            <option value="{{ $seller->id }}" {{ ($filters['seller_id'] ?? '') == $seller->id ? 'selected' : '' }}>
                                {{ $seller->name_ur }} - {{ $seller->cnic ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{--  <div class="col-md-3">
                    <label for="to_cnic_no" class="form-label">CNIC No</label>
                    <select class="form-control chosen-select" id="to_cnic_no" name="to_cnic_no">
                        <option value="">Select CNIC</option>
                        @foreach($dropdownData['buyers'] as $buyer)
                            <option value="{{ $buyer->id }}" {{ ($filters['to_cnic_no'] ?? '') == $buyer->id ? 'selected' : '' }}>
                                {{ $buyer->account_name }} - {{ $buyer->cnic ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>  --}}

                <div class="col-md-3">
                    <label for="khawat_no" class="form-label">@lang('messages.khawat_number')</label>
                    <input type="text" class="form-control" id="khawat_no" name="khawat_no"
                           value="{{ $filters['khawat_no'] ?? '' }}" placeholder="@lang('messages.khawat_number')">
                </div>
            <div class="col-md-3">
                    <label for="registry_type_name" class="form-label">@lang('messages.registry_type')</label>
                    <select class="form-control chosen-select" id="registry_type_name" name="registry_type_name">
                        <option value="">@lang('messages.select-an-option')</option>
                        @foreach($dropdownData['registryTypes'] as $type)
                            <option value="{{ $type->id }}" {{ ($filters['registry_type_name'] ?? '') == $type->id ? 'selected' : '' }}>
                                {{ $type->title_ur }}
                            </option>
                        @endforeach
                    </select>
                </div>

            <div class="col-md-3">
                    <label for="fard_no" class="form-label">@lang('messages.fard_number')</label>
                    <input type="text" class="form-control" id="fard_no" name="fard_no"
                           value="{{ $filters['fard_no'] ?? '' }}" placeholder="@lang('messages.fard_number')">
                </div>
            </div>

            <div class="row mt-3">
{{--
            <div class="col-md-3">
                    <label for="fard_no" class="form-label">Fard Number</label>
                    <input type="text" class="form-control" id="fard_no" name="fard_no"
                           value="{{ $filters['fard_no'] ?? '' }}" placeholder="Enter fard numbers">
                </div>  --}}
                {{--  <div class="col-md-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control"
                           value="{{ $filters['date_from'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control"
                           value="{{ $filters['date_to'] ?? '' }}">
                </div>  --}}

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" name="search" class="btn btn-alt-primary me-2">
                        <i class="fa fa-search me-1"></i> @lang('messages.search')
                    </button>
                    <a href="{{ route('land-report.area-summary') }}" class="btn btn-alt-secondary">
                        <i class="fa fa-refresh me-1"></i> @lang('messages.reset')
                    </a>
                </div>
            </div>
        </form>

        @if(request()->has('search'))
        <!-- Report Content -->
        <div class="divformCell" id="print-all-content">
            @if(count($reportData['landData']) > 0)
            <!-- Hidden Header for Print -->
            <div id="area-heading" style="display: none;">
                <div style="text-align: center;margin-bottom: 2%;">
                    <h2>{{ config('app.name_urdu', 'کمپنی کا نام') }}</h2>
                    <h3>ایریا سمری</h3>
                    <h4>مورخہ: {{ date('d-m-Y') }}</h4>
                </div>
            </div>

            <!-- Area Summary Section -->
            <div class="area-summary-section" id="print-content">
                <div class="table-responsive">
                    <table class="report-table"  cellspacing="10" width="100%">
                        <thead>
                            <tr class="border_bottom" align="right">
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">بقیہ رقبہ<br>(یارڈ-مرلہ-کنال)</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">منتقلی رقبہ<br>(یارڈ-مرلہ-کنال)</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">کل رقبہ<br>(یارڈ-مرلہ-کنال)</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">کھیوٹ</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">منجانب</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">بحق</th>
                                <th style="border-bottom: 1px solid grey !important;border-width: 2px;">دستاویز</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['landData'] as $index => $land)
                            @php
                                $marlaConversion = $reportData['totalInMarla'];
                                $transferConversion = $reportData['transferData']['totalOutConverted'];
                                $balanceConversion = $reportData['balanceData']['grandTotalConverted'];
                            @endphp
                            <tr align="right">
                                <td>
                                    {{ $balanceConversion['kanal'] }}-{{ $balanceConversion['marla'] }}-{{ $balanceConversion['yard'] }}
                                </td>
                                <td>
                                    {{ $transferConversion['kanal'] }}-{{ $transferConversion['marla'] }}-{{ $transferConversion['yard'] }}
                                </td>
                                <td>
                                    {{ $marlaConversion['kanal'] }}-{{ $marlaConversion['marla'] }}-{{ $marlaConversion['yard'] }}
                                </td>
                                <td>{{ $land->khawat_no ?? 'N/A' }}</td>
                                <td>
                                    {{ $land->party_name }}
                                </td>
                                <td>
                                    {{ $land->party_name }}
                                </td>
                                <td>
                                   {{ $land->registry_type_name }}
                                </td>
                            </tr>
                            @endforeach

                            <!-- Total Row -->
                            <tr>
                                <td style="border-top:3px double;border-bottom:3px double;">
                                    {{ $reportData['balanceData']['grandTotalConverted']['kanal'] }}-{{ $reportData['balanceData']['grandTotalConverted']['marla'] }}-{{ $reportData['balanceData']['grandTotalConverted']['yard'] }}
                                </td>
                                <td style="border-top:3px double;border-bottom:3px double;">
                                    {{ $reportData['transferData']['totalOutConverted']['kanal'] }}-{{ $reportData['transferData']['totalOutConverted']['marla'] }}-{{ $reportData['transferData']['totalOutConverted']['yard'] }}
                                </td>
                                <td style="border-top:3px double;border-bottom:3px double;">
                                    {{ $reportData['totalInMarla']['kanal'] }}-{{ $reportData['totalInMarla']['marla'] }}-{{ $reportData['totalInMarla']['yard'] }}
                                </td>
                                <td colspan="4" style="border-top:3px double;border-bottom:3px double; text-align: center;">کل</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Transfer Details Section -->
            <div class="mt-5">
                <h4 class="text-end" style="float: right;">منتقل رقبہ کی تفصیل</h4>
                <div class="clearfix"></div>

                <div class="table-responsive mt-3">
                    <table class="report-table table-content" cellspacing="10" width="100%">
                        <thead>
                            <tr class="border_bottom" align="right">
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">بقیہ رقبہ<br>(یارڈ-مرلہ-کنال)</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">منتقلہ رقبہ<br>(یارڈ-مرلہ-کنال)</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">بائع</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">بزریعہ</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">کھیوٹ</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">بحق</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">فرد نمبر</th>
                                <th style="border-bottom: 1px solid black !important;border-width: 2px;">دستاویز</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $balance = $reportData['totalMarlas'];
                                $totalDetailMarla = 0;
                            @endphp

                            @foreach($reportData['landData'] as $index => $land)
                            @php
                                $currentMarla = $land->total_marla ?? 0;
                                $totalDetailMarla += $currentMarla;
                                $balance -= $currentMarla;
                                $currentConversion = $reportData['totalInMarla'];
                                $balanceConversion = $reportData['totalInMarla'];
                            @endphp

                            <tr class="border_bottom_content" align="right">
                                <td style="border-bottom: 1px solid grey !important;border-width: 2px;">
                                    {{ $balanceConversion['kanal'] }}-{{ $balanceConversion['marla'] }}-{{ $balanceConversion['yard'] }}
                                </td>
                                <td style="border-bottom: 1px solid grey !important;border-width: 2px;">
                                    {{ $currentConversion['kanal'] }}-{{ $currentConversion['marla'] }}-{{ $currentConversion['yard'] }}
                                </td>
                                <td style="border-bottom: 1px solid grey !important;border-width: 2px;">
                                    فروش کا نام<br>
                                    <span style="font-size:11px;">قومی شناختی کارڈ</span>
                                </td>
                                <td style="border-bottom: 1px solid grey !important;border-width: 2px;">
                                    سورس رجسٹری<br>
                                    <span style="font-size:11px;">سورس نمبر</span>
                                </td>
                                <td style="border-bottom: 1px solid grey !important;border-width: 2px;">
                                    {{ $land->khawat_no ?? 'N/A' }}<br>
                                    موزہ کا نام
                                </td>
                                <td style="border-bottom: 1px solid grey !important;border-width: 2px;">
                                   {{ $land->party_name }}<br>
                                </td>
                                <td>
                                   {{ $land->fard_no }}
                                </td>
                                <td style="border-bottom: 1px solid grey !important;border-width: 2px;">
                                    <a href="javascript:void(0)" onclick="getImages({{ $land->id }})" class="text-decoration-none">
                                       {{ $land->registry_type_name }}<br>
                                        <span style="font-size:11px;">{{ $land->registry_no }}</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Total Row for Transfer Details -->
                            <tr>
                                <td style="border-top:3px double;border-bottom:3px double;">
                                    {{ $reportData['balanceData']['grandTotalConverted']['kanal'] }}-{{ $reportData['balanceData']['grandTotalConverted']['marla'] }}-{{ $reportData['balanceData']['grandTotalConverted']['yard'] }}
                                </td>
                                <td style="border-top:3px double;border-bottom:3px double;">
                                    {{ $reportData['totalInMarla']['kanal'] }}-{{ $reportData['totalInMarla']['marla'] }}-{{ $reportData['totalInMarla']['yard'] }}
                                </td>
                                <td colspan="6" style="border-top:3px double;border-bottom:3px double; text-align: center;">کل</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <!-- No Records Found -->
            <div class="text-center py-4">
                <i class="fa fa-inbox fa-3x text-muted"></i>
                <p class="mt-2">@lang('messages.no-record')</p>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 no-print">
            <div class="d-flex justify-content-start">
                <a href="{{ route('land-report.export-pdf', $filters) }}"
                   class="btn btn-alt-success me-2" target="_blank">
                    <i class="fa fa-file-pdf me-1"></i> @lang('messages.export_pdf')
                </a>
                <button type="button" class="btn btn-alt-info" onclick="exportToExcel()">
                    <i class="fa fa-file-excel me-1"></i> @lang('messages.export_excel')
                </button>
            </div>
        </div>
        @else
        <!-- Initial State -->
        <div class="text-center py-5">
            <i class="fa fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">@lang('messages.use_filters')</h4>
            <p class="text-muted">@lang('messages.select_criteria')</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>

<script>
    // Initialize chosen select
    $(document).ready(function() {
        $('.chosen-select').chosen();
    });

    // Print functionality
    $("#print").click(function () {
        var area_heading = $('#area-heading').html();
        var contents = $("#print-content").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px", "font-size": "10px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Area Summary Report</title>');
        frameDoc.document.write('<style>@media print { .print-style { font-size: 12px !important; } }</style>');
        frameDoc.document.write('</head><body class="print-font-size">');
        frameDoc.document.write('<link href="{{ asset("assets/css/style.css") }}" rel="stylesheet" type="text/css" />');
        frameDoc.document.write('<div style="text-align:right;font-size:6px !important;"></div>');
        frameDoc.document.write(area_heading + contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    });

    // Full page print
    $("#all-print").click(function () {
        var area_heading = $('#area-heading').html();
        var contents = $("#print-all-content").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px", "font-size": "10px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Area Summary Report</title>');
        frameDoc.document.write('<style>@media print { .print-style { font-size: 12px !important; } }</style>');
        frameDoc.document.write('</head><body class="print-font-size">');
        frameDoc.document.write('<link href="{{ asset("assets/css/style.css") }}" rel="stylesheet" type="text/css" />');
        frameDoc.document.write('<div style="text-align:right;font-size:6px !important;"></div>');
        frameDoc.document.write(area_heading + contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    });

    function getImages(id) {
        // Implement image viewing functionality
        alert('View images for ID: ' + id);
    }

    function exportToExcel() {
        // Implement Excel export functionality
        alert('Excel export functionality would be implemented here');
    }
</script>
@endsection
