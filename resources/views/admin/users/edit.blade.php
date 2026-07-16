@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">@lang('messages.assign-role-to-user')</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('users-roles.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- User Info -->
                <div class="mb-4">
                    <label class="form-label">@lang('messages.user-name')</label>
                    <input type="text" class="form-control" value="{{ $user->name_en }} ({{ $user->email }})" disabled>
                </div>

                <!-- Roles Table -->
                <div class="mb-4">
                    <label class="form-label">@lang('messages.list-of-roles')</label>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>@lang('messages.role-name')</th>
                                    <th>@lang('messages.role-name') (EN)</th>
                                    <th>@lang('messages.role-name') (UR)</th>
                                    <th>@lang('messages.assign')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $index => $role)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->name_en }}</td>
                                        <td>{{ $role->name_ur }}</td>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       id="role_{{ $role->id }}"
                                                       name="roles[]"
                                                       value="{{ $role->name }}"
                                                       {{ $user->roles->contains($role) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    @lang('messages.assign')
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($roles->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            @lang('messages.no-roles-found')
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Actions -->
                <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                <a href="{{ route('users-roles.index') }}" class="btn btn-secondary">@lang('messages.cancel')</a>
            </form>
        </div>
    </div>
</div>
@endsection
