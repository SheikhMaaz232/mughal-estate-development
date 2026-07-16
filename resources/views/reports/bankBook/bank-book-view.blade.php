@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.bank_book')</h2>
                </div>

            </div>
        </div>
    </div>

    <div class="content">
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
        <form action="{{ route('bank.book.report') }}" method="get" id="form-search" target="_blank">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="from_date">@lang('messages.from_date')</label>
                    <input id="from_date" name="from_date" style="color: black; " class="form-control" type="text"
                        value="{{ @$request['from_date'] }}" placeholder="@lang('messages.from_date')">
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="to_date">@lang('messages.to_date')</label>
                    <input id="to_date" type="text" name="to_date" class="form-control"
                        value="{{ @$request['to_date'] }}" placeholder="@lang('messages.to_date')" />
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="party_id">@lang('messages.party')</label>
                    <select name="party_id" id="party_id"
                        class="form-control form-select select2 @error('party_id') is-invalid @enderror">
                        <option value="">@lang('messages.select-an-option')</option>

                        @foreach ($searchParties as $searchParty)
                            <option value="{{ $searchParty->id }}"
                                {{ collect(request('party_id'))->contains($searchParty->id) ? 'selected' : '' }}>

                                {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                                -
                                ({{ App::getLocale() === 'ur' ? 'ذات' : 'CAST' }}:
                                {{ App::getLocale() === 'ur' ? $searchParty->cast->title_ur ?? '-' : $searchParty->cast->title_en ?? '-' }})
                                ({{ App::getLocale() === 'ur' ? 'شناختی کارڈ' : 'CNIC' }}:
                                {{ $searchParty->cnic_no ?? 'N/A' }})
                                ({{ App::getLocale() === 'ur' ? 'فون' : 'Phone' }}:
                                {{ $searchParty->contact_number_1 ?? 'N/A' }})

                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-lg-6 mb-3">
                    <label for="detail_account_id">@lang('messages.detail_account')</label>
                    <select name="detail_account_id[]" id="detail_account_id"
                        class="form-control form-select select2 @error('detail_account_id') is-invalid @enderror" multiple>
                        <option value="">@lang('messages.select-an-option')</option>
                        @foreach ($detailAccounts as $detailAccount)
                            <option value="{{ $detailAccount->id }}"
                                {{ collect(request('detail_account_id'))->contains($detailAccount->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-lg-6" style="margin-top: 25px;">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['party_id', 'from_date', 'to_date', 'project_id']))
                        <a href="{{ route('partyAccount.ledger') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('from_date'); // Change to your input's actual ID

            input.addEventListener('input', function() {
                // Remove all non-digit characters
                let raw = this.value.replace(/\D/g, '');

                // Limit to 8 digits max (DDMMYYYY)
                if (raw.length > 8) raw = raw.slice(0, 8);

                // Format as DD-MM-YYYY
                if (raw.length > 4) {
                    this.value = raw.slice(0, 2) + '-' + raw.slice(2, 4) + '-' + raw.slice(4);
                } else if (raw.length > 2) {
                    this.value = raw.slice(0, 2) + '-' + raw.slice(2);
                } else {
                    this.value = raw;
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('to_date'); // Change to your input's actual ID

            input.addEventListener('input', function() {
                // Remove all non-digit characters
                let raw = this.value.replace(/\D/g, '');

                // Limit to 8 digits max (DDMMYYYY)
                if (raw.length > 8) raw = raw.slice(0, 8);

                // Format as DD-MM-YYYY
                if (raw.length > 4) {
                    this.value = raw.slice(0, 2) + '-' + raw.slice(2, 4) + '-' + raw.slice(4);
                } else if (raw.length > 2) {
                    this.value = raw.slice(0, 2) + '-' + raw.slice(2);
                } else {
                    this.value = raw;
                }
            });
        });
    </script>
@endsection
