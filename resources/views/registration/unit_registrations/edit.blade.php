@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-unit')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('unitRegistration.update', $registeredUnit->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- PROJECT & PHASE --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="project_id">@lang('messages.projects')</label>
                        <select name="project_id" id="project"
                            class="form-control form-select @error('project_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-project')</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ (old('project_id') ?? $registeredUnit->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phase">@lang('messages.phase')</label>
                        <input type="text" class="form-control" id="phase" name="phase"
                            value="{{ old('phase', App::getLocale() === 'ur' ? $registeredUnit->project->phase_ur : $registeredUnit->project->phase_en) }}"
                            readonly>
                        @error('phase')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <input type="hidden" id="company_id" name="company_id" value="{{ $registeredUnit->company_id }}" />

                {{-- UNIT NO & NAMES --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="unit_no">@lang('messages.unit_no')</label>
                        <input type="text" class="form-control" name="unit_no"
                            value="{{ old('unit_no', $registeredUnit->unit_no) }}">
                        @error('unit_no')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unit_name_en">@lang('messages.unit_name') @lang('messages.english')</label>
                        <input type="text" class="form-control" name="unit_name_en"
                            value="{{ old('unit_name_en', $registeredUnit->unit_name_en) }}">
                        @error('unit_name_en')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="unit_name_ur">@lang('messages.unit_name') @lang('messages.urdu')</label>
                        <input type="text" class="form-control input-urdu keyboardInput" name="unit_name_ur"
                            value="{{ old('unit_name_ur', $registeredUnit->unit_name_ur) }}" placeholder="نام درج کریں">
                        @error('unit_name_ur')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="product_id">@lang('messages.products')</label>
                        <select name="product_id" id="product"
                            class="form-control form-select @error('product_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-product')</option>
                            @foreach ($productsData as $product)
                                <option value="{{ $product->id }}"
                                    {{ (old('product_id') ?? $registeredUnit->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $product->name_ur ?? '-' : $product->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- VOLUME & COVERING --}}
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.base-volume')</label>
                        <input type="text" class="form-control" name="volume" id="base_volume" readonly>
                        @error('volume')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.base-volume-units')</label>
                        <input type="text" class="form-control" id="base_volume_unit_name" readonly>
                        <input type="hidden" name="volume_unit" id="base_volume_unit_id"
                            value="{{ $registeredUnit->volume_unit }}">
                        @error('volume_unit')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.base-coverage')</label>
                        <input type="text" class="form-control" name="covering" id="base_coverage" {{-- value="{{ old('covering', $registeredUnit->covering) }}"  --}}
                            readonly>
                        @error('covering')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.base-coverage-units')</label>
                        <input type="text" class="form-control" id="base_coverage_unit_name" {{-- value="{{ App::getLocale() === 'ur' ? ($registeredUnit->coveringUnit->name_ur ?? '') : ($registeredUnit->coveringUnit->name_en ?? '') }}" --}}
                            readonly>
                        <input type="hidden" name="covering_unit" id="base_coverage_unit_id">
                        @error('covering_unit')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ROAD & FRONT --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>@lang('messages.road')</label>
                        <select name="road_id" class="form-control form-select">
                            <option value="">@lang('messages.select-road')</option>
                            @foreach ($roadCategories as $road)
                                <option value="{{ $road->id }}"
                                    {{ (old('road_id') ?? $registeredUnit->road_id) == $road->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $road->title_ur : $road->title_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('road_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>@lang('messages.front')</label>
                        {{-- <select name="front_id" class="form-control form-select">
                            <option value="">@lang('messages.front')</option>
                            @foreach ([
        1 => __('messages.que'),
        // 2 => __('messages.corner'),
        // 3 => __('messages.park_facing'),
        // 4 => __('messages.double_corner'),
        // 5 => __('messages.double_corner_park_facing'),
        // 6 => __('messages.corner_park_facing'),
        // 7 => __('messages.road_facing'),
    ] as $key => $label)
                                <option value="{{ $key }}"
                                    {{ (old('front_id') ?? $registeredUnit->front_id) == $key ? 'selected' : '' }}>
                                    @lang('messages.' . $label)
                                </option>
                            @endforeach
                        </select> --}}
                        <select name="front_id" id="front"
                            class="form-control form-select @error('front_id') is-invalid @enderror">
                            <option value="">{{ __('messages.front') }}</option>

                            <option value="1"
                                {{ old('front_id', $registeredUnit->front_id) == 1 ? 'selected' : '' }}>
                                {{ __('messages.que') }}
                            </option>
                            <option value="2"
                                {{ old('front_id', $registeredUnit->front_id) == 2 ? 'selected' : '' }}>
                                {{ __('messages.corner') }}
                            </option>
                            <option value="3"
                                {{ old('front_id', $registeredUnit->front_id) == 3 ? 'selected' : '' }}>
                                {{ __('messages.park_facing') }}
                            </option>
                            <option value="4"
                                {{ old('front_id', $registeredUnit->front_id) == 4 ? 'selected' : '' }}>
                                {{ __('messages.double_corner') }}
                            </option>
                            <option value="5"
                                {{ old('front_id', $registeredUnit->front_id) == 5 ? 'selected' : '' }}>
                                {{ __('messages.double_corner_park_facing') }}
                            </option>
                            <option value="6"
                                {{ old('front_id', $registeredUnit->front_id) == 6 ? 'selected' : '' }}>
                                {{ __('messages.corner_park_facing') }}
                            </option>
                            <option value="7"
                                {{ old('front_id', $registeredUnit->front_id) == 7 ? 'selected' : '' }}>
                                {{ __('messages.road_facing') }}
                            </option>
                        </select>

                        @error('front_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ACTUAL VOLUME & COVERING --}}
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.actual_volume')</label>
                        <input type="text" class="form-control" name="actual_volume"
                            value="{{ old('actual_volume', $registeredUnit->actual_volume) }}">
                        @error('actual_volume')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.unit')</label>
                        <input type="text" class="form-control base_volume_unit_name" {{-- value="{{ App::getLocale() === 'ur' ? ($registeredUnit->volumeUnit->name_ur ?? '') : ($registeredUnit->volumeUnit->name_en ?? '') }}" --}} readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.actual_coverage')</label>
                        <input type="text" class="form-control" name="actual_covering"
                            value="{{ old('actual_covering', $registeredUnit->actual_covering) }}">
                        @error('actual_covering')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.base-coverage-units')</label>
                        <input type="text" class="form-control base_coverage_unit_name" {{-- value="{{ App::getLocale() === 'ur' ? ($registeredUnit->coveringUnit->name_ur ?? '') : ($registeredUnit->coveringUnit->name_en ?? '') }}" --}} readonly>
                    </div>
                </div>

                {{-- MEASUREMENTS --}}
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.kanal')</label>
                        <input type="text" class="form-control" id="kanal" name="kanal"
                            value="{{ old('kanal', $registeredUnit->kanal) }}">
                        @error('kanal')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.marla')</label>
                        <input type="text" class="form-control" id="marla" name="marla"
                            value="{{ old('marla', $registeredUnit->marla) }}">
                        @error('marla')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.yard')</label>
                        <input type="text" class="form-control" id="yard" name="yard"
                            value="{{ old('yard', $registeredUnit->yard) }}">
                        @error('yard')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>@lang('messages.total_marla')</label>
                        <input type="text" class="form-control" id="total_marla" name="total_marla"
                            value="{{ old('total_marla', $registeredUnit->total_marla) }}" readonly>
                        @error('total_marla')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- STATUS --}}
                <input type="hidden" name="status"
                    value="{{ old('status', $registeredUnit->status ?? 'Unverified') }}">

                {{-- IMAGE --}}
                <div class="form-group mb-3">
                    <label for="image">@lang('messages.image')</label><br>

                    <!-- File Upload Input -->
                    <input type="file" name="image" id="image" class="form-control"
                        onchange="previewImage(this)">

                    <!-- Image Preview (Shows avatar if no image is selected) -->
                    <div id="imagePreview" class="mt-2">
                        {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                        <img id="previewImg"
                            src="{{ isset($registeredUnit) && $registeredUnit->image ? asset('storage/' . $registeredUnit->image) : asset('images/No-Image-Placeholder.svg.png') }}"
                            alt="" class="img-thumbnail" style="max-height: 200px;">
                    </div>

                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BUTTONS --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                    <a href="{{ route('unitRegistration.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.translations = {
            errorTitle: "@lang('messages.error-title')",
            loading: "@lang('messages.loading')",
        };

        var config = {
            routes: {
                getProjectInformation: "{{ route('get.project.information', ['projectId' => ':id']) }}",
                getProductInformation: "{{ route('get.product.information', ['productId' => ':id']) }}",
            }
        };
    </script>

    <script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/unitRegistration.js') }}"></script>
@endsection
