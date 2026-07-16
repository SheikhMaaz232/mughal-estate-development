@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">Add Company</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
            @include('registration.companies.partials.form', ['groups' => $groups])
        </form>
    </div>
</div>
@endsection
