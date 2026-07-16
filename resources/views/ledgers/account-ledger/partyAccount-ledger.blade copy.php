<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: black;
            background-color: white;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            /* A4 width in portrait */
            margin: 0 auto;
            padding: 20px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
                /* Set base font size for printing */
            }

            .container {
                width: 100%;
                max-width: 210mm;
                /* A4 width */
                height: auto;
                /* Allow content to flow naturally */
                page-break-after: auto;
            }

            /* Ensure portrait mode */
            @page {
                size: A4 portrait;
                /* Explicitly set portrait mode */
                margin: 10mm;
                /* Adjust margin if needed */
            }

            /* Adjust specific font sizes for better readability */
            h1 {
                font-size: 18px;
            }

            h2 {
                font-size: 16px;
            }

            h3 {
                font-size: 14px;
            }

            p,
            td,
            th {
                font-size: 12px;
            }
        }

        .header,
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            width: 100px;
            height: 100px;
        }

        .header div,
        .footer div {
            text-align: left;
        }

        .header div,
        {
            margin-bottom: 20px;
        }

        .header div p,
        .footer div p {
            margin: 0;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
        }

        .info,
        .table-container,
        .notes,
        .signatures,
        .contact,
        .totals {
            margin-bottom: 10px;
        }

        .info div,
        .signatures div {
            display: flex;
            justify-content: space-between;
        }

        .info div p,
        .signatures div p {
            margin: 0;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .notes {
            border: 1px solid black;
            padding: 10px;
            margin-right: 10px;
        }

        .notes div {
            height: 50px;
        }

        .signatures div {
            height: 30px;
        }

        .contact p {
            text-align: center;
            margin: 0;
        }

        .totals {
            width: 30%;
            text-align: left;
            border-collapse: collapse;
            margin-right: 5px;
        }

        .totals table {
            float: left;
        }

        .p-4 {
            padding: 0px !important;
        }

        .totals th,
        .totals td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .totals th {
            font-weight: bold;
            width: 50%;
        }

        .header {
            text-align: center;
            /* padding: 5px; */
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .line {
            border-top: 2px solid black;
            width: 100%;
            margin: 0 auto;
        }

        .line+.line {
            margin-top: 2px;
        }
    </style>
</head>

<body>


    <?php
    if (!function_exists('calculateStockBalance')) {
        function calculateStockBalance($openingBalance, $entries)
        {
            $balance = (float) $openingBalance;

            foreach ($entries as $key => $entry) {
                // For the first entry, set the balance as debit or credit
                if ($key === 0) {
                    if (!empty($entry->debit)) {
                        $balance = (float) $entry->debit; // Set balance to debit for the first entry
                    } elseif (!empty($entry->credit)) {
                        $balance = -(float) $entry->credit; // Set balance to negative credit for the first entry
                    }
                } else {
                    // Handle credit and debit values for subsequent entries
                    if (!empty($entry->credit)) {
                        $balance -= (float) $entry->credit; // Decrease balance by credit amount
                    }

                    if (!empty($entry->debit)) {
                        $balance += (float) $entry->debit; // Increase balance by debit amount
                    }
                }

                // Format values for readability
                $entries[$key]->Balance = number_format($balance, 2);
            }

            return $entries;
        }
    }

    $entries = $accountLedgers;
    $openingBalance = 0;
    // $result = calculateStockBalance($openingBalance, $entries);

    ?>

    <div class="container">
        <div class="header flex justify-between items-center p-4 border-b border-black">
            <div class="flex items-center">
                <img alt="Amir Foods logo with text 'Since 1996' and 'AMIR Food' in a shield-like shape" class="h-16"
                    height="80" src="{{ asset('images/logo.png') }}" width="80" />
                <div class="ml-4">
                    <p class="font-bold text-lg">AAMIR BROTHERS FOOD PRODUCTS MULTAN</p>
                    <p>12KM Vehari Road Multan <span class="font-bold">CELL:</span> 0309 6662476</p>
                    <p><span class="font-bold">EMAIL:</span> info.amirfoods@gmail.com</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-xl">@lang('messages.party_ledger')</p>
            </div>
        </div>

        {{-- <header class="header"> --}}
        <div class="line"></div>
        {{-- </header> --}}

        <div class="info" style="margin-top: 1%;">
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>Account Title:</b> <span>
                            {{ $party->account_name ?? '-' }}
                        </span></p>
                </div>
                <div style="width: 30%; text-align: left;">

                    <p><b>Opening Balance:</b> <span>
                            {{ $partyDetailAccount->opening_balance ?? 0 }}
                        </span></p>
                </div>
            </div>

            <div class="row" style="margin-bottom: 3px;">

                <div style="width: 70%; text-align: left;">
                    <p><b>Level 4:</b> <span>
                            {{-- {{ $subSubHead }} --}}
                        </span></p>
                    {{-- --}}

                </div>
                <div style="width: 30%; text-align: right;">
                    <p><b>Credit Limit:</b> <span>
                            {{-- {{ $partyDetailAccount->credit_limit ?? '' }} --}}
                        </span></p>
                </div>

            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>Level 03:</b> <span>
                            {{-- {{ $subHead }} --}}
                        </span></p>

                </div>
                <div style="width: 30%; text-align: right;">
                    <p><b>Credit Days:</b> <span>
                            {{-- {{ $partyDetailAccount->credit_days ?? '' }} --}}
                        </span></p>

                </div>
            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>Level 02:</b> <span>
                            {{-- {{ $controlHead }} --}}
                        </span></p>

                </div>
                <div style="width: 30%; text-align: right;">

                    <p><b>Belt:</b> <span>
                            {{-- {{ !empty($sectors) ? $sectors->implode(', ') : '' }} --}}
                        </span></p>
                </div>
            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">

                    <p><b>Level 01:</b> <span>
                            {{-- {{ $mainHead }} --}}
                        </span></p>
                </div>
                <div style="width: 30%; text-align: right;">

                    <p><b>Area:</b> <span>
                            {{-- {{ !empty($areas) ? $areas->implode(', ') : '' }} --}}
                        </span></p>
                </div>

            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">

                    <p><b>Sale Man:</b> <span>
                            {{-- {{ $saleMan }} --}}
                        </span></p>
                </div>
                <div style="width: 30%; text-align: right;">

                    <p><b>Email:</b> <span>
                            {{-- {{ $partyDetailAccount->email ?? '' }} --}}
                        </span></p>
                </div>

            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">

                    <p><b>WhatsApp #:</b> <span>
                            {{-- {{ $partyDetailAccount->contact_no_2 ?? '' }} --}}
                        </span></p>
                </div>

            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>

                    <tr>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 7%;">Date</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 5%;">Doc#</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 15%;">
                            Narration/Description</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 8%;">Debit</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 8%;">Credit</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 8%;">Ballance</th>

                    </tr>

                </thead>
                <tbody>

                    {{-- <tr>
                        <td></td>
                        <td>OPENING BALANCE</td>
                        <td></td>
                        <td></td>
                        <td style="text-align: end;">0</td>
                        <td style="text-align: end;">0</td>
                        <td style="text-align: end;"></td>
                        <td style="text-align: end;">{{ $partyDetailAccount->opening_balance ?? 0 }}</td>
                    <td style="text-align: end;">0</td>
                    <td style="text-align: end;">{{ $partyDetailAccount->opening_balance ?? 0 }}</td>
                    <td
                        style="text-align: center; padding: 0px 0px 0px 0px !important; width: 3%; border-color: white; border-right: black;">
                    </td>
                    <td style="text-align: end;"></td>
                    <td style="text-align: end;"></td>
                    </tr> --}}

                    {{-- @foreach ($result as $accountLedger)
                        <tr>

                            <td>{{ \Carbon\Carbon::parse($accountLedger->date)->format('d-m-Y') }}</td>
                    <td>{{ $accountLedger->description }}</td>
                    <td>{{ $accountLedger->document_number }}</td>
                    <td>{{ $accountLedger->bags }}</td>
                    <td style="text-align: end;">{{ $accountLedger->measurementType }}</td>
                    <td style="text-align: end;">{{ $accountLedger->total_quantity }}</td>
                    <td style="text-align: end;">{{ $accountLedger->rate }}</td>
                    <td style="text-align: end;">{{ $accountLedger->debit }}</td>
                    <td style="text-align: end;">{{ $accountLedger->credit }}</td>
                    <td style="text-align: end;">{{ $accountLedger->Balance }}</td>
                    <td
                        style="text-align: center; padding: 0px 0px 0px 0px !important; width: 3%; border-color: white; border-right: black;">
                    </td>
                    <td style="text-align: start;">
                        {{ $transporters[$accountLedger->transporter_id ?? ''] ?? '-' }}
                    </td>
                    <td style="text-align: center;">{{ $accountLedger->bilty_no ?? '-' }}</td>

                    </tr>
                    @endforeach --}}
                </tbody>
            </table>

        </div>
        <header class="header">
            <div class="line"></div>
        </header>

        <div class="signatures flex justify-between items-center mt-4">
            {{-- <div class="flex items-center">
                <p class="text-black mr-2"><b>Created By:</b></p>
                <div class="w-32 h-4 border-b-2 border-black" style="margin-left: 5px;"></div>
            </div> --}}
            <div class="flex items-center">
                <p class="text-black mr-2"><b>Signed & Approved By:</b></p>
                <div class="w-32 h-4 border-b-2 border-black"></div>
            </div>
        </div>

        <div class="contact mt-4">
            <p>If you have any questions about this Document, Please contact</p>
            <p><b>Phone:</b> 0309 6662476 <b>Email:</b> info.amirfoods@gmail.com</p>
        </div>

    </div>
</body>

</html>
