@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i> Company Details
            </h3>
            <div class="block-options">
                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i> Edit
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <!-- Company Information Card -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-info-circle text-primary me-1"></i> Basic Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-striped">
                                <tbody>
                                    <tr>
                                        <th width="30%">Group</th>
                                        <td>{{ $company->group->name_eng ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Company Code</th>
                                        <td>{{ $company->company_code ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name (English)</th>
                                        <td>{{ $company->name_en ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name (Urdu)</th>
                                        <td dir="rtl" class="text-end">{{ $company->name_ur ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Logo Display Card -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-image text-primary me-1"></i> Company Logo
                            </h4>
                        </div>
                        <div class="card-body text-center">
                            @if ($company->image)
                                <img src="{{ asset('storage/' . $company->image) }}" alt="Company Logo"
                                    class="img-fluid rounded border" style="max-height: 180px; max-width: 100%;">
                            @else
                                <div class="py-4">
                                    <i class="fa fa-building fa-4x text-muted mb-3"></i>
                                    <p class="text-muted">No logo available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description & Address Card -->
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-align-left text-primary me-1"></i> Additional Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Description (English)</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $company->description_en ?? 'No description available' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3 text-end" dir="rtl">تفصیل</h5>
                                    <div class="p-3 bg-light rounded text-end" dir="rtl">
                                        {{ $company->description_ur ?? 'تفصیل دستیاب نہیں' }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Address (English)</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $company->address_en ?? 'No address available' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3 text-end" dir="rtl">ایڈریس</h5>
                                    <div class="p-3 bg-light rounded text-end" dir="rtl">
                                        {{ $company->address_ur ?? 'ایڈریس دستیاب نہیں' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('companies.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back to Companies
                </a>
            </div>
        </div>
    </div>
@endsection
