@extends('layouts.backend')

@section('content')
<div class="content">
    <h2>Group Details</h2>
    <ul>
        <li><strong>Group Code:</strong> {{ $group->group_code }}</li>
        <li><strong>Name (EN):</strong> {{ $group->name_eng }}</li>
        <li><strong>Name (UR):</strong> {{ $group->name_ur }}</li>
        <li><strong>Description (EN):</strong> {{ $group->description_eng }}</li>
        <li><strong>Description (UR):</strong> {{ $group->description_ur }}</li>
        <li><strong>Address (EN):</strong> {{ $group->address_eng }}</li>
        <li><strong>Address (UR):</strong> {{ $group->address_ur }}</li>
        <li><strong>Vehicle Type:</strong> {{ $group->v_type }}</li>
        <li><strong>Image:</strong><br>
            <img src="{{ asset('storage/' . $group->image) }}" alt="Image" width="150">
        </li>
    </ul>
    <a href="{{ route('groups.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
