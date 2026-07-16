@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header">
                <h4 class="card-title">{{ __('messages.edit_possession_letter') }}</h4>
            </div>

            <form id="possession-letter-form" action="{{ route('possession-letter.update', $possessionLetter->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">

                    <div class="row">

                        <!-- File No -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.file_no') }} <span class="text-danger">*</span></label>
                                <input type="text" name="file_no"
                                    value="{{ old('file_no', $possessionLetter->file_no) }}" class="form-control"
                                    style="background-color: #e9ecef;" readonly>
                                @error('file_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.Date') }} <span class="text-danger">*</span></label>

                                <input type="date" class="form-control" name="date"
                                    value="{{ old('date', isset($possessionLetter->date) ? $possessionLetter->date : now()->format('Y-m-d')) }}">
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Project -->
                        <div class="col-md-6 mb-3">
                            <label for="project_id" class="form-label">@lang('messages.projects')</label>

                            <input type="hidden" name="project_id" value="{{ $possessionLetter->project_id }}">
                            <input type="text" class="form-control"
                                value="{{ App::getLocale() === 'ur' ? $possessionLetter->project->name_ur ?? '-' : $possessionLetter->project->name_en ?? '-' }}"
                                disabled>
                            @error('project_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <input type="hidden" name="status" value="{{ 'Unverified' }}">

                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">@lang('messages.product')</label>

                            <input type="hidden" name="product_id" value="{{ $possessionLetter->product_id }}">
                            <input type="text" class="form-control"
                                value="{{ App::getLocale() === 'ur' ? $possessionLetter->product->name_ur ?? '-' : $possessionLetter->product->name_en ?? '-' }}"
                                disabled>
                            @error('product_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="party_id" class="form-label">@lang('messages.party')</label>

                            <input type="hidden" name="party_id" value="{{ $possessionLetter->party_id }}">
                            <input type="text" class="form-control"
                                value="{{ App::getLocale() === 'ur' ? $possessionLetter->party->name_ur ?? '-' : $possessionLetter->party->name_en ?? '-' }}"
                                disabled>
                            @error('party_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.cnic_no') }}</label>

                                <input type="cnic_no" class="form-control" style="background-color: #e9ecef;"
                                    value="{{ old('cnic_no', $possessionLetter->party->cnic_no) }}" readonly>
                            </div>
                        </div>

                    </div>

                    <hr>

                    <h5 class="mt-3 mb-2">{{ __('messages.boundaries') }}</h5>

                    <div class="row">

                        {{-- East --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.east_side') }}</label>
                                <input type="text" name="east_side"
                                    value="{{ old('east_side', $possessionLetter->east_side) }}" class="form-control">
                                @error('east_side')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.east_bounded_by') }}</label>
                                <input type="text" name="east_bounded_by"
                                    value="{{ old('east_bounded_by', $possessionLetter->east_bounded_by) }}"
                                    class="form-control">
                                @error('east_bounded_by')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        {{-- West --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.west_side') }}</label>
                                <input type="text" name="west_side"
                                    value="{{ old('west_side', $possessionLetter->west_side) }}" class="form-control">
                                @error('west_side')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.west_bounded_by') }}</label>
                                <input type="text" name="west_bounded_by"
                                    value="{{ old('west_bounded_by', $possessionLetter->west_bounded_by) }}"
                                    class="form-control">
                                @error('west_bounded_by')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        {{-- South --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.south_side') }}</label>
                                <input type="text" name="south_side"
                                    value="{{ old('south_side', $possessionLetter->south_side) }}" class="form-control">
                                @error('south_side')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.south_bounded_by') }}</label>
                                <input type="text" name="south_bounded_by"
                                    value="{{ old('south_bounded_by', $possessionLetter->south_bounded_by) }}"
                                    class="form-control">
                                @error('south_bounded_by')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        {{-- North --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.north_side') }}</label>
                                <input type="text" name="north_side"
                                    value="{{ old('north_side', $possessionLetter->north_side) }}" class="form-control">
                                @error('north_side')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.north_bounded_by') }}</label>
                                <input type="text" name="north_bounded_by"
                                    value="{{ old('north_bounded_by', $possessionLetter->north_bounded_by) }}"
                                    class="form-control">
                                @error('north_bounded_by')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <hr>

                    <h5 class="mt-3 mb-2">{{ __('messages.area_details') }}</h5>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.kanal') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="kanal" style="background-color: #e9ecef;"
                                    value="{{ old('kanal', $possessionLetter->product->kanal) }}" class="form-control"
                                    readonly>
                                @error('kanal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.marla') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="marla" style="background-color: #e9ecef;"
                                    value="{{ old('marla', $possessionLetter->product->marla) }}" class="form-control"
                                    readonly>
                                @error('marla')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.square_feet') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="square_feet"
                                    style="background-color: #e9ecef;"
                                    value="{{ old('square_feet', $possessionLetter->product->square_feet) }}"
                                    class="form-control" readonly>
                                @error('square_feet')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.total_marla') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="total_marla"
                                    style="background-color: #e9ecef;"
                                    value="{{ old('total_marla', $possessionLetter->product->total_marla) }}"
                                    class="form-control" readonly>
                                @error('total_marla')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.total_square_feet') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="total_square_feet"
                                    style="background-color: #e9ecef;"
                                    value="{{ old('total_square_feet', $possessionLetter->product->total_square_feet) }}"
                                    class="form-control" readonly>
                                @error('total_square_feet')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <hr>

                    <div class="form-group">
                        <label>{{ __('messages.special_note') }}</label>
                        <textarea name="special_note" class="form-control" rows="3">{{ old('special_note', $possessionLetter->special_note) }}</textarea>
                        @error('special_note')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                    <a href="{{ route('possession-letter.index') }}"
                        class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                </div>

            </form>
        </div>
    </div>
@endsection
