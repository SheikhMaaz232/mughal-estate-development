<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Approval Form - Mughal Estate Developers</title>
    <style>
        @page {
            size: 8.5in 14in;
            margin: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 8.5in;
            min-height: 14in;
            margin: 0 auto;
            box-sizing: border-box;
            padding: 3.5in 0.6in 0.6in 0.6in;
            position: relative;
        }

        .title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section {
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px 5px;
            vertical-align: top;
        }

        .value {
            display: inline-block;
            border-bottom: 1px solid #B3B3B3;
            text-align: left;
            padding-bottom: 2px;
            white-space: nowrap;
            min-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
        }

        .pricing-line {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
            gap: 8px;
        }

        .pricing-line strong {
            font-weight: bold;
            text-decoration: underline;
        }

        .pricing-line .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        .pricing-line .value {
            display: inline-block;
            border-bottom: 1px solid #B3B3B3;
            text-align: left;
            padding-bottom: 2px;
            white-space: nowrap;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            text-align: center;
            font-size: 10pt;
            margin-top: 0.9in;
        }

        .signatures2 {
            display: flex;
            justify-content: space-between;
            text-align: center;
            font-size: 10pt;
            margin-top: 0.9in;
        }

        .row {
            margin-bottom: 10px;
        }

        table tr td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            text-align: center;
            line-height: 12px;
            font-size: 12px;
            font-weight: bold;
            vertical-align: middle;
        }

        .installment-plan p {
            line-height: 1.5;
            /* adds vertical spacing between lines */
        }

        .installment-plan br {
            line-height: 1.5;
        }

        @media print {
            .page {
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="page">

        <!-- Title -->
        <div class="title" style="font-size:20pt; font-weight:bold; text-align:center; margin-bottom:25px;">
            Plot Clearance Letter
        </div>

        <!-- File Info -->
        <div class="row">
            <div>
                File No:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $bookingApplication->form_no ?? '' }}
                </span>
            </div>

            <div>
                Security Note No:
                <span class="value" style="min-width:190px;"></span>
            </div>

            <div>
                Date:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $bookingApplication->date ? \Carbon\Carbon::parse($bookingApplication->date)->format('d-m-Y') : '' }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-top: 25px !important; margin-bottom:20px;">
            <div>Project Name
                <span class="value" style="min-width:430px; text-align:center;">
                    {{ $bookingApplication->project->name_en ?? '' }}
                </span>
            </div>

            <div>
                Phase:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $bookingApplication->project->phase_en ?? '' }}
                </span>
            </div>
        </div>

        <div style="margin-bottom:20px;">Project Address:
            <span class="value" style="min-width:589px; text-align:center;">
                {{ $bookingApplication->project->address_en ?? '' }}
            </span>
        </div>

        <div class="row" style=" margin-bottom:20px;">
            <div>Unit No:
                <span class="value" style="min-width:160px; text-align:center;">
                    {{ $bookingApplication->product->unit_no ?? '' }}
                </span>
            </div>

            <div>
                Area in Marla:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $bookingApplication->product->total_marla ?? '' }}
                </span>
            </div>
            <div>
                Area in Feet:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $bookingApplication->product->total_square_feet ?? '' }}
                </span>
            </div>
        </div>

        <div class="section">
            <div class="label" style="margin-top:30px; margin-bottom:20px; ">Booking Value:<span class="value"
                    style="min-width:360px; text-align:center;">
                    {{ $bookingApplication->total_amount ?? '0' }}
                </span></div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Development Charges:<span class="value"
                        style="min-width:308px; text-align:center; ">{{ $developmentCharges ?? '0' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">GST Charges:<span class="value"
                        style="min-width:364px; text-align:center; ">{{ $gstCharges ?? '0' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Proceeding Fee:<span class="value"
                        style="min-width:349px; text-align:center; ">{{ $proceedingCharges ?? '0' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Operating Expense:<span class="value"
                        style="min-width:328px; text-align:center; ">{{ $operatingExpense ?? '0' }} (Applicable From
                        <b>{{ !empty($bookingApplication->operating_start_date) ? \Carbon\Carbon::parse($bookingApplication->operating_start_date)->format('d-m-Y') : '' }}</b>
                        Date.)
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Registry Expense:<span class="value"
                        style="min-width:340px; text-align:center; ">{{ $registryFees ?? '0' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Possession Fee:<span class="value"
                        style="min-width:350px; text-align:center; ">{{ $possessionFees ?? '0' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Grand Total:<span class="value"
                        style="min-width:377px; text-align:center; "> {{ $grandTotal ?? '0' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Received Amount:<span class="value"
                        style="min-width:340px; text-align:center; "> {{ $totalCredit ?? '0' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Discount:<span class="value"
                        style="min-width:400px; text-align:center; "> {{ $feesDiscount ?? '0' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:20px;">Remaining Balance:<span class="value"
                        style="min-width:330px; text-align:center; "> {{ $remainingAmount ?? '0' }}
                    </span></div>
            </div>


        </div>


        <!-- Signatures -->
        <div class="signatures2" style="margin-top: 1.5in;">
            <div>Accountant Signature</div>
            <div>G.M Signature</div>
            <div>CEO Signature</div>
        </div>

    </div>

</body>


</html>
