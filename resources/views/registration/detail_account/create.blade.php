@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-detail-accounts')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('detail-accounts.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <p class="fs-sm text-muted">
                            @lang('messages.both-detail-account-names')
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">

                        <div class="col-md-12 mb-3">
                            <label for="main_head_id">@lang('messages.main-heads')</label>
                            <select name="main_head_id" id="main-head"
                                class="form-control form-select select2 @error('main_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-main-heads')</option>
                                @foreach ($mainHeads as $mainHead)
                                    <option value="{{ $mainHead->id }}"
                                        {{ old('main_head_id') == $mainHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $mainHead->name_ur ?? '-' : $mainHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('main_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="control_head_id">@lang('messages.control-heads')</label>
                            <select name="control_head_id" id="control-head"
                                class="form-control form-select select2 @error('control_head_id') is-invalid @enderror">
                                <option value="">{{ __('messages.select-control-heads') }}</option>
                                @foreach ($searchControlHeads as $searchControlHead)
                                    <option value="{{ $searchControlHead->id }}"
                                        {{ old('control_head_id') == $searchControlHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $searchControlHead->name_ur ?? '-' : $searchControlHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('control_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="sub_head_id">@lang('messages.sub-heads')</label>
                            <select name="sub_head_id" id="sub-head"
                                class="form-control form-select select2 @error('sub_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-sub-heads')</option>
                                @foreach ($searchSubHeads as $searchSubHead)
                                    <option value="{{ $searchSubHead->id }}"
                                        {{ old('sub_head_id') == $searchSubHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $searchSubHead->name_ur ?? '-' : $searchSubHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="sub_sub_head_id">@lang('messages.sub-sub-heads')</label>
                            <select name="sub_sub_head_id" id="sub-sub-head"
                                class="form-control form-select select2 @error('sub_sub_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-sub-sub-heads')</option>
                                @foreach ($searchSubSubHeads as $searchSubSubHead)
                                    <option value="{{ $searchSubSubHead->id }}"
                                        {{ old('sub_sub_head_id') == $searchSubSubHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $searchSubSubHead->name_ur ?? '-' : $searchSubSubHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_sub_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="sub_sub_sub_head_id">@lang('messages.sub-sub-sub-heads')</label>
                            <select name="sub_sub_sub_head_id" id="sub-sub-sub-head"
                                class="form-control form-select select2 @error('sub_sub_sub_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-sub-sub-sub-heads')</option>
                                @foreach ($searchSubSubSubHeads as $searchSubSubSubHead)
                                    <option value="{{ $searchSubSubSubHead->id }}"
                                        {{ old('sub_sub_sub_head_id') == $searchSubSubSubHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $searchSubSubSubHead->name_ur ?? '-' : $searchSubSubSubHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_sub_sub_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="party_id">@lang('messages.main_party')</label>
                            <select name="party_id" id="party_id"
                                class="form-control form-select select2 @error('party_id') is-invalid @enderror">
                                <option value="">@lang('messages.main_party')</option>
                                @foreach ($searchParties as $searchParty)
                                    <option value="{{ $searchParty->id }}"
                                        {{ old('party_id') == $searchParty->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                                        -
                                        ({{ App::getLocale() === 'ur' ? 'ذات' : 'CAST' }}:
                                        {{ App::getLocale() === 'ur' ? $searchParty->cast->title_ur ?? '-' : $searchParty->cast->title_en ?? '-' }})
                                        ({{ App::getLocale() === 'ur' ? 'شناختی کارڈ' : 'CNIC' }}:
                                        {{ $searchParty->cnic_no ?? 'N/A' }})
                                        ({{ App::getLocale() === 'ur' ? 'فون' : 'Phone' }}:
                                        {{ $searchParty->contact_number_1 ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('party_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="name_en">@lang('messages.name') @lang('messages.english')</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                placeholder="@lang('messages.enter-name-english')" autocomplete="off" value="{{ old('name_en') }}">
                            @error('name_en')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="name_ur">@lang('messages.name') @lang('messages.urdu')</label>
                            <input type="text" class="form-control input-urdu keyboardInput" id="name_ur"
                                name="name_ur" placeholder="نام درج کریں" autocomplete="off" value="{{ old('name_ur') }}">
                            @error('name_ur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            <a href="{{ route('detail-accounts.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                        </div>
                    </div>
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
            subHeaderrorText: "{{ __('messages.sub-head-fetch-failed') }}"

        };
    </script>

    <script>
        var config = {
            routes: {
                getControlHeads: "{{ route('get.control.head.account', ['mainHead' => ':id']) }}",
                getSubHeads: "{{ route('get.sub.head.account', ['controlHead' => ':id']) }}",
                getSubSubHeads: "{{ route('get.sub.sub.head.account', ['subHead' => ':id']) }}",
                getSubSubSubHeads: "{{ route('get.sub.sub.sub.head.account', ['subSubHead' => ':id']) }}",
                getDetailAccountCode: "{{ route('get.detail.account', ['code' => ':id']) }}",
            }
        };

        $(document).ready(function() {
            // If old main_head_id exists, trigger change to load dependent dropdowns
            @if (old('main_head_id'))
                $('#main-head').val('{{ old('main_head_id') }}').trigger('change');
            @endif

            // If old control_head_id exists, trigger change after delay to load sub-heads
            @if (old('control_head_id'))
                setTimeout(function() {
                    $('#control-head').val('{{ old('control_head_id') }}').trigger('change');
                }, 800);
            @endif
        });
    </script>

    <script src="{{ asset('js/detail-account.js') }}"></script>
@endsection
