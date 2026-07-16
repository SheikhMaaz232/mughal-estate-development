
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


$('#party_id, #project_id').on('change', function () {

    var partyCode = $('#party_id').val() || '';
    var projectCode = $('#project_id').val() || '';

    $("#detail_account_id")
        .empty()
        .append('<option selected disabled>' + window.customTranslations.loading + '</option>');

    let url = config.routes.getDetailAccounts;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        data: {
            party_id: partyCode,
            project_id: projectCode
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function (response) {

            $("#detail_account_id").empty();

            if (response.status === 'success' && Object.keys(response.data).length > 0) {

                $("#detail_account_id")
                    .append('<option selected disabled>' + window.customTranslations.pleaseSelect + '</option>');

                $.each(response.data, function (index, item) {
                    $("#detail_account_id").append(
                        $("<option />").val(index).text(item)
                    );
                });

            } else {

                $("#detail_account_id")
                    .append('<option selected disabled>' + window.customTranslations.noData + '</option>');
            }
        },

        error: function () {

            Swal.fire({
                icon: 'error',
                title: window.customTranslations.errorTitle,
                text: window.customTranslations.errorText
            });
        },

        complete: function () {
            $('#loading').hide();
        }
    });

});

$(document).on('click', 'body *', function () {
    $('.rate').on("input", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".quantity_" + row_id).val();
        let price = $(this).closest("tr").find(".price_" + row_id).val();
        if (parseInt(quantity) > 0) {
            $(this).closest("tr").find(".amount_" + row_id).val(quantity * price);
        } else {
            $(this).closest("tr").find(".amount_" + row_id).val('');
        }
        doAmountTotal();
    });



    $('.delete-item').on("click", function () {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
        // $('#net-amount').val(totalAmount.toFixed(2));
    }

    $(".price, #gross-amount, #tax, #shipping, #otherAmount").on("input", function () {
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        let tax = $("#tax").val() ? $("#tax").val() : 0;
        let shipping = $("#shipping").val() ? $("#shipping").val() : 0;
        let otherAmount = $("#otherAmount").val() ? $("#otherAmount").val() : 0;

        var totalLessAmount = parseInt(tax) + parseInt(shipping) + parseInt(
            otherAmount);

        $('#net-amount').val((totalAmount + (totalLessAmount || 0)).toFixed(2));
    })



});

$(document).ready(function () {

    // Function to calculate totals
    function calculateTotals() {
        let totalQuantity = 0;

        // Loop through each row
        $('input.quantity').each(function () {
            const val = parseFloat($(this).val()) || 0;
            totalQuantity += val;
        });

        // Set totals in footer fields
        $('#total_quantity').val(totalQuantity.toFixed(2));
    }

    // Calculate totals on page load
    calculateTotals();

    // Recalculate whenever received quantity changes
    $(document).on('input', '.quantity', function () {
        calculateTotals();
    });
    $(document).on('click', '.delete-item', function () {
        calculateTotals();
    });
});


$(document).ready(function () {
    $('.price, .quantity').on("input", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".quantity_" + row_id).val();
        let price = $(this).closest("tr").find(".price_" + row_id).val();
        // console.log(row_id + ", " + quantity + ", " + price);
        if (parseInt(quantity) > 0) {
            $(this).closest("tr").find(".amount_" + row_id).val(quantity * price);
        } else {
            $(this).closest("tr").find(".amount_" + row_id).val('');
        }
        doAmountTotal();
    });


    doAmountTotal();

    $('.delete-item').on("click", function () {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
        // $('#net-amount').val(totalAmount.toFixed(2));
    }

    $(".price, #gross-amount, #tax, #shipping, #otherAmount").on("input", function () {
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        let tax = $("#tax").val() ? $("#tax").val() : 0;
        let shipping = $("#shipping").val() ? $("#shipping").val() : 0;
        let otherAmount = $("#otherAmount").val() ? $("#otherAmount").val() : 0;

        var totalLessAmount = parseInt(tax) + parseInt(shipping) + parseInt(
            otherAmount);

        $('#net-amount').val((totalAmount + (totalLessAmount || 0)).toFixed(2));
    })

});
