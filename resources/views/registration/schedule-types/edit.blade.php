@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.edit-schedule-type')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('schedule-types.update', $scheduleType->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('registration.schedule-types.partials.form', ['scheduleType' => $scheduleType])
        </form>
    </div>
</div>
@endsection


