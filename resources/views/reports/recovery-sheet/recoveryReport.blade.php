{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Recovery Sheet Report</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, sans-serif;
            font-size: 14px;
            color: #2c3e50;
            margin: 30px;
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

        .report-header p {
            margin: 3px 0;
            color: #7f8c8d;
            font-size: 13px;
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
    <a href="{{ route('reports.recovery.sheet.view') }}" class="print-btn" style="background: black !important;">
        Back
    </a>

    <div class="report-header">
        <h1>Mughal Estate Developers</h1>
        <h2>Recovery Sheet Report</h2>
    </div>

    <div class="summary-box">
        <div><strong>Total Projects:</strong> {{ $recoveryAccounts->count() }}</div>
        <div><strong>Total Accounts:</strong> {{ $recoveryAccounts->flatten()->count() }}</div>
    </div>

    <table>

        <tbody>
            @php
                $grandDebit = 0;
                $grandCredit = 0;
                $grandBalance = 0;
            @endphp

            @foreach ($recoveryAccounts as $projectName => $accounts)
                @php
                    $projectDebit = 0;
                    $projectCredit = 0;
                    $projectBalance = 0;
                @endphp

                <h3 style="margin-top:30px; background:#ecf0f1; padding:8px 10px; border-left:4px solid #2c3e50;">
                    Project: {{ $projectName }}
                </h3>

                <table>
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Total Debit</th>
                            <th>Total Credit</th>
                            <th>Balance (Recovery)</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($accounts as $account)
                            @php
                                $projectDebit += $account->total_debit;
                                $projectCredit += $account->total_credit;
                                $projectBalance += $account->balance;

                                $grandDebit += $account->total_debit;
                                $grandCredit += $account->total_credit;
                                $grandBalance += $account->balance;
                            @endphp
                            <tr>
                                <td>{{ $account->detailAccount->name_en ?? 'N/A' }}</td>
                                <td>{{ number_format($account->total_debit, 2) }}</td>
                                <td>{{ number_format($account->total_credit, 2) }}</td>
                                <td><strong>{{ number_format($account->balance, 2) }}</strong></td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <td>Project Total</td>
                            <td>{{ number_format($projectDebit, 2) }}</td>
                            <td>{{ number_format($projectCredit, 2) }}</td>
                            <td>{{ number_format($projectBalance, 2) }}</td>
                        </tr>

                    </tbody>
                </table>
            @endforeach


            {{-- GRAND TOTAL SECTION
            <h3 style="margin-top:40px; background:#2c3e50; color:white; padding:10px;">
                Overall Recovery Summary
            </h3>

            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Total Debit</th>
                        <th>Total Credit</th>
                        <th>Balance (Recovery)</th>
                    </tr>
                </thead>
                <tr class="total-row">
                    <td style="text-align:left;"><strong>Grand Total</strong></td>
                    <td><strong>{{ number_format($grandDebit, 2) }}</strong></td>
                    <td><strong>{{ number_format($grandCredit, 2) }}</strong></td>
                    <td><strong>{{ number_format($grandBalance, 2) }}</strong></td>
                </tr>
            </table>
        </tbody>
    </table>

    <div class="footer">
        Generated by {{ config('app.name') }} | {{ now()->format('d M Y h:i A') }}
    </div>

</body>

</html> --}}



<!DOCTYPE html>
<html lang="ur" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>ریکوری شیٹ رپورٹ</title>
    <style>
        body {
            font-family: "Jameel Noori Nastaleeq", "Noto Nastaliq Urdu", serif;
            font-size: 16px;
            color: #2c3e50;
            margin: 30px;
            direction: rtl;
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

        .report-header p {
            margin: 3px 0;
            color: #7f8c8d;
            font-size: 13px;
        }

        .summary-box {
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #f4f6f7;
            border-right: 4px solid #2c3e50;
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

        th:last-child,
        td:last-child {
            text-align: left;
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
            background: green;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #7f8c8d;
            text-align: left;
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

    <a href="{{ route('reports.recovery.sheet.view') }}" class="print-btn" style="background: black !important;">
        واپس جائیں
    </a>

    <div class="report-header">
        <h1>مغل اسٹیٹ ڈیولپرز</h1>
        <h2>ریکوری شیٹ رپورٹ</h2>
    </div>

    <div class="summary-box">
        <div><strong>کل پروجیکٹس:</strong> {{ $recoveryAccounts->count() }}</div>
        <div><strong>کل اکاؤنٹس:</strong> {{ $recoveryAccounts->flatten()->count() }}</div>
    </div>

    @php
        $grandDebit = 0;
        $grandCredit = 0;
        $grandBalance = 0;
    @endphp

    @foreach ($recoveryAccounts as $projectName => $accounts)
        @php
            $projectDebit = 0;
            $projectCredit = 0;
            $projectBalance = 0;
        @endphp

        <h3 style="margin-top:30px; background:#ecf0f1; padding:8px 10px; border-right:4px solid #2c3e50;">
            پروجیکٹ: {{ $projectName }}
        </h3>

        <table>
            <thead>
                <tr>
                    <th>اکاؤنٹ کا نام</th>
                    <th>کل بنام</th>
                    <th>کل جمع</th>
                    <th>بیلنس (ریکوری)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    @php
                        $projectDebit += $account->total_debit;
                        $projectCredit += $account->total_credit;
                        $projectBalance += $account->balance;

                        $grandDebit += $account->total_debit;
                        $grandCredit += $account->total_credit;
                        $grandBalance += $account->balance;
                    @endphp
                    <tr>
                        <td>{{ $account->detailAccount->name_ur ?? 'دستیاب نہیں' }}</td>
                        <td>{{ number_format($account->total_debit, 2) }}</td>
                        <td>{{ number_format($account->total_credit, 2) }}</td>
                        <td><strong>{{ number_format($account->balance, 2) }}</strong></td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td>پروجیکٹ کا کل</td>
                    <td>{{ number_format($projectDebit, 2) }}</td>
                    <td>{{ number_format($projectCredit, 2) }}</td>
                    <td>{{ number_format($projectBalance, 2) }}</td>
                </tr>

            </tbody>
        </table>
    @endforeach


    {{-- GRAND TOTAL SECTION --}}
    <h3 style="margin-top:40px; background:#2c3e50; color:white; padding:10px;">
        مجموعی ریکوری خلاصہ
    </h3>

    <table>
        <thead>
            <tr>
                <th></th>
                <th> سیل ویلیو</th>
                <th> وصول رقم</th>
                <th>بقیہ وصول کرنے والی رقم</th>
            </tr>
        </thead>
        <tr class="total-row">
            <td><strong>مجموعی کل</strong></td>
            <td><strong>{{ number_format($grandDebit, 2) }}</strong></td>
            <td><strong>{{ number_format($grandCredit, 2) }}</strong></td>
            <td><strong>{{ number_format($grandBalance, 2) }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        تیار کردہ از {{ config('app.name') }} | {{ now()->format('d M Y h:i A') }}
    </div>

</body>

</html>
