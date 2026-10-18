@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.user_information')</h3>
            <div class="block-options">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-alt-secondary">
                    @lang('messages.go-to-list')
                </a>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">
                    @lang('messages.update')
                </a>
            </div>
        </div>

        <div class="block-content">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <img
                        src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/No-Image-Placeholder.svg.png') }}"
                        alt="@lang('messages.photo')"
                        class="img-thumbnail"
                        style="width: 180px; height: 180px; object-fit: cover;">
                </div>

                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>@lang('messages.id')</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.name') (EN)</th>
                                    <td>{{ $user->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.name') (UR)</th>
                                    <td>{{ $user->name_ur }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.father-name') (EN)</th>
                                    <td>{{ $user->father_name_en }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.father-name') (UR)</th>
                                    <td>{{ $user->father_name_ur }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.email')</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.roles')</th>
                                    <td>
                                        {{ $user->roles->map(fn ($role) => App::getLocale() === 'ur' ? ($role->name_ur ?: $role->name_en) : $role->name_en)->filter()->join(', ') ?: '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.permissions')</th>
                                    <td>
                                        @forelse($permissions as $permission)
                                            <span class="badge bg-info-subtle text-info-emphasis me-1 mb-1">
                                                {{ App::getLocale() === 'ur' ? ($permission->name_ur ?: $permission->name_en) : $permission->name_en }}
                                            </span>
                                        @empty
                                            —
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.password')</th>
                                    <td>
                                        <span class="text-muted">@lang('messages.password_not_viewable')</span>
                                        {{-- @if(auth()->id() === $user->id && auth()->user()->can('users.reset_password')) --}}
                                            <a href="{{ route('user-passwords.index') }}" class="btn btn-sm btn-outline-primary ms-2">
                                                @lang('messages.change_password')
                                            </a>
                                        {{-- @endif --}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.created_at')</th>
                                    <td>{{ $user->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('messages.updated_at')</th>
                                    <td>{{ $user->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
