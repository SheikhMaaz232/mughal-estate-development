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
            margin-top: 1in;
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
    <a href="{{ route('possession-letter.index') }}" class="print-btn" style="background: black !important;">
        Back
    </a>
    <div class="page">

        <!-- Title -->
        <div class="title" style="font-size:20pt; font-weight:bold; text-align:center; margin-bottom:25px;">
            Possession Letter
        </div>

        <!-- File Info -->
        <div class="row">
            <div>
                File No:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $possessionLetter->file_no ?? '' }}
                </span>
            </div>

            <div>
                Security Note No:
                <span class="value" style="min-width:190px;"></span>
            </div>

            <div>
                Date:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $possessionLetter->date ? \Carbon\Carbon::parse($possessionLetter->date)->format('d-m-Y') : '' }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-top: 25px !important;">
            <div>Project Name
                <span class="value" style="min-width:430px; text-align:center;">
                    {{ $possessionLetter->project->name_en ?? '' }}
                </span>
            </div>

            <div>
                Phase:
                <span class="value"
                    style="min-width:120px; text-align:center;">{{ $possessionLetter->project->phase_en ?? '' }}</span>
            </div>
        </div>

        <div>Project Address:
            <span class="value" style="min-width:589px; text-align:center;">
                {{ $possessionLetter->project->name_en ?? '' }}
            </span>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div>Unit No:
                <span class="value" style="min-width:160px; text-align:center;">
                    {{ $possessionLetter->product->unit_no ?? '' }}
                </span>
            </div>

            <div>
                Area in Marla:
                <span class="value" style="min-width:140px; text-align:center;">
                    {{ $possessionLetter->product->total_marla ?? '' }}
                </span>
            </div>
            <div>
                Area in Feet:
                <span class="value" style="min-width:120px; text-align:center;">
                    {{ $possessionLetter->product->total_square_feet ?? '' }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div>Name:
                <span class="value" style="min-width:430px; text-align:center;">
                    {{ $possessionLetter->party->name_en ?? '' }}
                </span>
            </div>

            <div>
                CNIC#:
                <span class="value" style="min-width:160px; text-align:center;">
                    {{ $possessionLetter->party->cnic_no ?? '' }}
                </span>
            </div>
        </div>

        <div class="section">


            <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">

                <!-- LEFT COLUMN -->
                <div style="width: 63%; font-size: 10.5pt; line-height: 1.35;">
                    <div class="section-title" style="margin-bottom: 5px;">Demarcation / Site Plan</div>

                    <p style="margin: 0 0 8px 0;">
                        1. It is Certified that possession of the above plot has been handed over / taken over as under:
                    </p>

                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
                        <tr>
                            <td>East Side:<span class="value" style="display:inline-block; width:135px; text-align:center;">
                                    {{ $possessionLetter->east_side ?? '' }}</span></td>
                            <td>Bounded by:<span class="value"
                                    style="display:inline-block; width:134px; text-align:center;">{{ $possessionLetter->east_bounded_by ?? '' }}</span>
                            </td>
                        </tr>

                        <tr>
                            <td>West Side:<span class="value"
                                    style=" width:135px; text-align:center;">{{ $possessionLetter->west_side ?? '' }}</span>
                            </td>
                            {{-- <td></td> --}}
                            <td>Bounded by:<span class="value"
                                    style="display:inline-block; width:134px; text-align:center;">{{ $possessionLetter->west_bounded_by ?? '' }}</span>
                            </td>
                        </tr>

                        <tr>
                            <td>South Side:<span class="value"
                                    style="display:inline-block; width:135px; text-align:center;">{{ $possessionLetter->south_side ?? '' }}</span>
                            </td>
                            <td>Bounded by:<span class="value"
                                    style="display:inline-block; width:134px; text-align:center;">{{ $possessionLetter->south_bounded_by ?? '' }}</span>
                            </td>
                        </tr>

                        <tr>
                            <td>North Side:<span class="value"
                                    style="display:inline-block; width:135px; text-align:center;">{{ $possessionLetter->north_side ?? '' }}</span>
                            </td>
                            <td>Bounded by:<span class="value"
                                    style="display:inline-block; width:134px; text-align:center;">{{ $possessionLetter->north_bounded_by ?? '' }}</span>
                            </td>
                        </tr>
                    </table>

                    <p style="margin: 0 0 6px 0;">
                        2. Total(Standard) Area:
                         K<span class="value"
                            style=" width:5% !important; text-align:center;">{{ $possessionLetter->kanal ?? '' }}</span>
                        M<span class="value"
                            style="width:5%; text-align:center;">{{ $possessionLetter->marla ?? '' }}</span><br>
                        SF<span class="value"
                            style="width:5%; text-align:center;">{{ $possessionLetter->square_feet ?? '' }}</span>
                    </p>

                    <p style="margin: 0 0 6px 0;">3. I, the allottee of the plot, hereby undertake that:</p>
                    <p style="margin: 0 0 6px 0;">1. I shall not indulge in any unauthorized encroachments and violations of the Bye-Laws of
                    Authorities.</p>
                </div>

                <!-- RIGHT COLUMN (Compass + Grid) -->
                <div style="width: 33%; display: flex; flex-direction: column; align-items: center;">

                    <!-- Compass -->
                    <div style="text-align:center; font-size:10.5pt; margin-bottom: 5px; margin-top: 18px;">
                        <div style="font-weight:bold;">N</div>
                        <div style="display:flex; justify-content:space-between; width:85px;">
                            <span style="font-weight:bold;">W</span>
                            <span style="font-weight:bold;">E</span>
                        </div>
                        <div style="font-weight:bold;">S</div>
                    </div>

                    <!-- Grid Box -->
                    <div
                        style="
                width: 240px;
                height: 245px;
                border: 1px solid #000;
                margin-top: 5px;
                display: grid;
                grid-template-columns: repeat(12, 1fr);
                grid-template-rows: repeat(12, 1fr);
            ">
                        @for ($i = 0; $i < 144; $i++)
                            <div style="border: 1px solid #00000030;"></div>
                        @endfor
                    </div>

                </div>
            </div>
        </div>
        <div class="section">

            <ol start="2" style="margin-top:0; padding-left: 18px; font-size: 10pt;">
                <li style="margin-bottom:4px;">

                    The original site will be restored in the event of any defaults besides penal actions
                    imposed by
                    Authority Bye-Laws.
                </li>

                <li style="margin-bottom:4px;">
                    I shall complete the construction of the Boundary wall conforming to the above
                    Demarcation/Site plan
                    within a period of two (02) month’s from the date of taking over possession and prior to
                    undertaking
                    the construction of the main building.
                </li>

                <li style="margin-bottom:4px;">
                    If the boundary wall is not constructed within the stipulated period of two months, then the
                    letter
                    shall be considered automatically cancelled on expiry of the prescribed period and the
                    second possession
                    shall be obtained by paying the letter fee before construction.
                </li>

                <li>
                    I will maintain the Porch Level 12” & Floor Level 18” above Road Level.
                </li>
            </ol>

            <p style="margin-top: 30px; margin-top:20px;">Special Note:<span class="value"
                    style="display:inline-block; width:613px;">{{ $possessionLetter->special_note ?? '' }}</span></p>


        </div>
        <!-- Signatures -->
        <div class="signatures2">
            <div>Site Engineer Signature</div>
            <div>Customer Signature</div>
            <div>Company Signature</div>
        </div>

    </div>
</body>


</html>
