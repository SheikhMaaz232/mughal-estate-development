@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.bookingReturns-listing')</h2>
                </div>
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

        {{-- <form method="GET" action="{{ route('bookings.index') }}">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="main_head_id">@lang('messages.booking_date')</label>
                    <input type="text" value="{{ request('date') }}" name="date" id="date" class="form-control"
                        placeholder="@lang('messages.booking_date')">
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="project_id">@lang('messages.projects')</label>
                    <select name="project_id[]" id="project_id"
                        class="form-control form-select select2 @error('project_id') is-invalid @enderror" multiple>
                        @foreach ($projects as $projects)
                            <option value="{{ $projects->id }}"
                                {{ collect(request('project_id'))->contains($projects->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $projects->name_ur ?? '-' : $projects->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
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
            </div>
            <div class="row">
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


                <div class="col-lg-6 mb-3">
                    <label for="sub_sub_sub_head_id">@lang('messages.sub-sub-sub-heads')</label>
                    <select name="sub_sub_sub_head_id[]" id="sub_sub_sub_head_id"
                        class="form-control form-select select2 @error('sub_sub_sub_head_id') is-invalid @enderror"
                        multiple>
                        @foreach ($searchSubSubSubHeads as $subSubSubHead)
                            <option value="{{ $subSubSubHead->id }}"
                                {{ collect(request('sub_sub_head_id'))->contains($subSubSubHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $subSubSubHead->name_ur ?? '-' : $subSubSubHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="party_id">@lang('messages.main_party')</label>
                    <select name="party_id[]" id="party_id"
                        class="form-control form-select select2 @error('party_id') is-invalid @enderror" multiple>
                        @foreach ($searchParties as $searchParty)
                            <option value="{{ $searchParty->id }}"
                                {{ collect(request('party_id'))->contains($searchParty->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="search">@lang('messages.name')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search')"
                        value="{{ request('search') }}">
                </div>

                <div class="col-lg-6">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['search', 'main_head_id', 'control_head_id', 'sub_head_id', 'sub_sub_head_id']))
                        <a href="{{ route('detail-accounts.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form> --}}

        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>@lang('messages.booking_date')</th>
                            <th>@lang('messages.booking_application_no')</th>
                            <th>@lang('messages.main_party')</th>
                            <th>@lang('messages.projects')</th>
                            <th>@lang('messages.products')</th>
                            <th>@lang('messages.unit_no')</th>
                            <th>@lang('messages.status')</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookingReturns as $index => $bookingReturn)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($bookingReturn->date)->format('d-m-Y') }}</td>
                                <td>{{ $bookingReturn->bookingApplication->form_no }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $bookingReturn->bookingApplication->party->name_ur ?? '-' : $bookingReturn->bookingApplication->party->name_en ?? '-' }}({{ $bookingReturn->bookingApplication->party->cnic_no ?? 'N/A' }})
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $bookingReturn->bookingApplication->project->name_ur ?? '-' : $bookingReturn->bookingApplication->project->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $bookingReturn->bookingApplication->product->name_ur ?? '-' : $bookingReturn->bookingApplication->product->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $bookingReturn->bookingApplication->product->unit_no ?? '-' : $bookingReturn->bookingApplication->product->unit_no ?? '-' }}
                                </td>
                                <td>
                                    @if ($bookingReturn->status === 'Unverified')
                                        @lang('messages.unverified')
                                    @elseif ($bookingReturn->status === 'Verified')
                                        @lang('messages.verified')
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($bookingReturn->status === 'Unverified')
                                        <form method="POST"
                                            action="{{ route('bookingReturns.updateStatus', $bookingReturn->id) }}"
                                            class="d-inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Verified">
                                            <input type="hidden" name="booking_id"
                                                value="{{ $bookingReturn->booking_id }}">
                                            <button type="button"
                                                class="btn btn-sm btn-success js-bs-tooltip-enabled btn-verify"
                                                data-bs-toggle="modal" data-bs-target="#confirmVerifyModal"
                                                aria-label="Verify booking" data-bs-original-title="Verify booking Return">
                                                <i class="fa fa-fw fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ($bookingReturn->status === 'Unverified')
                                        <a href="{{ route('bookingReturns.show', $bookingReturn->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View Booking"
                                            data-bs-original-title="View Booking">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('bookingReturns.edit', $bookingReturn->id) }}"
                                        class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip"
                                        aria-label="Edit booking" data-bs-original-title="Edit booking">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    {{-- @endif --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                                <p>@lang('messages.verify_confirmation_text_booking_return')</p>
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
                    {{ $bookingReturns->links() }}
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

        // On confirm, submit form
        document.getElementById('confirmVerifyBtn').addEventListener('click', function() {
            if (currentVerifyForm) {
                currentVerifyForm.submit();
            }
        });
    </script>
@endsection
