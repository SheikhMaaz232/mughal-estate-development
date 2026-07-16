@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full d-flex justify-content-between align-items-center py-2">
        <div>
            <h1 class="h3 fw-bold">@lang('messages.assign-role-to-user')</h1>
            <h2 class="fs-base fw-medium text-muted">@lang('messages.list-of-users')</h2>
        </div>
    </div>
</div>

<div class="content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>@lang('messages.id')</th>
                        <th>@lang('messages.name') (EN)</th>
                        <th>@lang('messages.name') (UR)</th>
                        <th>@lang('messages.email')</th>
                        <th>@lang('messages.roles')</th>
                        <th>@lang('messages.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name_en }}</td>
                            <td>{{ $user->name_ur }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('users-roles.edit', $user->id) }}"
                                        class="btn btn-sm btn-alt-secondary">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>

                                        <form method="POST" action="{{ route('users-roles.destroy', $user->id) }}" class="d-inline-block">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">@lang('messages.no-users-found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
