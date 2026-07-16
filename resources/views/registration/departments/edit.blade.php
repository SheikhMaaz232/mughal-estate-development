@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.edit-department')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('registration.departments.partials.form', ['department' => $department])
        </form>
    </div>
</div>
@endsection


