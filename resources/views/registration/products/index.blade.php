@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-products')</h2>
                </div>
                <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-products')</a>
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
        <form method="GET" action="{{ route('products.index') }}">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label for="project_id">@lang('messages.projects')</label>
                    <select name="project_id[]" id="project_id"
                        class="form-control form-select select2 @error('project_id') is-invalid @enderror" multiple>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ collect(request('project_id'))->contains($project->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 mb-3">
                    <label for="main_head_id">@lang('messages.main-heads')</label>
                    <select name="main_head_id[]" id="main_head_id"
                        class="form-control form-select select2 @error('main_head_id') is-invalid @enderror" multiple>
                        @foreach ($mainHeads as $mainHead)
                            <option value="{{ $mainHead->id }}"
                                {{ collect(request('main_head_id'))->contains($mainHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $mainHead->name_ur ?? '-' : $mainHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="control_head_id">@lang('messages.control-heads')</label>
                    <select name="control_head_id[]" id="control_head_id"
                        class="form-control form-select select2 @error('control_head_id') is-invalid @enderror" multiple>
                        @foreach ($searchControlHeads as $controlHead)
                            <option value="{{ $controlHead->id }}"
                                {{ collect(request('control_head_id'))->contains($controlHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $controlHead->name_ur ?? '-' : $controlHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="sub_head_id">@lang('messages.sub-heads')</label>
                    <select name="sub_head_id[]" id="sub_head_id"
                        class="form-control form-select select2 @error('sub_head_id') is-invalid @enderror" multiple>
                        @foreach ($searchSubHeads as $subHead)
                            <option value="{{ $subHead->id }}"
                                {{ collect(request('sub_head_id'))->contains($subHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $subHead->name_ur ?? '-' : $subHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="sub_sub_head_id">@lang('messages.sub-sub-heads')</label>
                    <select name="sub_sub_head_id[]" id="sub_sub_head_id"
                        class="form-control form-select select2 @error('sub_sub_head_id') is-invalid @enderror" multiple>
                        @foreach ($searchSubSubHeads as $subSubHead)
                            <option value="{{ $subSubHead->id }}"
                                {{ collect(request('sub_sub_head_id'))->contains($subSubHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $subSubHead->name_ur ?? '-' : $subSubHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="sub_sub_sub_head_id">@lang('messages.sub-sub-sub-heads')</label>
                    <select name="sub_sub_sub_head_id[]" id="sub_sub_sub_head_id"
                        class="form-control form-select select2 @error('sub_sub_sub_head_id') is-invalid @enderror"
                        multiple>
                        @foreach ($searchSubSubSubHeads as $subSubSubHead)
                            <option value="{{ $subSubSubHead->id }}"
                                {{ collect(request('sub_sub_sub_head_id'))->contains($subSubSubHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $subSubSubHead->name_ur ?? '-' : $subSubSubHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="search">@lang('messages.name')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search-SubSubSubHead')"
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="search">@lang('messages.unit_no')</label>
                    <input type="text" class="form-control" name="unit_no" placeholder="@lang('messages.unit_no')"
                        value="{{ request('unit_no') }}">
                </div>

                <div class="col-lg-6" style="margin-top: 25px;">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['search', 'main_head_id', 'control_head_id', 'sub_head_id', 'sub_sub_head_id']))
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>
        <div class="block block-rounded">
            <div class="block-content block-content-full" style="overflow-x: scroll !important;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>@lang('messages.projects')</th>
                            <th>@lang('messages.main-heads')</th>
                            <th>@lang('messages.control-heads')</th>
                            <th>@lang('messages.sub-heads')</th>
                            <th>@lang('messages.sub-sub-heads')</th>
                            <th>@lang('messages.sub-sub-sub-heads')</th>
                            <th>@lang('messages.unit_no')</th>
                            <th>@lang('messages.name')</th>
                            <th>@lang('messages.status')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="text-center">{{ $product->id }}</td>

                                <td>
                                    {{ App::getLocale() === 'ur' ? $product->project->name_ur ?? '-' : $product->project->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $product->mainHead->name_ur ?? '-' : $product->mainHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $product->controlHead->name_ur ?? '-' : $product->controlHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $product->subHead->name_ur ?? '-' : $product->subHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $product->subSubHead->name_ur ?? '-' : $product->subSubHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $product->subSubSubHead->name_ur ?? '-' : $product->subSubSubHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ $product->unit_no ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $product->name_ur ?? '-' : $product->name_en ?? '-' }}
                                </td>

                                <td>
                                    {{ match ($product->status) {
                                        'Unverified' => __('messages.unverified'),
                                        'Verified' => __('messages.verified'),
                                        'Booked' => __('messages.booked'),
                                        default => '-',
                                    } }}
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit product"
                                            data-bs-original-title="Edit product"> <i
                                                class="fa fa-fw fa-pencil-alt"></i></a>

                                        @if ($product->status !== 'Booked')
                                            <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                    <i class="fa fa-fw fa-times text-danger"></i>
                                                </button>

                                            </form>
                                        @endif

                                        <a href="{{ route('products.show', $product->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View Product"
                                            data-bs-original-title="View Product">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>

                                        {{-- Add Verify/Booking button based on status --}}
                                        @if ($product->status === 'Unverified')
                                            <form method="POST"
                                                action="{{ route('products.updateStatus', $product->id) }}"
                                                class="d-inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Verified">

                                                <button type="button"
                                                    class="btn btn-sm btn-success js-bs-tooltip-enabled btn-verify"
                                                    data-bs-toggle="modal" data-bs-target="#confirmVerifyModal"
                                                    aria-label="Verify Product" data-bs-original-title="Verify Product">
                                                    <i class="fa fa-fw fa-check"></i>
                                                </button>

                                            </form>
                                        @elseif ($product->status === 'Verified' && $product->type !== 'item')
                                            <a href="{{ route('bookings.create', ['product_id' => $product->id]) }}"
                                                class="btn btn-sm btn-info js-bs-tooltip-enabled" data-bs-toggle="tooltip"
                                                aria-label="Add Booking" data-bs-original-title="Add Booking">
                                                <i class="fa fa-fw fa-calendar-plus"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                <!-- Confirm Verify Modal -->
                <div class="modal fade" id="confirmVerifyModal" tabindex="-1" aria-labelledby="confirmVerifyLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="confirmVerifyLabel">@lang('messages.confirm_verification')</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="@lang('messages.close')"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p>@lang('messages.verify_confirmation_text')</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-alt-secondary"
                                    data-bs-dismiss="modal">@lang('messages.cancel')</button>
                                <button type="button" class="btn btn-success"
                                    id="confirmVerifyBtn">@lang('messages.yes_verify')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentVerifyForm;

        // When verify button clicked
        document.querySelectorAll('.btn-verify').forEach(button => {
            button.addEventListener('click', function() {
                currentVerifyForm = this.closest('form'); // store current form
            });
        });

        document.getElementById('confirmVerifyBtn').addEventListener('click', function() {
            if (currentVerifyForm) {
                this.disabled = true; // prevent double-click
                this.innerText = '{{ __("messages.processing") }}';
                currentVerifyForm.submit();
            }
        });
    </script>
@endsection
