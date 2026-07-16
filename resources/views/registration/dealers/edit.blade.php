@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.edit-dealer')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('dealers.update', $dealer->id) }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')
            @include('registration.dealers.partials.form',['dealer' => $dealer])
        </form>
    </div>
</div>
@endsection
