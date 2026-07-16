@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-designation')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.designations.update', $designation->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.designations.partials.form', ['designation' => $designation])
        </form>
    </div>
</div>
@endsection


