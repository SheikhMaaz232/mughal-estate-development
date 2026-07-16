    @extends('layouts.backend')

    @section('content')
        <div class="block block-rounded col-md-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.add-parties')</h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('parties.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name_en">@lang('messages.name') @lang('messages.english')</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                placeholder="@lang('messages.enter-name-english')" autocomplete="off" value="{{ old('name_en') }}">
                            @error('name_en')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name_ur">@lang('messages.name') @lang('messages.urdu')</label>
                            <input type="text" class="form-control input-urdu keyboardInput" id="name_ur"
                                name="name_ur" placeholder="نام درج کریں" autocomplete="off" value="{{ old('name_ur') }}">
                            @error('name_ur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="father_name_en">@lang('messages.father_name') @lang('messages.english')</label>
                            <input type="text" class="form-control" id="father_name_en" name="father_name_en"
                                placeholder="@lang('messages.enter-name-english')" autocomplete="off" value="{{ old('father_name_en') }}">
                            @error('father_name_en')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="father_name_ur">@lang('messages.father_name') @lang('messages.urdu')</label>
                            <input type="text" class="form-control input-urdu keyboardInput" id="father_name_ur"
                                name="father_name_ur" placeholder="والد کا نام درج کریں" autocomplete="off"
                                value="{{ old('father_name_ur') }}">
                            @error('father_name_ur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cnic_no">@lang('messages.cnic_no') </label>
                            <input type="text" class="form-control" id="cnic_no" name="cnic_no"
                                placeholder="12345-1234567-1" maxlength="15" placeholder="@lang('messages.cnic_no')"
                                autocomplete="off" value="{{ old('cnic_no') }}">
                            @error('cnic_no')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ntn_no">@lang('messages.ntn_no') </label>
                            <input type="text" class="form-control" id="ntn_no" name="ntn_no"
                                placeholder="@lang('messages.ntn_no')" autocomplete="off" value="{{ old('ntn_no') }}">
                            @error('ntn_no')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gst_no">@lang('messages.gst_no') </label>
                            <input type="text" class="form-control" id="gst_no" name="gst_no"
                                placeholder="@lang('messages.gst_no')" autocomplete="off" value="{{ old('gst_no') }}">
                            @error('gst_no')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cast_id">@lang('messages.cast')</label>
                            <select name="cast_id" id="cast_id"
                                class="form-control form-select select2 @error('cast_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-cast')</option>
                                @foreach ($casts as $cast)
                                    <option value="{{ $cast->id }}"
                                        {{ old('cast_id') == $cast->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $cast->title_ur ?? '-' : $cast->title_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cast_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="residential_status">@lang('messages.residential-Status')</label>
                            <select name="residential_status" id="residential_status"
                                class="form-control form-select select2 @error('residential_status') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($residentialStatus as $residential)
                                    <option value="{{ $residential->id }}"
                                        {{ old('main_head_id') == $residential->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $residential->title_ur ?? '-' : $residential->title_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('residential_status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="occupation_id">@lang('messages.occupation')</label>
                            <select name="occupation_id" id="occupation_id"
                                class="form-control form-select select2 @error('occupation_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($occupations as $occupation)
                                    <option value="{{ $occupation->id }}"
                                        {{ old('occupation_id') == $occupation->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $occupation->title_ur ?? '-' : $occupation->title_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('occupation_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_name_en">@lang('messages.business_name') @lang('messages.english')</label>
                            <input type="text" class="form-control" id="business_name_en" name="business_name_en"
                                placeholder="@lang('messages.enter-name-english')" autocomplete="off"
                                value="{{ old('business_name_en') }}">
                            @error('business_name_en')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_name_ur">@lang('messages.business_name') @lang('messages.urdu')</label>
                            <input type="text" class="form-control input-urdu keyboardInput" id="business_name_ur"
                                name="business_name_ur" placeholder="کاروبار کا نام درج کریں" autocomplete="off"
                                value="{{ old('business_name_ur') }}">
                            @error('business_name_ur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_address_en">@lang('messages.business_address') @lang('messages.english')</label>
                            <textarea type="text" class="form-control" id="business_address_en" name="business_address_en"
                                placeholder="@lang('messages.address')" autocomplete="off" value="{{ old('business_address_en') }}"></textarea>
                            @error('business_address_en')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_address_ur">@lang('messages.business_address') @lang('messages.urdu')</label>
                            <textarea type="text" class="form-control input-urdu keyboardInput" id="business_address_ur"
                                name="business_address_ur" placeholder="کاروبار کا پتہ درج کریں" autocomplete="off"
                                value="{{ old('business_address_ur') }}"></textarea>
                            @error('business_address_ur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="home_address_en">@lang('messages.home_address') @lang('messages.english')</label>
                            <textarea type="text" class="form-control" id="home_address_en" name="home_address_en"
                                placeholder="@lang('messages.address')" autocomplete="off" value="{{ old('home_address_en') }}"></textarea>
                            @error('home_address_en')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="home_address_ur">@lang('messages.home_address') @lang('messages.urdu')</label>
                            <textarea type="text" class="form-control input-urdu keyboardInput" id="home_address_ur" name="home_address_ur"
                                placeholder="کاروبار کا پتہ درج کریں" autocomplete="off" value="{{ old('home_address_ur') }}"></textarea>
                            @error('home_address_ur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_number_1">@lang('messages.contact_no') (1)</label>
                            <input type="text" class="form-control" id="contact_no_1" name="contact_number_1"
                                placeholder="@lang('messages.contact_no')" autocomplete="off"
                                value="{{ old('contact_number_1') }}">
                            @error('contact_number_1')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_number_2">@lang('messages.contact_no') (2)</label>
                            <input type="text" class="form-control" id="contact_no_2" name="contact_number_2"
                                placeholder="@lang('messages.contact_no')" autocomplete="off"
                                value="{{ old('contact_number_2') }}">
                            @error('contact_number_2')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="whatsApp_no">@lang('messages.whatsapp_no')</label>
                            <input type="text" class="form-control" id="whatsApp_no" name="whatsApp_no"
                                placeholder="@lang('messages.whatsapp_no')" autocomplete="off" value="{{ old('whatsApp_no') }}">
                            @error('whatsApp_no')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="remarks">@lang('messages.remarks')</label>
                            <textarea type="text" class="form-control" id="remarks" name="remarks" placeholder="@lang('messages.remarks')"
                                autocomplete="off" value="{{ old('remarks') }}"></textarea>
                            @error('remarks')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">

                            <div class="form-group mb-3">

                                <label for="image">@lang('messages.cnic_front') @lang('messages.image')</label><br>

                                <!-- File Upload Input -->
                                <input type="file" name="cnic_front_image" id="cnic_front_image" class="form-control"
                                    onchange="previewImage(this)">

                                <!-- Image Preview (Shows avatar if no image is selected) -->
                                <div id="imagePreview" class="mt-2">
                                    {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                                    <img id="previewImg"
                                        src="{{ isset($partyRegistration) && $partyRegistration->cnic_front_image ? asset('storage/' . $partyRegistration->cnic_front_image) : asset('images/No-Image-Placeholder.svg.png') }}"
                                        alt="" class="img-thumbnail" style="max-height: 200px;">
                                </div>

                                @error('cnic_front_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <div class="form-group mb-3">

                                <label for="image">@lang('messages.cnic_back') @lang('messages.image')</label><br>

                                <!-- File Upload Input -->
                                <input type="file" name="cnic_back_image" id="cnic_back_image" class="form-control"
                                    onchange="previewImage2(this)">

                                <!-- Image Preview (Shows avatar if no image is selected) -->
                                <div id="imagePreview2" class="mt-2">
                                    {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                                    <img id="previewImg2"
                                        src="{{ isset($partyRegistration) && $partyRegistration->cnic_back_image ? asset('storage/' . $partyRegistration->cnic_back_image) : asset('images/No-Image-Placeholder.svg.png') }}"
                                        alt="" class="img-thumbnail" style="max-height: 200px;">
                                </div>

                                @error('cnic_back_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <div class="form-group mb-3">

                                <label for="profile_image">@lang('messages.profile') @lang('messages.image')</label><br>

                                <!-- File Upload Input -->
                                <input type="file" name="profile_image" id="profile_image" class="form-control"
                                    onchange="previewImage3(this)">

                                <!-- Image Preview (Shows avatar if no image is selected) -->
                                <div id="imagePreview3" class="mt-2">
                                    {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                                    <img id="previewImg3"
                                        src="{{ isset($partyRegistration) && $partyRegistration->profile_image ? asset('storage/' . $partyRegistration->profile_image) : asset('images/No-Image-Placeholder.svg.png') }}"
                                        alt="" class="img-thumbnail" style="max-height: 200px;">
                                </div>

                                @error('profile_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <h2 style="color: red">@lang('messages.bank_details')</h2>
                    </div>

                    <div class="tab-content" id="pills-tabContent" style="margin-bottom: 5px;">
                        <div class="invoice-detail-items" style="padding: 0px 0px 0px 0px !important;">

                            <div class="table-responsive">

                                <table class="table item-table">
                                    <thead>
                                        <tr>
                                            <th class="">
                                            </th>
                                            <th>
                                            </th>
                                            <th style="width: 20% !important">@lang('messages.banks')</th>
                                            <th class="">
                                                @lang('messages.account_title')</th>
                                            <th class="">
                                                @lang('messages.account_number')</th>
                                            <th class="">
                                                @lang('messages.branch_code')</th>

                                        </tr>
                                        <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                            <a href="javascript:void(0)" class="btn btn-dark additem">@lang('messages.add-bank_details')</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            <a href="{{ route('parties.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>


        <script>
            document.getElementsByClassName('additem')[0].addEventListener('click', function() {

                let getTableElement = document.querySelector('.item-table');
                let currentIndex = getTableElement.rows.length;

                let $html = '<tr>' +
                    '<td class="delete-item-row">' +
                    '<ul class="table-controls">' +
                    '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                    '</ul>' +
                    '</td>' +
                    '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                    '" hidden></td>' +
                    '<td class="bank_type"><select name="bank_id[]" id="bank_id" class="form-control form-select select2 @error('bank_id') is-invalid @enderror bank_' +
                    currentIndex +
                    '"><option value="">@lang('messages.select-bank')</option>@foreach ($banks as $bank)<option value="{{ $bank->id }}"{{ old('bank_id') == $bank->id ? 'selected' : '' }}>{{ App::getLocale() === 'ur' ? $bank->name_ur ?? '-' : $bank->name_en ?? '-' }}</option>@endforeach</select> ' +
                    '</td> ' +
                    '<td class="account_title" >' +
                    '<input id="account_title" style="color: black; " type="text" name="account_title[]" class = "account_title form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  account_title_' +
                    currentIndex + '" placeholder="@lang('messages.account_title')" ></td>' +
                    '<td class="account_number" >' +
                    '<input type="text" style="color: black; " placeholder="@lang('messages.account_number')" id="account_number" name="account_number[]" class = "account_number form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} account_number_' +
                    currentIndex + '" > </td> ' +
                    '<td class="text-right branch_code">' +
                    '<input type="text" name="branch_code[]" class="branch_code form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} branch_code_' +
                    currentIndex + '" placeholder="@lang('messages.branch_code') ">' +
                    ' </td>' +

                    '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                    // '<input class="form-check-input inbox-chkbox contact-chkbox" type="checkbox">' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '</tr>';

                $(".item-table tbody").append($html);
                deleteItemRow();
                $('.select2').select2();

            })

            deleteItemRow();

            selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
            selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
            selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

            function deleteItemRow() {
                let deleteItem = document.querySelectorAll('.delete-item');
                for (var i = 0; i < deleteItem.length; i++) {
                    deleteItem[i].addEventListener('click', function() {
                        this.parentElement.parentNode.parentNode.parentNode.remove();
                    })
                }
            }
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const input = document.getElementById('contact_no_1');

                input.addEventListener('input', function() {
                    // Remove all non-digit characters
                    let raw = this.value.replace(/\D/g, '');

                    // Limit to 11 digits max
                    if (raw.length > 11) raw = raw.slice(0, 11);

                    // Auto-format: insert dash after 4 digits
                    if (raw.length > 4) {
                        this.value = raw.slice(0, 4) + '-' + raw.slice(4);
                    } else {
                        this.value = raw;
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const input = document.getElementById('contact_no_2');

                input.addEventListener('input', function() {
                    // Remove all non-digit characters
                    let raw = this.value.replace(/\D/g, '');

                    // Limit to 11 digits max
                    if (raw.length > 11) raw = raw.slice(0, 11);

                    // Auto-format: insert dash after 4 digits
                    if (raw.length > 4) {
                        this.value = raw.slice(0, 4) + '-' + raw.slice(4);
                    } else {
                        this.value = raw;
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const input = document.getElementById('whatsApp_no');

                input.addEventListener('input', function() {
                    // Remove all non-digit characters
                    let raw = this.value.replace(/\D/g, '');

                    // Limit to 11 digits max
                    if (raw.length > 11) raw = raw.slice(0, 11);

                    // Auto-format: insert dash after 4 digits
                    if (raw.length > 4) {
                        this.value = raw.slice(0, 4) + '-' + raw.slice(4);
                    } else {
                        this.value = raw;
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const cnicInput = document.getElementById('cnic_no');

                cnicInput.addEventListener('input', function() {
                    let raw = this.value.replace(/\D/g, ''); // Only digits

                    if (raw.length > 13) raw = raw.slice(0, 13); // Limit to 13 digits

                    let formatted = raw;
                    if (raw.length > 5 && raw.length <= 12) {
                        formatted = raw.slice(0, 5) + '-' + raw.slice(5);
                    }
                    if (raw.length > 12) {
                        formatted = raw.slice(0, 5) + '-' + raw.slice(5, 12) + '-' + raw.slice(12);
                    }

                    this.value = formatted;
                });
            });
        </script>
    @endsection
