@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-crv')</h2>
                </div>
                <a href="{{ route('cash-receipt-voucher.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-voucher')</a>
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

        <form method="GET" action="{{ route('cash-receipt-voucher.index') }}">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="detail_account_id">@lang('messages.detail_account')</label>
                    <select name="detail_account_id[]" id="detail_account_id"
                        class="form-control form-select select2 @error('detail_account_id') is-invalid @enderror" multiple>
                        @foreach ($coaReceivables as $coaReceivable)
                            <option value="{{ $coaReceivable->id }}"
                                {{ collect(request('detail_account_id'))->contains($coaReceivable->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $coaReceivable->name_ur ?? '-' : $coaReceivable->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="cash_account_id">@lang('messages.cash_accounts')</label>
                    <select name="cash_account_id[]" id="cash_account_id"
                        class="form-control form-select select2 @error('cash_account_id') is-invalid @enderror" multiple>
                        @foreach ($coaCashAccounts as $coaCashAccount)
                            <option value="{{ $coaCashAccount->id }}"
                                {{ collect(request('cash_account_id'))->contains($coaCashAccount->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $coaCashAccount->name_ur ?? '-' : $coaCashAccount->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-6">
                </div>
                <div class="col-md-6 mb-3" style="text-align: end">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['detail_account_id', 'cash_account_id']))
                        <a href="{{ route('cash-receipt-voucher.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>@lang('messages.Date')</th>
                            <th>@lang('messages.projects')</th>
                            <th>@lang('messages.detail_account')</th>
                            <th>@lang('messages.cash_accounts')</th>
                            <th>@lang('messages.total_amount')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashReceiptVouchers as $cashReceiptVoucher)
                            <tr>
                                <td class="text-center">CRV-{{ $cashReceiptVoucher->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($cashReceiptVoucher->date)->format('d M Y') }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $cashReceiptVoucher->project->name_ur ?? '-' : $cashReceiptVoucher->project->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $cashReceiptVoucher->detailAccount->name_ur ?? '-' : $cashReceiptVoucher->detailAccount->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $cashReceiptVoucher->cash->name_ur ?? '-' : $cashReceiptVoucher->cash->name_en ?? '-' }}
                                </td>
                                <td>{{ $cashReceiptVoucher->total_amount }}</td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        <a href="{{ route('cash-receipt-voucher.edit', $cashReceiptVoucher->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit sub Head"
                                            data-bs-original-title="Edit sub Head"> <i
                                                class="fa fa-fw fa-pencil-alt"></i></a>

                                        <form method="POST"
                                            action="{{ route('cash-receipt-voucher.destroy', $cashReceiptVoucher->id) }}"
                                            class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <i class="fa fa-fw fa-times text-danger"></i>
                                            </button>

                                        </form>
                                        <a href="{{ route('cash-receipt-voucher.show', $cashReceiptVoucher->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View cashReceiptVoucher"
                                            data-bs-original-title="View cashReceiptVoucher">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $cashReceiptVouchers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
