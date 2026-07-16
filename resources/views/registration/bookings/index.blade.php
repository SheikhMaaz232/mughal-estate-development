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

        <form method="GET" action="{{ route('bookings.bookingListing') }}">
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
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $searchParty->cnic_no ?? 'N/A' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                - {{ $searchParty->contact_number_1 ?? 'N/A' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -
                                {{ App::getLocale() === 'ur' ? $searchParty->cast->title_ur ?? '-' : $searchParty->cast->title_en ?? '-' }}
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

                <div class="col-lg-6 mb-3">
                    <label for="search">@lang('messages.case')</label>
                    <select name="case" class="form-control">
                        <option value="">@lang('messages.select_case')</option>

                        <option value="First Booking" {{ request('case') == 'First Booking' ? 'selected' : '' }}>
                            @lang('messages.first_booking')
                        </option>

                        <option value="transfer" {{ request('case') == 'transfer' ? 'selected' : '' }}>
                            @lang('messages.transfer_case')
                        </option>

                        <option value="ownership_changed" {{ request('case') == 'ownership_changed' ? 'selected' : '' }}>
                            @lang('messages.cancelled')
                        </option>
                    </select>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                </div>

                <div class="col-lg-6" style="margin-top: 25px;">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['search', 'project_id']))
                        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
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
                            <th>@lang('messages.case')</th>

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
                                    @elseif ($booking->status === 'Cancelled')
                                        @lang('messages.cancelled')
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($booking->case === 'First Booking')
                                        @lang('messages.first_booking')
                                    @elseif ($booking->case === 'transfer')
                                        @lang('messages.transfer_case')
                                    @elseif ($booking->case === 'ownership_changed')
                                        @lang('messages.ownership_changed')
                                    @endif
                                </td>
                                @if ($booking->case != 'ownership_changed')
                                    <td class="text-left">
                                        {{-- First row with edit, delete, verify --}}
                                        <div class="btn-group mb-2">
                                            @if ($booking->status !== 'Cancelled')
                                                {{-- Edit button --}}
                                                <a href="{{ route('bookings.edit', $booking->id) }}"
                                                    class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                                    data-bs-toggle="tooltip" aria-label="Edit booking"
                                                    data-bs-original-title="Edit booking">
                                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                                </a>
                                            @endif

                                            {{-- Delete button --}}
                                            @if ($booking->status === 'Unverified')
                                                <form method="POST" action="{{ route('bookings.destroy', $booking->id) }}"
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

                                            {{-- Verify button (only if unverified) --}}
                                            @if ($booking->status === 'Unverified')
                                                <form method="POST"
                                                    action="{{ route('bookings.updateStatus', $booking->id) }}"
                                                    class="d-inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if ($booking->case === 'First Booking')
                                                        <input type="hidden" name="status" value="Verified">
                                                    @else
                                                        <input type="hidden" name="status" value="Verified">
                                                        <input type="hidden" name="case" value="ownership_changed">
                                                    @endif

                                                    <button type="button"
                                                        class="btn btn-sm btn-success js-bs-tooltip-enabled btn-verify"
                                                        data-bs-toggle="modal" data-bs-target="#confirmVerifyModal"
                                                        aria-label="Verify booking"
                                                        data-bs-original-title="Verify booking">
                                                        <i class="fa fa-fw fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        @if ($booking->status !== 'Unverified')
                                            <a href="{{ route('bookings.show', $booking->id) }}"
                                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                                data-bs-toggle="tooltip" aria-label="View Booking"
                                                data-bs-original-title="View Booking">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </a>
                                        @endif

                                        {{-- Second row with your new button --}}
                                        @if ($booking->status !== 'Cancelled' && $booking->status === 'Verified')
                                            <div class="mt-2">
                                                <div class="mt-2">
                                                    <form method="GET" action="{{ route('bookingReturns.create') }}"
                                                        class="d-inline-block">
                                                        @csrf
                                                        <input type="hidden" name="booking_id"
                                                            value="{{ $booking->id }}">
                                                        <button type="button"
                                                            class="btn btn-sm btn-primary js-bs-tooltip-enabled btn-cancel"
                                                            data-bs-toggle="modal" data-bs-target="#confirmCancelModal">
                                                            @lang('messages.cancel_booking')
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                        {{-- <form method="POST" action="{{ route('bookings.scheduleCreate', $booking->id) }}"
                                        class="d-inline-block">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-sm btn-primary"
                                            style="margin-top: 5px !important;"
                                            onclick="openPreview({{ $booking->id }})">
                                            @lang('messages.schedule')
                                        </button>
                                        </form> --}}
                                        @if ($booking->status === 'Verified')
                                            <form method="POST"
                                                action="{{ route('bookings.pre-clearanceLetter', $booking->id) }}"
                                                class="d-inline-block">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    style="margin-top: 5px !important;"
                                                    onclick="openPreview({{ $booking->id }})">
                                                    @lang('messages.pre-clearanceLetter')
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('bookings.clearanceLetter', $booking->id) }}"
                                                class="d-inline-block">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    style="margin-top: 5px !important;"
                                                    onclick="openPreview({{ $booking->id }})">
                                                    @lang('messages.clearanceLetter')
                                                </button>
                                            </form>

                                            <form action="{{ route('bookings.transfer') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    style="margin-top: 5px !important;"
                                                    onclick="openPreview({{ $booking->id }})">
                                                    @lang('messages.transfer')
                                                </button>
                                            </form>
                                            {{--
                                        <form method="POST" action="{{ route('bookings.transfer', $booking->id) }}"
                                            class="d-inline-block">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-primary"
                                                style="margin-top: 5px !important;"
                                                onclick="openPreview({{ $booking->id }})">
                                                @lang('messages.transfer')
                                            </button>
                                        </form> --}}
                                            <a href="{{ route('registry-order.create', ['id' => $booking->id]) }}"
                                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled mt-2"
                                                data-bs-toggle="tooltip" aria-label="View Booking"
                                                data-bs-original-title="View Booking">
                                                @lang('messages.registry-letter')

                                            </a>

                                            <a href="{{ route('possession-letter.create', ['id' => $booking->id]) }}"
                                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled mt-2"
                                                data-bs-toggle="tooltip" aria-label="View Booking"
                                                data-bs-original-title="View Booking">
                                                @lang('messages.possession-letter')

                                            </a>
                                        @endif
                                    </td>
                                @endif

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
