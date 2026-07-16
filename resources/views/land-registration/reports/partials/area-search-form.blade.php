<form action="{{ route('land-report.area-summary') }}" method="POST">
    @csrf
    <div class="filter-section">
        <div class="row g-3">
            {{--  <div class="col-md-3">
                <label for="cnic_no" class="form-label">CNIC No (From)</label>
                <select class="form-select chosen-select" id="cnic_no" name="cnic_no">
                    <option value="">Select CNIC</option>
                    @foreach($dropdownData['sellers'] as $seller)
                        <option value="{{ $seller->id }}" {{ ($filters['id'] ?? '') == $seller->id ? 'selected' : '' }}>
                            {{ $seller->name_ur }} - {{ $seller->cnic ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>  --}}

            {{--  <div class="col-md-3">
                <label for="to_cnic_no" class="form-label">CNIC No (To)</label>
                <select class="form-select chosen-select" id="to_cnic_no" name="to_cnic_no">
                    <option value="">Select CNIC</option>
                    @foreach($dropdownData['buyers'] as $buyer)
                        <option value="{{ $buyer->id }}" {{ ($filters['to_cnic_no'] ?? '') == $buyer->id ? 'selected' : '' }}>
                            {{ $buyer->account_name }} - {{ $buyer->cnic ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>  --}}

            <div class="col-md-3">
                <label for="khawat_no" class="form-label">Khawat Number</label>
                <input type="text" class="form-control" id="khawat_no" name="khawat_no"
                       value="{{ $filters['khawat_no'] ?? '' }}" placeholder="Enter Khawat numbers">
            </div>


            <div class="col-md-12">
                <button type="submit" name="search" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Search
                </button>
            </div>
        </div>
    </div>
</form>
