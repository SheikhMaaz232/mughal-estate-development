@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-shift')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.shifts.update', $shift->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.shifts.partials.form', ['shift' => $shift])
        </form>
    </div>
</div>
@endsection


