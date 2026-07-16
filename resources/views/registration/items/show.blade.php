@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i>@lang('messages.item_details')
            </h3>
            <div class="block-options">
                <a href="{{ route('itemRegistration.edit', $itemRegistration->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i>@lang('messages.edit-item')
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <!-- item Information Card -->

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
                                        {{ App::getLocale() === 'ur' ? $itemRegistration->name_ur ?? '-' : $itemRegistration->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.main-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $itemRegistration->mainHead->name_ur ?? __('messages.no_available') : $itemRegistration->mainHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.control-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $itemRegistration->controlHead->name_ur ?? __('messages.no_available') : $itemRegistration->controlHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.sub-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $itemRegistration->subHead->name_ur ?? __('messages.no_available') : $itemRegistration->subHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.sub-sub-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $itemRegistration->subSubHead->name_ur ?? __('messages.no_available') : $itemRegistration->subSubHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.sub-sub-sub-heads')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $itemRegistration->subSubSubHead->name_ur ?? __('messages.no_available') : $itemRegistration->subSubSubHead->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>


                            <hr class="my-4">

                            <div class="row mt-3">

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.unit')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $itemRegistration->measurementUnit->name_ur ?? __('messages.no_available') : $itemRegistration->measurementUnit->name_en ?? __('messages.no_available') }}
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
                        <!-- item Main item_image -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    @if ($itemRegistration->item_image)
                                        <div id="mapContainer"
                                            style="width:100%; height:350px; background:#f8f9fa;
                                                    display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                            <img id="itemMapThumb" src="{{ asset('storage/' . $itemRegistration->item_image) }}"
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
                        <img id="zoomImage" src="{{ asset('storage/' . $itemRegistration->item_image) }}" alt="Zoomed Image"
                            style="max-width:none; max-height:none; cursor: grab; user-select:none; -webkit-user-drag:none;">
                    </div>
                </div>

            </div>

            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('itemRegistration.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>

    {{-- Panzoom JS (CDN) --}}
    <script src="/js/panzoom.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const thumb = document.getElementById("itemMapThumb");
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
