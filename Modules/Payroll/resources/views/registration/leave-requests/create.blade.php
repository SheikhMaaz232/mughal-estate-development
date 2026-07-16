@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.request-leave')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('payroll.leave-requests.store') }}" method="POST">
            @include('payroll::registration.leave-requests.partials.form')
        </form>
    </div>
</div>
@endsection
