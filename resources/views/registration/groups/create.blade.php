@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.add-group')</h3>
    </div>
    <div class="block-content block-content-full">
    <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('registration.groups.partials.form', ['group' => new \App\Models\Group])
    </form>
</div>
</div>
</div>
@endsection
