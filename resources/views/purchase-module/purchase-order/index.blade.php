@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-purchase-orders')</h2>
                </div>
                <a href="{{ route('purchase-order.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-purchase-order')</a>
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

        <form method="GET" action="{{ route('purchase-order.index') }}">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">@lang('messages.Date')</label>

                    <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="search">@lang('messages.purchase_order_no')</label>
                    <input type="number" class="form-control" name="purchase_order_no" placeholder="@lang('messages.purchase_order_no')"
                        step="any" onwheel="this.blur()" value="{{ request('purchase_order_no') }}">
                </div>
            </div>

            <div class="row">

                <div class="col-lg-6 mb-3">
                    <label for="party_id">@lang('messages.party')</label>
                    <select name="party_id[]" id="party_id"
                        class="form-control form-select select2 @error('party_id') is-invalid @enderror" multiple>
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
                <div class="col-lg-6 mb-3">
                    <label for="detail_account_id">@lang('messages.detail_account')</label>
                    <select name="detail_account_id[]" id="detail_account_id"
                        class="form-control form-select select2 @error('detail_account_id') is-invalid @enderror" multiple>
                        @foreach ($detailAccounts as $detailAccount)
                            <option value="{{ $detailAccount->id }}"
                                {{ collect(request('detail_account_id'))->contains($detailAccount->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['search', 'party_id', 'occupation_id', 'sub_head_id', 'sub_sub_head_id']))
                        <a href="{{ route('purchase-order.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">@lang('messages.purchase_order_no')</th>
                            <th>@lang('messages.Date')</th>
                            <th>@lang('messages.party')</th>
                            <th>@lang('messages.detail_account')</th>
                            <th>@lang('messages.contact_person')</th>
                            <th>@lang('messages.total_amount')</th>
                            <th>@lang('messages.status')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrderListing as $purchaseOrder)
                            <tr>
                                <td class="text-center">@lang('messages.PO'){{ ' - ' . $purchaseOrder->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($purchaseOrder->date)->format('d M Y') }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $purchaseOrder->party->name_ur ?? '-' : $purchaseOrder->party->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $purchaseOrder->detailAccount->name_ur ?? '-' : $purchaseOrder->detailAccount->name_en ?? '-' }}
                                </td>
                                <td>{{ $purchaseOrder->contact_person }}</td>
                                <td>{{ $purchaseOrder->total_amount }}</td>
                                <td>
                                    @if ($purchaseOrder->status === 'Unverified')
                                        @lang('messages.unverified')
                                    @elseif ($purchaseOrder->status === 'Verified')
                                        @lang('messages.verified')
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-left">
                                    <div class="btn-group">
                                        @if ($purchaseOrder->status === 'Unverified')
                                            <form method="POST"
                                                action="{{ route('purchase-order.updateStatus', $purchaseOrder->id) }}"
                                                class="d-inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Verified">
                                                <button type="button"
                                                    class="btn btn-sm btn-success js-bs-tooltip-enabled btn-verify"
                                                    data-bs-toggle="modal" data-bs-target="#confirmVerifyModal"
                                                    aria-label="Verify booking" data-bs-original-title="Verify booking">
                                                    <i class="fa fa-fw fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('purchase-order.edit', $purchaseOrder->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit Purchase Order"
                                            data-bs-original-title="Edit Purchase Order"> <i
                                                class="fa fa-fw fa-pencil-alt"></i></a>

                                        @if ($purchaseOrder->status === 'Unverified')
                                            <form method="POST"
                                                action="{{ route('purchase-order.destroy', $purchaseOrder->id) }}"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                    <i class="fa fa-fw fa-times text-danger"></i>
                                                </button>

                                            </form>
                                        @endif
                                        <a href="{{ route('purchase-order.show', $purchaseOrder->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View purchaseOrder"
                                            data-bs-original-title="View purchaseOrder">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                    </div>

                                    @if ($purchaseOrder->status === 'Verified')
                                        <div class="mt-2">
                                            <form action="{{ route('grn.create', ['id' => $purchaseOrder->id]) }}"
                                                method="GET">
                                                <input type="hidden" name="purchase_order_id"
                                                    value="{{ $purchaseOrder->id }}">
                                                <button type="submit"
                                                    class="action-btn btn-edit btn btn-sm btn-primary bs-tooltip me-2"
                                                    data-toggle="tooltip" data-placement="top" title="Enter GRN Info">
                                                    <i class="fa-solid fa-truck"></i>
                                                    @lang('messages.createGRN')
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="modal fade" id="confirmVerifyModal" tabindex="-1" aria-labelledby="confirmVerifyLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="confirmVerifyLabel">@lang('messages.confirm_verification')</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="@lang('messages.close')"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p>@lang('messages.verify_confirmation_text_purchase_order')</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-alt-secondary"
                                    data-bs-dismiss="modal">@lang('messages.cancel')</button>
                                <button type="button" class="btn btn-success"
                                    id="confirmVerifyBtn">@lang('messages.yes_verify')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $purchaseOrderListing->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentVerifyForm;

        // When verify button clicked
        document.querySelectorAll('.btn-verify').forEach(button => {
            button.addEventListener('click', function() {
                currentVerifyForm = this.closest('form'); // store current form
            });
        });

        document.getElementById('confirmVerifyBtn').addEventListener('click', function() {
            if (currentVerifyForm) {
                this.disabled = true; // prevent double-click
                this.innerText = '{{ __('messages.processing') }}';
                currentVerifyForm.submit();
            }
        });
    </script>
@endsection
