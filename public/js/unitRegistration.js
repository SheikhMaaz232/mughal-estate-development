$('#project').on('change', function () {
    var projectId = $('#project :selected').val();
    let url = config.routes.getProjectInformation.replace(':id', projectId);
    let currentLang = $('html').attr('lang'); // e.g., 'en' or 'ur'

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status === 'success' && response.data) {
                const phaseName = currentLang === 'ur' ? response.data.phase_ur : response.data.phase_en;

                // Set input field value for phase
                $('#phase').val(phaseName);

                // Set hidden field value for company_id
                $('#company_id').val(response.data.company_id);
            } else {
                $('#phase').val('');
                $('#company_id').val('');
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: window.translations.errorTitle,
                text: window.translations.errorText
            });
        },
        complete: function () {
            $('#loading').hide();
        }
    });
});

function fetchProductData(productId) {

    let url = config.routes.getProductInformation.replace(':id', productId);
    let currentLang = $('html').attr('lang'); // 'en' or 'ur'

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status === 'success') {
                const data = response.data;

                // Set volume and coverage values
                $('#base_volume').val(data.base_volume);
                $('#base_coverage').val(data.base_coverage);
                $('.base_volume').val(data.base_volume);
                $('.base_coverage').val(data.base_coverage);

                // Set language-dependent unit names
                const volumeUnit = currentLang === 'ur' ? data.volume_unit_ur : data.volume_unit_en;
                const coverageUnit = currentLang === 'ur' ? data.coverage_unit_ur : data.coverage_unit_en;

                $('#base_volume_unit_name').val(volumeUnit);
                $('#base_coverage_unit_name').val(coverageUnit);
                $('.base_volume_unit_name').val(volumeUnit);
                $('.base_coverage_unit_name').val(coverageUnit);

                // Set hidden unit IDs
                $('#base_volume_unit_id').val(data.base_volume_unit_id);
                $('#base_coverage_unit_id').val(data.base_coverage_unit_id);

            } else {
                // Clear fields if product not found
                $('#base_volume, #base_coverage, #base_volume_unit_name, #base_coverage_unit_name').val('');
                $('#base_volume_unit_id, #base_coverage_unit_id').val('');
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: window.translations.errorTitle,
                text: window.translations.errorText
            });
        }
    });
}

// Fetch on product dropdown change
$('#product').on('change', function () {
    const productId = $(this).val();
    fetchProductData(productId);
});

// Fetch on page load (for edit case)
$(document).ready(function () {
    const selectedProductId = $('#product').val();
    if (selectedProductId) {
        fetchProductData(selectedProductId);
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const kanalInput = document.getElementById('kanal');
    const marlaInput = document.getElementById('marla');
    const squareFeetInput = document.getElementById('square_feet');
    const totalMarlaInput = document.querySelector('input[name="total_marla"]');

    function calculateTotalMarla() {
        const kanal = parseFloat(kanalInput.value) || 0;
        const marla = parseFloat(marlaInput.value) || 0;
        const yard = parseFloat(yardInput.value) || 0;

        const totalMarla = (yard / 30) + marla + (kanal * 20);
        totalMarlaInput.value = totalMarla.toFixed(2); // Round to 2 decimal places
    }

    // Recalculate on every input change
    kanalInput.addEventListener('input', calculateTotalMarla);
    marlaInput.addEventListener('input', calculateTotalMarla);
    yardInput.addEventListener('input', calculateTotalMarla);
});
