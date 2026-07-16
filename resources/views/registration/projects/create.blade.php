@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-project')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('projects.store') }}" enctype="multipart/form-data" method="POST">
                @include('registration.projects.partials.form')
            </form>
        </div>
    </div>
    <script src="{{ asset('js/project.js') }}"></script>
@endsection
