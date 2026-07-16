@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-device')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.devices.update', $device->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.devices.partials.form', ['device' => $device])
        </form>
    </div>
</div>
@endsection


