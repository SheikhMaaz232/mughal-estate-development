@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.edit-allowance')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.allowances.update', $allowance->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('payroll::registration.allowances.partials.form', ['allowance' => $allowance])
        </form>
    </div>
</div>
@endsection


