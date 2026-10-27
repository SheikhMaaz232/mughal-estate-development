<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Receipt {{ $journalVoucher->voucher_no }}</title>
    <style>
        :root {
            font-family: Arial, Helvetica, sans-serif;
            color: #1c2b40;
            background: #eef1f5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 28px 16px;
        }

        .toolbar {
            width: min(210mm, 100%);
            margin: 0 auto 14px;
            text-align: right;
        }

        .print-button {
            padding: 10px 16px;
            border: 0;
            border-radius: 4px;
            background: #18395d;
            color: #fff;
            font: 600 14px Arial, Helvetica, sans-serif;
            cursor: pointer;
        }

        .sheet {
            position: relative;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 18mm 20mm;
            background: #fff;
            border: 1px solid #e4e8ee;
            border-radius: 3px;
            box-shadow: 0 8px 30px rgb(22 37 58 / 12%);
        }

        .sheet::before {
            position: absolute;
            inset: 12mm;
            border: 1px solid #e4e8ee;
            border-radius: 2px;
            content: "";
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        header {
            padding-bottom: 7mm;
            border-bottom: 1px solid #d9e0e8;
            text-align: center;
        }

        h1 {
            margin: 0;
            color: #18395d;
            font-size: 23pt;
            font-weight: 700;
            letter-spacing: .11em;
        }

        .gold-rule {
            width: 24mm;
            height: 2px;
            margin: 4mm auto 0;
            background: #bd9852;
        }

        .fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 7mm 10mm;
            margin-top: 9mm;
        }

        .field-label {
            display: block;
            margin-bottom: 2mm;
            color: #18395d;
            font-size: 10pt;
            font-weight: 700;
            letter-spacing: .045em;
        }

        .entry {
            display: block;
            min-height: 10mm;
            padding: 0 3mm 1mm;
            border: 1px solid #dce3ec;
            border-radius: 3px;
            background: #fbfcfe;
            font-size: 12pt;
            line-height: 10mm;
        }

        .remarks {
            grid-column: 1 / -1;
        }

        .remarks-entry {
            padding: 2mm 3mm;
            border: 1px solid #cbd3dd;
            border-radius: 3px;
            background-color: #fff;
            font-size: 11pt;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .remark {
            margin: 0 0 2mm;
        }

        .remark:last-child {
            margin-bottom: 0;
        }

        .amount {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 5mm;
            min-height: 22mm;
            margin-top: 0;
            padding: 4mm 6mm;
            border: 1px solid #d4bd8b;
            border-left: 3px solid #bd9852;
            border-radius: 3px;
            background: #fbfaf6;
        }

        .amount .field-label {
            margin: 0;
            font-size: 11pt;
            white-space: nowrap;
        }

        .currency {
            color: #18395d;
            font-size: 13pt;
            font-weight: 700;
            white-space: nowrap;
        }

        .amount-value {
            flex: 1;
            min-height: 11mm;
            padding: 0 2mm;
            border-bottom: 1px solid #8290a2;
            color: #18395d;
            font-size: 20pt;
            font-weight: 700;
            line-height: 11mm;
            text-align: left;
        }

        .signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10mm;
            margin-top: 15mm;
            padding-top: 4mm;
            border-top: 1px solid #e1e6ed;
        }

        .signature {
            min-height: 18mm;
            padding-top: 12mm;
            border-bottom: 1px solid #8491a2;
            color: #526176;
            font-size: 9pt;
            font-weight: 600;
            letter-spacing: .035em;
            text-align: center;
            text-transform: uppercase;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            :root {
                background: #fff;
            }

            body {
                padding: 0;
            }

            .toolbar {
                display: none;
            }

            .sheet {
                width: 210mm;
                min-height: 0;
                margin: 0;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .amount {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @media screen and (max-width: 760px) {
            body {
                padding: 12px;
            }

            .sheet {
                width: 100%;
                min-height: 0;
                padding: 32px 24px;
            }

            .sheet::before {
                inset: 16px;
            }

            .fields {
                gap: 22px 16px;
            }

            .signatures {
                gap: 14px;
                margin-top: 42px;
            }

            .signature {
                font-size: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="toolbar">
        <button class="print-button" type="button" onclick="window.print()">Print / Save as PDF</button>
    </div>

    <main class="sheet">
        <div class="content">
            <header>
                <h1>PAYMENT RECEIPT</h1>
                <div class="gold-rule" aria-hidden="true"></div>
            </header>

            <section class="fields" aria-label="Payment receipt">
                <div>
                    <span class="field-label">Receipt No.:</span>
                    <div class="entry">{{ $journalVoucher->voucher_no }}</div>
                </div>

                <div>
                    <span class="field-label">Payment Date:</span>
                    <div class="entry">
                        {{ $journalVoucher->voucher_date ? \Carbon\Carbon::parse($journalVoucher->voucher_date)->format('d-m-Y') : '' }}
                    </div>
                </div>

                <div class="remarks">
                    <span class="field-label">Remarks:</span>
                    <div class="remarks-entry">
                        @foreach ($journalVoucher->journalEntries as $entry)
                            @if (filled($entry->detail_description_en))
                                <p class="remark">{{ $entry->detail_description_en }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="amount">
                    <span class="field-label">Amount:</span>
                    <span class="currency">PKR</span>
                    <div class="amount-value">
                        {{ $journalVoucher->total_debit !== null ? number_format((float) $journalVoucher->total_debit, 2) : '' }}
                    </div>
                </div>
            </section>

            <footer class="signatures" aria-label="Signatures">
                <div class="signature">Prepared By</div>
                <div class="signature">Received By</div>
                <div class="signature">Authorized Signature</div>
            </footer>
        </div>
    </main>
</body>

</html>
