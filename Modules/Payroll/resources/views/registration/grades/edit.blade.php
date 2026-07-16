@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-grade')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.grades.update', $grade->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.grades.partials.form', ['grade' => $grade])
        </form>
    </div>
</div>
@endsection


