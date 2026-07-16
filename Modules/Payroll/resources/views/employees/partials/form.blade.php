<style>
    .accordion-button:not(.collapsed) {
        background-color: #e3f2fd;
        color: #0c63e4;
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .125);
    }

    .accordion-button:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .required-field::after {
        content: " *";
        color: red;
    }

    .section-icon {
        margin-right: 10px;
        font-size: 1.1rem;
    }

    .accordion-body {
        background-color: #f8f9fa;
    }

    .dynamic-section {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: white;
        position: relative;
    }

    .remove-section {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        color: #dc3545;
    }

    .add-section-btn {
        margin-top: 10px;
    }

    .card-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        @if (isset($employee) && $employee->id)
                            @lang('payroll::messages.edit-employee')
                        @else
                            @lang('payroll::messages.add-employee')
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="employeeFormAccordion">
                        <!-- Personal Information Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="personalInfoHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#personalInfoCollapse" aria-expanded="true"
                                    aria-controls="personalInfoCollapse">
                                    <i class="fas fa-user section-icon"></i> @lang('payroll::messages.personal-information')
                                </button>
                            </h2>
                            <div id="personalInfoCollapse" class="accordion-collapse collapse show"
                                aria-labelledby="personalInfoHeading" data-bs-parent="#employeeFormAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name_en" class="required-field">@lang('payroll::messages.first-name')
                                                (EN)</label>
                                            <input type="text" name="first_name_en" class="form-control"
                                                value="{{ old('first_name_en', $employee->first_name_en ?? '') }}">
                                            @error('first_name_en')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name_ur" class="required-field">@lang('payroll::messages.first-name')
                                                (اردو)</label>
                                            <input type="text" name="first_name_ur"
                                                class="form-control keyboardInput"
                                                value="{{ old('first_name_ur', $employee->first_name_ur ?? '') }}">
                                            @error('first_name_ur')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="last_name_en">@lang('payroll::messages.last-name') (EN)</label>
                                            <input type="text" name="last_name_en" class="form-control"
                                                value="{{ old('last_name_en', $employee->last_name_en ?? '') }}">
                                            @error('last_name_en')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="last_name_ur">@lang('payroll::messages.last-name') (اردو)</label>
                                            <input type="text" name="last_name_ur" class="form-control keyboardInput"
                                                value="{{ old('last_name_en', $employee->last_name_ur ?? '') }}">
                                            @error('last_name_ur')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="father_name_en">@lang('payroll::messages.father-name') (EN)</label>
                                            <input type="text" name="father_name_en" class="form-control"
                                                value="{{ old('father_name_en', $employee->father_name_en ?? '') }}">
                                            @error('father_name_en')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="father_name_ur">@lang('payroll::messages.father-name') (اردو)</label>
                                            <input type="text" name="father_name_ur"
                                                class="form-control keyboardInput"
                                                value="{{ old('father_name_ur', $employee->father_name_ur ?? '') }}">
                                            @error('father_name_ur')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="cnic" class="required-field">@lang('payroll::messages.cnic')</label>
                                            <input type="text" name="cnic" id="cnic" maxlength="15"
                                                class="form-control" placeholder="XXXXX-XXXXXXX-X"
                                                value="{{ old('cnic', $employee->cnic ?? '') }}">
                                            @error('cnic')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="dob">@lang('payroll::messages.date-of-birth')</label>
                                            <input type="date" name="dob" class="form-control"
                                                value="{{ old('dob', $employee->dob ?? '') }}">
                                            @error('dob')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="gender" class="required-field">@lang('payroll::messages.gender')</label>
                                            <select name="gender" class="form-select" id="gender">
                                                <option value="">@lang('payroll::messages.select-gender')</option>
                                                <option value="male" @selected(old('gender', $employee->gender ?? '') == 'male')>
                                                    @lang('payroll::messages.male')</option>
                                                <option value="female" @selected(old('gender', $employee->gender ?? '') == 'female')>
                                                    @lang('payroll::messages.female')</option>
                                                <option value="other" @selected(old('gender', $employee->gender ?? '') == 'other')>
                                                    @lang('payroll::messages.other')</option>
                                            </select>
                                            @error('gender')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="marital_status">@lang('payroll::messages.marital-status')</label>
                                            <select name="marital_status" class="form-select" id="marital_status">
                                                <option value="">@lang('payroll::messages.select-status')</option>
                                                <option value="single" @selected(old('marital_status', $employee->marital_status ?? '') == 'single')>
                                                    @lang('payroll::messages.single')</option>
                                                <option value="married" @selected(old('marital_status', $employee->marital_status ?? '') == 'married')>
                                                    @lang('payroll::messages.married')</option>
                                                <option value="divorced" @selected(old('marital_status', $employee->marital_status ?? '') == 'divorced')>
                                                    @lang('payroll::messages.divorced')</option>
                                            </select>
                                            @error('marital_status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="profile_picture">@lang('payroll::messages.profile-picture')</label>
                                            <input type="file" name="profile_picture" class="form-control">
                                            @error('profile_picture')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @if (isset($employee) && $employee->profile_picture)
                                                <img src="{{ asset('storage/' . $employee->profile_picture) }}"
                                                    width="50" class="mt-2">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Details Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="employmentDetailsHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#employmentDetailsCollapse" aria-expanded="false"
                                    aria-controls="employmentDetailsCollapse">
                                    <i class="fas fa-briefcase section-icon"></i> @lang('payroll::messages.employment-details')
                                </button>
                            </h2>
                            <div id="employmentDetailsCollapse" class="accordion-collapse collapse"
                                aria-labelledby="employmentDetailsHeading" data-bs-parent="#employeeFormAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="shift_id" class="form-label">@lang('payroll::messages.shifts')</label>
                                            <select name="shift_id"
                                                class="form-select @error('shift_id') is-invalid @enderror">
                                                <option value="">@lang('payroll::messages.select-shift')</option>
                                                @foreach (\Modules\Payroll\App\Models\Shift::all() as $shift)
                                                    <option value="{{ $shift->id }}" @selected(old('shift_id', $employee->shift_id ?? '') == $shift->id)>
                                                        {{ $shift->{'shift_name_' . app()->getLocale()} }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shift_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="device_id" class="form-label">@lang('payroll::messages.devices')</label>
                                            <select name="device_id"
                                                class="form-select @error('device_id') is-invalid @enderror">
                                                <option value="">@lang('payroll::messages.select-device')</option>
                                                @foreach (\Modules\Payroll\App\Models\AttendanceDevice::all() as $device)
                                                    <option value="{{ $device->id }}" @selected(old('device_id', $employee->device_id ?? '') == $device->id)>
                                                        {{ $device->{'name_' . app()->getLocale()} }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('device_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="department_id" class="form-label">@lang('payroll::messages.department')</label>
                                            <select name="department_id"
                                                class="form-select @error('department_id') is-invalid @enderror">
                                                <option value="">@lang('payroll::messages.select-department')</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        @selected(old('department_id', $employee->department_id ?? '') == $department->id)>
                                                        {{ $department->{'title_' . app()->getLocale()} }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="designation_id" class="form-label">@lang('payroll::messages.designation')</label>
                                            <select name="designation_id"
                                                class="form-select @error('designation_id') is-invalid @enderror">
                                                <option value="">@lang('payroll::messages.select-designation')</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}"
                                                        @selected(old('designation_id', $employee->designation_id ?? '') == $designation->id)>
                                                        {{ $designation->{'title_' . app()->getLocale()} }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('designation_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="joining_date" class="form-label">@lang('payroll::messages.joining-date')</label>
                                            <input type="date" name="joining_date"
                                                class="form-control @error('joining_date') is-invalid @enderror"
                                                value="{{ old('joining_date', $employee->joining_date ?? '') }}">
                                            @error('joining_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="basic_salary" class="form-label">@lang('payroll::messages.basic-salary')</label>
                                            <input type="number" step="0.01" name="basic_salary"
                                                class="form-control @error('basic_salary') is-invalid @enderror"
                                                value="{{ old('basic_salary', $employee->basic_salary ?? 0) }}">
                                            @error('basic_salary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3 mt-4">
                                            <div class="form-check">
                                                <input type="checkbox" name="status"
                                                    class="form-check-input @error('status') is-invalid @enderror"
                                                    value="active" @checked(old('status', $employee->status ?? 'active') == 'active')>
                                                <label class="form-check-label">@lang('payroll::messages.active-employee')</label>
                                            </div>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="contactInfoHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#contactInfoCollapse" aria-expanded="false"
                                    aria-controls="contactInfoCollapse">
                                    <i class="fas fa-address-card section-icon"></i> @lang('payroll::messages.contact-information')
                                </button>
                            </h2>
                            <div id="contactInfoCollapse" class="accordion-collapse collapse"
                                aria-labelledby="contactInfoHeading" data-bs-parent="#employeeFormAccordion">
                                <div class="accordion-body">
                                    <div id="contact-sections">
                                        <!-- Initial contact section -->
                                        @php
                                            $contactIndex = 0;
                                            $contactData = old(
                                                'contacts',
                                                isset($employee) && $employee->contacts
                                                    ? $employee->contacts->toArray()
                                                    : [],
                                            );
                                            if (empty($contactData)) {
                                                $contactData = [
                                                    [
                                                        'type' => '',
                                                        'phone' => '',
                                                        'email' => '',
                                                        'address' => '',
                                                    ],
                                                ];
                                            }
                                        @endphp

                                        @foreach ($contactData as $index => $contact)
                                            <div class="dynamic-section contact-section">
                                                @if ($index > 0)
                                                    <span class="remove-section" onclick="removeSection(this)"><i
                                                            class="fas fa-times-circle"></i></span>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.contact-type')</label>
                                                        <select name="contacts[{{ $index }}][type]"
                                                            class="form-select @error('contacts.' . $index . '.type') is-invalid @enderror">
                                                            <option value="">@lang('payroll::messages.select-contact-type')</option>
                                                            <option value="personal"
                                                                {{ old('contacts.' . $index . '.type', $contact['type'] ?? '') == 'personal' ? 'selected' : '' }}>
                                                                @lang('payroll::messages.personal')
                                                            </option>
                                                            <option value="work"
                                                                {{ old('contacts.' . $index . '.type', $contact['type'] ?? '') == 'work' ? 'selected' : '' }}>
                                                                @lang('payroll::messages.work')
                                                            </option>
                                                            <option value="emergency"
                                                                {{ old('contacts.' . $index . '.type', $contact['type'] ?? '') == 'emergency' ? 'selected' : '' }}>
                                                                @lang('payroll::messages.emergency')
                                                            </option>
                                                            <option value="home"
                                                                {{ old('contacts.' . $index . '.type', $contact['type'] ?? '') == 'home' ? 'selected' : '' }}>
                                                                @lang('payroll::messages.home')
                                                            </option>
                                                        </select>
                                                        @error('contacts.' . $index . '.type')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.phone-number')</label>
                                                        <input type="text"
                                                            name="contacts[{{ $index }}][phone]"
                                                            value="{{ old('contacts.' . $index . '.phone', $contact['phone'] ?? '') }}"
                                                            class="form-control @error('contacts.' . $index . '.phone') is-invalid @enderror">
                                                        @error('contacts.' . $index . '.phone')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.email')</label>
                                                        <input type="email"
                                                            name="contacts[{{ $index }}][email]"
                                                            value="{{ old('contacts.' . $index . '.email', $contact['email'] ?? '') }}"
                                                            class="form-control @error('contacts.' . $index . '.email') is-invalid @enderror">
                                                        @error('contacts.' . $index . '.email')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label>@lang('payroll::messages.address')</label>
                                                        <textarea name="contacts[{{ $index }}][address]"
                                                            class="form-control @error('contacts.' . $index . '.address') is-invalid @enderror" rows="2">{{ old('contacts.' . $index . '.address', $contact['address'] ?? '') }}</textarea>
                                                        @error('contacts.' . $index . '.address')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            @php $contactIndex = $index; @endphp
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary add-section-btn"
                                        onclick="addContactSection()">
                                        <i class="fas fa-plus me-1"></i> @lang('payroll::messages.add-more')
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="bankInfoHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#bankInfoCollapse" aria-expanded="false"
                                    aria-controls="bankInfoCollapse">
                                    <i class="fas fa-university section-icon"></i> @lang('payroll::messages.bank-information')
                                </button>
                            </h2>
                            <div id="bankInfoCollapse" class="accordion-collapse collapse"
                                aria-labelledby="bankInfoHeading" data-bs-parent="#employeeFormAccordion">
                                <div class="accordion-body">
                                    <div id="bank-sections">
                                        <!-- Initial bank section -->
                                        @php
                                            $bankIndex = 0;
                                            $bankData = old(
                                                'banks',
                                                isset($employee) && $employee->banks ? $employee->banks->toArray() : [],
                                            );
                                            if (empty($bankData)) {
                                                $bankData = [
                                                    [
                                                        'bank_id' => '',
                                                        'account_number' => '',
                                                        'account_title' => '',
                                                        'iban' => '',
                                                        'branch_code' => '',
                                                        'type' => '',
                                                    ],
                                                ];
                                            }
                                        @endphp

                                        @foreach ($bankData as $index => $bank)
                                            <div class="dynamic-section bank-section">
                                                @if ($index > 0)
                                                    <span class="remove-section" onclick="removeSection(this)"><i
                                                            class="fas fa-times-circle"></i></span>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.bank-name')</label>
                                                        <select name="banks[{{ $index }}][bank_id]"
                                                            class="form-select bank-select @error('banks.' . $index . '.bank_id') is-invalid @enderror">
                                                            <option value="">@lang('payroll::messages.select-bank')</option>
                                                            @foreach ($banks as $bankOption)
                                                                <option value="{{ $bankOption->id }}"
                                                                    @selected(old('banks.' . $index . '.bank_id', $bank['bank_id'] ?? '') == $bankOption->id)>
                                                                    {{ $bankOption->{'name_' . app()->getLocale()} }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('banks.' . $index . '.bank_id')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.account-number')</label>
                                                        <input type="text"
                                                            name="banks[{{ $index }}][account_number]"
                                                            class="form-control @error('banks.' . $index . '.account_number') is-invalid @enderror"
                                                            value="{{ old('banks.' . $index . '.account_number', $bank['account_number'] ?? '') }}">
                                                        @error('banks.' . $index . '.account_number')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.account-title')</label>
                                                        <input type="text"
                                                            name="banks[{{ $index }}][account_title]"
                                                            class="form-control @error('banks.' . $index . '.account_title') is-invalid @enderror"
                                                            value="{{ old('banks.' . $index . '.account_title', $bank['account_title'] ?? '') }}">
                                                        @error('banks.' . $index . '.account_title')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.iban')</label>
                                                        <input type="text" name="banks[{{ $index }}][iban]"
                                                            class="form-control @error('banks.' . $index . '.iban') is-invalid @enderror"
                                                            value="{{ old('banks.' . $index . '.iban', $bank['iban'] ?? '') }}">
                                                        @error('banks.' . $index . '.iban')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.branch-code')</label>
                                                        <input type="text"
                                                            name="banks[{{ $index }}][branch_code]"
                                                            class="form-control @error('banks.' . $index . '.branch_code') is-invalid @enderror"
                                                            value="{{ old('banks.' . $index . '.branch_code', $bank['branch_code'] ?? '') }}">
                                                        @error('banks.' . $index . '.branch_code')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label>@lang('payroll::messages.account-type')</label>
                                                        <select name="banks[{{ $index }}][type]"
                                                            class="form-select @error('banks.' . $index . '.type') is-invalid @enderror">
                                                            <option value="">@lang('payroll::messages.select-account-type')</option>
                                                            <option value="savings" @selected(old('banks.' . $index . '.type', $bank['type'] ?? '') == 'savings')>
                                                                @lang('payroll::messages.saving')
                                                            </option>
                                                            <option value="current" @selected(old('banks.' . $index . '.type', $bank['type'] ?? '') == 'current')>
                                                                @lang('payroll::messages.current')
                                                            </option>
                                                            <option value="salary" @selected(old('banks.' . $index . '.type', $bank['type'] ?? '') == 'salary')>
                                                                @lang('payroll::messages.salary')
                                                            </option>
                                                        </select>
                                                        @error('banks.' . $index . '.type')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            @php $bankIndex = $index; @endphp
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary add-section-btn"
                                        onclick="addBankSection()">
                                        <i class="fas fa-plus me-1"></i> @lang('payroll::messages.add-more')
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="allowanceHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#allowanceCollapse" aria-expanded="false"
                                    aria-controls="allowanceCollapse">
                                    <i class="fas fa-plus-circle section-icon"></i> @lang('payroll::messages.allowances')
                                </button>
                            </h2>
                            <div id="allowanceCollapse" class="accordion-collapse collapse"
                                aria-labelledby="allowanceHeading" data-bs-parent="#employeeFormAccordion">
                                <div class="accordion-body">
                                    <div id="allowance-sections">
                                        <!-- Initial Allowance Section -->
                                        @php
                                            $allowanceIndex = 0;
                                            $allowanceData = old(
                                                'allowances',
                                                isset($employee) && $employee->allowances
                                                    ? $employee->allowances->toArray()
                                                    : [],
                                            );
                                            if (empty($allowanceData)) {
                                                $allowanceData = [['allowance_id' => '', 'amount' => '']];
                                            }
                                        @endphp

                                        @foreach ($allowanceData as $index => $allowance)
                                            <div class="dynamic-section allowance-section">
                                                @if ($index > 0)
                                                    <span class="remove-section" onclick="removeSection(this)"><i
                                                            class="fas fa-times-circle"></i></span>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>@lang('payroll::messages.allowance-type')</label>
                                                        <select name="allowances[{{ $index }}][allowance_id]"
                                                            class="form-select allowance-select @error('allowances.' . $index . '.allowance_id') is-invalid @enderror">
                                                            <option value="">@lang('payroll::messages.allowance-type')</option>
                                                            @foreach ($allowances as $allowanceOption)
                                                                <option value="{{ $allowanceOption->id }}"
                                                                    @selected(old('allowances.' . $index . '.allowance_id', $allowance['allowance_id'] ?? '') == $allowanceOption->id)>
                                                                    {{ $allowanceOption->{'title_' . app()->getLocale()} }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('allowances.' . $index . '.allowance_id')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>@lang('payroll::messages.amount')</label>
                                                        <input type="number" step="0.01"
                                                            name="allowances[{{ $index }}][amount]"
                                                            class="form-control @error('allowances.' . $index . '.amount') is-invalid @enderror"
                                                            value="{{ old('allowances.' . $index . '.amount', $allowance['amount'] ?? '') }}">
                                                        @error('allowances.' . $index . '.amount')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            @php $allowanceIndex = $index; @endphp
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary add-section-btn"
                                        onclick="addAllowanceSection()">
                                        <i class="fas fa-plus me-1"></i> @lang('payroll::messages.add-more')
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Balances Accordion -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="leaveBalanceHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#leaveBalanceCollapse" aria-expanded="false"
                                    aria-controls="leaveBalanceCollapse">
                                    <i class="fas fa-balance-scale section-icon"></i> @lang('payroll::messages.Leave-Balances')
                                </button>
                            </h2>
                            <div id="leaveBalanceCollapse" class="accordion-collapse collapse"
                                aria-labelledby="leaveBalanceHeading" data-bs-parent="#employeeFormAccordion">
                                <div class="accordion-body">
                                    @php
                                        $leaveBalanceData = collect(
                                            old(
                                                'leave_balances',
                                                isset($employee) && $employee->leaveBalances
                                                    ? $employee->leaveBalances->toArray()
                                                    : [],
                                            ),
                                        )
                                            ->keyBy('leave_type_id')
                                            ->toArray();
                                    @endphp
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>@lang('payroll::messages.leave-type')</th>
                                                    <th>@lang('payroll::messages.total-days')</th>
                                                    <th>@lang('payroll::messages.used-days')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($leaveTypes as $index => $leaveType)
                                                    @php
                                                        $balance = $leaveBalanceData[$leaveType->id] ?? [];
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $leaveType->{'title_' . app()->getLocale()} ?? ($leaveType->name ?? 'N/A') }}
                                                            <input type="hidden"
                                                                name="leave_balances[{{ $index }}][leave_type_id]"
                                                                value="{{ $leaveType->id }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0"
                                                                name="leave_balances[{{ $index }}][total_days]"
                                                                class="form-control"
                                                                value="{{ old('leave_balances.' . $index . '.total_days', $balance['total_days'] ?? '') }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0"
                                                                name="leave_balances[{{ $index }}][used_days]"
                                                                class="form-control"
                                                                value="{{ old('leave_balances.' . $index . '.used_days', $balance['used_days'] ?? '') }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deductions Accordion -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="deductionHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#deductionCollapse" aria-expanded="false"
                                    aria-controls="deductionCollapse">
                                    <i class="fas fa-minus-circle section-icon"></i> @lang('payroll::messages.deductions')
                                </button>
                            </h2>
                            <div id="deductionCollapse" class="accordion-collapse collapse"
                                aria-labelledby="deductionHeading" data-bs-parent="#employeeFormAccordion">
                                <div class="accordion-body">
                                    <div id="deduction-sections">
                                        <!-- Initial Deduction Section -->
                                        @php
                                            $deductionIndex = 0;
                                            $deductionData = old(
                                                'deductions',
                                                isset($employee) && $employee->deductions
                                                    ? $employee->deductions->toArray()
                                                    : [],
                                            );
                                            if (empty($deductionData)) {
                                                $deductionData = [['deduction_id' => '', 'amount' => '']];
                                            }
                                        @endphp

                                        @foreach ($deductionData as $index => $deduction)
                                            <div class="dynamic-section deduction-section">
                                                @if ($index > 0)
                                                    <span class="remove-section" onclick="removeSection(this)"><i
                                                            class="fas fa-times-circle"></i></span>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>@lang('payroll::messages.deduction-type')</label>
                                                        <select name="deductions[{{ $index }}][deduction_id]"
                                                            class="form-select deduction-select @error('deductions.' . $index . '.deduction_id') is-invalid @enderror">
                                                            <option value="">@lang('payroll::messages.deduction-type')</option>
                                                            @foreach ($deductions as $deductionOption)
                                                                <option value="{{ $deductionOption->id }}"
                                                                    @selected(old('deductions.' . $index . '.deduction_id', $deduction['deduction_id'] ?? '') == $deductionOption->id)>
                                                                    {{ $deductionOption->{'title_' . app()->getLocale()} }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('deductions.' . $index . '.deduction_id')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>@lang('payroll::messages.amount')</label>
                                                        <input type="number" step="0.01"
                                                            name="deductions[{{ $index }}][amount]"
                                                            class="form-control @error('deductions.' . $index . '.amount') is-invalid @enderror"
                                                            value="{{ old('deductions.' . $index . '.amount', $deduction['amount'] ?? '') }}">
                                                        @error('deductions.' . $index . '.amount')
                                                            <div class="invalid-feedback">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            @php $deductionIndex = $index; @endphp
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary add-section-btn"
                                        onclick="addDeductionSection()">
                                        <i class="fas fa-plus me-1"></i> @lang('payroll::messages.add-more')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-sm btn-primary">
                            @if (isset($employee) && $employee->id)
                                @lang('messages.update')
                            @else
                                @lang('messages.save')
                            @endif
                        </button>
                        <a href="{{ route(name: 'payroll.employees.index') }}"
                            class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Store the dynamic data from server in JavaScript variables
    const banksData = {!! json_encode($banks) !!};
    const allowancesData = {!! json_encode($allowances) !!};
    const deductionsData = {!! json_encode($deductions) !!};
    const currentLocale = '{{ app()->getLocale() }}';

    // Counters for each section type - start from the number of existing sections
    let contactCounter = {{ $contactIndex + 1 }};
    let bankCounter = {{ $bankIndex + 1 }};
    let allowanceCounter = {{ $allowanceIndex + 1 }};
    let deductionCounter = {{ $deductionIndex + 1 }};

    // Function to add a new contact section
    function addContactSection(data = {}) {
        const contactSections = document.getElementById('contact-sections');
        const newSection = document.createElement('div');
        newSection.className = 'dynamic-section contact-section';

        const type = data.type || '';
        const phone = data.phone || '';
        const email = data.email || '';
        const address = data.address || '';

        newSection.innerHTML = `
        <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.contact-type')</label>
                <select name="contacts[${contactCounter}][type]" class="form-select">
                    <option value="">@lang('payroll::messages.select-contact-type')</option>
                    <option value="personal" ${type === 'personal' ? 'selected' : ''}>@lang('payroll::messages.personal')</option>
                    <option value="work" ${type === 'work' ? 'selected' : ''}>@lang('payroll::messages.work')</option>
                    <option value="emergency" ${type === 'emergency' ? 'selected' : ''}>@lang('payroll::messages.emergency')</option>
                    <option value="home" ${type === 'home' ? 'selected' : ''}>@lang('payroll::messages.home')</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.phone-number')</label>
                <input type="text" name="contacts[${contactCounter}][phone]" class="form-control" value="${phone}">
            </div>
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.email')</label>
                <input type="email" name="contacts[${contactCounter}][email]" class="form-control" value="${email}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label>@lang('payroll::messages.address')</label>
                <textarea name="contacts[${contactCounter}][address]" class="form-control" rows="2">${address}</textarea>
            </div>
        </div>
    `;
        contactSections.appendChild(newSection);
        contactCounter++;
    }

    // Add Bank Section
    function addBankSection(data = {}) {
        const bankSections = document.getElementById('bank-sections');
        const newSection = document.createElement('div');
        newSection.className = 'dynamic-section bank-section';

        const bankId = data.bank_id || '';
        const accountNumber = data.account_number || '';
        const accountTitle = data.account_title || '';
        const iban = data.iban || '';
        const branchCode = data.branch_code || '';
        const type = data.type || '';

        let bankOptions = '<option value="">@lang('payroll::messages.select-bank')</option>';
        banksData.forEach(bank => {
            const selected = bank.id == bankId ? 'selected' : '';
            bankOptions += `<option value="${bank.id}" ${selected}>${bank['name_' + currentLocale]}</option>`;
        });

        newSection.innerHTML = `
        <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.bank-name')</label>
                <select name="banks[${bankCounter}][bank_id]" class="form-select bank-select">
                    ${bankOptions}
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.account-number')</label>
                <input type="text" name="banks[${bankCounter}][account_number]" class="form-control" value="${accountNumber}">
            </div>
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.account-title')</label>
                <input type="text" name="banks[${bankCounter}][account_title]" class="form-control" value="${accountTitle}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.iban')</label>
                <input type="text" name="banks[${bankCounter}][iban]" class="form-control" value="${iban}">
            </div>
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.branch-code')</label>
                <input type="text" name="banks[${bankCounter}][branch_code]" class="form-control" value="${branchCode}">
            </div>
            <div class="col-md-4 mb-3">
                <label>@lang('payroll::messages.account-type')</label>
                <select name="banks[${bankCounter}][type]" class="form-select">
                    <option value="">@lang('payroll::messages.select-account-type')</option>
                    <option value="savings" ${type === 'savings' ? 'selected' : ''}>@lang('payroll::messages.saving')</option>
                    <option value="current" ${type === 'current' ? 'selected' : ''}>@lang('payroll::messages.current')</option>
                    <option value="salary" ${type === 'salary' ? 'selected' : ''}>@lang('payroll::messages.salary')</option>
                </select>
            </div>
        </div>
    `;
        bankSections.appendChild(newSection);
        bankCounter++;
    }

    // Add Allowance Section
    function addAllowanceSection(data = {}) {
        const allowanceSections = document.getElementById('allowance-sections');
        const newSection = document.createElement('div');
        newSection.className = 'dynamic-section allowance-section';

        const allowanceId = data.allowance_id || '';
        const amount = data.amount || '';

        let allowanceOptions = '<option value="">@lang('payroll::messages.allowance-type')</option>';
        allowancesData.forEach(allowance => {
            const selected = allowance.id == allowanceId ? 'selected' : '';
            allowanceOptions +=
                `<option value="${allowance.id}" ${selected}>${allowance['title_' + currentLocale]}</option>`;
        });

        newSection.innerHTML = `
        <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>@lang('payroll::messages.allowance-type')</label>
                <select name="allowances[${allowanceCounter}][allowance_id]" class="form-select allowance-select">
                    ${allowanceOptions}
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>@lang('payroll::messages.amount')</label>
                <input type="number" step="0.01" name="allowances[${allowanceCounter}][amount]" class="form-control" value="${amount}">
            </div>
        </div>
    `;
        allowanceSections.appendChild(newSection);
        allowanceCounter++;
    }

    // Add Deduction Section
    function addDeductionSection(data = {}) {
        const deductionSections = document.getElementById('deduction-sections');
        const newSection = document.createElement('div');
        newSection.className = 'dynamic-section deduction-section';

        const deductionId = data.deduction_id || '';
        const amount = data.amount || '';

        let deductionOptions = '<option value="">@lang('payroll::messages.deduction-type')</option>';
        deductionsData.forEach(deduction => {
            const selected = deduction.id == deductionId ? 'selected' : '';
            deductionOptions +=
                `<option value="${deduction.id}" ${selected}>${deduction['title_' + currentLocale]}</option>`;
        });

        newSection.innerHTML = `
        <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>@lang('payroll::messages.deduction-type')</label>
                <select name="deductions[${deductionCounter}][deduction_id]" class="form-select deduction-select">
                    ${deductionOptions}
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>@lang('payroll::messages.amount')</label>
                <input type="number" step="0.01" name="deductions[${deductionCounter}][amount]" class="form-control" value="${amount}">
            </div>
        </div>
    `;
        deductionSections.appendChild(newSection);
        deductionCounter++;
    }


    // Function to remove a section
    function removeSection(element) {
        const section = element.closest('.dynamic-section');
        section.remove();
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
