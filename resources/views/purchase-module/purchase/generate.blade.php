    @extends('layouts.backend')

    @section('content')
        <div class="block block-rounded col-md-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.add-purchase-invoice')</h3>
            </div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif
            <div class="block-content block-content-full">
                <form action="{{ route('purchase-invoice.create') }}" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id">@lang('messages.grn_no') </label>
                            <input type="number" class="form-control" id="id" name="grn_id"
                                step="any" min="0" onwheel="this.blur()" placeholder="@lang('messages.grn_no')"
                                value="{{ old('grn_no') }}">
                            @error('grn_no')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">@lang('messages.add')</button>
                            <a href="{{ route('purchase-invoice.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    @endsection
