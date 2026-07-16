@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full d-flex justify-content-between align-items-center py-2">
        <div>
            <h1 class="h3 fw-bold">@lang('messages.roles')</h1>
            <h2 class="fs-base fw-medium text-muted">@lang('messages.list-of-roles')</h2>
        </div>
        @hasanyrole('super-admin|manager')
            <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new')</a>
        @endcan
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
                        <th>@lang('messages.key')</th>
                        <th>@lang('messages.name') (EN)</th>
                        <th>@lang('messages.name') (UR)</th>
                        <th>@lang('messages.permissions')</th>
                        <th>@lang('messages.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->name_en }}</td>
                            <td>{{ $role->name_ur }}</td>
                            <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                       class="btn btn-sm btn-alt-secondary"
                                       data-bs-toggle="tooltip"
                                       title="@lang('messages.edit-role')">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline-block">
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
                            <td colspan="4">@lang('messages.no-roles-found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
