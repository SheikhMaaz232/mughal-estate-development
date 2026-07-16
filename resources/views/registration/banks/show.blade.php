@extends('layouts.backend')

@section('content')
<div class="container">
    <h1>City Details</h1>
    
    <div class="card">
        <div class="card-header">
            <h2>{{ $city->name_en }} / {{ $city->name_ur }}</h2>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $city->id }}</p>
            <p><strong>English Name:</strong> {{ $city->name_en }}</p>
            <p><strong>Urdu Name:</strong> {{ $city->name_ur }}</p>
            <p><strong>Created At:</strong> {{ $city->created_at }}</p>
            <p><strong>Updated At:</strong> {{ $city->updated_at }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('cities.edit', $city->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('cities.destroy', $city->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
            <a href="{{ route('cities.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection