@extends('layouts.backend')

@section('content')
    @php
        $isUrdu = App::getLocale() === 'ur';
    @endphp
    <style>
        /* Fee entries styling */
        .fee-entry {
            background-color: #f8f9fa !important;
            transition: all 0.3s ease;
        }

        .fee-entry.hidden {
            display: none !important;
        }

        /* Make sure any row with hidden class is hidden */
        tr.hidden {
            display: none !important;
        }

        .fee-entry td {
            font-size: 0.9rem;
        }

        @media print {

            /* Hide unnecessary elements */
            aside,
            nav,
            .bg-body-light,
            .alert,
            #printLedgerBtn,
            #toggleFeesBtn {
                display: none !important;
            }

            body {
                margin: 0 !important;
                background: #fff !important;
            }

            .content,
            .container,
            .container-fluid {
                width: 100% !important;
                max-width: 100% !important;
            }

            /* Bootstrap grid fix */
            .row {
                display: flex !important;
                flex-wrap: nowrap !important;
            }

            .col-md-8 {
                flex: 0 0 66.666667% !important;
                max-width: 66.666667% !important;
            }

            .col-md-4 {
                flex: 0 0 33.333333% !important;
                max-width: 33.333333% !important;
            }

            img {
                max-width: 100% !important;
                height: auto !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            th,
            td {
                border: 1px solid #000 !important;
            }

            /* Keep border for ledger tables */
            .card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
            }

            /* ✅ Remove border only for Party Info section */
            .party-info-section {
                border: none !important;
                box-shadow: none !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        {{ __('messages.party_ledger') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content"
        @if ($isUrdu) dir="rtl" style="text-align:right; font-family:'Noto Nastaliq Urdu', serif;" @endif>

        {{-- ✅ Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (!empty($ledger['entries']) && count($ledger['entries']))
            <div class="d-flex justify-content-{{ $isUrdu ? 'start' : 'end' }} gap-2 mt-4">
                <button class="btn btn-info" id="toggleFeesBtn">
                    <i class="fa fa-eye"></i>
                    {{ __('messages.hide_fees') }}
                </button>

                <button class="btn btn-success" id="printLedgerBtn">
                    <i class="fa fa-print"></i>
                    {{ __('messages.print_ledger') }}
                </button>
            </div>

            <div id="ledgerPrintArea" class="card mt-3">

                {{-- Party Information --}}
                @if (isset($selectedParty))
                    <div class="card-body border-bottom">

                        <div class="row">

                            <div class="col-md-8">

                                <h3 class="text-primary fw-bold">
                                    {{ $isUrdu
                                        ? $selectedParty->name_ur ?? $selectedParty->name_en
                                        : $selectedParty->name_en ?? $selectedParty->name_ur }}
                                </h3>

                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>{{ __('messages.father_name') }}</th>
                                        <td>
                                            {{ $isUrdu
                                                ? $selectedParty->father_name_ur ?? $selectedParty->father_name_en
                                                : $selectedParty->father_name_en ?? $selectedParty->father_name_ur }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>{{ __('messages.cnic_no') }}</th>
                                        <td>{{ $selectedParty->cnic_no }}</td>
                                    </tr>

                                    <tr>
                                        <th>{{ __('messages.contact_no') }}</th>
                                        <td>{{ $selectedParty->contact_number_1 }}</td>
                                    </tr>

                                    <tr>
                                        <th>{{ __('messages.address') }}</th>
                                        <td>
                                            {{ $isUrdu
                                                ? $selectedParty->home_address_ur ?? $selectedParty->home_address_en
                                                : $selectedParty->home_address_en ?? $selectedParty->home_address_ur }}
                                        </td>
                                    </tr>
                                </table>

                            </div>

                            <div class="col-md-4 text-center">

                                @php
                                    $imgPath = $selectedParty->profile_image
                                        ? asset('storage/' . $selectedParty->profile_image)
                                        : asset('images/no_image.png');
                                @endphp

                                <img src="{{ $imgPath }}" class="img-thumbnail"
                                    style="width:180px;height:180px;object-fit:cover">

                            </div>

                        </div>

                    </div>
                @endif

                {{-- Ledger Table --}}
                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped">

                            <thead class="table-light">

                                <tr>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.document_number') }}</th>
                                    <th>{{ __('messages.description') }}</th>
                                    <th class="text-end">{{ __('messages.debit') }}</th>
                                    <th class="text-end">{{ __('messages.credit') }}</th>
                                    <th class="text-end">{{ __('messages.balance') }}</th>
                                </tr>

                            </thead>

                            <tbody>
                                @if (request()->filled('from_date'))
                                    <tr class="table-warning">
                                        <td>
                                            {{ request('from_date') }}
                                        </td>
                                        <td>-</td>
                                        <td>
                                            {{ __('messages.opening_balance') }}
                                        </td>
                                        <td class="text-end">-</td>
                                        <td class="text-end">-</td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($ledger['opening_balance'], 2) }}
                                        </td>
                                    </tr>
                                @endif

                                @foreach ($ledger['entries'] as $entry)
                                    @php
                                        $isFeeEntry = $entry['is_fee_entry'] ?? 0;
                                    @endphp

                                    <tr class="{{ $isFeeEntry ? 'fee-entry bg-light' : '' }}"
                                        data-is-fee="{{ $isFeeEntry ? 'true' : 'false' }}"
                                        data-debit="{{ $entry['debit'] }}" data-credit="{{ $entry['credit'] }}">

                                        <td>
                                            {{ \Carbon\Carbon::parse($entry['date'])->format('d-m-Y') }}
                                        </td>

                                        <td>
                                            {{ $entry['document_number'] }}
                                        </td>

                                        <td>
                                            {{ $isUrdu ? $entry['description_ur'] ?? $entry['description_en'] : $entry['description_en'] }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($entry['debit'], 2) }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($entry['credit'], 2) }}
                                        </td>

                                        <td class="text-end balance-cell">
                                            {{ number_format($entry['balance'], 2) }}
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                            <tfoot class="table-light">

                                <tr>

                                    <th colspan="3" class="text-end">
                                        {{ __('messages.totals') }}
                                    </th>

                                    <th class="text-end total-debit">
                                        {{ number_format($ledger['total_debit'], 2) }}
                                    </th>

                                    <th class="text-end total-credit">
                                        {{ number_format($ledger['total_credit'], 2) }}
                                    </th>

                                    <th class="text-end total-balance">
                                        {{ number_format($ledger['closing_balance'], 2) }}
                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

                {{-- Summary --}}
                <div class="card-footer">

                    <div class="row">

                        <div class="col-md-4">

                            <div class="alert alert-success mb-0">

                                <h6>{{ __('messages.total_debit') }}</h6>

                                <span id="overallDebit">
                                    {{ number_format($ledger['total_debit'], 2) }}
                                </span>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="alert alert-danger mb-0">

                                <h6>{{ __('messages.total_credit') }}</h6>

                                <span id="overallCredit">
                                    {{ number_format($ledger['total_credit'], 2) }}
                                </span>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="alert alert-primary mb-0">

                                <h6>{{ __('messages.closing_balance') }}</h6>

                                <span id="overallBalance">
                                    {{ number_format($ledger['closing_balance'], 2) }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        @endif


        @if (isset($request) &&
                (empty($ledger['entries']) || count($ledger['entries']) == 0) &&
                request()->hasAny(['party_id', 'detail_account_id']))
            <div class="alert alert-info mt-4">
                {{ __('messages.no_ledger_records_found') }}
            </div>
        @endif

    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Store original balances on page load
            storeOriginalBalances();

            // Print button
            document.getElementById('printLedgerBtn')
                ?.addEventListener('click', function() {
                    window.print();
                });

            // Toggle fees button
            const toggleBtn = document.getElementById('toggleFeesBtn');
            let feesHidden = false;

            // Store original balances on load
            function storeOriginalBalances() {
                document.querySelectorAll('.card-body .table tbody tr').forEach(row => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 6) {
                        const originalBalance = cells[5].textContent.trim().replace(/,/g, '');
                        row.setAttribute('data-original-balance', originalBalance);
                    }
                });
            }

            if (toggleBtn) {
                // Get all fee entries
                function getAllFeeEntries() {
                    const feeRows = document.querySelectorAll('tr[data-is-fee="true"]');
                    console.log('Found fee entries:', feeRows.length);
                    feeRows.forEach((row, index) => {
                        console.log(`Fee entry ${index}:`, row.getAttribute('data-is-fee'), row.classList);
                    });
                    return feeRows;
                }

                toggleBtn.addEventListener('click', function() {
                    feesHidden = !feesHidden;
                    const feeEntries = getAllFeeEntries();

                    console.log('Toggle clicked. Fees hidden:', feesHidden, 'Total fee entries:', feeEntries
                        .length);

                    feeEntries.forEach(entry => {
                        if (feesHidden) {
                            entry.classList.add('hidden');
                            console.log('Hidden row:', entry);
                        } else {
                            entry.classList.remove('hidden');
                            console.log('Showed row:', entry);
                        }
                    });

                    // Recalculate totals based on visible entries
                    recalculateTotals();

                    // Update button text and icon
                    if (feesHidden) {
                        toggleBtn.innerHTML =
                            '<i class="fa fa-eye-slash"></i> {{ __('messages.show_fees') ?? 'Show Fees' }}';
                        toggleBtn.classList.remove('btn-info');
                        toggleBtn.classList.add('btn-warning');
                    } else {
                        toggleBtn.innerHTML =
                            '<i class="fa fa-eye"></i> {{ __('messages.hide_fees') ?? 'Hide Fees' }}';
                        toggleBtn.classList.remove('btn-warning');
                        toggleBtn.classList.add('btn-info');
                    }

                    // Save state to localStorage (session-based)
                    localStorage.setItem('ledger_fees_hidden', feesHidden);
                    console.log('Ledger state saved to localStorage');
                });

                // Restore state from localStorage
                const savedState = localStorage.getItem('ledger_fees_hidden');
                console.log('Saved state from localStorage:', savedState);
                if (savedState === 'true') {
                    // Auto-hide fees if previously hidden
                    const feeEntries = getAllFeeEntries();
                    console.log('Restoring hidden state with', feeEntries.length, 'fee entries');
                    feeEntries.forEach(entry => {
                        entry.classList.add('hidden');
                    });
                    feesHidden = true;
                    toggleBtn.innerHTML =
                        '<i class="fa fa-eye-slash"></i> {{ __('messages.show_fees') ?? 'Show Fees' }}';
                    toggleBtn.classList.remove('btn-info');
                    toggleBtn.classList.add('btn-warning');

                    // Recalculate totals based on hidden fees
                    recalculateTotals();
                }

                // Show total number of fee entries
                const totalFees = getAllFeeEntries().length;
                if (totalFees > 0) {
                    console.log('✅ Ledger filter ready. Total fee entries:', totalFees);
                } else {
                    console.warn('⚠️ No fee entries found. Check if data-is-fee="true" is present on rows');
                }

                // Recalculate totals based on visible entries


                function recalculateTotals() {

                    let overallDebit = 0;
                    let overallCredit = 0;
                    let overallBalance = 0;

                    document.querySelectorAll('.table-responsive table').forEach((table, index) => {

                        const rows = table.querySelectorAll('tbody tr');

                        let ledgerDebit = 0;
                        let ledgerCredit = 0;
                        let balance = 0;

                        rows.forEach(row => {

                            if (row.classList.contains('hidden')) {
                                return;
                            }

                            const debit = parseFloat(row.dataset.debit || 0);
                            const credit = parseFloat(row.dataset.credit || 0);

                            ledgerDebit += debit;
                            ledgerCredit += credit;

                            balance += debit - credit;
                        });

                        const closingCell = table.querySelector('.ledger-closing');

                        if (closingCell) {
                            closingCell.textContent =
                                balance.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                        }

                        overallDebit += ledgerDebit;
                        overallCredit += ledgerCredit;
                        overallBalance += balance;

                        const summaryRows =
                            document.querySelectorAll('.card-footer tbody tr:not(.table-light)');

                        if (summaryRows[index]) {

                            const cells = summaryRows[index].querySelectorAll('td');

                            cells[1].textContent = ledgerDebit.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            cells[2].textContent = ledgerCredit.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            cells[3].textContent = balance.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    });

                    const overallRow =
                        document.querySelector('.card-footer tbody tr.table-light');

                    if (overallRow) {

                        const cells = overallRow.querySelectorAll('td');

                        cells[1].textContent = overallDebit.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        cells[2].textContent = overallCredit.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        cells[3].textContent = overallBalance.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                }
            }
        });
    </script>
@endsection
