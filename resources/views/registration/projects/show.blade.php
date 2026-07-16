@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i>@lang('messages.project_details')
            </h3>
            <div class="block-options">
                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i>@lang('messages.edit-project')
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <!-- project Information Card -->

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
                                        {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.phase')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $project->phase_ur ?? '-' : $project->phase_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.group')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $project->group->name_ur ?? __('messages.no_available') : $project->group->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.company')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $project->company->name_ur ?? __('messages.no_available') : $project->company->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.marla_in_square_feet')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $project->square_feet ?? __('messages.no_available') : $project->square_feet ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.address')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $project->address_ur ?? __('messages.no_available') : $project->address_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5 class="text-muted mb-3">@lang('messages.description')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $project->description_ur ?? __('messages.no_available') : $project->description_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- project Information Card -->

                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.detail-of-area-project')
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.roads_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->roads_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.park_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->park_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.mosque_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->mosque_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.cemetery_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->cemetery_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.social_waste_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->social_waste_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.disposable_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->disposal_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.commercial_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->commercial_plots_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.residential_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->residential_plots_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.public_buildings_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->public_buildings_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="text-muted mb-3">@lang('messages.miscellaneous_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->miscellaneous_area ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.total_area')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->total_area ?? __('messages.no_available') }}
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
                            <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.map_image')
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Project Main Image -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    @if ($project->project_map)
                                        <div id="mapContainer"
                                            style="width:100%; height:350px; background:#f8f9fa;
                                                    display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                            <img id="projectMapThumb"
                                                src="{{ asset('storage/' . $project->project_map) }}"
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
                        <img id="zoomImage" src="{{ asset('storage/' . $project->project_map) }}" alt="Zoomed Image"
                            style="max-width:none; max-height:none; cursor: grab; user-select:none; -webkit-user-drag:none;">
                    </div>
                </div>

            </div>

            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('projects.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>

    {{-- Panzoom JS (CDN) --}}
    <script src="/js/panzoom.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const thumb = document.getElementById("projectMapThumb");
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
