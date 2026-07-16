@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Project Details</h1>
    
    <div class="card">
        <div class="card-header">
            <h2>{{ $project->name_en }} / {{ $project->name_ur }}</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h3>English Information</h3>
                    <p><strong>Description:</strong> {{ $project->description_en }}</p>
                    <p><strong>Phase:</strong> {{ $project->phase_en }}</p>
                    <p><strong>Address:</strong> {{ $project->address_en }}</p>
                </div>
                <div class="col-md-6">
                    <h3>Urdu Information</h3>
                    <p><strong>تفصیل:</strong> {{ $project->description_ur }}</p>
                    <p><strong>مرحلہ:</strong> {{ $project->phase_ur }}</p>
                    <p><strong>پتہ:</strong> {{ $project->address_ur }}</p>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Project Code:</strong> {{ $project->project_code }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Debit Code:</strong> {{ $project->debit_code }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Phase Map:</strong> {{ $project->phase_map }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Group ID:</strong> {{ $project->group_id }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Company ID:</strong> {{ $project->company_id }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection