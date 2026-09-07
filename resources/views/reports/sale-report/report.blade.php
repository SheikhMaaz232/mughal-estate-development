@extends('layouts.backend')

@section('content')

    <style>
        .report-page {
            background: #f5f6fa;
            min-height: 100vh;
            padding: 25px 0;
        }

        .report-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .report-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .report-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .report-title {
            margin: 0;
            font-size: 25px;
            font-weight: 700;
            color: #1f2937;
        }

        .report-subtitle {
            margin-top: 6px;
            color: #6b7280;
            font-size: 14px;
        }

        .report-actions {
            display: flex;
            gap: 10px;
        }

        .report-actions .btn {
            min-width: 100px;
        }

        .report-info {
            padding: 18px 30px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item {
            display: inline-flex;
            margin-right: 35px;
            font-size: 14px;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
            margin-right: 7px;
        }

        .info-value {
            color: #111827;
        }

        .section-header {
            padding: 18px 25px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .section-badge {
            font-size: 13px;
            padding: 5px 10px;
            border-radius: 20px;
            background: #eef2ff;
            color: #4338ca;
            font-weight: 600;
        }

        .table-wrapper {
            padding: 20px 25px 25px;
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .report-table thead th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 700;
            border: 1px solid #d1d5db;
            padding: 11px 10px;
            white-space: nowrap;
            text-align: left;
        }

        .report-table tbody td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            vertical-align: middle;
            color: #374151;
        }

        .report-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .report-table tbody tr:hover {
            background: #f9fafb;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .amount {
            font-weight: 600;
            white-space: nowrap;
        }

        .total-row {
            background: #f3f4f6 !important;
            font-weight: 700;
        }

        .total-row td {
            color: #111827 !important;
        }

        .summary-section {
            padding: 25px;
        }

        .summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 18px;
            background: #fff;
            height: 100%;
        }

        .summary-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 35px;
            margin-bottom: 10px;
            display: block;
            color: #9ca3af;
        }

        .report-footer {
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
            text-align: center;
        }

        /* Urdu */
        [dir="rtl"] .report-table th,
        [dir="rtl"] .report-table td {
            text-align: right;
        }

        [dir="rtl"] .info-item {
            margin-right: 0;
            margin-left: 35px;
        }

        [dir="rtl"] .info-label {
            margin-right: 0;
            margin-left: 7px;
        }

        /* Print */
        @media print {

            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body {
                background: #fff !important;
            }

            .report-page {
                background: #fff !important;
                padding: 0 !important;
            }

            .report-container {
                max-width: 100% !important;
            }

            .report-card {
                box-shadow: none !important;
                border-radius: 0 !important;
                margin-bottom: 15px;
            }

            .no-print,
            .navbar,
            .main-sidebar,
            .main-header,
            .main-footer,
            .sidebar {
                display: none !important;
            }

            .report-header {
                padding: 10px 0 15px;
            }

            .report-title {
                font-size: 22px;
            }

            .report-info {
                padding: 10px 0;
            }

            .section-header {
                padding: 10px 0;
            }

            .table-wrapper {
                padding: 10px 0 20px;
                overflow: visible !important;
            }

            .report-table {
                font-size: 10px;
            }

            .report-table thead th {
                padding: 6px;
                background: #eee !important;
                color: #000 !important;
            }

            .report-table tbody td {
                padding: 6px;
                color: #000 !important;
            }

            .summary-section {
                padding: 10px 0;
            }

            .summary-card {
                padding: 10px;
            }

            .summary-value {
                font-size: 16px;
            }

            .report-footer {
                padding: 10px 0;
            }

            .report-table tr {
                page-break-inside: avoid;
            }

            .section-header,
            .report-header {
                page-break-after: avoid;
            }
        }
    </style>

    @php
        $isUrdu = app()->getLocale() == 'ur';
    @endphp

    <div class="report-page">

        <div class="container-fluid report-container">

            {{-- Report Header --}}
            <div class="report-card">

                <div class="report-header">

                    <div>
                        <h1 class="report-title">
                            <i class="fas fa-chart-line mr-2"></i>
                            {{ __('messages.sale_report') }}
                        </h1>

                        <div class="report-subtitle">
                            {{ $isUrdu ? 'تاریخ اور پروجیکٹ کے حساب سے سیل رپورٹ' : 'Date-wise and project-wise sales report' }}
                        </div>
                    </div>

                    <div class="report-actions no-print">

                        {{-- Back Button --}}
                        <a href="{{ route('reports.sale-report.filter') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            {{ __('messages.back') }}
                        </a>

                        {{-- Print Button --}}
                        <button type="button" onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print mr-1"></i>
                            {{ __('messages.print') }}
                        </button>

                    </div>

                </div>

                {{-- Filter Information --}}
                <div class="report-info">

                    <div class="info-item">
                        <span class="info-label">
                            {{ __('messages.from_date') }}:
                        </span>

                        <span class="info-value">
                            {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d-m-Y') : __('messages.all') }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            {{ __('messages.to_date') }}:
                        </span>

                        <span class="info-value">
                            {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d-m-Y') : __('messages.all') }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            {{ __('messages.project') }}:
                        </span>

                        <span class="info-value">

                            @php
                                $selectedProject = null;

                                if ($projectId) {
                                    $selectedProject = DB::table('projects')->where('id', $projectId)->first();
                                }
                            @endphp

                            @if ($selectedProject)
                                {{ $isUrdu ? $selectedProject->name_ur : $selectedProject->name_en }}
                            @else
                                {{ __('messages.all_projects') }}
                            @endif

                        </span>
                    </div>

                </div>

            </div>


            {{-- DIRECT PRODUCTS --}}
            <div class="report-card">

                <div class="section-header">

                    <h2 class="section-title">
                        <i class="fas fa-building mr-2"></i>
                        {{ __('messages.direct_products') }}
                    </h2>

                    <span class="section-badge">
                        {{ $directBookings->count() }}
                        {{ $isUrdu ? 'ریکارڈز' : 'Records' }}
                    </span>

                </div>

                @if ($directBookings->count() > 0)
                    <div class="table-wrapper">

                        <table class="report-table">

                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.booking_no') }}</th>
                                    <th>{{ __('messages.project_name') }}</th>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.unit_no') }}</th>
                                    <th>{{ __('messages.purchaser') }}</th>
                                    <th class="text-right">
                                        {{ __('messages.total_marla') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($directBookings as $index => $booking)
                                    <tr>

                                        <td class="text-center">
                                            {{ $index + 1 }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($booking->date)->format('d-m-Y') }}
                                        </td>

                                        <td>
                                            {{ $booking->form_no }}
                                        </td>

                                        <td>
                                            {{ $isUrdu ? $booking->project_name_ur : $booking->project_name_en }}
                                        </td>

                                        <td>
                                            {{ $isUrdu ? $booking->product_name_ur : $booking->product_name_en }}
                                        </td>

                                        <td>
                                            {{ $booking->unit_no ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $isUrdu ? $booking->purchaser_name_ur : $booking->purchaser_name_en }}(
                                            {{ $isUrdu ? $booking->purchaser_cast_ur : $booking->purchaser_cast_en }})<br>

                                            {{ $booking->purchaser_cnic_no }}
                                        </td>

                                        <td class="text-right amount">
                                            {{ number_format((float) ($booking->total_marla ?? 0), 2) }}
                                        </td>

                                    </tr>
                                @endforeach

                                <tr class="total-row">

                                    <td colspan="7" class="text-right">
                                        {{ __('messages.total') }}
                                    </td>

                                    <td class="text-right">
                                        {{ number_format($totalDirectMarla, 2) }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>
                @else
                    <div class="empty-state">

                        <i class="fas fa-inbox"></i>

                        {{ __('messages.no_record_found') }}

                    </div>
                @endif

            </div>


            {{-- ITEM PRODUCTS --}}
            <div class="report-card">

                <div class="section-header">

                    <h2 class="section-title">
                        <i class="fas fa-boxes mr-2"></i>
                        {{ __('messages.item_products') }}
                    </h2>

                    <span class="section-badge">
                        {{ $itemSales->count() }}
                        {{ $isUrdu ? 'ریکارڈز' : 'Records' }}
                    </span>

                </div>

                @if ($itemSales->count() > 0)
                    <div class="table-wrapper">

                        <table class="report-table">

                            <thead>

                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.invoice_no') }}</th>
                                    <th>{{ __('messages.project_name') }}</th>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.purchaser') }}</th>

                                    <th class="text-right">
                                        {{ __('messages.quantity') }}
                                    </th>

                                    <th class="text-right">
                                        {{ __('messages.price') }}
                                    </th>

                                    <th class="text-right">
                                        {{ __('messages.amount') }}
                                    </th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($itemSales as $index => $sale)
                                    <tr>

                                        <td class="text-center">
                                            {{ $index + 1 }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($sale->date)->format('d-m-Y') }}
                                        </td>

                                        <td>
                                            {{ $sale->sale_invoice_no }}
                                        </td>

                                        <td>
                                            {{ $isUrdu ? $sale->project_name_ur : $sale->project_name_en }}
                                        </td>

                                        <td>
                                            {{ $isUrdu ? $sale->product_name_ur : $sale->product_name_en }}
                                        </td>

                                        <td>

                                            {{ $isUrdu ? $sale->purchaser_name_ur : $sale->purchaser_name_en }}(
                                            {{ $isUrdu ? $sale->purchaser_cast_ur : $sale->purchaser_cast_en }})<br>

                                            {{ $sale->purchaser_cnic_no }}
                                        </td>

                                        <td class="text-right">
                                            {{ number_format((float) ($sale->quantity ?? 0), 2) }}
                                        </td>

                                        <td class="text-right">
                                            {{ number_format((float) ($sale->price ?? 0), 2) }}
                                        </td>

                                        <td class="text-right amount">
                                            {{ number_format((float) ($sale->amount ?? 0), 2) }}
                                        </td>

                                    </tr>
                                @endforeach

                                <tr class="total-row">

                                    <td colspan="6" class="text-right">
                                        {{ __('messages.total') }}
                                    </td>

                                    <td class="text-right">
                                        {{ number_format($totalItemQuantity, 2) }}
                                    </td>

                                    <td></td>

                                    <td class="text-right">
                                        {{ number_format($totalItemAmount, 2) }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>
                @else
                    <div class="empty-state">

                        <i class="fas fa-inbox"></i>

                        {{ __('messages.no_record_found') }}

                    </div>
                @endif

            </div>


            {{-- SUMMARY --}}
            <div class="report-card">

                <div class="section-header">

                    <h2 class="section-title">
                        <i class="fas fa-calculator mr-2"></i>
                        {{ __('messages.total') }}
                    </h2>

                </div>

                <div class="summary-section">

                    <div class="row">

                        {{-- Direct Marla --}}
                        <div class="col-md-4 mb-3">

                            <div class="summary-card">

                                <div class="summary-label">
                                    {{ __('messages.total_direct_marla') }}
                                </div>

                                <div class="summary-value">
                                    {{ number_format($totalDirectMarla, 2) }}
                                </div>

                            </div>

                        </div>


                        {{-- Item Quantity --}}
                        <div class="col-md-4 mb-3">

                            <div class="summary-card">

                                <div class="summary-label">
                                    {{ __('messages.total_item_quantity') }}
                                </div>

                                <div class="summary-value">
                                    {{ number_format($totalItemQuantity, 2) }}
                                </div>

                            </div>

                        </div>


                        {{-- Item Amount --}}
                        <div class="col-md-4 mb-3">

                            <div class="summary-card">

                                <div class="summary-label">
                                    {{ __('messages.total_item_amount') }}
                                </div>

                                <div class="summary-value">
                                    {{ number_format($totalItemAmount, 2) }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Footer --}}
            <div class="report-card">

                <div class="report-footer">

                    {{ __('messages.sale_report') }}

                    &nbsp; | &nbsp;

                    {{ now()->format('d-m-Y h:i A') }}

                </div>

            </div>

        </div>

    </div>

@endsection
