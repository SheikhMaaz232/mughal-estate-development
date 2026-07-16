@extends('payroll::layouts.payroll')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.generate-payroll')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.generate-payroll-description')</h2>
            </div>
            <a href="{{ route('payroll.payrolls.index') }}" class="btn btn-sm btn-secondary">@lang('payroll::messages.back-to-payroll-list')</a>
        </div>
    </div>
</div>
<div class="content">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <form method="POST" action="{{ route('payroll.payrolls.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="month" class="form-label">@lang('payroll::messages.payroll-month')</label>
                    <input id="month" type="month" name="month" class="form-control @error('month') is-invalid @enderror" value="{{ old('month', now()->format('Y-m')) }}" required>
                    @error('month')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-0">
                    <button type="submit" class="btn btn-primary">@lang('payroll::messages.generate-payroll')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
