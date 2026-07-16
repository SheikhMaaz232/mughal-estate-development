@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-payroll-type')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.payroll-types.update', $payrollType->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.payroll-types.partials.form', ['payrollType' => $payrollType])
        </form>
    </div>
</div>
@endsection


