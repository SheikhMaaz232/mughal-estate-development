@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.edit-road-specification')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('road-specifications.update', $roadSpecification->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('registration.road-specifications.partials.form', ['roadSecification' => $roadSpecification])
        </form>
    </div>
</div>
@endsection


