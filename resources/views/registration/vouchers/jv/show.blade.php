@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i>@lang('messages.jv_voucher_details')
            </h3>
            <div class="block-options">
                <a href="{{ route('jv-voucher.edit', $journalVoucher->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i>@lang('messages.edit-jv')
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <!-- journalVoucher Information Card -->

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
                                    <h5 class="text-muted mb-3">@lang('messages.voucher_no')</h5>
                                    <div class="p-3 bg-light rounded">
                                        JV-{{ $journalVoucher->id ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.Date')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? \Carbon\Carbon::parse($journalVoucher->voucher_date ?? __('messages.no_available'))->format('d-m-Y') ?? __('messages.no_available') : \Carbon\Carbon::parse($journalVoucher->voucher_date ?? __('messages.no_available'))->format('d-m-Y') ?? __('messages.no_available') }}

                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.debit_account')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{$journalVoucher->description}}
                                    </div>
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
                            <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.voucher_entries')
                        </h4>
                    </div>
                    <div class="card-body">
                        @forelse ($journalVoucherDetails as $journalVoucherDetail)
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.debit_account')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $journalVoucherDetail->debitAccount->name_ur ?? __('messages.no_available') : $journalVoucherDetail->debitAccount->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.credit_account')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $journalVoucherDetail->creditAccount->name_ur ?? __('messages.no_available') : $journalVoucherDetail->creditAccount->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.debit')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $journalVoucherDetail->debit ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.credit')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $journalVoucherDetail->credit ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.description_en')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $journalVoucherDetail->description_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.description_ur')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $journalVoucherDetail->description_ur ?? __('messages.no_available') }}
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
                <a href="{{ route('cash-receipt-voucher.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>

    {{-- Panzoom JS (CDN) --}}
    <script src="/js/panzoom.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const thumb = document.getElementById("cashReceiptVoucherMapThumb");
            const modal = document.getElementById("imageModal");
            const closeModal = document.getElementById("closeModal");
            const zoomImage = document.getElementById("zoomImage");
            const zoomContainer = document.getElementById("zoomContainer");
            let panzoom;

            if (thumb) {
                // Open modal on click
                thumb.addEventListener("click", () => {
                    modal.style.display = "flex";
                    // Init Panzoom only once
                    if (!panzoom) {
                        panzoom = Panzoom(zoomImage, {
                            startScale: 1,
                            minScale: 0.5,
                            maxScale: 3
                        });
                        zoomContainer.addEventListener("wheel", panzoom.zoomWithWheel);
                    }
                });
            }

            // Close modal
            closeModal.addEventListener("click", () => {
                modal.style.display = "none";
                panzoom.reset({
                    animate: false
                }); // reset zoom on close
            });

            // ESC to close
            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape") {
                    modal.style.display = "none";
                    if (panzoom) panzoom.reset({
                        animate: false
                    });
                }
            });

            // Drag cursor feedback
            zoomImage.addEventListener("mousedown", () => zoomImage.style.cursor = "grabbing");
            document.addEventListener("mouseup", () => zoomImage.style.cursor = "grab");
        });
    </script>
@endsection
