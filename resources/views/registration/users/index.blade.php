@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-4">@lang('messages.users')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-all-users')</h2>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new')</a>
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

    <!-- Search Form -->
    <div class="block block-rounded mb-4">
        <div class="block-content block-content-full">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search-user')..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="block block-rounded">
        <div class="block-content block-content-full">
            @if($users->isEmpty())
                <div class="text-center py-4">
                    <p class="text-muted">@lang('messages.no-records-found')</p>
                </div>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('messages.photo')</th>
                            <th>@lang('messages.name') (EN)</th>
                            <th>@lang('messages.name') (UR)</th>
                            <th>@lang('messages.father-name') (EN)</th>
                            <th>@lang('messages.father-name') (UR)</th>
                            <th>@lang('messages.email') </th>
                            <th style="width: 150px;">@lang('messages.actions') </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="text-center">
                                <img src="{{ isset($user) && $user->avatar ? asset('storage/' . $user->avatar) : asset('images/No-Image-Placeholder.svg.png') }}" width="50" height="50" style="object-fit: cover;">
                            </td>
                            <td>{{ $user['name_en'] }}</td>
                            <td>{{ $user['name_ur'] }}</td>
                            <td>{{ $user['father_name_en'] }}</td>
                            <td>{{ $user['father_name_ur'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <!-- Edit Button -->
                                    <a href="{{ route('users.edit', $user['id']) }}"
                                    class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                    data-bs-toggle="tooltip"
                                    aria-label="Edit User"
                                    data-bs-original-title="Edit User">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>

                                    <!-- Delete Form -->
                                    <form method="POST" action="{{ route('users.destroy', $user['id']) }}" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
