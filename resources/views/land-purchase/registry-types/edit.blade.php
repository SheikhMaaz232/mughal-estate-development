@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.edit-registry-type')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('registry-types.update', $registryType->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('land-purchase.registry-types.partials.form', ['registryType' => $registryType])
        </form>
    </div>
</div>
@endsection


