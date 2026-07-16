@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.edit-tehsil')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('tehsils.update', $tehsil->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('registration.tehsils.partials.form', ['tehsil' => $tehsil])
        </form>
    </div>
</div>
@endsection


