@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-jv')</h2>
                </div>
                <a href="{{ route('jv-voucher.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-voucher')</a>
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

        <form method="GET" action="{{ route('jv-voucher.index') }}">
            <div class="row mb-4">

                <div class="col-md-6">
                    <label class="form-label">@lang('messages.voucher_no')</label>
                    <input type="text" name="voucher_no" class="form-control" value="{{ request('voucher_no') }}"
                        placeholder="@lang('messages.voucher_no')">
                </div>

                <div class="col-md-6">
                    <label class="form-label">@lang('messages.debit_account')</label>
                    <select name="debit_account_id" class="form-select select2">
                        <option value="">@lang('messages.debit_account')</option>

                        @foreach ($detailAccounts as $account)
                            <option value="{{ $account->id }}"
                                {{ request('debit_account_id') == $account->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="row mb-4">

                <div class="col-md-6">
                    <label class="form-label">@lang('messages.credit_account')</label>
                    <select name="credit_account_id" class="form-select select2 ">
                        <option value="">@lang('messages.credit_account')</option>

                        @foreach ($detailAccounts as $account)
                            <option value="{{ $account->id }}"
                                {{ request('credit_account_id') == $account->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        @lang('messages.search')
                    </button>

                    <a href="{{ route('jv-voucher.index') }}" class="btn btn-secondary">
                        @lang('messages.clear')
                    </a>
                </div>

            </div>
        </form>

        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>@lang('messages.voucher_no')</th>
                            <th>@lang('messages.Date')</th>
                            <th>@lang('messages.total_amount')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($journalVouchers as $journalVoucher)
                            <tr>
                                <td class="text-center">{{ $journalVoucher->id }}</td>
                                <td class="text-center">{{ $journalVoucher->voucher_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($journalVoucher->voucher_date)->format('d-m-Y') }}</td>
                                <td>{{ $journalVoucher->total_debit }}</td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        <a href="{{ route('jv-voucher.edit', $journalVoucher->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit sub Head"
                                            data-bs-original-title="Edit sub Head"> <i
                                                class="fa fa-fw fa-pencil-alt"></i></a>

                                        <form method="POST"
                                            action="{{ route('jv-voucher.destroy', $journalVoucher->id) }}"
                                            class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <i class="fa fa-fw fa-times text-danger"></i>
                                            </button>

                                        </form>
                                        <a href="{{ route('jv-voucher.show', $journalVoucher->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View journalVoucher"
                                            data-bs-original-title="View journalVoucher">
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
                    {{ $journalVouchers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
