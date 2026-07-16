@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-4">@lang('messages.permissions')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-permissions')</h2>
            </div>
            <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new')</a>
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
                        <th>@lang('messages.key')</th>
                        <th>@lang('messages.name') (EN)</th>
                        <th>@lang('messages.name') (UR)</th>
                        <th>@lang('messages.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->name_en }}</td>
                            <td>{{ $permission->name_ur }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('permissions.edit', $permission->id) }}"
                                       class="btn btn-sm btn-alt-secondary"
                                       data-bs-toggle="tooltip"
                                       title="@lang('messages.edit-permission')">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline-block">
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
                            <td colspan="3">@lang('messages.no-permissions-found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
