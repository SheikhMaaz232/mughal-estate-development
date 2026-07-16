@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-deduction')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.deductions.update', $deduction->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.deductions.partials.form', ['deduction' => $deduction])
        </form>
    </div>
</div>
@endsection


