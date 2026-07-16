@extends('layouts.backend')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Audit Logs</h3>

    {{-- Filters --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="model" class="form-select">
                <option value="">All Models</option>
                @foreach($models as $model)
                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                        {{ class_basename($model) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="event" class="form-select">
                <option value="">All Actions</option>
                @foreach($events as $event)
                    <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                        {{ ucfirst($event) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="user_id" class="form-select">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    {{-- Audit Logs Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover ">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Model</th>
                    <th>Action</th>
                    <th>Record ID</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits as $audit)
                    <tr>
                        <td>{{ $audit->created_at->format('d-m-Y H:i:s') }}</td>
                        <td>{{ optional($audit->user)->name_eng ?? 'System' }}</td>
                        <td>{{ class_basename($audit->auditable_type) }}</td>
                        <td>{{ ucfirst($audit->event) }}</td>
                        <td>{{ $audit->auditable_id }}</td>
                        <td>
                            <pre class="mb-0">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </td>
                        <td>
                            <pre class="mb-0">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No audit logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $audits->withQueryString()->links() }}
</div>
@endsection
