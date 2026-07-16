<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.invoice') - {{ $invoice->invoice_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .invoice-container {
            max-width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }

        .invoice-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .invoice-no {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .details-group {
            border: 1px solid #ddd;
            padding: 15px;
        }

        .details-group h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .details-group p {
            font-size: 11px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th {
            background-color: #f0f0f0;
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        table td {
            border: 1px solid #999;
            padding: 8px;
            font-size: 11px;
        }

        .total-section {
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .total-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            padding: 10px;
            border: 1px solid #999;
            font-weight: bold;
        }

        .remarks {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 15px;
        }

        .remarks h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .footer-item {
            font-size: 10px;
        }

        .footer-item .line {
            margin-top: 40px;
            border-top: 1px solid #000;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
                page-break-after: always;
            }

            .no-print {
                display: none;
            }
        }

        .no-print {
            margin-bottom: 20px;
        }

        .no-print button {
            padding: 10px 20px;
            margin-right: 10px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">@lang('messages.print')</button>
        <button onclick="window.close()" class="btn btn-secondary">@lang('messages.close')</button>
    </div>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="invoice-title">{{ $invoice->tender->constructionSite?->name_en ?? 'INVOICE' }}</div>
            <div class="invoice-no">
                <strong>@lang('messages.invoice-no'):</strong> {{ $invoice->invoice_no }}
            </div>
        </div>

        <!-- Details -->
        <div class="invoice-details">
            <div class="details-group">
                <h3>@lang('messages.from')</h3>
                <p><strong>{{ config('app.name') }}</strong></p>
            </div>

            <div class="details-group">
                <h3>@lang('messages.invoice-to')</h3>
                <p><strong>{{ $invoice->client?->name_en }}</strong></p>
                <p>@lang('messages.tender'): {{ app()->getLocale() === 'ur' ? $invoice->tender->title_ur : $invoice->tender->title_en }}</p>
            </div>
        </div>

        <!-- Invoice Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">@lang('messages.sr-no')</th>
                    <th style="width: 50%;">@lang('messages.description')</th>
                    <th style="width: 20%;" class="text-right">@lang('messages.amount')</th>
                    <th style="width: 20%;" class="text-right">@lang('messages.total')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $invoice->remarks ?: 'Client Invoice' }}</td>
                    <td style="text-align: right;">{{ number_format($invoice->amount, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <div>@lang('messages.total-amount')</div>
                <div>{{ number_format($invoice->amount, 2) }}</div>
            </div>
        </div>

        <!-- Remarks -->
        @if ($invoice->remarks)
            <div class="remarks">
                <h3>@lang('messages.remarks')</h3>
                <p>{{ $invoice->remarks }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-item">
                <p>@lang('messages.prepared-by')</p>
                <div class="line"></div>
            </div>
            <div class="footer-item">
                <p>@lang('messages.verified-by')</p>
                <div class="line"></div>
            </div>
            <div class="footer-item">
                <p>@lang('messages.authorized-by')</p>
                <div class="line"></div>
            </div>
        </div>

        <div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; text-align: center;">
            <p>@lang('messages.invoice-date'): {{ $invoice->invoice_date->format('d M Y') }}</p>
            <p>@lang('messages.status'): <strong>@lang('messages.status-' . $invoice->status)</strong></p>
            @if ($invoice->isJVPosted())
                <p>@lang('messages.journal-voucher'): JV-{{ $invoice->journal_voucher_id }}</p>
            @endif
        </div>
    </div>
</body>

</html>
