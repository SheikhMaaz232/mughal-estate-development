@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i>@lang('messages.bpv_voucher_details')
            </h3>
            <div class="block-options">
                <a href="{{ route('bank-payment-voucher.edit', $bankPaymentVoucher->id) }}"
                    class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i>@lang('messages.edit-bpv')
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <!-- bankPaymentVoucher Information Card -->

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
                                        BPV-{{ $bankPaymentVoucher->id ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.Date')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? \Carbon\Carbon::parse($bankPaymentVoucher->date ?? __('messages.no_available'))->format('d m Y') ?? __('messages.no_available') : \Carbon\Carbon::parse($bankPaymentVoucher->date ?? __('messages.no_available'))->format('d M Y') ?? __('messages.no_available') }}

                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.projects')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $bankPaymentVoucher->project->name_ur ?? __('messages.no_available') : $bankPaymentVoucher->project->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.detail_account')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $bankPaymentVoucher->detailAccount->name_ur ?? __('messages.no_available') : $bankPaymentVoucher->detailAccount->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.banks')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $bankPaymentVoucher->bank->name_ur ?? __('messages.no_available') : $bankPaymentVoucher->bank->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.total_amount')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $bankPaymentVoucher->total_amount ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.description') @lang('messages.english')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $bankPaymentVoucher->description_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.description') @lang('messages.urdu')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $bankPaymentVoucher->description_ur ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.image')
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- bankPaymentVoucher Main Image -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    @if ($bankPaymentVoucher->attachment)
                                        <div id="mapContainer"
                                            style="width:100%; height:350px; background:#f8f9fa;
                                                    display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                            <img id="bankPaymentVoucherMapThumb"
                                                src="{{ asset('storage/' . $bankPaymentVoucher->attachment) }}"
                                                alt="@lang('messages.no_image')" class="rounded border"
                                                style="max-width:100%; max-height:100%; cursor: zoom-in; object-fit: contain;">
                                        </div>
                                    @else
                                        <div class="py-4 text-center">
                                            <i class="fa fa-building fa-4x text-muted mb-3"></i>
                                            <p class="text-muted">@lang('messages.no_image')</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div id="imageModal"
                    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
                            background:rgba(0,0,0,0.8); z-index:1050; justify-content:center; align-items:center;">
                    <span id="closeModal"
                        style="position:absolute; top:15px; right:20px; color:#fff; font-size:28px; cursor:pointer;">&times;</span>
                    <div id="zoomContainer"
                        style="width:90%; height:90%; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#000;">
                        <img id="zoomImage" src="{{ asset('storage/' . $bankPaymentVoucher->attachment) }}"
                            alt="Zoomed Image"
                            style="max-width:none; max-height:none; cursor: grab; user-select:none; -webkit-user-drag:none;">
                    </div>
                </div>

            </div>

            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('bank-payment-voucher.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>

    {{-- Panzoom JS (CDN) --}}
    <script src="/js/panzoom.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const thumb = document.getElementById("bankPaymentVoucherMapThumb");
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
