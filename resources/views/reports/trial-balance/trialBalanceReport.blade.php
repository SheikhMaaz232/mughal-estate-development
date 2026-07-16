<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ App::getLocale() === 'ur' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>@lang('messages.trial_balance')</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, sans-serif;
            font-size: 14px;
            color: #2c3e50;
            margin: 30px;
            direction: {{ App::getLocale() === 'ur' ? 'rtl' : 'ltr' }};
            text-align: {{ App::getLocale() === 'ur' ? 'right' : 'left' }};
        }

        .report-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .report-header h2 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 1px;
        }

        .summary-box {
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #f4f6f7;
            border-left: 4px solid #2c3e50;
            width: fit-content;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background-color: #2c3e50;
            color: white;
        }

        th,
        td {
            padding: 10px 8px;
            border: 1px solid #dcdde1;
            text-align: right;
        }

        th:first-child,
        td:first-child {
            text-align: left;
        }

        body[dir="rtl"] th:first-child,
        body[dir="rtl"] td:first-child {
            text-align: right;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fbfc;
        }

        tbody tr:hover {
            background-color: #eef3f7;
        }

        .total-row {
            font-weight: bold;
            background-color: #ecf0f1;
        }

        .print-btn {
            padding: 8px 16px;
            margin-top: 10px;
            align-content: flex-end;
            background: green;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #7f8c8d;
            text-align: right;
        }

        @media print {
            body {
                margin: 10px;
            }

            .no-print {
                display: none;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    @php
        $isUrdu = App::getLocale() === 'ur';
    @endphp

    <div class="report-header">
        <h1>@lang('messages.company_name')</h1>
        <h2>@lang('messages.trial_balance')</h2>
        @if($asOfDate)
            <p>@lang('messages.as_of_date'): {{ $asOfDate->format('d-m-Y') }}</p>
        @endif
    </div>

    <div class="summary-box">
        <div><strong>@lang('messages.total_projects'):</strong> {{ $projectTrialBalances->count() }}</div>
        <div><strong>@lang('messages.total_debit'):</strong> {{ number_format($totalDebit, 2) }}</div>
        <div><strong>@lang('messages.total_credit'):</strong> {{ number_format($totalCredit, 2) }}</div>
        <div><strong>@lang('messages.difference'):</strong> {{ number_format(abs($totalDebit - $totalCredit), 2) }}</div>
    </div>

    @forelse($projectTrialBalances as $project)
        <h3>{{ $isUrdu ? $project->project_name_ur : $project->project_name_en }}</h3>
        <table>
            <thead>
                <tr>
                    <th>@lang('messages.account_name')</th>
                    <th>@lang('messages.account_code')</th>
                    <th>@lang('messages.total_debit')</th>
                    <th>@lang('messages.total_credit')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($project->entries as $entry)
                    <tr>
                        <td>{{ $isUrdu ? $entry->account_name_ur ?? $entry->account_name_en : $entry->account_name_en }}</td>
                        <td>{{ $isUrdu ? $entry->main_head_ur ?? $entry->main_head_en : $entry->main_head_en }}</td>
                        <td>{{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}</td>
                        <td>{{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">@lang('messages.no_records_found')</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2"><strong>@lang('messages.total')</strong></td>
                    <td><strong>{{ number_format($project->total_debit, 2) }}</strong></td>
                    <td><strong>{{ number_format($project->total_credit, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    @empty
        <table>
            <tbody>
                <tr>
                    <td colspan="4">@lang('messages.no_records_found')</td>
                </tr>
            </tbody>
        </table>
    @endforelse

    <div class="summary-box">
        <div><strong>@lang('messages.grand_total_debit'):</strong> {{ number_format($totalDebit, 2) }}</div>
        <div><strong>@lang('messages.grand_total_credit'):</strong> {{ number_format($totalCredit, 2) }}</div>
    </div>

    <div class="footer">
        Generated by {{ config('app.name') }} | {{ now()->format('d-m-Y h:i A') }}
    </div>
</body>

</html>
