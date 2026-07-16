@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-products')</h3>
        </div>
        <div class="block-content block-content-full">

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="project_id">@lang('messages.projects')</label>
                        <select name="project_id" id="project"
                            class="form-control select2 form-select @error('project_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-project')</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ (old('project_id') ?? $product->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="main_head_id">@lang('messages.main-heads')</label>
                        <select name="main_head_id" id="main-head"
                            class="form-control select2 form-select @error('main_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-main-heads')</option>
                            @foreach ($mainHeads as $mainHead)
                                <option value="{{ $mainHead->id }}"
                                    {{ isset($product) && $product->main_head_id == $mainHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $mainHead->name_ur ?? '-' : $mainHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('main_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="control_head_id">@lang('messages.control-heads')</label>
                        <select name="control_head_id" id="control-head"
                            class="form-control select2 form-select @error('control_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-control-heads')</option>

                            @foreach ($controlHeads as $controlHead)
                                <option value="{{ $controlHead->id }}"
                                    {{ isset($product) && $product->control_head_id == $controlHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $controlHead->name_ur ?? '-' : $controlHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('control_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="sub_head_id">@lang('messages.sub-heads')</label>
                        <select name="sub_head_id" id="sub-head"
                            class="form-control select2 form-select @error('sub_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-sub-heads')</option>
                            @foreach ($subHeads as $subHead)
                                <option value="{{ $subHead->id }}"
                                    {{ isset($product) && $product->sub_head_id == $subHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $subHead->name_ur ?? '-' : $subHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="sub_sub_head_id">@lang('messages.sub-sub-heads')</label>
                        <select name="sub_sub_head_id" id="sub-sub-head"
                            class="form-control select2 form-select @error('sub_sub_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-sub-sub-heads')</option>
                            @foreach ($subSubHeads as $subSubHead)
                                <option value="{{ $subSubHead->id }}"
                                    {{ isset($product) && $product->sub_sub_head_id == $subSubHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $subSubHead->name_ur ?? '-' : $subSubHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('sub_sub_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="sub_sub_sub_head_id">@lang('messages.sub-sub-sub-heads')</label>
                        <select name="sub_sub_sub_head_id" id="sub-sub-sub-head"
                            class="form-control select2 form-select @error('sub_sub_sub_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-sub-sub-sub-heads')</option>
                            @foreach ($subSubSubHeads as $subSubSubHead)
                                <option value="{{ $subSubSubHead->id }}"
                                    {{ isset($product) && $product->sub_sub_sub_head_id == $subSubSubHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $subSubSubHead->name_ur ?? '-' : $subSubSubHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('sub_sub_sub_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="block">@lang('messages.block')</label>
                        <input type="text" class="form-control" id="block" name="block"
                            value="{{ old('block', $product->block) }}" placeholder="@lang('messages.block')"
                            autocomplete="off">
                        @error('block')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="unit_no">@lang('messages.unit_no')</label>
                        <input type="text" class="form-control" id="unit_no" name="unit_no"
                            value="{{ old('unit_no', $product->unit_no) }}" placeholder="@lang('messages.enter-unit-no')"
                            autocomplete="off">
                        @error('unit_no')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" value="{{ old('company_id', $product->company_id) }}" id="company_id"
                        name="company_id" />
                    @error('company_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                    <div class="col-md-6 mb-3">
                        <label for="name_en">@lang('messages.name') @lang('messages.english')</label>
                        <input type="text" class="form-control" id="name_en" name="name_en"
                            value="{{ old('name_en', $product->name_en) }}" placeholder="@lang('messages.enter-name-english')"
                            autocomplete="off">
                        @error('name_en')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name_ur">@lang('messages.name') @lang('messages.urdu')</label>
                        <input type="text" class="form-control input-urdu keyboardInput" id="name_ur"
                            name="name_ur" value="{{ old('name_ur', $product->name_ur) }}" placeholder="نام درج کریں"
                            autocomplete="off">
                        @error('name_ur')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount_in_pkr">@lang('messages.amount_in_pkr')</label>
                        <input type="number" class="form-control" id="amount_in_pkr" name="amount_in_pkr"
                            value="{{ old('amount_in_pkr', $product->amount_in_pkr) }}" placeholder="@lang('messages.amount_in_pkr')"
                            autocomplete="off">
                        @error('amount_in_pkr')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">
                        <label>@lang('messages.road')</label>
                        <select name="road_id" class="form-control select2 form-select">
                            <option value="">@lang('messages.select-road')</option>
                            @foreach ($roadCategories as $road)
                                <option value="{{ $road->id }}"
                                    {{ (old('road_id') ?? $product->road_id) == $road->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $road->title_ur : $road->title_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('road_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>@lang('messages.front')</label>
                        <select name="front_id" id="front_id"
                            class="form-control select2 form-select @error('front_id') is-invalid @enderror">
                            @foreach ($facings as $facing)
                                <option value="{{ $facing->id }}"
                                    {{ (old('front_id') ?? $product->front_id) == $facing->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $facing->name_ur : $facing->name_en }}
                                </option>
                            @endforeach
                        </select>

                        @error('front_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="product_type">@lang('messages.product-type')</label>
                        <select name="type" id="product_type"
                            class="form-control select2 form-select @error('type') is-invalid @enderror">
                            <option value="">@lang('messages.select-product-type')</option>
                            <option value="Direct" {{ old('type', $product->type ?? '') == 'Direct' ? 'selected' : '' }}>
                                @lang('messages.direct')
                            </option>
                            <option value="Indirect"
                                {{ old('type', $product->type ?? '') == 'Indirect' ? 'selected' : '' }}>
                                @lang('messages.indirect')
                            </option>
                        </select>

                        @error('type')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="knal">@lang('messages.kanal')</label>
                        <input type="text" class="form-control" id="kanal" name="kanal"
                            value="{{ old('kanal', $product->kanal) }}" placeholder="@lang('messages.enter-kanal')"
                            autocomplete="off">
                        @error('kanal')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="marla">@lang('messages.marla') </label>
                        <input type="number" class="form-control" id="marla" name="marla"
                            value="{{ old('marla', $product->marla) }}" placeholder="@lang('messages.enter-marla')"
                            autocomplete="off">
                        @error('marla')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="square_feet">@lang('messages.square_feet') </label>
                        <input type="number" class="form-control" id="square_feet" name="square_feet"
                            value="{{ old('square_feet', $product->square_feet) }}" placeholder="@lang('messages.square_feet')"
                            autocomplete="off">
                        @error('square_feet')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="total_marla">@lang('messages.total_marla') </label>
                        <input type="text" class="form-control" id="total_marla" name="total_marla"
                            value="{{ old('total_marla', $product->total_marla) }}" placeholder="@lang('messages.total_marla')"
                            autocomplete="off" readonly>
                        @error('total_marla')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="total_square_feet">@lang('messages.total_square_feet') </label>
                        <input type="number" class="form-control" id="total_square_feet" name="total_square_feet"
                            step="any" min="0" onwheel="this.blur()"
                            value="{{ old('total_marla', $product->total_square_feet) }}"
                            placeholder="@lang('messages.total_square_feet')" autocomplete="off" readonly>
                        @error('total_square_feet')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-3 mb-3">
                        <label for="total_amount">@lang('messages.total_amount') </label>
                        <input type="text" class="form-control" id="total_amount" name="total_amount" step="any"
                            min="0" onwheel="this.blur()"
                            value="{{ old('total_marla', $product->total_amount) }}" placeholder="@lang('messages.total_amount')"
                            readonly>
                        @error('total_amount')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="front_width">@lang('messages.front_width') </label>
                        <input type="number" class="form-control" id="front_width" name="front_width" step="any"
                            min="0" onwheel="this.blur()" value="{{ old('front_width', $product->front_width) }}"
                            placeholder="@lang('messages.front_width')">
                        @error('front_width')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="length">@lang('messages.length') </label>
                        <input type="number" class="form-control" id="length" name="length" step="any"
                            min="0" onwheel="this.blur()" value="{{ old('length', $product->length) }}"
                            placeholder="@lang('messages.length')">
                        @error('length')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                </div>


                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="front_width">@lang('messages.front_width')(2) </label>
                        <input type="text" class="form-control" id="front_width" name="front_width2"
                            value="{{ old('front_width2', $product->front_width2) }}"
                            placeholder="@lang('messages.front_width')(2)">
                        @error('front_width2')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="length2">@lang('messages.length')(2) </label>
                        <input type="text" class="form-control" id="length2" name="length2"
                            value="{{ old('length2', $product->length2) }}" placeholder="@lang('messages.length')(2)">
                        @error('length2')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <input type="hidden" name="status" value="{{ $product->status }}">


                <div class="row">
                    {{-- IMAGE --}}
                    <div class="col-md-6 mb-3">
                        <label for="image">@lang('messages.image')</label><br>

                        <!-- File Upload Input -->
                        <input type="file" name="image" id="image" class="form-control"
                            onchange="previewImage(this)">

                        <!-- Image Preview (Shows avatar if no image is selected) -->
                        <div id="imagePreview" class="mt-2">
                            <img id="previewImg"
                                src="{{ isset($product) && $product->image ? asset('storage/' . $product->image) : asset('images/No-Image-Placeholder.svg.png') }}"
                                alt="" class="img-thumbnail" style="max-height: 200px;">
                        </div>

                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="termsAndConditions">@lang('messages.terms')</label>
                        <textarea class="form-control" id="termsAndConditions" name="termsAndConditions" placeholder="......"
                            rows="3">{{ old('termsAndConditions', $product->termsAndConditions ?? '') }}</textarea>
                        @error('termsAndConditions')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">@lang('messages.go-to-list')</a>
                </div>

            </form>
        </div>
    </div>
    <script>
        window.customTranslations = {
            pleaseSelect: "{{ __('messages.select-control-heads') }}",
            noData: "{{ __('messages.no-control-head-found') }}",
            errorTitle: "{{ __('messages.error-title') }}",
            errorText: "{{ __('messages.control-head-fetch-failed') }}",
            loading: "{{ __('messages.loading') }}",
            selectSubHead: "{{ __('messages.select-sub-head') }}",
            selectSubSubHead: "{{ __('messages.select-sub-sub-heads') }}",
            selectSubSubSubHead: "{{ __('messages.select-sub-sub-sub-heads') }}",
            noSubHeads: "{{ __('messages.no-sub-head-found') }}",
            noSubSubSubHeads: "{{ __('messages.no-sub-sub-sub-head-found') }}",
            subHeaderrorTitle: "{{ __('messages.subHeaderror-title') }}",
            subHeaderrorText: "{{ __('messages.sub-head-fetch-failed') }}",
            selectProjectFirst: "{{ __('messages.select-project-first') }}",
            pleaseSelectProject: "{{ __('messages.pleaseSelectProject') }}",

        };

        var config = {
            routes: {
                getControlHeads: "{{ route('get.control.head.account', ['mainHead' => ':id']) }}",
                getSubHeads: "{{ route('get.sub.head.account', ['controlHead' => ':id']) }}",
                getSubSubHeads: "{{ route('get.sub.sub.head.account', ['subHead' => ':id']) }}",
                getSubSubSubHeads: "{{ route('get.sub.sub.sub.head.account', ['subSubHead' => ':id']) }}",
                getDetailAccountCode: "{{ route('get.detail.account', ['code' => ':id']) }}",
                getProjectSquareFeet: "{{ route('get.project.squareFeet', ['projectId' => ':id']) }}",
            }
        };
    </script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.js') }}"></script>

    <script src="{{ asset('js/detail-account.js') }}"></script>
@endsection
