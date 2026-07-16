<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Slip</title>

    <style>
        @page {
            size: A4 Portrait;
            margin: 5mm;
        }

        body {
            margin: 0;
            font-family: "Times New Roman", Georgia, serif;
            background: #f3ecd6;
            color: #5b4b2a;

            /* Better print colors */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            display: flex;
            border: 2px solid #c9b27c;
            padding: 0;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .slip-right {
            width: 100%;
            padding: 8px 15px;
            font-size: 14px;
            box-sizing: border-box;
        }

        /* HEADER */
        .header {
            width: 100%;
            text-align: center;
            position: relative;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }

        .header-left {
            position: absolute;
            left: 0;
            top: 0;
        }

        .header-left img {
            width: 80px;
        }

        .header-center {
            padding-left: 90px;
        }

        .company-name {
            color: #d0ae6e;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .address {
            font-size: 13px;
            margin-top: 4px;
        }

        .header-title {
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 2px;
            padding-left: 90px;
        }

        /* ROWS */
        .row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 6px;
        }

        .label {
            white-space: nowrap;
            padding-right: 5px;
        }

        .line {
            flex: 1;
            border-bottom: 1px dotted #5b4b2a;
            min-height: 14px;
        }

        .receipt-no {
            color: #b44a4a;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .left-group,
        .right-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ROW2 */
        .row2 {
            display: flex;
            width: 100%;
            gap: 10px;
            margin-top: 8px;
        }

        .row2 .col {
            display: flex;
            align-items: center;
        }

        .row2 .rupees {
            width: 30%;
        }

        .row2 .bank {
            width: 70%;
        }

        /* FOOTER */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 12px;
        }

        .sign-line {
            width: 140px;
            border-top: 1px solid #5b4b2a;
            text-align: center;
            padding-top: 4px;
        }

        /* PRINT SETTINGS */
        @media print {

            body {
                background: none !important;
            }

            .page {
                border: 2px solid #c9b27c;
            }

            .header {
                margin-bottom: 10px;
            }

            .row {
                margin-bottom: 5px;
            }

            .slip-right {
                page-break-inside: avoid;
            }

            .no-print {
                display: none !important;
            }
        }

        /* PRINT BUTTON */
        .print-btn {
            text-align: right;
            margin-bottom: 10px;
        }

        button {
            padding: 6px 12px;
            cursor: pointer;
        }
    </style>
</head>

<!-- ✅ AUTO PRINT -->
<body onload="window.print()">

    <!-- PRINT BUTTON -->
    <div class="print-btn no-print">
        <button onclick="window.print()">Print</button>
    </div>

    <div class="page">

        <!-- RIGHT COPY -->
        <div class="slip-right">

            <!-- HEADER -->
            <div class="header">
                <div class="header-left">
                    <img src="{{ asset('images/Voucher_logo.png') }}" alt="Logo">
                </div>

                <div class="header-center">
                    <div class="company-name">
                        {{ $voucher->project->name_en ?? '-' }}
                    </div>
                    <div class="address">
                        {{ $voucher->project->address_en ?? '-' }}
                    </div>
                </div>

                <div class="header-title" style="color: #d0ae6e;">
                    PAYMENT SLIP
                </div>
            </div>

            <!-- RECEIPT + DATE -->
            <div class="row receipt-row" style="padding-top: 20px;">
                <div class="left-group">
                    <span class="label">RECEIPT #:</span>
                    <span class="receipt-no line">BPV-{{ $voucher->id }}</span>
                </div>

                <div class="right-group">
                    <span class="label">Date:</span>
                    <span class="line">
                        {{ \Carbon\Carbon::parse($voucher->date)->format('d-m-Y') }}
                    </span>
                </div>
            </div>

            <!-- NAME -->
            <div class="row">
                <div class="label">Mr./Mrs./Miss:</div>
                <div class="line">{{ $voucher->detailAccount->party->name_en ?? '-' }}</div>

                <div class="label">S/o, D/o, W/o:</div>
                <div class="line">{{ $voucher->detailAccount->party->father_name_en ?? '-' }}</div>
            </div>

            <!-- AMOUNT + BANK -->
            <div class="row2">
                <div class="col rupees">
                    <span class="label">Rupees:</span>
                    <span class="line">{{ number_format($voucher->total_amount, 2) }}</span>
                </div>

                <div class="col bank">
                    <span class="label">In Bank Acc. No:</span>
                    <span class="line">{{ $voucher->bank->name_en ?? '-' }}</span>
                </div>
            </div>

            <!-- AMOUNT WORDS -->
            <div class="row" style="margin-top: 5px;">
                <div class="label">Amount in Words:</div>
                <div class="line">{{ amountInWords($voucher->total_amount) }}</div>
            </div>

            <!-- PLOT + PHASE -->
            {{-- <div class="row receipt-row" style="padding-top: 10px;">
                <div class="left-group">
                    <div class="label">For Plot / Commercial No:</div>
                    <div class="line">{{ $bookingData->product->name_en ?? '-' }}</div>
                </div>

                <div class="right-group">
                    <div class="label">Phase:</div>
                    <div class="line" style="width: 80px;">
                        {{ $bookingData->project->phase_en ?? '-' }}
                    </div>
                </div>
            </div> --}}

            <!-- FOOTER -->
            <div class="signatures" style="padding-top: 10px;">
                <div class="footer">
                    <strong>NOTE:</strong>
                    Receipt valid subject to realization of Cheque / Pay Order / Bank Draft etc.
                </div>

                <div class="sign-line">
                    (K) For ME-Developers
                </div>
            </div>

        </div>

    </div>

</body>

</html>
