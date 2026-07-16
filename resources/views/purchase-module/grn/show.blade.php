@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i>@lang('messages.grn')
            </h3>
            <div class="block-options">
                <a href="{{ route('grn.edit', $goodsReceivedNoteMaster->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i>@lang('messages.edit-grn')
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
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.purchase_order_no')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ 'PO - ' . $goodsReceivedNoteMaster->id ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.Date')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ \Carbon\Carbon::parse($goodsReceivedNoteMaster->date)->format('d M Y') }}
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.projects')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $goodsReceivedNoteMaster->project->name_ur ?? __('messages.no_available') : $goodsReceivedNoteMaster->project->name_en ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.party')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $goodsReceivedNoteMaster->party->name_ur ?? __('messages.no_available') : $goodsReceivedNoteMaster->party->name_en ?? __('messages.no_available') }}({{ $goodsReceivedNoteMaster->party->cnic_no }})
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.detail_account')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $goodsReceivedNoteMaster->detailAccount->name_ur ?? __('messages.no_available') : $goodsReceivedNoteMaster->detailAccount->name_en ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.supplier-bill-no')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $goodsReceivedNoteMaster->supplier_bill_no ?? __('messages.no_available') }}
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.driver_name')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $goodsReceivedNoteMaster->driver_name ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.fare')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $goodsReceivedNoteMaster->fare ?? __('messages.no_available') }}
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.unloaded_by')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $goodsReceivedNoteMaster->unloaded_by ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.status')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{-- {{ $goodsReceivedNoteMaster->status ?? __('messages.no_available') }} --}}
                                    @if ($goodsReceivedNoteMaster->status === 'Unverified')
                                        @lang('messages.unverified')
                                    @elseif ($goodsReceivedNoteMaster->status === 'Verified')
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
                                    {{ $goodsReceivedNoteMaster->remarks ?? __('messages.no_available') }}
                                </div>
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
                        @forelse ($goodsReceivedNoteDetails as $goodsReceivedNoteDetail)
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.items')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $goodsReceivedNoteDetail->product->name_ur ?? __('messages.no_available') : $goodsReceivedNoteDetail->product->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.unit')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $goodsReceivedNoteDetail->product->measurementUnit->name_ur ?? __('messages.no_available') : $goodsReceivedNoteDetail->product->measurementUnit->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <h5 class="text-muted mb-3">@lang('messages.po_quantity')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $goodsReceivedNoteDetail->po_quantity ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="text-muted mb-3">@lang('messages.received_quantity')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $goodsReceivedNoteDetail->received_qty ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="text-muted mb-3">@lang('messages.balance')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $goodsReceivedNoteDetail->balance ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5 class="text-muted mb-3">@lang('messages.remarks')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $goodsReceivedNoteDetail->detail_remarks ?? __('messages.no_available') }}
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
                <a href="{{ route('grn.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>
@endsection
