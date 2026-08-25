<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ur' ? 'rtl' : 'ltr' }}">

<head>

    <meta charset="utf-8">

    <title>
        {{ app()->getLocale() == 'ur'
            ? 'فعال بکنگ اور وصول شدہ ادائیگی رپورٹ'
            : 'Active Booking & Received Payment Report' }}
    </title>


    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        h3 {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        thead {
            background: #34495e;
            color: white;
        }

        .project-header {
            background: #dfe6e9;
            padding: 10px;
            margin-top: 20px;
            font-weight: bold;
        }

        .total-row {
            background: #ecf0f1;
            font-weight: bold;
        }
    </style>

</head>


<body>


    @php

        $isUrdu = app()->getLocale() == 'ur';

    @endphp


    <h2>

        {{ $isUrdu ? 'فعال بکنگ اور وصول شدہ ادائیگی رپورٹ' : 'Active Booking & Received Payment Report' }}

    </h2>


    {{-- ============================================================
     PROJECTS
============================================================= --}}

    @foreach ($groupedBookings as $projectId => $projectBookings)
        @php

            $firstBooking = $projectBookings->first();

            $projectName = $firstBooking->project
                ? ($isUrdu
                    ? $firstBooking->project->name_ur
                    : $firstBooking->project->name_en)
                : 'N/A';

            $projectBookingAmount = $projectBookings->sum('total_amount');

            $projectReceivedAmount = $projectBookings->sum(function ($booking) use ($receivedAmountByAccount) {
                return $receivedAmountByAccount[$booking->detail_account_id] ?? 0;
            });

            $projectRemainingAmount = $projectBookingAmount - $projectReceivedAmount;

        @endphp


        {{-- ============================================================
         PROJECT HEADER
    ============================================================= --}}

        <div class="project-header">

            <strong>
                {{ $projectName }}
            </strong>

        </div>


        {{-- ============================================================
         BOOKING TABLE
    ============================================================= --}}

        <table>

            <thead>

                <tr>

                    <th>
                        {{ $isUrdu ? 'نمبر شمار' : 'S.No' }}
                    </th>


                    <th>
                        {{ $isUrdu ? 'بکنگ نمبر' : 'Booking No.' }}
                    </th>


                    <th>
                        {{ $isUrdu ? 'پروڈکٹ / یونٹ' : 'Product / Unit' }}
                    </th>


                    <th>
                        {{ $isUrdu ? 'پارٹی' : 'Party' }}
                    </th>


                    <th>
                        {{ $isUrdu ? 'تاریخ' : 'Date' }}
                    </th>


                    <th>
                        {{ $isUrdu ? 'بکنگ رقم' : 'Booking Amount' }}
                    </th>


                    <th>
                        {{ $isUrdu ? 'وصول شدہ رقم' : 'Received Amount' }}
                    </th>


                    <th>
                        {{ $isUrdu ? 'باقی رقم' : 'Remaining Amount' }}
                    </th>

                </tr>

            </thead>


            <tbody>


                @foreach ($projectBookings as $index => $booking)
                    @php

                        /*
                |--------------------------------------------------------------------------
                | PRODUCT NAME
                |--------------------------------------------------------------------------
                */

                        $productName = 'N/A';

                        if ($booking->product) {
                            $productName = $isUrdu ? $booking->product->name_ur : $booking->product->name_en;
                        }

                        /*
                |--------------------------------------------------------------------------
                | UNIT NUMBER
                |--------------------------------------------------------------------------
                */

                        $unitNo = $booking->product->unit_no ?? 'N/A';

                        /*
                |--------------------------------------------------------------------------
                | PARTY NAME
                |--------------------------------------------------------------------------
                */

                        $partyName = 'N/A';

                        if ($booking->party) {
                            $partyName = $isUrdu
                                ? $booking->party->name_ur ??
                                    ($booking->party->name_en ?? ($booking->party->name ?? 'N/A'))
                                : $booking->party->name_en ??
                                    ($booking->party->name_ur ?? ($booking->party->name ?? 'N/A'));

                            $partyCast = $isUrdu
                                ? $booking->party->cast->title_ur ??
                                    ($booking->party->cast->title_en ?? ($booking->party->cast->name ?? 'N/A'))
                                : $booking->party->cast->title_en ??
                                    ($booking->party->cast->title_ur ?? ($booking->party->cast->name ?? 'N/A'));

                            $partyCnic = $booking->party->cnic_no ?? 'N/A';
                            $partyContact = $booking->party->contact_number_1 ?? 'N/A';
                        }

                        /*
                |--------------------------------------------------------------------------
                | RECEIVED AMOUNT
                |--------------------------------------------------------------------------
                */

                        $receivedAmount = $receivedAmountByAccount[$booking->detail_account_id] ?? 0;

                        /*
                |--------------------------------------------------------------------------
                | REMAINING AMOUNT
                |--------------------------------------------------------------------------
                */

                        $remainingAmount = (float) $booking->total_amount - (float) $receivedAmount;
                    @endphp


                    <tr>


                        {{-- S.NO --}}

                        <td>
                            {{ $index + 1 }}
                        </td>


                        {{-- BOOKING NO --}}

                        <td>

                            {{ \App\Models\BookingApplication::bookingNo($booking->id) }}

                        </td>


                        {{-- PRODUCT / UNIT --}}

                        <td>

                            {{ $productName }}

                            @if ($unitNo)
                                <br>
                                <small>
                                    ({{ $unitNo }})
                                </small>
                            @endif

                        </td>


                        {{-- PARTY --}}

                        <td>
                            {{ $partyName }} - {{ $partyCast }}<br> ({{ $partyCnic }}) {{$partyContact}}
                        </td>


                        {{-- DATE --}}

                        <td>

                            {{ \Carbon\Carbon::parse($booking->date)->format('d-m-Y') }}

                        </td>

                        {{-- BOOKING AMOUNT --}}

                        <td>

                            {{ number_format((float) $booking->total_amount, 2) }}

                        </td>


                        {{-- RECEIVED AMOUNT --}}

                        <td>

                            {{ number_format((float) $receivedAmount, 2) }}

                        </td>


                        {{-- REMAINING AMOUNT --}}

                        <td>

                            {{ number_format((float) $remainingAmount, 2) }}

                        </td>


                    </tr>
                @endforeach


                {{-- ========================================================
             PROJECT TOTAL
        ========================================================= --}}

                <tr class="total-row">


                    <td colspan="5">

                        {{ $isUrdu ? 'پروجیکٹ کل' : 'Project Total' }}

                    </td>


                    <td>

                        {{ number_format($projectBookingAmount, 2) }}

                    </td>


                    <td>

                        {{ number_format($projectReceivedAmount, 2) }}

                    </td>


                    <td>

                        {{ number_format($projectRemainingAmount, 2) }}

                    </td>


                </tr>


            </tbody>

        </table>
    @endforeach



    {{-- ================================================================
     GRAND SUMMARY
================================================================= --}}

    <h3>

        {{ $isUrdu ? 'کل خلاصہ' : 'Grand Summary' }}

    </h3>


    <table>

        <thead>

            <tr>

                <th>
                    {{ $isUrdu ? 'کل بکنگ' : 'Total Bookings' }}
                </th>


                <th>
                    {{ $isUrdu ? 'کل بکنگ رقم' : 'Total Booking Amount' }}
                </th>


                <th>
                    {{ $isUrdu ? 'کل وصول شدہ رقم' : 'Total Received Amount' }}
                </th>


                <th>
                    {{ $isUrdu ? 'کل باقی رقم' : 'Total Remaining Amount' }}
                </th>

            </tr>

        </thead>


        <tbody>

            <tr class="total-row">


                <td>

                    {{ $grandTotals['bookings'] }}

                </td>


                <td>

                    {{ number_format($grandTotals['booking_amount'], 2) }}

                </td>


                <td>

                    {{ number_format($grandTotals['received_amount'], 2) }}

                </td>


                <td>

                    {{ number_format($grandTotals['remaining_amount'], 2) }}

                </td>


            </tr>

        </tbody>

    </table>


</body>

</html>
