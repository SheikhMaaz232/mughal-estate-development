<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registry Order - Mughal Estate Developers</title>

    <style>
        @page {
            size: 8.5in 14in;
            margin: 0;
        }

        @media print {
            .page {
                margin: 0;
                box-shadow: none;
            }

            .print-btn {
                display: none;
            }
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

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
        }

        .page {
            width: 8.5in;
            min-height: 14in;
            margin: 0 auto;
            box-sizing: border-box;
            padding: 3.5in 0.6in 0.6in 0.6in;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header img {
            max-width: 100%;
            height: auto;
        }

        .title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin: 20px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
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

        .field {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 150px;
            padding-bottom: 2px;
            text-align: center;
        }

        .full-field {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 100%;
            padding-bottom: 2px;
        }

        .checkbox {
            width: 25px;
            height: 25px;
            border: 2px solid #000;
            display: inline-block;
            margin-right: 6px;
            vertical-align: middle;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }

        .section {
            margin-bottom: 15px;
        }

        .declaration {
            font-size: 10.5pt;
            margin-top: 30px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            text-align: center;
            font-size: 10pt;
            margin-top: 0.9in;
        }

        .signature-line {
            width: 220px;
            /* border-top: 1px solid #000; */
            text-align: center;
            padding-top: 6px;
        }
    </style>
</head>

<body>
    <button class="print-btn" onclick="window.print()">Print</button>
    <a href="{{ route('registry-order.index') }}" class="print-btn" style="background: black !important;">
        Back
    </a>
    <div class="page">
        <div class="title">Registry Order</div>

        <div class="row">
            <div>
                File No:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $registryOrder->booking->form_no ?? '' }}
                </span>
            </div>

            <div>
                Security Note No:
                <span class="value" style="min-width:190px;"></span>
            </div>

            <div>
                Date:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $registryOrder->date ? \Carbon\Carbon::parse($registryOrder->date)->format('d-m-Y') : '' }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-top: 30px !important;">
            <div>Project Name
                <span class="value" style="min-width:430px; text-align:center;">
                    {{ $registryOrder->booking->project->name_ur ?? '' }}
                </span>
            </div>

            <div>
                Phase:
                <span class="value"
                    style="min-width:120px; text-align:center;">{{ $registryOrder->booking->project->phase_ur ?? '' }}</span>
            </div>
        </div>

        <div style="margin-top: 30px !important;">Project Address:
            <span class="value" style="min-width:589px; text-align:center;">
                {{ $registryOrder->booking->project->name_ur ?? '' }}
            </span>
        </div>

        <div class="row" style="margin-top: 30px;">
            <div>Unit No:
                <span class="value" style="min-width:160px; text-align:center;">
                    {{ $registryOrder->booking->product->unit_no ?? '' }}
                </span>
            </div>

            <div>
                Area in Marla:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $registryOrder->booking->product->total_marla ?? '' }}
                </span>
            </div>
            <div>
                Area in Feet:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $registryOrder->booking->product->total_square_feet ?? '' }}
                </span>
            </div>
        </div>


        <!-- Relation -->
        <div class="section" style="text-align: center !important; margin-top:45px;">

            File Owner &nbsp;&nbsp;<span class="checkbox">
                {{ $registryOrder->relation == 'file_owner' ? '✓' : '-' }}
            </span>



            Nominee &nbsp;&nbsp;<span class="checkbox">
                {{ $registryOrder->relation == 'nominee' ? '✓' : '-' }}
            </span>



            Blood Relation &nbsp;&nbsp;<span class="checkbox">
                {{ $registryOrder->relation == 'blood_relation' ? '✓' : '-' }}
            </span>



            Third Party &nbsp;&nbsp;<span class="checkbox">
                {{ $registryOrder->relation == 'third_party' ? '✓' : '-' }}
            </span>
        </div>

        <!-- Personal Info -->
        <div class="row" style="margin-top: 30px !important;">
            <div>Name:
                <span class="value" style="min-width:260px; text-align:center;">
                    {{ $registryOrder->party->name_ur ?? '' }}
                </span>
            </div>

            <div>
                Father / Husband’s Name:
                <span class="value"
                    style="min-width:210px; text-align:center;">{{ $registryOrder->party->father_name_ur ?? '' }}</span>
            </div>
        </div>
        <div class="row" style="margin-top: 30px !important;">
            <div>Cast:
                <span class="value" style="min-width:350px; text-align:center;">
                    {{ $registryOrder->party->cast->title_ur ?? '' }}
                </span>
            </div>

            <div>
                CNIC #:
                <span class="value"
                    style="min-width:250px; text-align:center;">{{ $registryOrder->party->cnic_no ?? '' }}</span>
            </div>
        </div>
        <div style="margin-top: 30px !important;">Address:
            <span class="value" style="min-width:638px; text-align:center;">
                {{ $registryOrder->party->home_address_ur ?? '' }}
            </span>
        </div>
        <div style="margin-top: 30px !important;">NTN No:
            <span class="value" style="min-width:638px; text-align:center;">
                {{ $registryOrder->party->ntn_no ?? '' }}
            </span>
        </div>
        <div style="margin-top: 30px !important;">Fard ID:
            <span class="value" style="min-width:642px; text-align:center;">
                {{ $registryOrder->fard_id ?? '' }}
            </span>
        </div>

        <!-- Declaration -->
        <div class="declaration" style="margin-top: 1.2in !important;">
            I <span class="value" style="min-width:170px; text-align:center;">
            </span> state that I have studied this document carefully, the given information is correct in all aspects.
            In case of any mistake or missing information, I will be responsible for any loss.
        </div>

        <!-- Signatures -->
        <div class="signatures" style="margin-top: 1.7in !important;">
            <div class="signature-line" style="text-align: left !important;">Company Signature</div>
            <div class="signature-line" style="text-align: right !important;">Customer Signature</div>
        </div>

    </div>
</body>

</html>
