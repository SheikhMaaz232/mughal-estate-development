@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-leave-type')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.leave-types.update', $leaveType->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.leave-types.partials.form', ['leaveType' => $leaveType])
        </form>
    </div>
</div>
@endsection


