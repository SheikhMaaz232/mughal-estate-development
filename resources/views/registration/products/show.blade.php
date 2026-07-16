@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i>@lang('messages.product_details')
            </h3>
            <div class="block-options">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i>@lang('messages.edit-products')
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <!-- product Information Card -->

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
                                    <h5 class="text-muted mb-3">@lang('messages.name')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->name_ur ?? '-' : $product->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.unit_no')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->unit_no ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.company')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->company->name_ur ?? __('messages.no_available') : $product->company->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.projects')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->project->name_ur ?? __('messages.no_available') : $product->project->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.main-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->mainHead->name_ur ?? __('messages.no_available') : $product->mainHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.control-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->controlHead->name_ur ?? __('messages.no_available') : $product->controlHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.sub-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->subHead->name_ur ?? __('messages.no_available') : $product->subHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.sub-sub-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->subSubHead->name_ur ?? __('messages.no_available') : $product->subSubHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>


                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.sub-sub-sub-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->subSubSubHead->name_ur ?? __('messages.no_available') : $product->subSubSubHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.road')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->road->title_ur ?? __('messages.no_available') : $product->road->title_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.front')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $product->facing->name_ur ?? __('messages.no_available') : $product->facing->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.amount_in_pkr')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->amount_in_pkr ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.product-type')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->type ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.total_amount')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->total_amount ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <!-- product Information Card -->

                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.additional_information')
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.kanal')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->kanal ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.marla')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->marla ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.square_feet')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->square_feet ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.total_square_feet')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->total_square_feet ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.total_marla')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->total_marla ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.front_width')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->front_width ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.length')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->length ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.front_width')(2)</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->front_width2 ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.length')(2)</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->length2 ?? __('messages.no_available') }}
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <h5 class="text-muted mb-3">@lang('messages.terms')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $product->termsAndConditions ?? __('messages.no_available') }}
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
                        <!-- product Main Image -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    @if ($product->image)
                                        <div id="mapContainer"
                                            style="width:100%; height:350px; background:#f8f9fa;
                                                    display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                            <img id="productMapThumb" src="{{ asset('storage/' . $product->image) }}"
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
                        <img id="zoomImage" src="{{ asset('storage/' . $product->image) }}" alt="Zoomed Image"
                            style="max-width:none; max-height:none; cursor: grab; user-select:none; -webkit-user-drag:none;">
                    </div>
                </div>

            </div>

            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('products.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>

    {{-- Panzoom JS (CDN) --}}
    <script src="/js/panzoom.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const thumb = document.getElementById("productMapThumb");
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
