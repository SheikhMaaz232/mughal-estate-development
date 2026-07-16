
$(document).ready(function () {
    function fetchProductSizeDetail(el) {
        var row_id = $(el).closest("tr").find(".row_id").val();
        var name = $(el).val();

        if (!name) return; // skip if empty

        let url = config.routes.getProductSizeDetail.replace(':id', name);

        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $(".measurement_unit_" + row_id).val(response.data);
            },
            complete: function () {
                $('#loading').css('display', 'none');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                });
            }
        });
    }

    // 🔹 Run on input
    $(".product").on('input', function () {
        fetchProductSizeDetail(this);
    });

    // 🔹 ALSO run once automatically after page load
    $(".product").each(function () {
        fetchProductSizeDetail(this);
    });
});

$(document).on('click', 'body *', function () {
    $('.quantity').on("input", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".quantity_" + row_id).val();
        let price = $(this).closest("tr").find(".price_" + row_id).val();
        if (parseInt(quantity) > 0) {
            $(this).closest("tr").find(".amount_" + row_id).val(quantity * price);
        } else {
            $(this).closest("tr").find(".amount_" + row_id).val('');
        }
    });
});

$(document).ready(function () {

    // Function to calculate totals
    function calculateTotals() {
        let totalPoQuantity = 0;
        let totalReceivedQty = 0;

        // Loop through each row
        $('input.po_quantity').each(function () {
            const val = parseFloat($(this).val()) || 0;
            totalPoQuantity += val;
        });

        $('input.received_qty').each(function () {
            const val = parseFloat($(this).val()) || 0;
            totalReceivedQty += val;
        });

        // Set totals in footer fields
        $('#total_po_quantity').val(totalPoQuantity.toFixed(2));
        $('#total_received_qty').val(totalReceivedQty.toFixed(2));
    }

    // Calculate totals on page load
    calculateTotals();

    // Recalculate whenever received quantity changes
    $(document).on('input', '.received_qty', function () {
        calculateTotals();
    });
});

document.getElementById('grn-form').addEventListener('submit', function (e) {
    let pOQty = parseFloat(document.getElementById('total_po_quantity').value) || 0;
    let receivedQty = parseFloat(document.getElementById('total_received_qty').value) || 0;

    if (receivedQty > pOQty) {
        e.preventDefault();

        Swal.fire({
            icon: 'error',
            title: window.customTranslations.errorTitle2,
            text: window.customTranslations.errorText2,
            confirmButtonText:  window.customTranslations.confirmButtonText,
        });
    }
});
