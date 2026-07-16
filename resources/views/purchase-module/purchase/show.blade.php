@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i>@lang('messages.purchase-invoice')
            </h3>
            <div class="block-options">
                <a href="{{ route('purchase-invoice.edit', $purchaseMaster->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i>@lang('messages.edit-purchase-invoice')
                </a>
            </div>
        </div>
        <div class="block-content">

            <!-- Description & Address Card -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.basic-information')
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="text-muted mb-3">@lang('messages.purchase_invoice_no')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $purchaseMaster->id ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-muted mb-3">@lang('messages.grn_no')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $purchaseMaster->grn_no ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-muted mb-3">@lang('messages.purchase_order_no')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $purchaseMaster->purchase_order_no ?? __('messages.no_available') }}
                                </div>
                            </div>


                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.Date')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ \Carbon\Carbon::parse($purchaseMaster->date)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.projects')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $purchaseMaster->project->name_ur ?? __('messages.no_available') : $purchaseMaster->project->name_en ?? __('messages.no_available') }}
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.party')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $purchaseMaster->party->name_ur ?? __('messages.no_available') : $purchaseMaster->party->name_en ?? __('messages.no_available') }}({{ $purchaseMaster->party->cnic_no }})
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.detail_account')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $purchaseMaster->detailAccount->name_ur ?? __('messages.no_available') : $purchaseMaster->detailAccount->name_en ?? __('messages.no_available') }}
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.supplier-bill-no')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $purchaseMaster->supplier_bill_no ?? __('messages.no_available') }}
                                </div>
                            </div>


                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.fare')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $purchaseMaster->carriage ?? __('messages.no_available') }}
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.unloaded_by')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $purchaseMaster->unloaded_by ?? __('messages.no_available') }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.status')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{-- {{ $purchaseMaster->status ?? __('messages.no_available') }} --}}
                                    @if ($purchaseMaster->status === 'Unverified')
                                        @lang('messages.unverified')
                                    @elseif ($purchaseMaster->status === 'Verified')
                                        @lang('messages.verified')
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5 class="text-muted mb-3">@lang('messages.remarks')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $purchaseMaster->remarks ?? __('messages.no_available') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.product_details')
                            </h4>
                        </div>
                        <div class="card-body">
                            @forelse ($purchaseInvoiceDetails as $purchaseInvoiceDetail)
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-muted mb-3">@lang('messages.items')</h5>
                                        <div class="p-3 bg-light rounded">
                                            {{ App::getLocale() === 'ur' ? $purchaseInvoiceDetail->product->name_ur ?? __('messages.no_available') : $purchaseInvoiceDetail->product->name_en ?? __('messages.no_available') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="text-muted mb-3">@lang('messages.unit')</h5>
                                        <div class="p-3 bg-light rounded">
                                            {{ App::getLocale() === 'ur' ? $purchaseInvoiceDetail->product->measurementUnit->name_ur ?? __('messages.no_available') : $purchaseInvoiceDetail->product->measurementUnit->name_en ?? __('messages.no_available') }}
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <h5 class="text-muted mb-3">@lang('messages.quantity')</h5>
                                        <div class="p-3 bg-light rounded">
                                            {{ $purchaseInvoiceDetail->quantity ?? __('messages.no_available') }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="text-muted mb-3">@lang('messages.price')</h5>
                                        <div class="p-3 bg-light rounded">
                                            {{ $purchaseInvoiceDetail->price ?? __('messages.no_available') }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="text-muted mb-3">@lang('messages.amount')</h5>
                                        <div class="p-3 bg-light rounded">
                                            {{ $purchaseInvoiceDetail->amount ?? __('messages.no_available') }}
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5 class="text-muted mb-3">@lang('messages.remarks')</h5>
                                        <div class="p-3 bg-light rounded">
                                            {{ $purchaseInvoiceDetail->detail_remarks ?? __('messages.no_available') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center mt-3 mb-3">
                                    <hr class="my-4"
                                        style="width:50%; height:8px; background-color:#000000; border:none; border-radius:4px;">
                                </div>

                            @empty
                                <p class="text-muted">@lang('messages.no_available')</p>
                            @endforelse
                        </div>

                    </div>
                </div>

                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('purchase-invoice.index') }}" class="btn btn-alt-secondary">
                        <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                    </a>
                </div>
            </div>
        </div>
    @endsection
