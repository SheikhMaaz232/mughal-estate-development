@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.work-progress-details')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        #{{ $progress->id }}
                    </h2>
                </div>
                <a href="{{ route('work-progress.index') }}" class="btn btn-sm btn-secondary">@lang('messages.back')</a>
            </div>
        </div>
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Work Order Information -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.general-information')</h3>
                <div class="block-options">
                    <a href="{{ route('work-progress.edit', $progress->id) }}"
                        class="btn btn-sm btn-primary">@lang('messages.edit')</a>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.date')</label>
                        <p>{{ \Carbon\Carbon::parse($progress->date)->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.construction-site')</label>
                        <p>{{ App::getLocale() === 'ur' ? $progress->workOrder->constructionSite->name_ur : $progress->workOrder->constructionSite->name_en }}
                        </p>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.tender')</label>
                        <p>{{ App::getLocale() === 'ur' ? $progress->workOrder->tender->title_ur : $progress->workOrder->tender->title_en }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.work_order')</label>
                        <p>{{ App::getLocale() === 'ur' ? $progress->workOrder->description_ur : $progress->workOrder->description_en }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Descriptions -->
        @if ($progress->description_en || $progress->description_ur)
            <div class="row mt-4">
                @if ($progress->description_en)
                    <div class="col-md-6">
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">@lang('messages.description') @lang('messages.english')</h3>
                            </div>
                            <div class="block-content block-content-full">
                                <p>{{ $progress->description_en }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($progress->description_ur)
                    <div class="col-md-6">
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">@lang('messages.description') @lang('messages.urdu')</h3>
                            </div>
                            <div class="block-content block-content-full">
                                <p>{{ $progress->description_ur }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Work Order Items -->
        <div class="block block-rounded mt-4">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.work-order-items')</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>@lang('messages.item')</th>
                                <th>@lang('messages.qty')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($progress->details as $item)
                                <tr>
                                    <td>
                                        <strong>{{ App::getLocale() === 'ur' ? $item->item->name_ur : $item->item->name_en }}</strong>
                                        <br>
                                        <small
                                            class="text-muted">{{ App::getLocale() === 'ur' ? $item->item->measurementUnit->name_ur ?? 'N/A' : $item->item->measurementUnit->name_en ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ number_format($item->completed_qty, 4) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-muted">@lang('messages.no-items-added')</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Actions -->
        <div class="mt-4">
            <a href="{{ route('work-progress.edit', $progress->id) }}" class="btn btn-primary">
                <i class="fa fa-pencil"></i> @lang('messages.edit')
            </a>

            <form action="{{ route('work-progress.destroy', $progress->id) }}" method="POST" class="d-inline"
                onsubmit="return confirm('@lang(\"messages.are-you-sure\")')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-trash"></i> @lang('messages.delete')
                </button>
            </form>

            <a href="{{ route('work-progress.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> @lang('messages.back')
            </a>
        </div>
    </div>
@endsection
