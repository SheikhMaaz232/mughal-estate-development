@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-unit')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('unitRegistration.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="example-select">Select Option</label>
                    <select class="form-control select2" id="example-select" name="example-select">
                        <option value="">-- Select an option --</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                </div>
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="project_id">@lang('messages.projects')</label>
                        <select name="project_id" id="project"
                            class="form-control form-select @error('project_id') is-invalid @enderror ">
                            <option value="">@lang('messages.select-project')</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phase">@lang('messages.phase') </label>
                        <input type="text" class="form-control" id="phase" name="phase" value="{{ old('phase') }}"
                            placeholder="@lang('messages.phase')" autocomplete="off" readonly>

                        @error('phase')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" id="company_id" name="company_id" />

                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="unit_no">@lang('messages.unit_no')</label>
                        <input type="text" class="form-control" id="unit_no" name="unit_no"
                            value="{{ old('unit_no') }}" placeholder="@lang('messages.enter-unit-no')" autocomplete="off">
                        @error('unit_no')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unit_name_en">@lang('messages.unit_name') @lang('messages.english')</label>
                        <input type="text" class="form-control" id="unit_name_en" name="unit_name_en"
                            value="{{ old('unit_name_en') }}" placeholder="@lang('messages.enter-unit-name')" autocomplete="off">
                        @error('unit_name_en')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ur">@lang('messages.unit_name') @lang('messages.urdu')</label>
                        <input type="text" class="form-control input-urdu keyboardInput" id="unit_name_ur"
                            name="unit_name_ur" value="{{ old('unit_name_ur') }}" placeholder="نام درج کریں"
                            autocomplete="off">
                        @error('unit_name_ur')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="product_id">@lang('messages.products')</label>
                        <select name="product_id" id="product"
                            class="form-control form-select @error('product_id') is-invalid @enderror ">
                            <option value="">@lang('messages.select-product')</option>
                            @foreach ($productsData as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $product->name_ur ?? '-' : $product->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="base_volume">@lang('messages.base-volume')</label>
                        <input type="text" class="form-control" id="base_volume" name="volume"
                            value="{{ old('volume') }}" placeholder="@lang('messages.enter-base-volume')" autocomplete="off" readonly>
                        @error('volume')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="base_volume_unit_name">@lang('messages.base-volume-units')</label>
                        <input type="text" class="form-control" id="base_volume_unit_name" readonly>
                        <input type="hidden" id="base_volume_unit_id" name="volume_unit">
                        @error('volume_unit')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="base_coverage">@lang('messages.base-coverage')</label>
                        <input type="text" class="form-control" id="base_coverage" name="covering"
                            value="{{ old('covering') }}" placeholder="@lang('messages.enter-base-coverage')" autocomplete="off" readonly>
                        @error('covering')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="base_coverage_unit_name">@lang('messages.base-coverage-units')</label>
                        <input type="text" class="form-control" id="base_coverage_unit_name" readonly>
                        <input type="hidden" id="base_coverage_unit_id" name="covering_unit">
                        @error('covering_unit')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="unit">@lang('messages.road')</label>
                        <select name="road_id" id="road_id"
                            class="form-control form-select @error('road_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-road')</option>
                            @foreach ($roadCategories as $road)
                                <option value="{{ $road->id }}" {{ old('road_id') == $road->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $road->title_ur ?? '-' : $road->title_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('road_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="front">@lang('messages.front')</label>
                        <select name="front_id" id="front"
                            class="form-control form-select @error('road_id') is-invalid @enderror">
                            <option value="">@lang('messages.front')</option>
                            <option value="1">@lang('messages.que')</option>
                            <option value="2">@lang('messages.corner')</option>
                            <option value="3">@lang('messages.park_facing')</option>
                            <option value="4">@lang('messages.double_corner')</option>
                            <option value="5">@lang('messages.double_corner_park_facing')</option>
                            <option value="6">@lang('messages.corner_park_facing')</option>
                            <option value="7">@lang('messages.road_facing')</option>
                        </select>
                        @error('front_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="actual_volume">@lang('messages.actual_volume')</label>
                        <input type="text" class="form-control" id="actual_volume" name="actual_volume"
                            value="{{ old('actual_volume') }}" placeholder="@lang('messages.enter-actual-volume')" autocomplete="off">
                        @error('base_coverage')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="base_volume_unit_name">@lang('messages.unit')</label>
                        <input type="text" class="form-control base_volume_unit_name" id="base_volume_unit_name"
                            readonly>
                        @error('base_volume_unit')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="actual_coverage">@lang('messages.actual_coverage')</label>
                        <input type="text" class="form-control" id="actual_coverage" name="actual_covering"
                            value="{{ old('actual_covering') }}" placeholder="@lang('messages.enter-actual-covering')" autocomplete="off">
                        @error('actual_covering')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="base_coverage_unit_name">@lang('messages.base-coverage-units')</label>
                        <input type="text" class="form-control base_coverage_unit_name" id="base_coverage_unit_name"
                            readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="knal">@lang('messages.kanal')</label>
                        <input type="text" class="form-control" id="kanal" name="kanal"
                            value="{{ old('kanal') }}" placeholder="@lang('messages.enter-kanal')" autocomplete="off">
                        @error('kanal')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="marla">@lang('messages.marla') </label>
                        <input type="text" class="form-control" id="marla" name="marla"
                            value="{{ old('marla') }}" placeholder="@lang('messages.enter-marla')" autocomplete="off">
                        @error('marla')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="yard">@lang('messages.yard') </label>
                        <input type="text" class="form-control" id="yard" name="yard"
                            value="{{ old('yard') }}" placeholder="@lang('messages.enter-yard')" autocomplete="off">
                        @error('yard')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="total_marla">@lang('messages.total_marla') </label>
                        <input type="text" class="form-control" id="yard" name="total_marla"
                            value="{{ old('total_marla') }}" placeholder="@lang('messages.total_marla')" autocomplete="off"
                            readonly>
                        @error('total_marla')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <select name="status" id="status"
                    class="form-control form-select @error('status') is-invalid @enderror" hidden>
                    <option value="Unverified" {{ old('status') == 'Unverified' ? 'selected' : '' }}>Unverified
                    </option>
                </select>

                <div class="form-group mb-3">
                    <label for="image">@lang('messages.image')</label><br>

                    <!-- File Upload Input -->
                    <input type="file" name="image" id="image" class="form-control"
                        onchange="previewImage(this)">

                    <!-- Image Preview (Shows avatar if no image is selected) -->
                    <div id="imagePreview" class="mt-2">
                        {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                        <img id="previewImg"
                            src="{{ isset($unitRegistration) && $unitRegistration->image ? asset('storage/' . $unitRegistration->image) : asset('images/No-Image-Placeholder.svg.png') }}"
                            alt="" class="img-thumbnail" style="max-height: 200px;">
                    </div>

                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
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

    {{-- <script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/unitRegistration.js') }}"></script> --}}
@endsection
