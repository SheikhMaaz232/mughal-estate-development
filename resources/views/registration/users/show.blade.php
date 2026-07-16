@extends('layouts.backend')

@section('content')
<div class="block">
    <div class="block-header">
        <h3 class="block-title">Company Details</h3>
    </div>
    <div class="block-content">
        <p><strong>Name (English):</strong> {{ $company->name_eng }}</p>
        <p><strong>Name (Urdu):</strong> {{ $company->name_ur }}</p>
    </div>
</div>
@endsection
