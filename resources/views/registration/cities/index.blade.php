@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('messages.cities')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-all-cities')</h2>
            </div>
            <a href="{{ route('cities.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new')</a>

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

             <div class="mb-4">
        <form action="{{ route('cities.index') }}" method="GET">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       name="search"
                       placeholder="@lang('messages.search')..."
                       value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('cities.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

 @if($cities->isEmpty())
                <div class="text-center py-4">
                    <p class="text-muted">@lang('messages.no-records-found')</p>
                </div>
            @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>@lang('messages.id')</th>
                <th>@lang('messages.name') (EN)</th>
                <th>@lang('messages.name') (UR)</th>
                <th>@lang('messages.actions')</th>
            </tr>
        </thead>
            <tbody>
                @foreach($cities as $city)
                    <tr>
                        <td>{{ $city->id }}</td>
                        <td>{{ $city->name_en }}</td>
                        <td>{{ $city->name_ur }}</td>
                        <td class="text-center">
                        <div class="btn-group">
                            <!-- Edit Button -->
                            <a href="{{ route('cities.edit', $city->id) }}"
                               class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                               data-bs-toggle="tooltip"
                               aria-label="Edit City"
                               data-bs-original-title="Edit City">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>

                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('cities.destroy', $city->id) }}" class="d-inline-block delete-form">
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
         <div class="d-flex justify-content-center">
                    {{ $cities->withQueryString()->links() }}
                </div>
        </div>
        @endif
    </div>
</div>

@endsection
