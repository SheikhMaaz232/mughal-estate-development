    @extends('layouts.backend')

    @section('content')
        <div class="block block-rounded col-md-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.add-purchase-return')</h3>
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
                <form action="{{ route('purchase-return.create') }}" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id">@lang('messages.purchase_invoice_no') </label>
                            <input type="number" class="form-control" id="id" name="purchase_invoice_no"
                                step="any" min="0" onwheel="this.blur()" placeholder="@lang('messages.purchase_invoice_no')"
                                value="{{ old('purchase_invoice_no') }}">
                            @error('purchase_invoice_no')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">@lang('messages.add')</button>
                            <a href="{{ route('purchase-return.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    @endsection
