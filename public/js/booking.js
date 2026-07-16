$('#party_id').on('change', function () {
    var partyCode = $('#party_id :selected').val();

    $("#detail_account_id").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');

    let url = config.routes.getDetailAccounts.replace(':id', partyCode);
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#detail_account_id").empty();

            if (response.status === 'success' && Object.keys(response.data).length > 0) {

                $("#detail_account_id").append('<option selected disabled>' + window.customTranslations.pleaseSelect + '</option>');

                $.each(response.data, function (index, item) {
                    $("#detail_account_id").append($("<option />").val(index).text(item));
                });

            } else {
                $("#detail_account_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
            }
        },
        error: function () {
            // $('#account_code').val('');
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

document.addEventListener("DOMContentLoaded", function () {
    const addValueInput = document.getElementById("add_value");
    const discountInput = document.getElementById("discount");
    const totalAmountInput = document.getElementById("total_amount");

    // Get base amount from backend (the one stored in DB)
    let baseAmount = parseFloat(totalAmountInput.value) || 0;

    function calculateTotal() {
        let addValue = parseFloat(addValueInput.value) || 0;
        let discount = parseFloat(discountInput.value) || 0;

        let finalAmount = baseAmount + addValue - discount;

        totalAmountInput.value = finalAmount.toFixed(2); // keep 2 decimals
    }

    // Trigger calculation on input change
    addValueInput.addEventListener("input", calculateTotal);
    discountInput.addEventListener("input", calculateTotal);

    calculateTotal();
});

$(document).on('click', 'body *', function () {
    $('.number, .pay_amount').on("input", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let number = parseFloat($(this).closest("tr").find(".number_" + row_id).val().trim()) || 0;
        let pay_amount = parseFloat($(this).closest("tr").find(".pay_amount_" + row_id).val().trim()) || 0;


        if (parseInt(number) > 0) {
            let total = number * pay_amount || 0;
            $(this).closest("tr").find(".calculated_total_amount_" + row_id).val(total);
        } else {
            $(this).closest("tr").find(".calculated_total_amount_" + row_id).val('0');
        }
        doAmountTotal();
    });
    $('.delete-item2').on("click", function () {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#grand-total').text("");
        var totalAmount = 0;
        $(".calculated_total_amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#grand-total').val(totalAmount.toFixed(2));
        // $('#net-amount').val(totalAmount.toFixed(2));
    }
});



document.getElementById('booking-form').addEventListener('submit', function (e) {
    let total = parseFloat(document.getElementById('total_amount').value) || 0;
    let grandTotal = parseFloat(document.getElementById('grand-total').value) || 0;

    if (total !== grandTotal) {
        e.preventDefault();

        Swal.fire({
            icon: 'error',
            title: window.customTranslations.errorTitle2,
            text: window.customTranslations.errorText2,
            confirmButtonText:  window.customTranslations.confirmButtonText,
        });
    }
});
