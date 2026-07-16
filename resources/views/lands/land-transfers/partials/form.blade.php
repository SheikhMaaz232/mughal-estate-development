@csrf

@if (isset($landTransfer))
    @method('PUT')
    <input type="hidden" name="land_id" value="{{ $landTransfer->land_id }}">
@else
    <input type="hidden" name="land_id" value="{{ request('land_id') }}">
@endif

<!-- Basic Information -->
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="border-bottom pb-2">@lang('messages.basic-information')</h5>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="transfer_date" class="form-label">@lang('messages.transfer-date') *</label>
        <input type="date" name="transfer_date" id="transfer_date" class="form-control"
            value="{{ old('transfer_date', isset($landTransfer) ? $landTransfer->transfer_date->format('Y-m-d') : '') }}"
            required>
        @error('transfer_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="registry_type_id" class="form-label">@lang('messages.registry-type') *</label>
        <select name="registry_type_id" id="registry_type_id" class="form-control select2" required>
            <option value="">@lang('messages.select-registry-type')</option>
            @foreach ($registryTypes as $type)
                <option value="{{ $type->id }}"
                    {{ old('registry_type_id', $landTransfer->registry_type_id ?? '') == $type->id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $type->title_ur ?? '-' : $type->title_en ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('registry_type_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="purchaser_account_id" class="form-label">@lang('messages.purchaser-account') *</label>
        <select name="purchaser_account_id" id="purchaser_account_id" class="form-control select2" required>
            <option value="">@lang('messages.select-purchaser')</option>
            @foreach ($purchaserAccounts as $account)
                <option value="{{ $account->id }}"
                    {{ old('purchaser_account_id', $landTransfer->purchaser_account_id ?? '') == $account->id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $account->name_ur ?? '-' : $account->name_en ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('purchaser_account_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="seller_account_id" class="form-label">@lang('messages.seller-account') *</label>
        <select name="seller_account_id" id="seller_account_id" class="form-control select2" required>
            <option value="">@lang('messages.select-seller')</option>
            @foreach ($sellerAccounts as $account)
                <option value="{{ $account->id }}"
                    {{ old('seller_account_id', $landTransfer->seller_account_id ?? '') == $account->id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $account->name_ur ?? '-' : $account->name_en ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('seller_account_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Land Details -->
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="border-bottom pb-2">@lang('messages.land-details')</h5>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="fard_no" class="form-label">@lang('messages.fard-no') *</label>
        <input type="text" name="fard_no" id="fard_no" class="form-control"
            value="{{ old('fard_no', $landTransfer->fard_no ?? ($landData['fard_no'] ?? '')) }}" readonly required>
        @error('fard_no')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="khawat_no" class="form-label">@lang('messages.khawat-no') *</label>
        <input type="text" name="khawat_no" id="khawat_no" class="form-control"
            value="{{ old('khawat_no', $landTransfer->khawat_no ?? ($landData['khawat_no'] ?? '')) }}" readonly
            required>
        @error('khawat_no')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="khatoni_no" class="form-label">@lang('messages.khatoni-no')</label>
        <input type="text" name="khatoni_no" id="khatoni_no" class="form-control"
            value="{{ old('khatoni_no', $landTransfer->khatoni_no ?? '') }}">
        @error('khatoni_no')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="value" class="form-label">@lang('messages.value') *</label>
        <input type="number" name="value" id="value" class="form-control" step="0.01" min="0"
            value="{{ old('value', $landTransfer->value ?? '') }}" required>
        @error('value')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Additional Details -->
<div class="row mb-3">
    <div class="col-md-3">
        <label for="mushtarqa_khata" class="form-label">@lang('messages.mushtarqa-khata')</label>
        <input type="text" name="mushtarqa_khata" id="mushtarqa_khata" class="form-control"
            value="{{ old('mushtarqa_khata', $landTransfer->mushtarqa_khata ?? '') }}">
        @error('mushtarqa_khata')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="makhsoos_raqba" class="form-label">@lang('messages.makhsoos-raqba')</label>
        <input type="text" name="makhsoos_raqba" id="makhsoos_raqba" class="form-control"
            value="{{ old('makhsoos_raqba', $landTransfer->makhsoos_raqba ?? '') }}">
        @error('makhsoos_raqba')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="qitaat" class="form-label">@lang('messages.qitaat')</label>
        <input type="text" name="qitaat" id="qitaat" class="form-control"
            value="{{ old('qitaat', $landTransfer->qitaat ?? '') }}">
        @error('qitaat')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="saalam_khata" class="form-label">@lang('messages.saalam-khata')</label>
        <input type="text" name="saalam_khata" id="saalam_khata" class="form-control"
            value="{{ old('saalam_khata', $landTransfer->saalam_khata ?? '') }}">
        @error('saalam_khata')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="hissa_mutaliqa" class="form-label">@lang('messages.hissa-mutaliqa')</label>
        <input type="text" name="hissa_mutaliqa" id="hissa_mutaliqa" class="form-control"
            value="{{ old('hissa_mutaliqa', $landTransfer->hissa_mutaliqa ?? '') }}">
        @error('hissa_mutaliqa')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="raqba_muntaqila" class="form-label">@lang('messages.raqba-muntaqila')</label>
        <input type="text" name="raqba_muntaqila" id="raqba_muntaqila" class="form-control"
            value="{{ old('raqba_muntaqila', $landTransfer->raqba_muntaqila ?? '') }}">
        @error('raqba_muntaqila')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Images -->
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="border-bottom pb-2">@lang('messages.images')</h5>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label for="attachment_1" class="form-label">@lang('messages.attachment-1')</label>
        <input type="file" name="attachment_1" id="image1" class="form-control" accept="image/*">
        @error('attachment_1')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        @if (isset($landTransfer) && $landTransfer->attachment_1)
            <div class="mt-2">
                <img src="{{ asset('storage/land-transfers/' . $landTransfer->attachment_1) }}" alt="Image 1"
                    class="img-thumbnail" style="max-height: 100px;">
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="remove_image1" id="remove_image1"
                        value="1">
                    <label class="form-check-label" for="remove_image1">
                        @lang('messages.remove-image')
                    </label>
                </div>
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <label for="attachment_2" class="form-label">@lang('messages.attachment-2')</label>
        <input type="file" name="attachment_2" id="image2" class="form-control" accept="image/*">
        @error('attachment_2')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        @if (isset($landTransfer) && $landTransfer->attachment_2)
            <div class="mt-2">
                <img src="{{ asset('storage/land-transfers/' . $landTransfer->attachment_2) }}" alt="Image 2"
                    class="img-thumbnail" style="max-height: 100px;">
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="remove_image2" id="remove_image2"
                        value="1">
                    <label class="form-check-label" for="remove_image2">
                        @lang('messages.remove-image')
                    </label>
                </div>
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <label for="attachment_3" class="form-label">@lang('messages.attachment-3')</label>
        <input type="file" name="attachment_3" id="image3" class="form-control" accept="image/*">
        @error('attachment_3')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        @if (isset($landTransfer) && $landTransfer->attachment_3)
            <div class="mt-2">
                <img src="{{ Storage::url('land-transfers/' . $landTransfer->attachment_3) }}" alt="Image 3"
                    class="img-thumbnail" style="max-height: 100px;">
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="remove_image3" id="remove_image3"
                        value="1">
                    <label class="form-check-label" for="remove_image3">
                        @lang('messages.remove-image')
                    </label>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Submit Buttons -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                @if (isset($landTransfer))
                    <i class="fa fa-save me-1"></i> @lang('messages.update')
                @else
                    <i class="fa fa-save me-1"></i> @lang('messages.save')
                @endif
            </button>
            <a href="{{ route('land-transfers.index') }}" class="btn btn-alt-secondary">
                <i class="fa fa-times me-1"></i> @lang('messages.cancel')
            </a>
        </div>
    </div>
</div>
