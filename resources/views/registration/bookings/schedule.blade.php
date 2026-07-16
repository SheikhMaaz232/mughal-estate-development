<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Schedule</title>

    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #1f4fd8;
            margin: 0;
            font-size: 26px;
        }

        .header h2 {
            margin: 5px 0 15px;
            font-size: 18px;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .info div {
            line-height: 1.6;
        }

        .schedule-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
        }

        .grand-total {
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
        }

        .sign-box {
            width: 200px;
            text-align: center;
        }

        .line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }

        .print-bar {
            text-align: right;
            margin-bottom: 10px;
        }

        .print-bar button {
            padding: 6px 14px;
            font-size: 14px;
            cursor: pointer;
        }

        /* Hide print button when printing */
        @media print {
            .print-bar {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="print-bar">
        <button onclick="window.print()">🖨 Print</button>
    </div>


    <div class="header">
        <h1>Mughal Estate Developers</h1>
        <h2>{{ $bookingApplicationData->project->name_en }}</h2>
    </div>

    <div class="info">
        <div>
            <strong>Name:</strong> {{ $bookingApplicationData->party->name_en }}<br>
            <strong>Booking Price:</strong> {{ $bookingApplicationData->total_amount }}
        </div>
        <div>
            <strong>Unit:</strong> {{ $bookingApplicationData->product->name_en }}<br>
            <strong>File #:</strong> {{ $bookingApplicationData->form_no }}
        </div>
    </div>

    <div class="schedule-title">Payment Schedule</div>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Period</th>
                <th>Due Date</th>
                <th>No</th>
                <th>P/Amount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookingPaymentSchedules as $booking)
                <tr>
                    <td>{{ $booking->scheduleType->title_en }}</td>
                    <td>{{ $booking->schedulePeriod->title_en }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->due_date)->format('d M Y') }}</td>

                    <td>{{ $booking->number }}</td>
                    <td>{{ $booking->pay_amount }}</td>
                    <td>{{ $booking->calculated_total_amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="grand-total">
        Grand Total = {{ $grandTotal }}
    </div>

    <div class="signatures">
        <div class="sign-box">
            <div class="line"></div>
            Verified By
        </div>
        <div class="sign-box">
            <div class="line"></div>
            Client
        </div>
    </div>

</body>

</html>
