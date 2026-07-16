@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-employee')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('payroll::employees.partials.form', ['employee' => $employee])
        </form>
    </div>
</div>
@endsection


