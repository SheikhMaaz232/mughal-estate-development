@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-qualification')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.qualifications.update', $qualification->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.qualifications.partials.form', ['qualification' => $qualification])
        </form>
    </div>
</div>
@endsection


