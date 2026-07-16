$(document).on('click', 'body *', function() {
    $('.debit').on("input", function() {
        doAmountTotal();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total_debit').text("");

        var totalAmount = 0;
        $(".debit").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#total_debit').val(totalAmount.toFixed(2));
    }
});

$(document).on('click', 'body *', function() {
    $('.credit').on("input", function() {
        doAmountTotal();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total_credit').text("");
        var totalAmount = 0;
        $(".credit").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#total_credit').val(totalAmount.toFixed(2));
    }
});
