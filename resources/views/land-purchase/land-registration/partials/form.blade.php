@csrf

@if(isset($landRegistration))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6 mx-auto">
        <label for="project_id">@lang('messages.select-project')</label>
        <select name="project_id" id="project_id" class="form-control select2" required>
            <option value="">@lang('messages.select-project')</option>
            @foreach($projects as $id => $project)
                <option value="{{ $id }}" {{ old('project_id', $landRegistration->project_id ?? '') == $id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('project_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="party_account_id">@lang('messages.select-party')</label>
        <select name="party_account_id" id="party_account_id" class="form-control select2" required>
            <option value="">@lang('messages.select-party-account')</option>
            @foreach($partyAccounts as $id => $account)
                <option value="{{ $id }}" {{ old('party_account_id', $landRegistration->party_account_id ?? '') == $id ? 'selected' : '' }}>
                    {{ App::getLocale() === 'ur' ? $account->name_ur ?? '-' : $account->name_en ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('party_account_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Space between rows --}}
<div class="mb-3"></div>

<div class="row">
    <div class="col-md-2">
        <label for="khawat_number">@lang('messages.khawat-number')</label>
        <input type="text" name="khawat_number" id="khawat_number" class="form-control"
               value="{{ old('khawat_number', $landRegistration->khawat_number ?? '') }}"
               placeholder="@lang('messages.enter-khawat-number')">
        @error('khawat_number')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="kanal">@lang('messages.kanal')</label>
        <input type="number" name="kanal" id="kanal" class="form-control calculate-total"
               step="0.01" min="0" required
               value="{{ old('kanal', $landRegistration->kanal ?? 0) }}">
        @error('kanal')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="merla">@lang('messages.merla')</label>
        <input type="number" name="merla" id="merla" class="form-control calculate-total"
               step="0.01" min="0" required
               value="{{ old('merla', $landRegistration->merla ?? 0) }}">
        @error('merla')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="square_feet">@lang('messages.square-feet')</label>
        <input type="number" name="square_feet" id="square_feet" class="form-control calculate-total"
               step="0.01" min="0" required
               value="{{ old('square_feet', $landRegistration->square_feet ?? 0) }}">
        @error('square_feet')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="total_merla">@lang('messages.total-merla')</label>
        <input type="text" name="total_merla_display" id="total_merla_display" class="form-control"
               value="0.0000" readonly placeholder="@lang('messages.total-merla')">
        <input type="hidden" name="total_merla" id="total_merla" value="0">
        @error('total_merla')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Space between rows --}}
<div class="mb-3"></div>

<div class="row">
    <div class="col-md-12">
        <label for="remarks">@lang('messages.remarks')</label>
        <textarea name="remarks" id="remarks" class="form-control" rows="3"
                  placeholder="@lang('messages.enter-remarks')">{{ old('remarks', $landRegistration->remarks ?? '') }}</textarea>
        @error('remarks')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-sm btn-primary">
        @if(isset($landRegistration) && $landRegistration->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('land-registrations.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();

    // Calculate total merla when any input changes
    $('.calculate-total').on('input', function() {
        calculateTotalMerla();
    });

    function calculateTotalMerla() {
        const projectId = $('#project_id').val();
        const kanal = parseFloat($('#kanal').val()) || 0;
        const merla = parseFloat($('#merla').val()) || 0;
        const squareFeet = parseFloat($('#square_feet').val()) || 0;

        if (!projectId) {
            alert('@lang("messages.select-project-first")');
            return;
        }

        $.ajax({
            url: '{{ route("land-registrations.calculate") }}',
            type: 'POST',
            data: {
                project_id: projectId,
                kanal: kanal,
                merla: merla,
                square_feet: squareFeet,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update the total merla textbox
                $('#total_merla_display').val(response.total_merla.toFixed(4));
                $('#total_merla').val(response.total_merla.toFixed(4));
            },
            error: function(xhr) {
                console.error('@lang("messages.calculation-error"):', xhr.responseText);
            }
        });
    }

    // Initial calculation on page load
    @if(isset($landRegistration) && $landRegistration->project_id)
        calculateTotalMerla();
    @endif
});
</script>
@endpush
