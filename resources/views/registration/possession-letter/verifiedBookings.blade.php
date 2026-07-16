@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.bookings')</h2>
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
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="GET" action="{{ route('possession-letter.bookingListing') }}">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="main_head_id">@lang('messages.booking_date')</label>
                    <input type="date" value="{{ request('date') }}" name="date" id="date" class="form-control"
                        placeholder="@lang('messages.booking_date')">
                </div>

                <div class="col-lg-6">
                    <label for="search">@lang('messages.booking_application_no')</label>
                    <input type="number" step="any" min="0" onwheel="this.blur()" class="form-control"
                        name="booking_application_no" placeholder="@lang('messages.booking_application_no')"
                        value="{{ request('booking_application_no') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="party_id">@lang('messages.party')</label>
                    <select name="party_id" id="party_id"
                        class="form-control form-select select2 @error('party_id') is-invalid @enderror">
                        <option value="">@lang('messages.select-an-option')</option>
                        @foreach ($searchParties as $searchParty)
                            <option value="{{ $searchParty->id }}"
                                {{ collect(request('party_id'))->contains($searchParty->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                                &nbsp; -
                                &nbsp;{{ $searchParty->cnic_no ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6">
                    <label for="project_id">@lang('messages.projects')</label>
                    <select name="project_id" id="project_id"
                        class="form-control form-select select2 @error('project_id') is-invalid @enderror" multiple>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ collect(request('project_id'))->contains($project->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
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

                    @if (request()->hasAny(['search', 'project_id']))
                        <a href="{{ route('possession-letter.bookingListing') }}"
                            class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>

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
                        @foreach ($bookings as $index => $booking)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</td>
                                <td>{{ $booking->form_no }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $booking->party->name_ur ?? '-' : $booking->party->name_en ?? '-' }}({{ $booking->party->cnic_no ?? 'N/A' }})
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $booking->project->name_ur ?? '-' : $booking->project->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $booking->product->name_ur ?? '-' : $booking->product->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $booking->product->unit_no ?? '-' : $booking->product->unit_no ?? '-' }}
                                </td>
                                <td>
                                    @if ($booking->status === 'Unverified')
                                        @lang('messages.unverified')
                                    @elseif ($booking->status === 'Verified')
                                        @lang('messages.verified')
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-left">

                                    <a href="{{ route('possession-letter.create', ['id' => $booking->id]) }}"
                                        class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip"
                                        aria-label="View Booking" data-bs-original-title="View Booking"><i
                                            class="fa fa-file-alt me-1"></i> @lang('messages.possession-letter')

                                    </a>

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
                                <p>@lang('messages.verify_confirmation_text_booking')</p>
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

                {{-- Cancel Confirmation Modal --}}
                <div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmCancelModalLabel">@lang('messages.cancel_booking')</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="@lang('messages.close')"></button>
                            </div>
                            <div class="modal-body">
                                @lang('messages.are_you_sure_cancel')
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">@lang('messages.no')</button>
                                <button type="button" class="btn btn-danger"
                                    id="confirmCancelButton">@lang('messages.yes_cancel')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $bookings->links() }}
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
                this.innerText = '{{ __('messages.processing') }}';
                currentVerifyForm.submit();
            }
        });
    </script>
    <script>
        let currentCancelForm;

        document.querySelectorAll('.btn-cancel').forEach(button => {
            button.addEventListener('click', function() {
                currentCancelForm = this.closest('form'); // store form reference
            });
        });

        document.getElementById('confirmCancelButton').addEventListener('click', function() {
            if (currentCancelForm) {
                currentCancelForm.submit();
            }
        });
    </script>
@endsection
