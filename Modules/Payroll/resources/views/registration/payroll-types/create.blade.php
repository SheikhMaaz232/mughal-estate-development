@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.add-payroll-type')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.payroll-types.store') }}" method="POST">
            @include('payroll::registration.payroll-types.partials.form')
        </form>
    </div>
</div>
@endsection
