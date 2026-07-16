@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">Edit Area</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('areas.update', $area->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('registration.areas.partials.form', ['area' => $area])
        </form>
    </div>
</div>
@endsection


