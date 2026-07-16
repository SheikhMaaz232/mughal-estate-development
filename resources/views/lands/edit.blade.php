@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.edit-land-registration')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('lands.update', $land->id) }}" method="POST">
            @include('lands.partials.form', ['landRegistration' => $land])
        </form>
    </div>
</div>
@endsection
