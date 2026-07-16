@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-brv')</h2>
                </div>
                <a href="{{ route('bank-receipt-voucher.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-voucher')</a>
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

        <form method="GET" action="{{ route('bank-receipt-voucher.index') }}">
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
                    <label for="bank_id">@lang('messages.banks')</label>
                    <select name="bank_id[]" id="bank_id"
                        class="form-control form-select select2 @error('bank_id') is-invalid @enderror" multiple>
                        @foreach ($coaBanks as $coaBank)
                            <option value="{{ $coaBank->id }}"
                                {{ collect(request('bank_id'))->contains($coaBank->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $coaBank->name_ur ?? '-' : $coaBank->name_en ?? '-' }}
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

                    @if (request()->hasAny(['detail_account_id', 'bank_id']))
                        <a href="{{ route('bank-receipt-voucher.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
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
                            <th>@lang('messages.banks')</th>
                            <th>@lang('messages.total_amount')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bankReceiptVouchers as $bankReceiptVoucher)
                            <tr>
                                <td class="text-center">BRV-{{ $bankReceiptVoucher->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($bankReceiptVoucher->date)->format('d M Y') }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $bankReceiptVoucher->project->name_ur ?? '-' : $bankReceiptVoucher->project->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $bankReceiptVoucher->detailAccount->name_ur ?? '-' : $bankReceiptVoucher->detailAccount->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $bankReceiptVoucher->bank->name_ur ?? '-' : $bankReceiptVoucher->bank->name_en ?? '-' }}
                                </td>
                                <td>{{ $bankReceiptVoucher->total_amount }}</td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        <a href="{{ route('bank-receipt-voucher.edit', $bankReceiptVoucher->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit sub Head"
                                            data-bs-original-title="Edit sub Head"> <i
                                                class="fa fa-fw fa-pencil-alt"></i></a>

                                        <form method="POST"
                                            action="{{ route('bank-receipt-voucher.destroy', $bankReceiptVoucher->id) }}"
                                            class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <i class="fa fa-fw fa-times text-danger"></i>
                                            </button>

                                        </form>
                                        <a href="{{ route('bank-receipt-voucher.show', $bankReceiptVoucher->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View bankReceiptVoucher"
                                            data-bs-original-title="View bankReceiptVoucher">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>

                                        <a href="{{ route('bank-receipt-voucher.print', $bankReceiptVoucher->id) }}"
                                            class="btn btn-sm btn-alt-success" target="_blank" title="Print">
                                            <i class="fa fa-fw fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $bankReceiptVouchers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
