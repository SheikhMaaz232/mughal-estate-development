@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">Edit Company</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('registration.companies.partials.form', ['company' => $company])
        </form>
    </div>
</div>
@endsection


