@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('messages.groups')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-groups')</h2>
            </div>
            <a href="{{ route('groups.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new')</a>

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
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>@lang('messages.logo')</th>
                <th>@lang('messages.group-code')</th>
                <th>@lang('messages.name') (EN)</th>
                <th>@lang('messages.name') (UR)</th>
                <th width="150">@lang('messages.actions')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as $group)
                <tr>
                     <td>
                        @if ($group->image)
                            <img class="img-avatar img-avatar48" src="{{ asset('storage/' . $group->image) }}" alt="Logo" width="50" height="50" style="object-fit: cover;">
                        @else
                            <span class="text-muted">No Logo</span>
                        @endif
                    </td>
                    <td>{{ $group->group_code }}</td>
                    <td>{{ $group->name_eng }}</td>
                    <td>{{ $group->name_ur }}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <!-- Edit Button -->
                            <a href="{{ route('groups.edit', $group->id) }}"
                               class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                               data-bs-toggle="tooltip"
                               aria-label="Edit Group"
                               data-bs-original-title="Edit Group">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>

                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('groups.destroy', $group->id) }}" class="d-inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                {{--  <button type="button" class="fa fa-fw fa-pencil-alt" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    Del
                                </button>  --}}
                                <button type="button" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>

                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
