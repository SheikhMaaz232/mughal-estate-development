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

        .page-break {
            page-break-before: always;
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
    </style>
</head>

<body>
    <button class="print-btn" onclick="window.print()">Print</button>
    <a href="{{ route('bookingReturns.index') }}" class="print-btn" style="background: black !important;">
        Back
    </a>
    <div class="page">

        <!-- Title -->
        <div class="title" style="font-size:20pt; font-weight:bold; text-align:center; margin-bottom:25px;">
            File Cancellation
        </div>

        <!-- File Info -->
        <div class="row">
            <div>
                File No:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->form_no ?? '' }}
                </span>
            </div>

            <div>
                Security Note No:
                <span class="value" style="min-width:190px;"></span>
            </div>

            <div>
                Date:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->date ? \Carbon\Carbon::parse($fileCancellation->bookingApplication->date)->format('d-m-Y') : '' }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-top: 15px !important; margin-bottom:10px;">
            <div>Project Name
                <span class="value" style="min-width:430px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->project->name_en ?? '' }}
                </span>
            </div>

            <div>
                Phase:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->project->phase_en ?? '' }}
                </span>
            </div>
        </div>

        <div style="margin-bottom:10px;">Project Address:
            <span class="value" style="min-width:589px; text-align:center;">
                {{ $fileCancellation->bookingApplication->project->address_en ?? '' }}
            </span>
        </div>

        <div class="row" style=" margin-bottom:10px;">
            <div>Unit No:
                <span class="value" style="min-width:160px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->product->unit_no ?? '' }}
                </span>
            </div>

            <div>
                Area in Marla:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->product->total_marla ?? '' }}
                </span>
            </div>
            <div>
                Area in Feet:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->product->total_square_feet ?? '' }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-bottom:10px;">
            <div>Name:
                <span class="value" style="min-width:280px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->party->name_en ?? '' }}
                </span>
            </div>

            <div>
                Father/Husbans's Name:
                <span class="value" style="min-width:195px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->party->father_name_en ?? '' }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-bottom:10px;">
            <div>Cast:
                <span class="value" style="min-width:210px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->party->cast->title_en ?? '' }}
                </span>
            </div>

            <div>
                CNIC #:
                <span class="value" style="min-width:210px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->party->cnic_no ?? '' }}
                </span>
            </div>
            <div>
                Cell No:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->party->contact_number_1 ?? '' }}
                </span>
            </div>
        </div>


        <div class="section">
            <div class="section-title" style="margin-top:20px; margin-bottom:20px; ">1- Booking Value:<span
                    class="value" style="min-width:300px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->total_amount ?? '' }}
                </span></div>

            <div class="row">
                <div class="label" style="margin-bottom:10px;">Received Amount:<span class="value"
                        style="min-width:337px; text-align:center; ">
                        {{ $balanceAmount ?? '' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:10px;">Remaining Amount:<span class="value"
                        style="min-width:328px; text-align:center; ">
                        {{ $remainingAmount ?? '' }}
                    </span></div>
            </div>

            <div class="section-title" style="margin-top:10px; margin-bottom:10px; ">2- Received Amount:<span
                    class="value" style="min-width:272px; text-align:center;">
                    {{ $balanceAmount ?? '' }}
                </span></div>

            <div class="row">
                <div class="label" style="margin-bottom:10px;">Cancellations Charges:<span class="value"
                        style="min-width:305px; text-align:center; ">
                        {{ $cancellationsCharges ?? '' }}
                    </span></div>
            </div>

            <div class="row">
                <div class="label" style="margin-bottom:10px;">Payable Amount:<span class="value"
                        style="min-width:346px; text-align:center;">
                        {{ $companyPays ?? '' }}
                    </span></div>
                <div class="label" style="margin-bottom:10px;">Customer Amount :<span class="value"
                        style="min-width:90px; text-align:center;">
                        {{ $customerPays ?? '' }}
                    </span></div>
            </div>
        </div>

        <!-- 3- Payable Amount Via -->
        <div class="section" style="margin-top:10px; margin-bottom:10px;">
            <div class="section-title">3- Payable Amount Via:</div>

            <div style="margin-bottom:20px;">
                A- Amount Transfer to Plot No:
                <span class="value"
                    style="min-width:495px;">{{ $fileCancellation->detailAccount->name_en ?? '' }}</span>
            </div>
            <div style="margin-bottom:10px;">
                Name Party:
                <span class="value"
                    style="min-width:612px; text-align:center;">{{ $fileCancellation->bookingApplication->detailAccount->party->name_en ?? '' }}</span>
            </div>

            <div style="margin-bottom:10px;">
                Project Name:
                <span class="value" style="min-width:370px; text-align:center;">
                    {{ $fileCancellation->project->name_en ?? '' }}
                </span>
                Phase:
                <span class="value"
                    style="min-width:175px; text-align:center;">{{ $fileCancellation->project->phase_en }}</span>
            </div>

            <div style="margin-bottom:15px;">
                Remarks:
                <span class="value"
                    style="min-width:630px; text-align:center;">{{ $fileCancellation->remarks }}</span>
            </div>

            <div style="margin-bottom:15px;">
                <p>
                    “<b>Important!</b>   I acknowledge that the above plot has been cancelled at my request in accordance with the
                    cancellation policy of the company. The refundable amount will be paid in accordance with the
                    company’s policy and procedure.
                    I fully agree that I will not bring any legal claim, suit or action against the company, its
                    owners, directors or representatives on the basis of the time, procedure or administrative delay
                    in payment and the company’s policy will be acceptable to me.

                </p>
            </div>
        </div>


        <!-- Signatures -->
        <div class="signatures2" style="margin-top: 1.2in;">
            <div>Accountant Signature</div>
            <div>Customer Signature</div>
            <div>Company Signature</div>
        </div>

    </div>
    {{--
    <div class="page page-break">

        <!-- Title -->
        <div class="title" style="font-size:20pt; font-weight:bold; text-align:center; margin-bottom:25px;">
            File Transfer
        </div>

        <!-- File Info -->
        <div class="row">
            <div>
                File No:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->form_no ?? '' }}
                </span>
            </div>

            <div>
                Security Note No:
                <span class="value" style="min-width:190px;"></span>
            </div>

            <div>
                Date:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $fileCancellation->bookingApplication->date ? \Carbon\Carbon::parse($fileCancellation->bookingApplication->date)->format('d-m-Y') : '' }}
                </span>
            </div>
        </div>

        <!-- Project Info -->
        <div class="row" style="margin-top: 25px !important; margin-bottom:20px;">
            <div>
                <strong>1- Project Name:</strong>
                <span class="value" style="min-width:581px;"></span>
            </div>
        </div>

        <div class="row" style="margin-top: 25px !important;">
            <div>
                Project Address:
                <span class="value" style="min-width:590px;"></span>
            </div>
        </div>

        <div class="row" style="margin-top: 25px !important;">
            <div>
                A-Unit No:
                <span class="value" style="min-width:160px;"></span>
            </div>

            <div>
                Phase:
                <span class="value" style="min-width:140px;"></span>
            </div>

            <div>
                Area in Marla:
                <span class="value" style="min-width:140px;"></span>
            </div>
        </div>

        <!-- File Seller -->
        <div class="section" style="margin-top: 25px !important;">
            <div class="section-title" style="text-align: center !important; margin-top:5px;">File Seller</div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    <strong>2-Name:</strong>
                    <span class="value" style="min-width:638px;"></span>
                </div>


            </div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    Father / Husband’s Name:
                    <span class="value" style="min-width:293px;"></span>
                </div>
                <div>
                    CNIC #:
                    <span class="value" style="min-width:170px;"></span>
                </div>
            </div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    Booking Value:
                    <span class="value" style="min-width:115px;"></span>
                </div>

                <div>
                    Received Amount:
                    <span class="value" style="min-width:115px;"></span>
                </div>

                <div>
                    Remaining Amount:
                    <span class="value" style="min-width:100px;"></span>
                </div>
            </div>
        </div>

        <!-- File Buyer -->
        <div class="section" style="margin-top: 25px !important;">
            <div class="section-title" style="text-align: center !important;">File Buyer</div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    <strong>3-Name:</strong>
                    <span class="value" style="min-width:638px;"></span>
                </div>


            </div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    Father / Husband’s Name:
                    <span class="value" style="min-width:293px;"></span>
                </div>
                <div>
                    CNIC #:
                    <span class="value" style="min-width:170px;"></span>
                </div>
            </div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    Booking Value:
                    <span class="value" style="min-width:115px;"></span>
                </div>

                <div>
                    Transfer Amount:
                    <span class="value" style="min-width:115px;"></span>
                </div>

                <div>
                    Remaining Amount:
                    <span class="value" style="min-width:100px;"></span>
                </div>
            </div>
        </div>

        <!-- Transfer Charges
        <div class="section" style="margin-top: 25px !important;">
            {{-- <div class="section-title">Transfer Charges</div>
            <div>
                <strong>4-Transfer Charges:</strong>
                <span class="value" style="min-width:558px;"></span>
            </div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    Received Transfer Charges:
                    <span class="value" style="min-width:514px;"></span>
                </div>
            </div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    Remarks:
                    <span class="value" style="min-width:633px;"></span>
                </div>
            </div>
        </div>

        <!-- Declaration -->
        <div style="margin-top:30px; font-size:10pt; line-height:1.6;">
            I hereby declare that I have no objection to transfer this file and its deposit to the above party.
            And that after today I will not own this file, nor will I be authorized to take any legal action
            related to this file. Moreover, I have no objection regarding the registry and possession being
            given to the above party.
        </div>

        <!-- Signatures -->
        <div class="signatures" style="margin-top:1in;">
            <div>Company Signature</div>
            <div>Seller Signature</div>
            <div>Buyer Signature</div>
        </div>

    </div> --}}

</body>


</html>
