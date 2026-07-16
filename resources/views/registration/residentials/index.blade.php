@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-4">@lang('messages.residentials')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-all-residential-types')</h2>
            </div>
            <a href="{{ route('residentials.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new')</a>

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
                <th>@lang('messages.title') (EN)</th>
                <th>@lang('messages.title') (UR)</th>
                <th>@lang('messages.actions')</th>
            </tr>
        </thead>
            <tbody>
                @foreach($residentials as $residential)
                    <tr>
                        <td>{{ $residential->id }}</td>
                        <td>{{ $residential->title_en }}</td>
                        <td>{{ $residential->title_ur }}</td>
                        <td class="text-center">
                        <div class="btn-group">
                            <!-- Edit Button -->
                            <a href="{{ route('residentials.edit', $residential->id) }}"
                               class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                               data-bs-toggle="tooltip"
                               aria-label="Edit Residential Type"
                               data-bs-original-title="Edit Residential Type">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>

                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('residentials.destroy', $residential->id) }}" class="d-inline-block delete-form">
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
    </div>
</div>

@endsection