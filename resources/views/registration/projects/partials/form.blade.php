@csrf

@if (isset($project))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group  mb-3">
            <label for="name_en">@lang('messages.name') @lang('messages.english')</label>
            <input type="text" class="form-control" id="name_en" name="name_en" maxlength="100"
                value="{{ old('name_en', $project->name_en ?? '') }}">
            @error('name_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group   mb-3">
            <label for="description_en">@lang('messages.description') @lang('messages.english')</label>
            <textarea class="form-control" id="description_en" name="description_en" rows="3">{{ old('description_en', $project->description_en ?? '') }}</textarea>
            @error('description_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group  mb-3">
            <label for="phase_en">@lang('messages.phase') @lang('messages.english')</label>
            <input type="text" class="form-control" id="phase_en" name="phase_en" maxlength="100"
                value="{{ old('phase_en', $project->phase_en ?? '') }}">
            @error('phase_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="address_en">@lang('messages.address') @lang('messages.english')</label>
            <textarea class="form-control" id="address_en" name="address_en" rows="3">{{ old('address_en', $project->address_en ?? '') }}</textarea>
            @error('address_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 ">
        <div class="form-group  mb-3">
            <label for="name_ur">@lang('messages.name') @lang('messages.urdu')</label>
            <input type="text" class="form-control keyboardInput" id="name_ur" name="name_ur" maxlength="100"
                dir="rtl" value="{{ old('name_ur', $project->name_ur ?? '') }}">
            @error('name_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="description_ur">@lang('messages.description') @lang('messages.urdu')</label>
            <textarea type="text" class="form-control keyboardInput" rows="3" id="description_ur" name="description_ur"
                dir="rtl">{{ old('description_ur', $project->description_ur ?? '') }}</textarea>
            @error('description_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group  mb-3">
            <label for="phase_ur">@lang('messages.phase') @lang('messages.urdu')</label>
            <input type="text" class="form-control keyboardInput" id="phase_ur" name="phase_ur" maxlength="100"
                dir="rtl" value="{{ old('phase_ur', $project->phase_ur ?? '') }}">
            @error('phase_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="address_ur">@lang('messages.address') @lang('messages.urdu')</label>
            <textarea type="text" class="form-control keyboardInput" id="address_ur" name="address_ur" maxlength="250"
                dir="rtl" rows="3">{{ old('address_ur', $project->address_ur ?? '') }} </textarea>
            @error('address_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group  mb-3">
            <label for="group_id">@lang('messages.group')</label>
            <select name="group_id" id="group_id"
                class="form-control select2 form-select @error('group_id') is-invalid @enderror">
                <option value="">@lang('messages.select-group')</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}"
                        {{ old('group_id') == $group->id || (isset($project) && $project->group_id == $group->id) ? 'selected' : '' }}>
                        {{ $group->{'name_' . app()->getLocale()} }}
                    </option>
                @endforeach
            </select>
            @error('group_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="company_id">@lang('messages.company')</label>
            <select name="company_id" id="company_id"
                class="form-control select2 form-select @error('company_id') is-invalid @enderror">
                <option value="">Select Company</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}"
                        {{ old('company_id') == $company->id || (isset($project) && $project->company_id == $company->id) ? 'selected' : '' }}>
                        {{ $company->{'name_' . app()->getLocale()} }}
                    </option>
                @endforeach
            </select>

            @error('company_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="square_feet">@lang('messages.marla_in_square_feet')</label>
            <input type="number" step="any" min="0" class="form-control" id="square_feet" name="square_feet"
                value="{{ old('square_feet', $project->square_feet ?? '') }}">
            @error('square_feet')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

</div>
<div class="row">
    <h2 style="color: #8B0000">
        @lang('messages.detail-of-area-project')
    </h2>
    <h4 style="color: red">
        @lang('messages.enter-in-marla')
    </h4>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="roads_area">@lang('messages.roads_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="roads_area" name="roads_area"
                value="{{ old('roads_area', $project->roads_area ?? '') }}" onwheel="this.blur()">
            @error('roads_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="park_area">@lang('messages.park_area')</label>
            <input tytype="number" step="any" min="0" class="form-control" id="park_area" name="park_area"
                value="{{ old('park_area', $project->park_area ?? '') }}" onwheel="this.blur()">
            @error('park_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="mosque_area">@lang('messages.mosque_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="mosque_area" name="mosque_area"
                value="{{ old('mosque_area', $project->mosque_area ?? '') }}" onwheel="this.blur()">
            @error('mosque_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="cemetery_area">@lang('messages.cemetery_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="cemetery_area" name="cemetery_area"
                value="{{ old('cemetery_area', $project->cemetery_area ?? '') }}" onwheel="this.blur()">
            @error('cemetery_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="social_waste_area">@lang('messages.social_waste_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="social_waste_area" name="social_waste_area"
                 value="{{ old('social_waste_area', $project->social_waste_area ?? '') }}" onwheel="this.blur()">
            @error('social_waste_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="disposal_area">@lang('messages.disposable_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="disposable_area" name="disposal_area"
                value="{{ old('disposal_area', $project->disposal_area ?? '') }}" onwheel="this.blur()">
            @error('disposal_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="commercial_plots_area">@lang('messages.commercial_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="commercial_area" name="commercial_plots_area"
                 value="{{ old('commercial_plots_area', $project->commercial_plots_area ?? '') }}" onwheel="this.blur()">
            @error('commercial_plots_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="residential_plots_area">@lang('messages.residential_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="residential_area" name="residential_plots_area"
                 value="{{ old('residential_plots_area', $project->residential_plots_area ?? '') }}" onwheel="this.blur()">
            @error('residential_plots_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="public_buildings_area">@lang('messages.public_buildings_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="public_buildings_area" name="public_buildings_area"
                 value="{{ old('public_buildings_area', $project->public_buildings_area ?? '') }}" onwheel="this.blur()">
            @error('public_buildings_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="miscellaneous_area">@lang('messages.miscellaneous_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="miscellaneous_area" name="miscellaneous_area"
                 value="{{ old('miscellaneous_area', $project->miscellaneous_area ?? '') }}" onwheel="this.blur()">
            @error('miscellaneous_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="total_area">@lang('messages.total_area')</label>
            <input type="number" step="any" min="0" class="form-control" id="total_area" name="total_area"
                value="{{ old('total_area', $project->total_area ?? '') }}" readonly>
            @error('total_area')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group mb-3">
    <label for="project_map">@lang('messages.project-map')</label><br>

    <!-- File Upload Input -->
    <input type="file" name="project_map" id="project_map" class="form-control" onchange="previewImage(this)">

    <!-- Image Preview (Shows avatar if no image is selected) -->
    <div id="imagePreview" class="mt-2">
        {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
        <img id="previewImg"
            src="{{ isset($project) && $project->project_map ? asset('storage/' . $project->project_map) : asset('images/No-Image-Placeholder.svg.png') }}"
            alt="" class="img-thumbnail" style="max-height: 200px;">
    </div>

    @error('project_map')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-sm btn-primary">
        @if (isset($project) && $project->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
