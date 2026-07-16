@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.add-land-registration')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('land-registrations.store') }}" method="POST">
            @csrf
            @include('land-purchase.land-registration.partials.form')
        </form>
    </div>
</div>
@endsection
