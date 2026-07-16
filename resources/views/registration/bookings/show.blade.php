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

        .page2 {
            width: 8.5in;
            min-height: 14in;
            margin: 0 auto;
            box-sizing: border-box;
            padding: 0.1in 0.6in 0.6in 0.2in;
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
            line-height: 1.2;
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

        /* th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        } */

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

        .signatures2 {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
        }

        .sign-box2 {
            width: 200px;
            text-align: center;
        }

        .line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <button class="print-btn" onclick="window.print()">Print</button>
    <a href="{{ route('bookings.bookingListing') }}" class="print-btn" style="background: black !important;">
        Back
    </a>

    <div class="page">
        <div class="title">Booking Approval Form</div>

        <!-- File Info -->
        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value" style="min-width: 200px !important; text-align: center;"></span>
            </div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <!-- Unit Profile -->
        <div class="section">
            <div class="section-title" style="margin-bottom: 0px !important;">Unit Profile</div>
            <table>
                <tr>
                    <td style="width: 65% !important">Project Name: <span class="value"
                            style="min-width: 340px !important; text-align: center;">{{ $bookingData->project->name_en ?? '' }}</span>
                    </td>
                    <td>Phase: <span class="value"
                            style="min-width: 185px !important; text-align: center;">{{ $bookingData->project->phase_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Project Address: <span class="value"
                            style="min-width:579px; text-align: center;">{{ $bookingData->project->address_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Unit No: <span class="value"
                            style="min-width:140px; text-align: center;">{{ $bookingData->product->unit_no ?? '' }}</span>
                        &nbsp;
                        Area in Marla: <span class="value"
                            style="min-width:145px; text-align: center;">{{ $bookingData->product->total_marla ?? '' }}</span>&nbsp;
                        Area in Feet: <span class="value"
                            style="min-width:145px; text-align: center;">{{ $bookingData->product->total_square_feet ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Unit Facing: <span class="value"
                            style="min-width:360px; text-align: center;">{{ $bookingData->product->facing->name_en ?? '' }}</span>
                        &nbsp;
                        Road Facing: <span class="value"
                            style="min-width:144px; text-align: center;">{{ $bookingData->product->road->title_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Residential / Commercial: <span class="value"
                            style="min-width:518px; text-align: center;">{{ $bookingData->product->name_en ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Allottee Profile -->
        <div class="section">
            <div class="section-title">Allottee Profile</div>
            <table>
                <tr>
                    <td>Name: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingData->party->name_en ?? '' }}</span>
                        &nbsp;
                        S/o,D/o,W/o: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingData->party->father_name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>CNIC: <span class="value"
                            style="min-width:155px; text-align: center;">{{ $bookingData->party->cnic_no ?? '' }}</span>
                        &nbsp;
                        Cast: <span class="value"
                            style="min-width:190px; text-align: center;">{{ $bookingData->party->cast->title_en ?? '' }}</span>
                        &nbsp;
                        Occupation: <span class="value"
                            style="min-width:155px; text-align: center;">{{ $bookingData->party->occupation->title_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{-- Resident <span class="checkbox"></span> &nbsp;
                        Overseas <span class="checkbox"></span> &nbsp; --}}
                        Resident
                        <span class="checkbox">
                            @if (isset($bookingData->party->residential_status) && $bookingData->party->residential_status == 7)
                                ✓
                            @endif
                        </span>
                        &nbsp;

                        Overseas
                        <span class="checkbox">
                            @if (isset($bookingData->party->residential_status) && $bookingData->party->residential_status != 7)
                                ✓
                            @endif
                        </span>
                        &nbsp;
                        Address: <span class="value"
                            style="min-width:441px; text-align: center;">{{ $bookingData->party->home_address_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Phone: <span class="value"
                            style="min-width:130px; text-align: center;">{{ $bookingData->party->contact_number_1 ?? '' }}</span>
                        &nbsp;
                        Phone2: <span class="value"
                            style="min-width:190px; text-align: center;">{{ $bookingData->party->contact_number_2 ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Nominee Profile -->
        <div class="section">
            <div class="section-title" style="margin-bottom: 0px !important;">Nominee Profile</div>
            <table>
                <tr>
                    <td>Name: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingNomineeData->nomineeParty->name_en ?? '' }}</span>
                        &nbsp;
                        S/o,D/o,W/o: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingNomineeData->nomineeParty->father_name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Cast: <span class="value"
                            style="min-width:170px; text-align: center;">{{ $bookingNomineeData->nomineeParty->cast->title_en ?? '' }}</span>
                        CNIC: <span class="value"
                            style="min-width:185px; text-align: center;">{{ $bookingNomineeData->nomineeParty->cnic_no ?? '' }}</span>
                        &nbsp;
                        Relation with Buyer: <span class="value"
                            style="min-width:102px; text-align: center;">{{ $bookingNomineeData->relation->name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Phone: <span class="value"
                            style="min-width:125px; text-align: center;">{{ $bookingNomineeData->nomineeParty->contact_number_1 ?? '' }}</span>
                        &nbsp;
                        Address: <span class="value"
                            style="min-width:440px; text-align: center;">{{ $bookingNomineeData->nomineeParty->home_address_en ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Pricing -->
        <div class="section">
            <div class="pricing-line">
                <div class="section-title">Pricing:</div>
                <span class="checkbox"></span>Installment Duration:
                <span class="value">{{ $installment_duration ?? '' }}</span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="checkbox"></span> Cash Duration:
                <span class="value">{{ $cash_duration ?? '' }}</span>
            </div>

            <p style="margin-top: 0px !important;">
                Client shall pay to the company a total sum of Rs. <span class="value"
                    style="min-width:140px; text-align: center;">{{ $bookingData->total_amount ?? '' }}</span>
                @ <span class="value" style="min-width:140px; text-align: center;">{{ $permarlaValue ?? '' }}</span>
                Per Marla within
                agreed period as per
                following
                payment plan.
            </p>
        </div>

        <!-- Installment Plan -->
        <div class="section">
            <div class="section-title" style="margin-bottom: 0px !important;">Installment Plan</div>
            <div class="installment-plan">
                <p style="margin-top: 0px !important;">
                    Advance: <span class="value"
                        style="min-width:440px; text-align: center;">{{ $advancePayment ?? '' }}</span> &nbsp;
                    Date: <span class="value"
                        style="min-width:143px">{{ $advancePaymentScheduleData?->due_date ? \Carbon\Carbon::parse($advancePaymentScheduleData->due_date)->format('d-m-Y') : '' }}
                    </span><br>
                    <span class="value"
                        style="min-width:62px; text-align: center;">{{ $installmentArrayCount ?? '' }}</span>Installment
                    @
                    <span class="value">{{ $installmentPayAmount ?? '' }}</span>
                    Total Installment Value: <span class="value"
                        style="min-width:85px">{{ $installmentPayment ?? '--' }}</span>
                    Ins Start on Date: <span class="value"
                        style="min-width:75px">{{ $installmentPaymentScheduleData?->due_date ? \Carbon\Carbon::parse($installmentPaymentScheduleData->due_date)->format('d-m-Y') : '' }}
                    </span><br>
                    <span class="value"
                        style="min-width:75px; text-align: center;">{{ $duePaymentArrayCount ?? '' }}</span>Dues @
                    <span class="value"
                        style="min-width:135px; text-align: center;">{{ $duePayAmount ?? '--' }}</span>&nbsp;
                    Total Dues Value: <span class="value"
                        style="min-width:110px; text-align: center;">{{ $duePayment ?? '--' }}</span>
                    &nbsp;
                    Start Date: <span class="value"
                        style="min-width:110px; text-align: center;">{{ $duePaymentScheduleData?->due_date ? \Carbon\Carbon::parse($duePaymentScheduleData->due_date)->format('d-m-Y') : '' }}</span><br>
                    D-Name: <span class="value"
                        style="min-width:480px; text-align: center;">{{ $bookingData->dealer->name_en ?? '' }}</span>
                    C-Value: <span class="value"
                        style="min-width:93px; text-align: center;">{{ $bookingData->commission ?? '' }}</span>
                    &nbsp;
                </p>
                <p style=" line-height: 0.2 !important;"><Strong>Note:</Strong>The Payment Schedule is attached to the
                    file.</p>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="sign-box">Company Signature</div>
            <div class="sign-box">Verified by</div>
            <div class="sign-box">Client Signature</div>
        </div>
    </div>

    <!-- Second Page: Sale Deed -->
    <div class="page">
        <div class="title">Sale Deed</div>

        <!-- File Info -->
        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value"
                    style="min-width: 200px !important; text-align: center;"></span></div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <!-- Unit Profile -->
        <div class="section">
            <div class="section-title">Unit Profile</div>
            <table>
                <tr>
                    <td>Project Name:
                        <span class="value" style="min-width:280px; text-align:center;">
                            {{ $bookingData->project->name_en ?? '' }}
                        </span>
                    </td>

                    <td style="width: 20%;">Phase:
                        <span class="value" style="min-width:70px; text-align:center;">
                            {{ $bookingData->project->phase_en ?? '' }}
                        </span>
                    </td>

                    <td rowspan="7"
                        style="width:140px; height:160px; border:1px solid #000; text-align:center; vertical-align:middle; padding:0;">
                        @if (!empty($bookingData->party->profile_image))
                            <img src="{{ asset('storage/' . $bookingData->party->profile_image) }}" alt="Party Photo"
                                style="width:100%; height:100%; object-fit:contain; display:block; margin:0; padding:0;">
                        @else
                            <div style="font-size:10pt; color:#555; line-height:160px;">Photo</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Project Address:
                        <span class="value" style="min-width:430px; text-align:center;">
                            {{ $bookingData->project->address_en ?? '' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Unit No: <span class="value"
                            style="min-width:255px; text-align:center;">{{ $bookingData->product->unit_no ?? '' }}</span>
                        &nbsp;
                        Area in Marla:
                        <span class="value"
                            style="min-width:120px; text-align:center;">{{ $bookingData->product->total_marla ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Area in Feet:
                        <span class="value"
                            style="min-width:110px; text-align:center;">{{ $bookingData->product->total_square_feet ?? '' }}</span>&nbsp;
                        Unit Facing:
                        <span class="value"
                            style="min-width:250px; text-align:center;">{{ $bookingData->product->facing->name_en ?? '' }}</span>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">Residential/Commercial:
                        <span class="value"
                            style="min-width:150px; text-align:center;">{{ $bookingData->product->name_en ?? '' }}</span>&nbsp;
                        Road Facing:
                        <span class="value"
                            style="min-width:125px; text-align:center;">{{ $bookingData->product->road->title_en ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Allottee Profile</div>
            <table>
                <tr>
                    <td>Name: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingData->party->name_en ?? '' }}</span>
                        &nbsp;
                        S/o,D/o,W/o: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingData->party->father_name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>CNIC: <span class="value"
                            style="min-width:155px; text-align: center;">{{ $bookingData->party->cnic_no ?? '' }}</span>
                        &nbsp;
                        Cast: <span class="value"
                            style="min-width:190px; text-align: center;">{{ $bookingData->party->cast->title_en ?? '' }}</span>
                        &nbsp;
                        Occupation: <span class="value"
                            style="min-width:155px; text-align: center;">{{ $bookingData->party->occupation->title_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{-- Resident <span class="checkbox"></span> &nbsp;
                        Overseas <span class="checkbox"></span> &nbsp; --}}
                        Resident
                        <span class="checkbox">
                            @if (isset($bookingData->party->residential_status) && $bookingData->party->residential_status == 7)
                                ✓
                            @endif
                        </span>
                        &nbsp;

                        Overseas
                        <span class="checkbox">
                            @if (isset($bookingData->party->residential_status) && $bookingData->party->residential_status != 7)
                                ✓
                            @endif
                        </span>
                        &nbsp;
                        Address: <span class="value"
                            style="min-width:436px; text-align: center;">{{ $bookingData->party->home_address_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Phone: <span class="value"
                            style="min-width:130px; text-align: center;">{{ $bookingData->party->contact_number_1 ?? '' }}</span>
                        &nbsp;
                        Phone2: <span class="value"
                            style="min-width:190px; text-align: center;">{{ $bookingData->party->contact_number_2 ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Nominee Profile -->
        <div class="section">
            <div class="section-title">Nominee Profile</div>
            <table>
                <tr>
                    <td>Name: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingNomineeData->nomineeParty->name_en ?? '' }}</span>
                        &nbsp;
                        S/o,D/o,W/o: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingNomineeData->nomineeParty->father_name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Cast: <span class="value"
                            style="min-width:170px; text-align: center;">{{ $bookingNomineeData->nomineeParty->cast->title_en ?? '' }}</span>
                        CNIC: <span class="value"
                            style="min-width:185px; text-align: center;">{{ $bookingNomineeData->nomineeParty->cnic_no ?? '' }}</span>
                        &nbsp;
                        Relation with Buyer: <span class="value"
                            style="min-width:102px; text-align: center;">{{ $bookingNomineeData->relation->name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Phone: <span class="value"
                            style="min-width:125px; text-align: center;">{{ $bookingNomineeData->nomineeParty->contact_number_1 ?? '' }}</span>
                        &nbsp;
                        Address: <span class="value"
                            style="min-width:440px; text-align: center;">{{ $bookingNomineeData->nomineeParty->home_address_en ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Pricing -->
        <div class="section">
            <div class="pricing-line">
                <div class="section-title">Pricing:</div>
                <span class="checkbox"></span>Installment Duration:
                <span class="value">{{ $installment_duration ?? '' }}</span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="checkbox"></span> Cash Duration:
                <span class="value">{{ $cash_duration ?? '' }}</span>
            </div>

            <p>
                Client shall pay to the company a total sum of Rs. <span class="value"
                    style="min-width:140px; text-align: center;">{{ $bookingData->total_amount ?? '' }}</span>
                @ <span class="value" style="min-width:140px; text-align: center;">{{ $permarlaValue ?? '' }}</span>
                Per Marla within
                agreed period as per
                following
                payment plan.
            </p>
        </div>

        <!-- Installment Plan -->
        <div class="section">
            <div class="section-title">Installment Plan</div>
            <div class="installment-plan">
                <p>
                    Advance: <span class="value"
                        style="min-width:440px; text-align: center;">{{ $advancePayment ?? '' }}</span> &nbsp;
                    Date: <span class="value"
                        style="min-width:143px">{{ $advancePaymentScheduleData?->due_date ? \Carbon\Carbon::parse($advancePaymentScheduleData->due_date)->format('d-m-Y') : '' }}
                    </span><br>
                    <span class="value"
                        style="min-width:62px; text-align: center;">{{ $installmentArrayCount ?? '' }}</span>Installment
                    @
                    <span class="value">{{ $installmentPayAmount ?? '' }}</span>
                    Total Installment Value: <span class="value"
                        style="min-width:85px">{{ $installmentPayment ?? '--' }}</span>
                    Ins Start on Date: <span class="value"
                        style="min-width:75px">{{ $installmentPaymentScheduleData?->due_date ? \Carbon\Carbon::parse($installmentPaymentScheduleData->due_date)->format('d-m-Y') : '' }}
                    </span><br>
                    <span class="value"
                        style="min-width:75px; text-align: center;">{{ $duePaymentArrayCount ?? '' }}</span>Dues @
                    <span class="value"
                        style="min-width:135px; text-align: center;">{{ $duePayAmount ?? '--' }}</span>&nbsp;
                    Total Dues Value: <span class="value"
                        style="min-width:110px; text-align: center;">{{ $duePayment ?? '--' }}</span>
                    &nbsp;
                    Start Date: <span class="value"
                        style="min-width:110px; text-align: center;">{{ $duePaymentScheduleData?->due_date ? \Carbon\Carbon::parse($duePaymentScheduleData->due_date)->format('d-m-Y') : '' }}</span><br>
                    {{-- D-Name: <span class="value"
                    style="min-width:637px; text-align: center;">{{ $bookingData->dealer->name_en ?? '' }}</span><br>
                C-Value: <span class="value"
                    style="min-width:164px; text-align: center;">{{ $bookingData->commission ?? '' }}</span> &nbsp;
                Cell No: <span class="value"
                    style="min-width:164px; text-align: center;">{{ $bookingData->dealer->contact_number_1 ?? '' }}</span> --}}
                </p>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures2">
            <div class="sign-box">Company Signature</div>
            <div class="sign-box">Verified by</div>
            <div class="sign-box">Client Signature</div>
        </div>
    </div>

    <!-- ================= Third Page (Terms & Conditions) ================= -->
    <div class="page">

        <div class="title" style="margin-bottom: 0px !important;">TERMS AND CONDITIONS
        </div>
        <div class="content" style="margin: 0px !important;">
            <p style="text-align:center; font-weight:bold; font-size: 20px;">Annexure – B</p>
        </div>

        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value"
                    style="min-width: 200px !important; text-align: center;"></span></div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <div class="content" style="font-size:14px; line-height:1.6; text-align:justify;">

            <p style="margin-top:15px; font-weight:bold;">Now therefore, this agreement witness and parties mutually
                agree as follows:
            </p>

            <ol style="margin-left:-25px; margin-top:10px;">
                <li>The client clearly understands that the payment of the installments on due dates as per Annexure-A
                    is to be treated as the essence of this contract and it has been made clear to the client that the
                    company has to make the payments for the purchase of the land, materials, and to deposit advance
                    towards the supplies, hence the necessity.</li>
                <li>The client shall pay the installments within 10 days of the due date or before, without any demand
                    or notice by the Company. However, if payment of any installment is not made within 10 days of its
                    due date, a penalty equivalent to 1% of the due amount shall be charged for each day for the next 20
                    days. After that, the next installment shall automatically become due. If both installments are not
                    paid till the due date of the second unpaid installment, the company may in its sole discretion
                    treat the Agreement as rescinded, and in that event, the company shall be entitled to cancel the
                    Plot/Shop.</li>
                <li>If the client cancels the Plot/Shop at any stage, the company will return his/her amount after
                    deducting 18% of the booking value.
                    <ul style="list-style-type:lower-alpha; margin-left:20px;">
                        <li>After deduction of cancellation charges, refund of the balance amount will start after six
                            months of the cancellation date.</li>
                        <li>Keeping in view clause No.1, as the refund period starts, the company shall not refund the
                            balance amount in one installment; it will be refunded to the client in 6 to 12
                            installments.</li>
                    </ul>
                </li>
                <li>If the client transfers the ownership of property to a third party at any stage, the company will
                    charge 5% transfer fee.</li>
                <li>The client shall bear the cost of stamp duty, registration fee, 3% processing fee, Rs.10,000 per
                    unit possession fee, and development charges, etc., in connection with execution and completion of
                    sale deed documents. The client shall also be liable for any other taxes (e.g., CVT, GST, GT, etc.)
                    imposed by the Provincial or Federal Government in connection with the Project/Property.</li>
                <li>The client and any other person under him/her shall not use any part of the project other than the
                    demised premises. The client/cohabitant cannot convert the common areas into personal use by any
                    means.</li>
                <li>It is the exclusive right of the company to utilize, allot, sell, rent out, or lease the space of
                    boarding for publicity/advertising in the common areas or on the exterior of the project without any
                    reservation.</li>
                <li>The area of the plot mentioned is approximate. If the actual measurement of the area is found more
                    or less, the buyer shall be charged on the actual allocated area on a proportionate basis.</li>
            </ol>

            <!-- Footer Section -->
            <div style="margin-top:60px; display:flex; justify-content:space-between;">
                <div style="width:45%; text-align:left;">

                </div>
                <div style="width:45%; text-align:right;">
                    <p><b>Client’s Signature</b></p>
                </div>
            </div>
        </div>
    </div>


    <div class="page">

        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value"
                    style="min-width: 200px !important; text-align: center;"></span></div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <div class="content" style="font-size:14px; line-height:1.6; text-align:justify;">

            {{-- <p style="margin-top:15px; font-weight:bold;">Now therefore, this agreement witness and parties mutually agree as follows:
            </p> --}}

            <ol start="9" style="margin-left:-25px; margin-top:10px;">
                <li>as per the contract, the company will be bound to provide the client with project development,
                    possession, and plot registry within the stipulated period. However, if there is any delay or
                    stoppage in the approval or development works of the project due to any reasons, then the company
                    can transfer the client to any of its other projects.</li>
                <li>
                    <ul style="list-style-type:upper-alpha; margin-left:-20px; ">
                        <li>That the Company shall provide in the Project common amenities, facilities such as roads,
                            sewerage, disposal network, street lights, parks, electricity, main gate and surrounding
                            wall upto 5.5 feet. The amount of Sui gas is not included in the sale value. When the gas is
                            supplied by the government, whatever cost will be incurred will have to be paid by the
                            consumers on a per marla basis.</li>
                        <li>The client shall not alter the exterior of the property into misbalance the general out-look
                            of the scheme or lift the level of the ramp of the house from allowed height of 12 inches
                            from road or any such other act.</li>
                        <li>That the Company may further extend the 12/1/2 and utilize all aminities wholly or partially
                            for further extension.</li>
                        <li>That the Client shall become member of the Management Society (set by the developer) and
                            shall abide by all the rules and regulation as framed by the said society for operating the
                            common amenities of the Project. The developer shall constitute a committee of members and
                            shall have the power to change or remove any member if necessary.</li>
                        <li>That the client shall regularly pay his share of the operational maintenance cost and
                            utility bills determined by the Society from time to time on a monthly basis to the said
                            Society or company managing the Project and operating the above said common amenities</li>
                        <li>That the Company shall operate, run and maintain common amenities/ services in the Project
                            on no-profit-no-loss basis for a period of six months after completion of said amenities.
                            After erection of Electricity, The Client shall regularly pay on monthly basis his/her share
                            of all costs and expense involved in the regard, regardless of whether or not he/she has
                            actually taken possession of his/her completed house. After this period of two years, the
                            company may continue to extend this service subject consent of the client, othervise the
                            Management Society or any representatives of clients of the project shall become responsible
                            to operate, run maintain and manage all common amenities services etc.</li>
                    </ul>
                </li>

                <li>The parties here to agree with all obligations and restrictions contained here in all the
                    circumstances under this agreement and each and everyone of such covenant, obligations and each
                    every part there of shall be deemed to be a severable and an independent covenant.</li>
            </ol>

            <!-- Footer Section -->
            <div style="margin-top:100px; display:flex; justify-content:space-between;">
                <div style="width:45%; text-align:left;">

                </div>
                <div style="width:45%; text-align:right;">
                    <p><b>Client’s Signature</b></p>
                </div>
            </div>
        </div>
    </div>

    <div class="page">

        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value"
                    style="min-width: 200px !important; text-align: center;"></span></div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <div class="content" style="font-size:14px; line-height:1.6; text-align:justify;">

            {{-- <p style="margin-top:15px; font-weight:bold;">Now therefore, this agreement witness and parties mutually agree as follows:
            </p> --}}

            <ol start="12" style="margin-left:-25px; margin-top:20px;">
                <li>That the property will not be used for any other purposes, except for the within have been expressly
                    mentioned in this agreement even after the execution of the sale deed and passing on the title to
                    client.</li>
                <li>in the event of any dispute or differences regarding any matter touching the interpretation or
                    any clause here of, performance of any agreement, committing of default by any of the party or any
                    other ancillary or incidental matter, it shall be referred to and finally determined by arbitration
                    accordance with the provision of Arbitration Act, 1940. The cost of the arbitration shall be born by
                    person invoking the remedy.</li>

                <li>That the territorial jurisdiction shall be the same area where the registered office of the company
                    would be situated not with standing where the instant agreement is executed.</li>
                <li>That is no case the Client shall ask the company to revise or modify the design concept and such
                    extemal architectural features as window elements, railings and exterior finishes etc.</li>
                <li>That the client here by nominates Mr/Mrs./Miss. For the purpose of dealing with the Company and the
                    person so nominated shall be for all intents and purposes treated as the representative of the
                    client for the due performance of the Agreement.</li>
                <li>That the client shall not be authorized to transfer his/her rights in the Agreement unless with the
                    permission of the company and subject to the payment of charges to be determined by the Company at
                    the time of transfer.</li>
                <li>That the address given by the Client in this agn agreement shall be deemed to be correct
                    official/postal address for any correspondence, and a letter/notice by post/Courier by the Company
                    at the said address shall be deemed to the proper and valid notice for the purpose of these terms
                    and conditions.</li>
                <li>That this is certify that the Company and the client have studied this Agreement, the translation of
                    which has been read out and explained in the language they understand, and they, after understanding
                    and following all the clauses, have a fixed their signatures.</li>
                <li>If the plot area is less on the spot, the company will refund the amount based on the booking price.
                    If the area is more, the user will pay according to the price available on the spot.</li>
                <li>For project improvement and road access requirements, the company reserves the right to change the
                    location of the plot if necessary.</li>

                <li>
                    The file holder or registry holder who will sell his plot must transfer the file to the buyer's name
                    at the time of sale so that the buyer can take possession from the company and start construction.
                </li>

            </ol>

            <!-- Footer Section -->
            <div style="margin-top:2.4in; display:flex; justify-content:space-between;">
                <div style="width:45%; text-align:left;">

                    <p><b>Company Signature</b></p>
                </div>
                <div style="width:45%; text-align:right;">

                    <p><b>Client’s Signature</b></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Page: Sale Deed -->
    <div class="page">
        <div class="title">Sale Deed</div>

        <!-- File Info -->
        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value"
                    style="min-width: 200px !important; text-align: center;"></span></div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <!-- Unit Profile -->
        <div class="section">
            <div class="section-title">Unit Profile</div>
            <table>
                <tr>
                    <td>Project Name:
                        <span class="value" style="min-width:280px; text-align:center;">
                            {{ $bookingData->project->name_en ?? '' }}
                        </span>
                    </td>

                    <td style="width: 20%;">Phase:
                        <span class="value" style="min-width:70px; text-align:center;">
                            {{ $bookingData->project->phase_en ?? '' }}
                        </span>
                    </td>

                    <td rowspan="7"
                        style="width:140px; height:160px; border:1px solid #000; text-align:center; vertical-align:middle; padding:0;">
                        @if (!empty($bookingData->party->profile_image))
                            <img src="{{ asset('storage/' . $bookingData->party->profile_image) }}" alt="Party Photo"
                                style="width:100%; height:100%; object-fit:contain; display:block; margin:0; padding:0;">
                        @else
                            <div style="font-size:10pt; color:#555; line-height:160px;">Photo</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Project Address:
                        <span class="value" style="min-width:430px; text-align:center;">
                            {{ $bookingData->project->address_en ?? '' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Unit No: <span class="value"
                            style="min-width:255px; text-align:center;">{{ $bookingData->product->unit_no ?? '' }}</span>
                        &nbsp;
                        Area in Marla:
                        <span class="value"
                            style="min-width:120px; text-align:center;">{{ $bookingData->product->total_marla ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Area in Feet:
                        <span class="value"
                            style="min-width:110px; text-align:center;">{{ $bookingData->product->total_square_feet ?? '' }}</span>&nbsp;
                        Unit Facing:
                        <span class="value"
                            style="min-width:250px; text-align:center;">{{ $bookingData->product->facing->name_en ?? '' }}</span>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">Residential/Commercial:
                        <span class="value"
                            style="min-width:150px; text-align:center;">{{ $bookingData->product->name_en ?? '' }}</span>&nbsp;
                        Road Facing:
                        <span class="value"
                            style="min-width:125px; text-align:center;">{{ $bookingData->product->road->title_en ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Allottee Profile</div>
            <table>
                <tr>
                    <td>Name: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingData->party->name_en ?? '' }}</span>
                        &nbsp;
                        S/o,D/o,W/o: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingData->party->father_name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>CNIC: <span class="value"
                            style="min-width:155px; text-align: center;">{{ $bookingData->party->cnic_no ?? '' }}</span>
                        &nbsp;
                        Cast: <span class="value"
                            style="min-width:190px; text-align: center;">{{ $bookingData->party->cast->title_en ?? '' }}</span>
                        &nbsp;
                        Occupation: <span class="value"
                            style="min-width:155px; text-align: center;">{{ $bookingData->party->occupation->title_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{-- Resident <span class="checkbox"></span> &nbsp;
                        Overseas <span class="checkbox"></span> &nbsp; --}}
                        Resident
                        <span class="checkbox">
                            @if (isset($bookingData->party->residential_status) && $bookingData->party->residential_status == 7)
                                ✓
                            @endif
                        </span>
                        &nbsp;

                        Overseas
                        <span class="checkbox">
                            @if (isset($bookingData->party->residential_status) && $bookingData->party->residential_status != 7)
                                ✓
                            @endif
                        </span>
                        &nbsp;
                        Address: <span class="value"
                            style="min-width:436px; text-align: center;">{{ $bookingData->party->home_address_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Phone: <span class="value"
                            style="min-width:130px; text-align: center;">{{ $bookingData->party->contact_number_1 ?? '' }}</span>
                        &nbsp;
                        Phone2: <span class="value"
                            style="min-width:190px; text-align: center;">{{ $bookingData->party->contact_number_2 ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Nominee Profile -->
        <div class="section">
            <div class="section-title">Nominee Profile</div>
            <table>
                <tr>
                    <td>Name: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingNomineeData->nomineeParty->name_en ?? '' }}</span>
                        &nbsp;
                        S/o,D/o,W/o: <span class="value"
                            style="min-width:272px; text-align: center;">{{ $bookingNomineeData->nomineeParty->father_name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Cast: <span class="value"
                            style="min-width:170px; text-align: center;">{{ $bookingNomineeData->nomineeParty->cast->title_en ?? '' }}</span>
                        CNIC: <span class="value"
                            style="min-width:185px; text-align: center;">{{ $bookingNomineeData->nomineeParty->cnic_no ?? '' }}</span>
                        &nbsp;
                        Relation with Buyer: <span class="value"
                            style="min-width:102px; text-align: center;">{{ $bookingNomineeData->relation->name_en ?? '' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Phone: <span class="value"
                            style="min-width:125px; text-align: center;">{{ $bookingNomineeData->nomineeParty->contact_number_1 ?? '' }}</span>
                        &nbsp;
                        Address: <span class="value"
                            style="min-width:440px; text-align: center;">{{ $bookingNomineeData->nomineeParty->home_address_en ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Pricing -->
        <div class="section">
            <div class="pricing-line">
                <div class="section-title">Pricing:</div>
                <span class="checkbox"></span>Installment Duration:
                <span class="value">{{ $installment_duration ?? '' }}</span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="checkbox"></span> Cash Duration:
                <span class="value">{{ $cash_duration ?? '' }}</span>
            </div>

            <p>
                Client shall pay to the company a total sum of Rs. <span class="value"
                    style="min-width:140px; text-align: center;">{{ $bookingData->total_amount ?? '' }}</span>
                @ <span class="value"
                    style="min-width:140px; text-align: center;">{{ $permarlaValue ?? '' }}</span>
                Per Marla within
                agreed period as per
                following
                payment plan.
            </p>
        </div>

        <!-- Installment Plan -->
        <div class="section">
            <div class="section-title">Installment Plan</div>
            <div class="installment-plan">
                <p>
                    Advance: <span class="value"
                        style="min-width:440px; text-align: center;">{{ $advancePayment ?? '' }}</span> &nbsp;
                    Date: <span class="value"
                        style="min-width:143px">{{ $advancePaymentScheduleData?->due_date ? \Carbon\Carbon::parse($advancePaymentScheduleData->due_date)->format('d-m-Y') : '' }}
                    </span><br>
                    <span class="value"
                        style="min-width:62px; text-align: center;">{{ $installmentArrayCount ?? '' }}</span>Installment
                    @
                    <span class="value">{{ $installmentPayAmount ?? '' }}</span>
                    Total Installment Value: <span class="value"
                        style="min-width:85px">{{ $installmentPayment ?? '--' }}</span>
                    Ins Start on Date: <span class="value"
                        style="min-width:75px">{{ $installmentPaymentScheduleData?->due_date ? \Carbon\Carbon::parse($installmentPaymentScheduleData->due_date)->format('d-m-Y') : '' }}
                    </span><br>
                    <span class="value"
                        style="min-width:75px; text-align: center;">{{ $duePaymentArrayCount ?? '' }}</span>Dues @
                    <span class="value"
                        style="min-width:135px; text-align: center;">{{ $duePayAmount ?? '--' }}</span>&nbsp;
                    Total Dues Value: <span class="value"
                        style="min-width:110px; text-align: center;">{{ $duePayment ?? '--' }}</span>
                    &nbsp;
                    Start Date: <span class="value"
                        style="min-width:110px; text-align: center;">{{ $duePaymentScheduleData?->due_date ? \Carbon\Carbon::parse($duePaymentScheduleData->due_date)->format('d-m-Y') : '' }}</span><br>
                    {{-- D-Name: <span class="value"
                    style="min-width:637px; text-align: center;">{{ $bookingData->dealer->name_en ?? '' }}</span><br>
                C-Value: <span class="value"
                    style="min-width:164px; text-align: center;">{{ $bookingData->commission ?? '' }}</span> &nbsp;
                Cell No: <span class="value"
                    style="min-width:164px; text-align: center;">{{ $bookingData->dealer->contact_number_1 ?? '' }}</span> --}}
                </p>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures2">
            <div class="sign-box">Company Signature</div>
            <div class="sign-box">Verified by</div>
            <div class="sign-box">Client Signature</div>
        </div>
    </div>

    @if ($bookingData->case === 'transfer')
        <div class="page">

            <!-- Title -->
            <div class="title" style="font-size:20pt; font-weight:bold; text-align:center; margin-bottom:25px;">
                File Transfer
            </div>

            <!-- File Info -->
            <div class="row">
                <div>
                    File No:
                    <span class="value" style="min-width:140px; text-align:center;">
                        {{ $bookingNo ?? '' }}
                    </span>
                </div>

                <div>
                    Security Note No:
                    <span class="value" style="min-width:190px;"></span>
                </div>

                <div>
                    Date:
                    <span class="value" style="min-width:120px; text-align:center;">
                        {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}
                    </span>
                </div>
            </div>

            <!-- Project Info -->
            <div class="row" style="margin-top: 25px !important; margin-bottom:20px;">
                <div>
                    <strong>1- Project Name:</strong>
                    <span class="value"
                        style="min-width:581px; text-align:center;">{{ $bookingData->project->name_en }}</span>
                </div>
            </div>

            <div class="row" style="margin-top: 25px !important;">
                <div>
                    Project Address:
                    <span class="value"
                        style="min-width:590px; text-align:center;">{{ $bookingData->project->address_en }}</span>
                </div>
            </div>

            <div class="row" style="margin-top: 25px !important; ">
                <div>
                    A-Unit No:
                    <span class="value"
                        style="min-width:160px; text-align:center;">{{ $bookingData->product->name_en }}</span>
                </div>

                <div>
                    Phase:
                    <span class="value"
                        style="min-width:140px; text-align:center;">{{ $bookingData->project->phase_en }}</span>
                </div>

                <div>
                    Area in Marla:
                    <span class="value"
                        style="min-width:140px; text-align:center;">{{ $bookingData->product->total_marla }}</span>
                </div>
            </div>

            <!-- File Seller -->
            <div class="section" style="margin-top: 25px !important;">
                <div class="section-title" style="text-align: center !important; margin-top:5px;">File Seller</div>

                <div class="row" style="margin-top: 25px !important;">
                    <div>
                        <strong>2-Name:</strong>
                        <span class="value"
                            style="min-width:638px; text-align:center;">{{ $previousBooking->party->name_en }}</span>
                    </div>


                </div>

                <div class="row" style="margin-top: 25px !important;">
                    <div>
                        Father / Husband’s Name:
                        <span class="value"
                            style="min-width:293px; text-align:center;">{{ $previousBooking->party->father_name_en }}</span>
                    </div>
                    <div>
                        CNIC #:
                        <span class="value"
                            style="min-width:170px; text-align:center;">{{ $previousBooking->party->cnic_no }}</span>
                    </div>
                </div>

                <div class="row" style="margin-top: 25px !important;">
                    <div>
                        Booking Value:
                        <span class="value"
                            style="min-width:115px; text-align:center;">{{ $previousBooking->total_amount }}</span>
                    </div>

                    <div>
                        Received Amount:
                        <span class="value" style="min-width:115px; text-align:center;">{{$bookingReceivedAmount ?? 0}}</span>
                    </div>

                    <div>
                        Remaining Amount:
                        <span class="value" style="min-width:100px; text-align:center;">{{$remainingBalance ?? 0}}</span>
                    </div>
                </div>
            </div>

            <!-- File Buyer -->
            <div class="section" style="margin-top: 25px !important;">
                <div class="section-title" style="text-align: center !important;">File Buyer</div>

                <div class="row" style="margin-top: 25px !important;">
                    <div>
                        <strong>3-Name:</strong>
                        <span class="value"
                            style="min-width:638px; text-align:center;">{{ $bookingData->party->name_en }}</span>
                    </div>


                </div>

                <div class="row" style="margin-top: 25px !important;">
                    <div>
                        Father / Husband’s Name:
                        <span class="value"
                            style="min-width:293px; text-align:center;">{{ $bookingData->party->father_name_en }}</span>
                    </div>
                    <div>
                        CNIC #:
                        <span class="value"
                            style="min-width:170px; text-align:center;">{{ $bookingData->party->cnic_no }}</span>
                    </div>
                </div>

                <div class="row" style="margin-top: 25px !important;">
                    <div>
                        Booking Value:
                        <span class="value"
                            style="min-width:115px; text-align:center;">{{ $bookingData->total_amount }}</span>
                    </div>

                    <div>
                        Transfer Amount:
                        <span class="value" style="min-width:115px; text-align:center;">{{$bookingReceivedAmount ?? 0}}</span>
                    </div>

                    <div>
                        Remaining Amount:
                        <span class="value" style="min-width:100px; text-align:center;">{{$remainingBalance ?? 0}}</span>
                    </div>
                </div>
            </div>

            {{-- <!-- Transfer Charges --}}
            <div class="section" style="margin-top: 25px !important;">
                <div class="section-title">Transfer Charges</div>
                <div>
                    <strong>4-Transfer Charges:</strong>
                    <span class="value"
                        style="min-width:558px; text-align:center;">{{ $bookingData->transfer_charges ?? '' }}</span>
                </div>

                <div class="row" style="margin-top: 25px !important;">
                    <div>
                        Received Transfer Charges:
                        <span class="value"
                            style="min-width:514px; text-align:center;">{{ $bookingData->transfer_charges ?? '' }}</span>
                    </div>
                </div>

                <div class="row" style="margin-top: 25px !important;">
                    <div>
                        Remarks:
                        <span class="value" style="min-width:633px; text-align:center;"></span>
                    </div>
                </div>
            </div>

            <!-- Declaration -->
            <div style="margin-top:20px; font-size:10pt; line-height:1.5;">
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

        </div>
    @endif

    <!-- ================= Third Page (Terms & Conditions) ================= -->
    <div class="page">

        <div class="title" style="margin-bottom: 0px !important;">TERMS AND CONDITIONS
        </div>
        <div class="content" style="margin: 0px !important;">
            <p style="text-align:center; font-weight:bold; font-size: 20px;">Annexure – B</p>
        </div>

        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value"
                    style="min-width: 200px !important; text-align: center;"></span></div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <div class="content" style="font-size:14px; line-height:1.6; text-align:justify;">

            <p style="margin-top:15px; font-weight:bold;">Now therefore, this agreement witness and parties mutually
                agree as follows:
            </p>

            <ol style="margin-left:-25px; margin-top:10px;">
                <li>The client clearly understands that the payment of the installments on due dates as per Annexure-A
                    is to be treated as the essence of this contract and it has been made clear to the client that the
                    company has to make the payments for the purchase of the land, materials, and to deposit advance
                    towards the supplies, hence the necessity.</li>
                <li>The client shall pay the installments within 10 days of the due date or before, without any demand
                    or notice by the Company. However, if payment of any installment is not made within 10 days of its
                    due date, a penalty equivalent to 1% of the due amount shall be charged for each day for the next 20
                    days. After that, the next installment shall automatically become due. If both installments are not
                    paid till the due date of the second unpaid installment, the company may in its sole discretion
                    treat the Agreement as rescinded, and in that event, the company shall be entitled to cancel the
                    Plot/Shop.</li>
                <li>If the client cancels the Plot/Shop at any stage, the company will return his/her amount after
                    deducting 18% of the booking value.
                    <ul style="list-style-type:lower-alpha; margin-left:20px;">
                        <li>After deduction of cancellation charges, refund of the balance amount will start after six
                            months of the cancellation date.</li>
                        <li>Keeping in view clause No.1, as the refund period starts, the company shall not refund the
                            balance amount in one installment; it will be refunded to the client in 6 to 12
                            installments.</li>
                    </ul>
                </li>
                <li>If the client transfers the ownership of property to a third party at any stage, the company will
                    charge 5% transfer fee.</li>
                <li>The client shall bear the cost of stamp duty, registration fee, 3% processing fee, Rs.10,000 per
                    unit possession fee, and development charges, etc., in connection with execution and completion of
                    sale deed documents. The client shall also be liable for any other taxes (e.g., CVT, GST, GT, etc.)
                    imposed by the Provincial or Federal Government in connection with the Project/Property.</li>
                <li>The client and any other person under him/her shall not use any part of the project other than the
                    demised premises. The client/cohabitant cannot convert the common areas into personal use by any
                    means.</li>
                <li>It is the exclusive right of the company to utilize, allot, sell, rent out, or lease the space of
                    boarding for publicity/advertising in the common areas or on the exterior of the project without any
                    reservation.</li>
                <li>The area of the plot mentioned is approximate. If the actual measurement of the area is found more
                    or less, the buyer shall be charged on the actual allocated area on a proportionate basis.</li>
            </ol>

            <!-- Footer Section -->
            <div style="margin-top:60px; display:flex; justify-content:space-between;">
                <div style="width:45%; text-align:left;">

                </div>
                <div style="width:45%; text-align:right;">
                    <p><b>Client’s Signature</b></p>
                </div>
            </div>
        </div>
    </div>


    <div class="page">

        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value"
                    style="min-width: 200px !important; text-align: center;"></span></div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <div class="content" style="font-size:14px; line-height:1.6; text-align:justify;">

            {{-- <p style="margin-top:15px; font-weight:bold;">Now therefore, this agreement witness and parties mutually agree as follows:
            </p> --}}

            <ol start="9" style="margin-left:-25px; margin-top:10px;">
                <li>as per the contract, the company will be bound to provide the client with project development,
                    possession, and plot registry within the stipulated period. However, if there is any delay or
                    stoppage in the approval or development works of the project due to any reasons, then the company
                    can transfer the client to any of its other projects.</li>
                <li>
                    <ul style="list-style-type:upper-alpha; margin-left:-20px; ">
                        <li>That the Company shall provide in the Project common amenities, facilities such as roads,
                            sewerage, disposal network, street lights, parks, electricity, main gate and surrounding
                            wall upto 5.5 feet. The amount of Sui gas is not included in the sale value. When the gas is
                            supplied by the government, whatever cost will be incurred will have to be paid by the
                            consumers on a per marla basis.</li>
                        <li>The client shall not alter the exterior of the property into misbalance the general out-look
                            of the scheme or lift the level of the ramp of the house from allowed height of 12 inches
                            from road or any such other act.</li>
                        <li>That the Company may further extend the 12/1/2 and utilize all aminities wholly or partially
                            for further extension.</li>
                        <li>That the Client shall become member of the Management Society (set by the developer) and
                            shall abide by all the rules and regulation as framed by the said society for operating the
                            common amenities of the Project. The developer shall constitute a committee of members and
                            shall have the power to change or remove any member if necessary.</li>
                        <li>That the client shall regularly pay his share of the operational maintenance cost and
                            utility bills determined by the Society from time to time on a monthly basis to the said
                            Society or company managing the Project and operating the above said common amenities</li>
                        <li>That the Company shall operate, run and maintain common amenities/ services in the Project
                            on no-profit-no-loss basis for a period of six months after completion of said amenities.
                            After erection of Electricity, The Client shall regularly pay on monthly basis his/her share
                            of all costs and expense involved in the regard, regardless of whether or not he/she has
                            actually taken possession of his/her completed house. After this period of two years, the
                            company may continue to extend this service subject consent of the client, othervise the
                            Management Society or any representatives of clients of the project shall become responsible
                            to operate, run maintain and manage all common amenities services etc.</li>
                    </ul>
                </li>

                <li>The parties here to agree with all obligations and restrictions contained here in all the
                    circumstances under this agreement and each and everyone of such covenant, obligations and each
                    every part there of shall be deemed to be a severable and an independent covenant.</li>


            </ol>

            <!-- Footer Section -->
            <div style="margin-top:100px; display:flex; justify-content:space-between;">
                <div style="width:45%; text-align:left;">

                </div>
                <div style="width:45%; text-align:right;">
                    <p><b>Client’s Signature</b></p>
                </div>
            </div>
        </div>
    </div>

    <div class="page">

        <div class="row">
            <div style="color: red !important;">
                File No: <span class="value"
                    style="min-width: 130px !important; text-align: center; color:#000 !important">{{ $bookingNo ?? '' }}</span>
            </div>&nbsp;
            <div style="color: red;">
                Security Note No: <span class="value"
                    style="min-width: 200px !important; text-align: center;"></span></div>
            <div>Date: <span class="value">
                    {{ $bookingData->date ? \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') : '' }}</span>
            </div>
        </div>

        <div class="content" style="font-size:14px; line-height:1.6; text-align:justify;">

            {{-- <p style="margin-top:15px; font-weight:bold;">Now therefore, this agreement witness and parties mutually agree as follows:
            </p> --}}

            <ol start="12" style="margin-left:-25px; margin-top:20px;">
                <li>That the property will not be used for any other purposes, except for the within have been expressly
                    mentioned in this agreement even after the execution of the sale deed and passing on the title to
                    client.</li>
                <li>in the event of any dispute or differences regarding any matter touching the interpretation or
                    any clause here of, performance of any agreement, committing of default by any of the party or any
                    other ancillary or incidental matter, it shall be referred to and finally determined by arbitration
                    accordance with the provision of Arbitration Act, 1940. The cost of the arbitration shall be born by
                    person invoking the remedy.</li>

                <li>That the territorial jurisdiction shall be the same area where the registered office of the company
                    would be situated not with standing where the instant agreement is executed.</li>
                <li>That is no case the Client shall ask the company to revise or modify the design concept and such
                    extemal architectural features as window elements, railings and exterior finishes etc.</li>
                <li>That the client here by nominates Mr/Mrs./Miss. For the purpose of dealing with the Company and the
                    person so nominated shall be for all intents and purposes treated as the representative of the
                    client for the due performance of the Agreement.</li>
                <li>That the client shall not be authorized to transfer his/her rights in the Agreement unless with the
                    permission of the company and subject to the payment of charges to be determined by the Company at
                    the time of transfer.</li>
                <li>That the address given by the Client in this agn agreement shall be deemed to be correct
                    official/postal address for any correspondence, and a letter/notice by post/Courier by the Company
                    at the said address shall be deemed to the proper and valid notice for the purpose of these terms
                    and conditions.</li>
                <li>That this is certify that the Company and the client have studied this Agreement, the translation of
                    which has been read out and explained in the language they understand, and they, after understanding
                    and following all the clauses, have a fixed their signatures.</li>
                <li>If the plot area is less on the spot, the company will refund the amount based on the booking price.
                    If the area is more, the user will pay according to the price available on the spot.</li>
                <li>For project improvement and road access requirements, the company reserves the right to change the
                    location of the plot if necessary.</li>
                <li>
                    The file holder or registry holder who will sell his plot must transfer the file to the buyer's name
                    at the time of sale so that the buyer can take possession from the company and start construction.
                </li>
            </ol>

            <!-- Footer Section -->
            <div style="margin-top:2.4in; display:flex; justify-content:space-between;">
                <div style="width:45%; text-align:left;">

                    <p><b>Company Signature</b></p>
                </div>
                <div style="width:45%; text-align:right;">

                    <p><b>Client’s Signature</b></p>
                </div>
            </div>
        </div>
    </div>

    <div class="page2">
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
                    <th style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">Type
                    </th>
                    <th style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">
                        Period</th>
                    <th style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">Due
                        Date</th>
                    <th style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">No
                    </th>
                    <th style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">
                        P/Amount</th>
                    <th style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">Total
                    </th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($bookingPaymentSchedules as $booking)
                    <tr>
                        <td style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">
                            {{ $booking->scheduleType->title_en }}</td>
                        <td style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">
                            {{ $booking->schedulePeriod->title_en }}</td>
                        <td style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">
                            {{ \Carbon\Carbon::parse($booking->due_date)->format('d M Y') }}</td>

                        <td style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">
                            {{ $booking->number }}</td>
                        <td style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">
                            {{ $booking->pay_amount }}</td>
                        <td style="border: 1px solid #000;
            padding: 6px;
            text-align: center;">
                            {{ $booking->calculated_total_amount }}</td>
                    </tr>
                @endforeach --}}

                @php
                    $balanceAmount = 0;
                @endphp
                @foreach ($expandedSchedules as $schedule)
                    @php
                        $balanceAmount += $schedule->pay_amount;
                    @endphp
                    <tr>
                        <td>{{ $schedule->type }}</td>
                        <td>{{ $schedule->period }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->due_date)->format('d M Y') }}</td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ number_format($schedule->pay_amount) }}</td>
                        <td>{{ number_format($balanceAmount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="grand-total">
            Grand Total = {{ $grandTotal }}
        </div>

        <div class="signatures2">
            <div class="sign-box2">
                <div class="line"></div>
                Verified By
            </div>
            <div class="sign-box2">
                <div class="line"></div>
                Client
            </div>
        </div>
    </div>


</body>

</html>
