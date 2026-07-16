@csrf

@if (isset($landRegistration))
    @method('PUT')
@endif

<!-- Account Information Section -->
<div class="row mb-3">
    <div class="col-md-6">
        <label for="seller_account_id" class="form-label">@lang('messages.seller-account')</label>
        <select name="seller_account_id" id="seller_account_id" class="form-control select2" required>
            <option value="">@lang('messages.select-seller-account')</option>
            @foreach ($sellerAccounts as $account)
                <option value="{{ $account->id }}"
                    {{ old('seller_account_id', $landRegistration->seller_account_id ?? '') == $account->id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                </option>
            @endforeach
        </select>
        @error('seller_account_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="buyer_account_id" class="form-label">@lang('messages.buyer-account')</label>
        <select name="buyer_account_id" id="buyer_account_id" class="form-control select2" required>
            <option value="">@lang('messages.select-buyer-account')</option>
            @foreach ($buyerAccounts as $account)
                <option value="{{ $account->id }}"
                    {{ old('buyer_account_id', $landRegistration->buyer_account_id ?? '') == $account->id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                </option>
            @endforeach
        </select>
        @error('buyer_account_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="commission_account_id" class="form-label">@lang('messages.commission-account') - @lang('messages.dealer-account-payable')</label>
        <select name="commission_account_id" id="commission_account_id" class="form-control select2" required>
            <option value="">@lang('messages.select-commission-account')</option>
            @foreach ($commissionAccounts as $account)
                <option value="{{ $account->id }}"
                    {{ old('commission_account_id', $landRegistration->commission_account_id ?? '') == $account->id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                </option>
            @endforeach
        </select>
        @error('commission_account_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="project_id">@lang('messages.project')</label>
        <select name="project_id" id="project_id" class="form-control select2" required>
            <option value="">@lang('messages.select-project')</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}"
                    {{ old('project_id', $landRegistration->project_id ?? '') == $project->id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('project_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="commission_amount" class="form-label">@lang('messages.commission-amount')</label>
        <input type="number" name="commission_amount" id="commission_amount" class="form-control" step="0.01"
            min="0" value="{{ old('commission_amount', $landRegistration->commission_amount ?? '') }}">
        @error('commission_amount')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="land_amount" class="form-label">@lang('messages.land-amount')</label>
        <input type="number" name="land_amount" id="land_amount" class="form-control" step="0.01" min="0"
            value="{{ old('land_amount', $landRegistration->land_amount ?? '') }}">
        @error('land_amount')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Land Details Section -->
<div class="section-title"><strong>@lang('messages.land-details')</strong></div>
<div id="land-details-container">
    <!-- Existing land details for editing -->
    @if (isset($landRegistration) && $landRegistration->details && $landRegistration->details->count() > 0)
        @foreach ($landRegistration->details as $index => $detail)
            <div class="land-detail-row border rounded p-3 mb-3 bg-light" data-index="{{ $index }}">
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.khawat-no')</label>
                        <input type="text" name="land_details[{{ $index }}][khawat_no]"
                            class="form-control form-control-lg"
                            value="{{ old('land_details.' . $index . '.khawat_no', $detail->khawat_no ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.fard-id-no')</label>
                        <input type="text" name="land_details[{{ $index }}][fard_id_no]"
                            class="form-control form-control-lg"
                            value="{{ old('land_details.' . $index . '.fard_id_no', $detail->fard_id_no ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.registry-no')</label>
                        <input type="text" name="land_details[{{ $index }}][registry_no]"
                            class="form-control form-control-lg"
                            value="{{ old('land_details.' . $index . '.registry_no', $detail->registry_no ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.moza')</label>
                        <input type="text" name="land_details[{{ $index }}][moza]"
                            class="form-control form-control-lg"
                            value="{{ old('land_details.' . $index . '.moza', $detail->moza ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.murabba')</label>
                        <input type="number" name="land_details[{{ $index }}][murabba]"
                            class="form-control form-control-lg area-input" step="0.01" min="0"
                            value="{{ old('land_details.' . $index . '.murabba', $detail->murabba ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.acre')</label>
                        <input type="number" name="land_details[{{ $index }}][acre]"
                            class="form-control form-control-lg area-input" step="0.01" min="0"
                            value="{{ old('land_details.' . $index . '.acre', $detail->acre ?? '') }}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.kanal')</label>
                        <input type="number" name="land_details[{{ $index }}][kanal]"
                            class="form-control form-control-lg area-input" step="0.01" min="0"
                            value="{{ old('land_details.' . $index . '.kanal', $detail->kanal ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.wigha')</label>
                        <input type="number" name="land_details[{{ $index }}][wigha]"
                            class="form-control form-control-lg area-input" step="0.01" min="0"
                            value="{{ old('land_details.' . $index . '.wigha', $detail->wigha ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.marla')</label>
                        <input type="number" name="land_details[{{ $index }}][marla]"
                            class="form-control form-control-lg area-input" step="0.01" min="0"
                            value="{{ old('land_details.' . $index . '.marla', $detail->marla ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">@lang('messages.square-feet')</label>
                        <input type="number" name="land_details[{{ $index }}][square_feet]"
                            class="form-control form-control-lg area-input" step="0.01" min="0"
                            value="{{ old('land_details.' . $index . '.square_feet', $detail->square_feet ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('messages.remarks')</label>
                        <input type="text" name="land_details[{{ $index }}][remarks]"
                            class="form-control form-control-lg"
                            value="{{ old('land_details.' . $index . '.remarks', $detail->remarks ?? '') }}">
                    </div>
                </div>
                @if ($index > 0)
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-danger remove-land-detail">
                                <i class="fa fa-trash me-1"></i> @lang('messages.remove')
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <!-- Default empty land detail row -->
        <div class="land-detail-row border rounded p-3 mb-3 bg-light" data-index="0">
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.khawat-no')</label>
                    <input type="text" name="land_details[0][khawat_no]" class="form-control form-control-lg"
                        value="{{ old('land_details.0.khawat_no', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.fard-id-no')</label>
                    <input type="text" name="land_details[0][fard_id_no]" class="form-control form-control-lg"
                        value="{{ old('land_details.0.fard_id_no', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.registry-no')</label>
                    <input type="text" name="land_details[0][registry_no]" class="form-control form-control-lg"
                        value="{{ old('land_details.0.registry_no', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.moza')</label>
                    <input type="text" name="land_details[0][moza]" class="form-control form-control-lg"
                        value="{{ old('land_details.0.moza', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.murabba')</label>
                    <input type="number" name="land_details[0][murabba]"
                        class="form-control form-control-lg area-input" step="0.01" min="0"
                        value="{{ old('land_details.0.murabba', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.acre')</label>
                    <input type="number" name="land_details[0][acre]"
                        class="form-control form-control-lg area-input" step="0.01" min="0"
                        value="{{ old('land_details.0.acre', '') }}">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.kanal')</label>
                    <input type="number" name="land_details[0][kanal]"
                        class="form-control form-control-lg area-input" step="0.01" min="0"
                        value="{{ old('land_details.0.kanal', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.wigha')</label>
                    <input type="number" name="land_details[0][wigha]"
                        class="form-control form-control-lg area-input" step="0.01" min="0"
                        value="{{ old('land_details.0.wigha', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.marla')</label>
                    <input type="number" name="land_details[0][marla]"
                        class="form-control form-control-lg area-input" step="0.01" min="0"
                        value="{{ old('land_details.0.marla', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.square-feet')</label>
                    <input type="number" name="land_details[0][square_feet]"
                        class="form-control form-control-lg area-input" step="0.01" min="0"
                        value="{{ old('land_details.0.square_feet', '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('messages.remarks')</label>
                    <input type="text" name="land_details[0][remarks]" class="form-control form-control-lg"
                        value="{{ old('land_details.0.remarks', '') }}">
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Add More Button -->
<div class="row mb-4">
    <div class="col-md-12">
        <button type="button" id="add-more-land-detail" class="btn btn-sm btn-primary">
            <i class="fa fa-plus me-1"></i> @lang('messages.add-more-land-detail')
        </button>
    </div>
</div>

<!-- Total Area Information - MOVED UNDER LAND DETAILS -->
<div class="row mb-3">
    <div class="col-md-2">
        <label for="total_murabba" class="form-label">@lang('messages.total-murabba')</label>
        <input type="number" name="total_murabba" id="total_murabba" class="form-control total-area-field"
            step="0.01" min="0" readonly
            value="{{ old('total_murabba', $landRegistration->total_murabba ?? '') }}">
        @error('total_murabba')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="total_acre" class="form-label">@lang('messages.total-acre')</label>
        <input type="number" name="total_acre" id="total_acre" class="form-control total-area-field"
            step="0.01" min="0" readonly
            value="{{ old('total_acre', $landRegistration->total_acre ?? '') }}">
        @error('total_acre')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="total_kanal" class="form-label">@lang('messages.total-kanal')</label>
        <input type="number" name="total_kanal" id="total_kanal" class="form-control total-area-field"
            step="0.01" min="0" readonly
            value="{{ old('total_kanal', $landRegistration->total_kanal ?? '') }}">
        @error('total_kanal')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="total_wigha" class="form-label">@lang('messages.total-wigha')</label>
        <input type="number" name="total_wigha" id="total_wigha" class="form-control total-area-field"
            step="0.01" min="0" readonly
            value="{{ old('total_wigha', $landRegistration->total_wigha ?? '') }}">
        @error('total_wigha')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="total_marla" class="form-label">@lang('messages.total-marla')</label>
        <input type="number" name="total_marla" id="total_marla" class="form-control total-area-field"
            step="0.01" min="0" readonly
            value="{{ old('total_marla', $landRegistration->total_marla ?? '') }}">
        @error('total_marla')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="total_square_feet" class="form-label">@lang('messages.total-square-feet')</label>
        <input type="number" name="total_square_feet" id="total_square_feet" class="form-control total-area-field"
            step="0.01" min="0" readonly
            value="{{ old('total_square_feet', $landRegistration->total_square_feet ?? '') }}">
        @error('total_square_feet')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Terms & Conditions - MOVED UNDER LAND DETAILS -->
<div class="row mb-3">
    <div class="col-md-6">
        <label for="terms_conditions_en" class="form-label">@lang('messages.terms-conditions-english')</label>
        <textarea name="terms_conditions_en" id="terms_conditions_en" class="form-control" rows="4">{{ old('terms_conditions_en', $landRegistration->terms_conditions_en ?? '') }}</textarea>
        @error('terms_conditions_en')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="terms_conditions_ur" class="form-label">@lang('messages.terms-conditions-urdu')</label>
        <textarea name="terms_conditions_ur" id="terms_conditions_ur" class="form-control keyboardInput" rows="4"
            dir="rtl">{{ old('terms_conditions_ur', $landRegistration->terms_conditions_ur ?? '') }}</textarea>
        @error('terms_conditions_ur')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Remarks -->
<div class="row mb-3">
    <div class="col-md-12">
        <label for="remarks" class="form-label">@lang('messages.remarks')</label>
        <textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $landRegistration->remarks ?? '') }}</textarea>
        @error('remarks')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Submit Buttons -->
<div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-sm btn-primary">
        @if (isset($landRegistration) && $landRegistration->id)
            <i class="fa fa-save me-1"></i> @lang('messages.update')
        @else
            <i class="fa fa-save me-1"></i> @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('lands.index') }}" class="btn btn-sm btn-alt-primary">
        <i class="fa fa-arrow-left me-1"></i> @lang('messages.go-to-list')
    </a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2').select2();

        let landDetailIndex =
            {{ isset($landRegistration) && $landRegistration->details ? $landRegistration->details->count() : 1 }};

        // Add more land detail
        document.getElementById('add-more-land-detail').addEventListener('click', function() {
            const container = document.getElementById('land-details-container');
            const newRow = document.createElement('div');
            newRow.className = 'land-detail-row border rounded p-3 mb-3 bg-light';
            newRow.setAttribute('data-index', landDetailIndex);

            newRow.innerHTML = `
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.khawat-no')</label>
                    <input type="text" name="land_details[${landDetailIndex}][khawat_no]" class="form-control form-control-lg">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.fard-id-no')</label>
                    <input type="text" name="land_details[${landDetailIndex}][fard_id_no]" class="form-control form-control-lg">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.registry-no')</label>
                    <input type="text" name="land_details[${landDetailIndex}][registry_no]" class="form-control form-control-lg">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.moza')</label>
                    <input type="text" name="land_details[${landDetailIndex}][moza]" class="form-control form-control-lg">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.murabba')</label>
                    <input type="number" name="land_details[${landDetailIndex}][murabba]" class="form-control form-control-lg area-input" step="0.01" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.acre')</label>
                    <input type="number" name="land_details[${landDetailIndex}][acre]" class="form-control form-control-lg area-input" step="0.01" min="0">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.kanal')</label>
                    <input type="number" name="land_details[${landDetailIndex}][kanal]" class="form-control form-control-lg area-input" step="0.01" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.wigha')</label>
                    <input type="number" name="land_details[${landDetailIndex}][wigha]" class="form-control form-control-lg area-input" step="0.01" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.marla')</label>
                    <input type="number" name="land_details[${landDetailIndex}][marla]" class="form-control form-control-lg area-input" step="0.01" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('messages.square-feet')</label>
                    <input type="number" name="land_details[${landDetailIndex}][square_feet]" class="form-control form-control-lg area-input" step="0.01" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('messages.remarks')</label>
                    <input type="text" name="land_details[${landDetailIndex}][remarks]" class="form-control form-control-lg">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <button type="button" class="btn btn-sm btn-danger remove-land-detail">
                        <i class="fa fa-trash me-1"></i> @lang('messages.remove')
                    </button>
                </div>
            </div>
        `;

            container.appendChild(newRow);
            landDetailIndex++;

            // Re-attach event listeners to new area inputs
            attachAreaInputListeners();
        });

        // Remove land detail
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-land-detail')) {
                e.target.closest('.land-detail-row').remove();
                calculateTotalAreas();
            }
        });

        // Function to attach event listeners to area inputs
        function attachAreaInputListeners() {
            const areaInputs = document.querySelectorAll('.area-input');
            areaInputs.forEach(input => {
                // Remove existing listeners to avoid duplicates
                input.removeEventListener('input', calculateTotalAreas);
                // Add new listener
                input.addEventListener('input', calculateTotalAreas);
            });
        }

        // Auto-calculate total areas when land details change
        function calculateTotalAreas() {
            let totalMurabba = 0;
            let totalAcre = 0;
            let totalKanal = 0;
            let totalWigha = 0;
            let totalMarla = 0;
            let totalSquareFeet = 0;

            document.querySelectorAll('.land-detail-row').forEach(row => {
                const murabba = parseFloat(row.querySelector('input[name*="[murabba]"]')?.value) || 0;
                const acre = parseFloat(row.querySelector('input[name*="[acre]"]')?.value) || 0;
                const kanal = parseFloat(row.querySelector('input[name*="[kanal]"]')?.value) || 0;
                const wigha = parseFloat(row.querySelector('input[name*="[wigha]"]')?.value) || 0;
                const marla = parseFloat(row.querySelector('input[name*="[marla]"]')?.value) || 0;
                const squareFeet = parseFloat(row.querySelector('input[name*="[square_feet]"]')
                    ?.value) || 0;

                totalMurabba += murabba;
                totalAcre += acre;
                totalKanal += kanal;
                totalWigha += wigha;
                totalMarla += marla;
                totalSquareFeet += squareFeet;
            });

            document.getElementById('total_murabba').value = totalMurabba.toFixed(2);
            document.getElementById('total_acre').value = totalAcre.toFixed(2);
            document.getElementById('total_kanal').value = totalKanal.toFixed(2);
            document.getElementById('total_wigha').value = totalWigha.toFixed(2);
            document.getElementById('total_marla').value = totalMarla.toFixed(2);
            document.getElementById('total_square_feet').value = totalSquareFeet.toFixed(2);
        }

        // Initial attachment of event listeners
        attachAreaInputListeners();

        // Initial calculation
        calculateTotalAreas();
    });
</script>
