@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.add-road-specification')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('road-specifications.store') }}" method="POST">
            @include('registration.road-specifications.partials.form')
        </form>
    </div>
</div>
@endsection
