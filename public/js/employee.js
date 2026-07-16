        // Contact section counter
        let contactCounter = 1;

        // Bank section counter
        let bankCounter = 1;

        let allowanceCounter = 1;

        let deductionCounter = 1;

        // Function to add a new contact section
        function addContactSection() {
            const contactSections = document.getElementById('contact-sections');
            const newSection = document.createElement('div');
            newSection.className = 'dynamic-section contact-section';
            newSection.innerHTML = `
                <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>@lang('Contact Type')</label>
                        <select name="contacts[${contactCounter}][type]" class="form-select">
                            <option value="personal">@lang('Personal')</option>
                            <option value="work">@lang('Work')</option>
                            <option value="emergency">@lang('Emergency')</option>
                            <option value="other">@lang('Other')</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Phone Number')</label>
                        <input type="text" name="contacts[${contactCounter}][phone]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Email Address')</label>
                        <input type="email" name="contacts[${contactCounter}][email]" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>@lang('Address')</label>
                        <textarea name="contacts[${contactCounter}][address]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>@lang('Notes')</label>
                        <textarea name="contacts[${contactCounter}][notes]" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            `;
            contactSections.appendChild(newSection);
            contactCounter++;
        }

        // Function to add a new bank section
        function addBankSection() {
            const bankSections = document.getElementById('bank-sections');
            const newSection = document.createElement('div');
            newSection.className = 'dynamic-section bank-section';
            newSection.innerHTML = `
                <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>@lang('Bank Name')</label>
                        <input type="text" name="banks[${bankCounter}][name]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Account Number')</label>
                        <input type="text" name="banks[${bankCounter}][account_number]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Account Title')</label>
                        <input type="text" name="banks[${bankCounter}][account_title]" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>@lang('IBAN')</label>
                        <input type="text" name="banks[${bankCounter}][iban]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Branch Code')</label>
                        <input type="text" name="banks[${bankCounter}][branch_code]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Account Type')</label>
                        <select name="banks[${bankCounter}][type]" class="form-select">
                            <option value="savings">@lang('Savings')</option>
                            <option value="current">@lang('Current')</option>
                            <option value="salary">@lang('Salary')</option>
                        </select>
                    </div>
                </div>
            `;
            bankSections.appendChild(newSection);
            bankCounter++;
        }

        // Function to remove a section
        function removeSection(element) {
            const section = element.closest('.dynamic-section');
            section.remove();
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        function addBankSection() {
            const bankSections = document.getElementById('bank-sections');
            const newSection = document.createElement('div');
            newSection.className = 'dynamic-section bank-section';
            newSection.innerHTML = `
                <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>@lang('Bank Name')</label>
                        <input type="text" name="banks[${bankCounter}][name]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Account Number')</label>
                        <input type="text" name="banks[${bankCounter}][account_number]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Account Title')</label>
                        <input type="text" name="banks[${bankCounter}][account_title]" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>@lang('IBAN')</label>
                        <input type="text" name="banks[${bankCounter}][iban]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Branch Code')</label>
                        <input type="text" name="banks[${bankCounter}][branch_code]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>@lang('Account Type')</label>
                        <select name="banks[${bankCounter}][type]" class="form-select">
                            <option value="savings">@lang('Savings')</option>
                            <option value="current">@lang('Current')</option>
                            <option value="salary">@lang('Salary')</option>
                        </select>
                    </div>
                </div>
            `;
            bankSections.appendChild(newSection);
            bankCounter++;
        }

        function addAllowanceSection() {
            const container = document.getElementById('allowance-sections');
            const section = document.createElement('div');
            section.className = 'dynamic-section allowance-section';
            section.innerHTML = `
                <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>@lang('allowance-type')</label>
                        <input type="text" name="allowances[${allowanceCounter}][type]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>@lang('amount')</label>
                        <input type="number" step="0.01" name="allowances[${allowanceCounter}][amount]" class="form-control">
                    </div>
                </div>
            `;
            container.appendChild(section);
            allowanceCounter++;
        }

        function addDeductionSection() {
            const container = document.getElementById('deduction-sections');
            const section = document.createElement('div');
            section.className = 'dynamic-section deduction-section';
            section.innerHTML = `
                <span class="remove-section" onclick="removeSection(this)"><i class="fas fa-times-circle"></i></span>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>@lang('deduction-type')</label>
                        <input type="text" name="deductions[${deductionCounter}][type]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>@lang('amount')</label>
                        <input type="number" step="0.01" name="deductions[${deductionCounter}][amount]" class="form-control">
                    </div>
                </div>
            `;
            container.appendChild(section);
            deductionCounter++;
        }

        // Common remove function
        function removeSection(element) {
            const section = element.closest('.dynamic-section');
            section.remove();
        }
