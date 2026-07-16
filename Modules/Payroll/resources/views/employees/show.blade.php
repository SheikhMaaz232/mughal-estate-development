@extends('payroll::layouts.payroll')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details - {{ $employee->first_name_en }} {{ $employee->last_name_en }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .employee-profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0d6efd;
        }
        .detail-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .detail-card-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .section-title {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-primary">
                        <i class="fas fa-user-circle me-2"></i>@lang('payroll::messages.employee-details')
                    </h1>
                    <div>
                        <a href="{{ route('payroll.employees.edit', $employee) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-1"></i> @lang('payroll::messages.edit-employee')
                        </a>
                        <a href="{{ route('payroll.employees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> @lang('payroll::messages.back-to-list')
                        </a>
                    </div>
                </div>

                <!-- Employee Profile Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header detail-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i> @lang('payroll::messages.personal-information')
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-4">
                                @if($employee->profile_picture)
                                    <img src="{{ asset('storage/' . $employee->profile_picture) }}"
                                         alt="Profile Picture" class="employee-profile-img mb-3">
                                @else
                                    <div class="employee-profile-img bg-light d-flex align-items-center justify-content-center mb-3">
                                        <i class="fas fa-user fa-3x text-secondary"></i>
                                    </div>
                                @endif
                                <h4 class="mb-1">{{ $employee->first_name_en }} {{ $employee->last_name_en }}</h4>
                                <p class="text-muted">{{ $employee->designation->title_en ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.first-name') (EN):</span>
                                        <span class="info-value d-block">{{ $employee->first_name_en }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.first-name') (UR):</span>
                                        <span class="info-value d-block">{{ $employee->first_name_ur }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.last-name') (EN):</span>
                                        <span class="info-value d-block">{{ $employee->last_name_en ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.last-name') (UR):</span>
                                        <span class="info-value d-block">{{ $employee->last_name_ur ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.father-name') (EN):</span>
                                        <span class="info-value d-block">{{ $employee->father_name_en ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.father-name') (UR):</span>
                                        <span class="info-value d-block">{{ $employee->father_name_ur ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.cnic'):</span>
                                        <span class="info-value d-block">{{ $employee->cnic }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.date-of-birth'):</span>
                                        <span class="info-value d-block">{{ $employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('d/m/Y') : 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.gender'):</span>
                                        <span class="info-value d-block">
                                            @if($employee->gender == 'male') @lang('payroll::messages.male')
                                            @elseif($employee->gender == 'female') @lang('payroll::messages.female')
                                            @elseif($employee->gender == 'other') @lang('payroll::messages.other')
                                            @else N/A @endif
                                        </span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <span class="info-label">@lang('payroll::messages.marital-status'):</span>
                                        <span class="info-value d-block">
                                            @if($employee->marital_status == 'single') @lang('payroll::messages.single')
                                            @elseif($employee->marital_status == 'married') @lang('payroll::messages.married')
                                            @elseif($employee->marital_status == 'divorced') @lang('payroll::messages.divorced')
                                            @else N/A @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Details Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header detail-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-briefcase me-2"></i> @lang('payroll::messages.employment-details')
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <span class="info-label">@lang('payroll::messages.department'):</span>
                                <span class="info-value d-block">{{ $employee->department->title_en ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">@lang('payroll::messages.designation'):</span>
                                <span class="info-value d-block">{{ $employee->designation->title_en ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">@lang('payroll::messages.joining-date'):</span>
                                <span class="info-value d-block">{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">@lang('payroll::messages.basic-salary'):</span>
                                <span class="info-value d-block">{{ $employee->basic_salary ? number_format($employee->basic_salary, 2) : '0.00' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">@lang('payroll::messages.status'):</span>
                                <span class="info-value d-block">
                                    @if($employee->status == 'active')
                                        <span class="badge bg-success">@lang('payroll::messages.active')</span>
                                    @else
                                        <span class="badge bg-danger">@lang('payroll::messages.inactive')</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header detail-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-address-card me-2"></i> @lang('payroll::messages.contact-information')
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($employee->contacts->count() > 0)
                            <div class="row">
                                @foreach($employee->contacts as $contact)
                                    <div class="col-md-6 mb-4">
                                        <div class="border p-3 rounded">
                                            <h6 class="text-primary mb-3">
                                                @if($contact->type == 'personal') @lang('payroll::messages.personal')
                                                @elseif($contact->type == 'work') @lang('payroll::messages.work')
                                                @elseif($contact->type == 'emergency') @lang('payroll::messages.emergency')
                                                @elseif($contact->type == 'home') @lang('payroll::messages.home')
                                                @else {{ $contact->type }} @endif
                                            </h6>
                                            @if($contact->phone)
                                                <div class="mb-2">
                                                    <span class="info-label">@lang('payroll::messages.phone'):</span>
                                                    <span class="info-value d-block">{{ $contact->phone }}</span>
                                                </div>
                                            @endif
                                            @if($contact->email)
                                                <div class="mb-2">
                                                    <span class="info-label">@lang('payroll::messages.email'):</span>
                                                    <span class="info-value d-block">{{ $contact->email }}</span>
                                                </div>
                                            @endif
                                            @if($contact->address)
                                                <div>
                                                    <span class="info-label">@lang('payroll::messages.address'):</span>
                                                    <span class="info-value d-block">{{ $contact->address }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">@lang('payroll::messages.no-contact-info')</p>
                        @endif
                    </div>
                </div>

                <!-- Bank Information Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header detail-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-university me-2"></i> @lang('payroll::messages.bank-information')
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($employee->banks->count() > 0)
                            <div class="row">
                                @foreach($employee->banks as $bank)
                                    <div class="col-md-6 mb-4">
                                        <div class="border p-3 rounded">
                                            <h6 class="text-primary mb-3">{{ $bank->bank->name_en ?? 'N/A' }}</h6>
                                            @if($bank->account_number)
                                                <div class="mb-2">
                                                    <span class="info-label">@lang('payroll::messages.account-number'):</span>
                                                    <span class="info-value d-block">{{ $bank->account_number }}</span>
                                                </div>
                                            @endif
                                            @if($bank->account_title)
                                                <div class="mb-2">
                                                    <span class="info-label">@lang('payroll::messages.account-title'):</span>
                                                    <span class="info-value d-block">{{ $bank->account_title }}</span>
                                                </div>
                                            @endif
                                            @if($bank->iban)
                                                <div class="mb-2">
                                                    <span class="info-label">@lang('payroll::messages.iban'):</span>
                                                    <span class="info-value d-block">{{ $bank->iban }}</span>
                                                </div>
                                            @endif
                                            @if($bank->branch_code)
                                                <div class="mb-2">
                                                    <span class="info-label">@lang('payroll::messages.branch-code'):</span>
                                                    <span class="info-value d-block">{{ $bank->branch_code }}</span>
                                                </div>
                                            @endif
                                            @if($bank->type)
                                                <div>
                                                    <span class="info-label">@lang('payroll::messages.account-type'):</span>
                                                    <span class="info-value d-block">
                                                        @if($bank->type == 'savings') @lang('payroll::messages.saving')
                                                        @elseif($bank->type == 'current') @lang('payroll::messages.current')
                                                        @elseif($bank->type == 'salary') @lang('payroll::messages.salary')
                                                        @else {{ $bank->type }} @endif
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">@lang('payroll::messages.no-bank-info')</p>
                        @endif
                    </div>
                </div>

                <!-- Allowances Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header detail-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i> @lang('payroll::messages.allowances')
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($employee->allowances->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>@lang('payroll::messages.allowance-type')</th>
                                            <th>@lang('payroll::messages.amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->allowances as $allowance)
                                            <tr>
                                                <td>{{ $allowance->allowance->title_en ?? 'N/A' }}</td>
                                                <td>{{ number_format($allowance->amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">@lang('payroll::messages.no-allowances')</p>
                        @endif
                    </div>
                </div>

                <!-- Deductions Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header detail-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-minus-circle me-2"></i> @lang('payroll::messages.deductions')
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($employee->deductions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>@lang('payroll::messages.deduction-type')</th>
                                            <th>@lang('payroll::messages.amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->deductions as $deduction)
                                            <tr>
                                                <td>{{ $deduction->deduction->title_en ?? 'N/A' }}</td>
                                                <td>{{ number_format($deduction->amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">@lang('payroll::messages.no-deductions')</p>
                        @endif
                    </div>
                </div>

                <!-- Leave Balance Card -->
                <div class="card detail-card mb-4">
                    <div class="card-header detail-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-balance-scale me-2"></i> {{ __('Leave Balances') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($employee->leaveBalances->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Leave Type') }}</th>
                                            <th>{{ __('Total Days') }}</th>
                                            <th>{{ __('Used Days') }}</th>
                                            <th>{{ __('Remaining Days') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->leaveBalances as $balance)
                                            <tr>
                                                <td>{{ $balance->leaveType->title_en ?? $balance->leaveType->name ?? 'N/A' }}</td>
                                                <td>{{ $balance->total_days }}</td>
                                                <td>{{ $balance->used_days }}</td>
                                                <td>{{ max(0, $balance->total_days - $balance->used_days) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">{{ __('No leave balance information available.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
@endsection
