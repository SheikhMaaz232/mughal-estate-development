@extends('layouts.backend')

@section('content')
    @php
        $isUrdu = App::getLocale() === 'ur';
    @endphp
    <style>
        .fee-entry {
            background-color: #f8f9fa !important;
        }

        .ledger-section-title.ledger-balance-zero {
            color: #0f5132 !important;
            background-color: #d1e7dd;
            padding: 0.35rem 0.6rem;
            border-radius: 0.25rem;
        }

        .ledger-section-title.ledger-balance-nonzero {
            color: #842029 !important;
            background-color: #f8d7da;
            padding: 0.35rem 0.6rem;
            border-radius: 0.25rem;
        }

        .ledger-closing.ledger-balance-zero {
            color: #0f5132 !important;
            background-color: #d1e7dd !important;
        }

        .ledger-closing.ledger-balance-nonzero {
            color: #842029 !important;
            background-color: #f8d7da !important;
        }

        @media print {
            aside,
            nav,
            .bg-body-light,
            .alert,
            #printLedgerBtn {
                display: none !important;
            }
        }
    </style>

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        {{ __('messages.general_journal_ledger') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content" @if ($isUrdu) dir="rtl" style="text-align:right; font-family:'Noto Nastaliq Urdu', serif;" @endif>
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

        @if ($ledgers->isNotEmpty())
            <div class="d-flex justify-content-{{ $isUrdu ? 'start' : 'end' }} gap-2 mt-4">
                <button class="btn btn-success" id="printLedgerBtn">
                    <i class="fa fa-print"></i> {{ __('messages.print_ledger') }}
                </button>
            </div>

            <div id="ledgerPrintArea" class="card mt-3">
                <div class="card-body">
                    @if (isset($selectedParty))
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="fw-bold text-primary mb-3">
                                    {{ $isUrdu ? $selectedParty->name_ur ?? $selectedParty->name_en : $selectedParty->name_en ?? $selectedParty->name_ur }}
                                </h2>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    @foreach ($ledgers as $ledger)
                        @php
                            $isZeroBalance = round((float) ($ledger['closing_balance'] ?? 0), 2) == 0;
                            $balanceClass = $isZeroBalance ? 'ledger-balance-zero' : 'ledger-balance-nonzero';
                        @endphp
                        <div class="mb-4">
                            <h5 class="fw-bold ledger-section-title {{ $balanceClass }}">
                                {{ $isUrdu ? $ledger['account_name_ur'] ?? $ledger['account_name_en'] : $ledger['account_name_en'] }}
                                @if (!empty($ledger['party_name']))
                                    <small class="text-muted">
                                        ({{ $isUrdu ? $ledger['party_name_ur'] ?? $ledger['party_name_en'] : $ledger['party_name_en'] }})
                                    </small>
                                @endif
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('messages.Date') }}</th>
                                            <th>{{ __('messages.document_number') }}</th>
                                            <th>{{ __('messages.description') }}</th>
                                            <th class="text-end">{{ __('messages.debit') }}</th>
                                            <th class="text-end">{{ __('messages.credit') }}</th>
                                            <th class="text-end">{{ __('messages.balance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ledger['entries'] as $entry)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d-m-Y') }}</td>
                                                <td>{{ $entry['document_number'] }}</td>
                                                <td>{{ $isUrdu ? $entry['description_ur'] ?? $entry['description_en'] : $entry['description_en'] }}</td>
                                                <td class="text-end">{{ number_format($entry['debit'], 2) }}</td>
                                                <td class="text-end">{{ number_format($entry['credit'], 2) }}</td>
                                                <td class="text-end fw-semibold">{{ number_format($entry['balance'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="5" class="text-end">{{ __('messages.closing_balance') }}</th>
                                            <th class="text-end fw-bold ledger-closing {{ $balanceClass }}">{{ number_format($ledger['closing_balance'], 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (isset($request) && !$ledgers->count() && request()->hasAny(['party_id', 'detail_account_id']))
            <div class="alert alert-info mt-4">
                {{ __('messages.no_ledger_records_found') }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('printLedgerBtn')?.addEventListener('click', function () {
                window.print();
            });
        });
    </script>
@endsection
