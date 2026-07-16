<!DOCTYPE html>

<body class="{{ App::getLocale() === 'ur' ? 'rtl' : 'ltr' }}">

    <head>
        <meta charset="UTF-8">
        <title>@lang('messages.bill_aging_report')</title>
        <style>
            body {
                font-family: "Segoe UI", Tahoma, sans-serif;
                font-size: 14px;
                color: #2c3e50;
                margin: 30px;
                direction: {{ App::getLocale() === 'ur' ? 'rtl' : 'ltr' }};
                text-align: {{ App::getLocale() === 'ur' ? 'right' : 'left' }};
            }

            body.rtl {
                direction: rtl;
                text-align: right;
            }

            body.ltr {
                direction: ltr;
                text-align: left;
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

            /* body[dir="rtl"] th:first-child,
        body[dir="rtl"] td:first-child {
            text-align: right;
        } */

            html[dir="rtl"] th:first-child,
            html[dir="rtl"] td:first-child {
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
            <h2>@lang('menu.bill-aging')</h2>
            @if ($asOfDate)
                <p>@lang('messages.as_of_date'): {{ $asOfDate->format('d-m-Y') }}</p>
            @endif
            <p>@lang('messages.report_type'): {{ __('messages.' . $reportType) }}</p>
        </div>

        <div class="summary-box">
            <div><strong>@lang('messages.total_parties'):</strong> {{ $partySchedules->count() }}</div>
            <div><strong>@lang('messages.total_scheduled_amount'):</strong> {{ number_format($partySchedules->sum('total_schedule'), 2) }}
            </div>
            <div><strong>@lang('messages.total_ledger_entities'):</strong> {{ $ledgerAging->count() }}</div>
        </div>

        @if ($reportType !== 'payable')
            <h3>@lang('messages.booking_schedule_aging')</h3>
            <table>
                <thead>
                    <tr>
                        <th>@lang('messages.party_name')</th>
                        <th>@lang('messages.account_name')</th>
                        <th>@lang('messages.project')</th>
                        <th>@lang('messages.total_scheduled')</th>
                        <th>@lang('messages.amount_due_by_date')</th>
                        <th>@lang('messages.till_date_short_payment')</th>
                        <th>@lang('messages.amount_due_after_date')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partySchedules as $schedule)
                        <tr>
                            <td>{{ $isUrdu ? $schedule->party_name_ur ?? $schedule->party_name_en : $schedule->party_name_en }}
                            </td>
                            <td>
                                {{ $isUrdu ? ($schedule->account_name_ur ?: $schedule->account_name_en) : $schedule->account_name_en }}
                            </td>
                            <td>{{ $isUrdu ? implode(', ', $schedule->project_names_ur ?: $schedule->project_names_en) : implode(', ', $schedule->project_names_en) }}
                            </td>
                            <td>{{ number_format($schedule->total_schedule, 2) }}</td>
                            <td>{{ number_format($schedule->scheduled_by_date, 2) }}</td>
                            <td>{{ number_format($schedule->till_date_short_payment ?? 0, 2) }}</td>
                            <td>{{ number_format($schedule->scheduled_after_date, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">@lang('messages.no_records_found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <h3 style="margin-top: 30px;">@lang('messages.ledger_aging')</h3>
        <table>
            <thead>
                <tr>
                    <th>@lang('messages.party_name')</th>
                    <th>@lang('messages.account_name')</th>
                    <th>@lang('messages.total_debit')</th>
                    <th>@lang('messages.total_credit')</th>
                    <th>@lang('messages.net_balance')</th>
                    <th>@lang('messages.status')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ledgerAging as $ledger)
                    <tr>
                        <td>{{ $isUrdu ? $ledger->party_name_ur ?? $ledger->party_name_en : $ledger->party_name_en }}
                        </td>
                        <td>
                            {{ $isUrdu ? ($ledger->account_name_ur ?: $ledger->account_name_en) : $ledger->account_name_en }}
                        </td>
                        <td>{{ number_format($ledger->debit, 2) }}</td>
                        <td>{{ number_format($ledger->credit, 2) }}</td>
                        <td>{{ number_format(abs($ledger->balance), 2) }}</td>
                        <td>{{ $ledger->balance >= 0 ? __('messages.receivable') : __('messages.payable') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">@lang('messages.no_records_found')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Generated by {{ config('app.name') }} | {{ now()->format('d-m-Y h:i A') }}
        </div>
    </body>

    </html>
