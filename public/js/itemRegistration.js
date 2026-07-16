
$('#main-head').on('change', function () {
    var mainCode = $('#main-head :selected').val();

    $("#control-head").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');

    let url = config.routes.getControlHeads.replace(':id', mainCode);
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#control-head").empty();

            if (response.status === 'success' && Object.keys(response.data).length > 0) {

                $("#control-head").append('<option selected disabled>' + window.customTranslations.pleaseSelect + '</option>');

                $.each(response.data, function (index, item) {
                    $("#control-head").append($("<option />").val(index).text(item));
                });

            } else {
                $("#control-head").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
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

$('#control-head').on('change', function () {
    var controlCode = $('#control-head :selected').val();
    $("#sub-head").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');

    let url = config.routes.getSubHeads.replace(':id', controlCode);
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#sub-head").empty();

            if (response.status === 'success' && Object.keys(response.data).length > 0) {
                $("#sub-head").append('<option selected disabled>' + window.customTranslations.selectSubHead + '</option>');

                $.each(response.data, function (i, item) {
                    $("#sub-head").append($("<option />").val(i).text(item));
                });
            } else {
                $("#sub-head").append('<option selected disabled>' + window.customTranslations.noSubHeads + '</option>');
            }
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function () {
            // $('#account_code').val('');
            Swal.fire({
                icon: 'error',
                title: window.customTranslations.errorTitle,
                text: window.customTranslations.errorText
            });
        }
    });
});

$('#sub-head').on('change', function () {
    var subCode = $('#sub-head :selected').val();
    // $("#account_code").val('');
    $("#sub-sub-head").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');

    let url = config.routes.getSubSubHeads.replace(':id', subCode);

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#sub-sub-head").empty();

            if (response.status === 'success' && Object.keys(response.data).length > 0) {
                $("#sub-sub-head").append('<option selected disabled>' + window.customTranslations.selectSubSubHead + '</option>');

                $.each(response.data, function (key, value) {
                    $("#sub-sub-head").append($("<option />").val(key).text(value));
                });

            } else {
                $("#sub-sub-head").append('<option selected disabled>' + window.customTranslations.noSubSubHeads + '</option>');
            }
        },
        complete: function () {
            // $('#loading').hide();
        },

    });
});

// $('#sub-sub-head').on('change', function () {
//     var subSubCode = $('#sub-sub-head :selected').val();
//     // $("#account_code").val('');
//     $("#sub-sub-sub-head").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');

//     let url = config.routes.getSubSubSubHeads.replace(':id', subSubCode);

//     $.ajax({
//         url: url,
//         type: 'GET',
//         dataType: 'json',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (response) {
//             $("#sub-sub-sub-head").empty();

//             if (response.status === 'success' && Object.keys(response.data).length > 0) {
//                 $("#sub-sub-sub-head").append('<option selected disabled>' + window.customTranslations.selectSubSubSubHead + '</option>');

//                 $.each(response.data, function (key, value) {
//                     $("#sub-sub-sub-head").append($("<option/>").val(key).text(value));
//                 });
//             } else {
//                 $("#sub-sub-sub-head").append('<option selected disabled>' + window.customTranslations.noSubSubSubHeads + '</option>');
//             }
//         },
//         complete: function () {
//             $('#loading').hide();
//         },

//     });
// });



$('#sub-sub-head').on('change', function () {

    var subSubCode = $('#sub-sub-head').val();

    $("#sub-sub-sub-head")
        .empty()
        .append('<option selected disabled>' + window.customTranslations.loading + '</option>');

    let url = config.routes.getSubSubSubHeads.replace(':id', subSubCode);

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function (response) {

            $("#sub-sub-sub-head").empty();

            if (response.status === 'success' && Object.keys(response.data).length > 0) {

                // Select All option
                $("#sub-sub-sub-head").append(
                    $('<option/>')
                        .val('all')
                        .text(window.customTranslations.selectAll)
                );

                $.each(response.data, function (key, value) {
                    $("#sub-sub-sub-head").append(
                        $('<option/>')
                            .val(key)
                            .text(value)
                    );
                });

            } else {

                $("#sub-sub-sub-head").append(
                    '<option selected disabled>' +
                    window.customTranslations.noSubSubSubHeads +
                    '</option>'
                );
            }

            // Refresh Select2
            $('#sub-sub-sub-head').trigger('change');
        },

        complete: function () {
            $('#loading').hide();
        }
    });
});


// Select All functionality
$(document).on('change', '#sub-sub-sub-head', function () {

    let selectedValues = $(this).val() || [];

    if (selectedValues.includes('all')) {

        let allValues = [];

        $('#sub-sub-sub-head option').each(function () {

            let value = $(this).val();

            if (value && value !== 'all') {
                allValues.push(value);
            }
        });

        $('#sub-sub-sub-head')
            .val(allValues)
            .trigger('change');
    }
});

$(document).ready(function () {
    $('.select2').select2();
});

$('#code').on('keypress', function (event) {
    if (event.key === "Enter") {
        var code = $('#code :selected').val();
        let url = config.routes.getParty + '/' + code;
        // let url = "{{ url('co-inv-party/get-party-account') }}" + '/' + subCode;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $("#party").val(response.account_name);
            },
            complete: function () {
                $('#loading').css('display', 'none');
            },
            error: function (errorThrown) {
                $('#party').val('');
                var errors = errorThrown.responseJSON.errors;
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                })
            }
        })
    }
})

$('#party').on('change', function () {
    var name = $('#party :selected').text();

    let url = config.routes.getPartyCode + '/' + name;
    // let url = "{{ url('co-inv-party/get-party-account') }}" + '/' + subCode;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#code").val(response.account_code);
        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('#code').val('');
            var errors = errorThrown.responseJSON.errors;
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
            })
        }
    })
})

$('#product').on('change', function () {
    var name = $('#product :selected').text();

    let url = config.routes.getProductPrice + '/' + name;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#price").val(response.price);
        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('').val('');
            var errors = errorThrown.responseJSON.errors;
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
            })
        }
    })
})


document.addEventListener("DOMContentLoaded", function () {
    const kanalInput = document.getElementById('kanal');
    const marlaInput = document.getElementById('marla');
    const squareFeetInput = document.getElementById('square_feet');
    const projectSelect = document.getElementById('project');

    const totalMarlaInput = document.querySelector('input[name="total_marla"]');
    const totalSquareFeetInput = document.querySelector('input[name="total_square_feet"]'); // hidden/input field
    const amountInput = document.getElementById("amount_in_pkr");
    const totalAmountInput = document.getElementById("total_amount");
    const facingSelect = document.getElementById("front_id");


    let projectSquareFeet = 0;
    let warningShown = false;

    // 🔹 Facing percentage mapping
    const facingPercentages = {
        1: 15,
        2: 25,
        3: 25,
        4: 25,
        5: 25,
        6: 10,
        7: 20,
        8: 20,
        9: 20,
        10: 20,
        11: 10,
        12: 10,
        13: 25,
        14: 0,
        15: 10,
        16: 20,
        17: 25,
        18: 10,
        19: 20,
        20: 20,
        21: 25,
        22: 20,
        23: 20,
        24: 25,
        25: 25
    };

    // 🔹 Totals calculation (marla + sq.ft.)
    function calculateTotals() {
        const kanal = parseFloat(kanalInput.value) || 0;
        const marla = parseFloat(marlaInput.value) || 0;
        const squareFeet = parseFloat(squareFeetInput.value) || 0;

        // MARLA calculation
        let totalMarla = marla + (kanal * 20);

        if (squareFeet > 0) {
            if (projectSquareFeet > 0) {
                totalMarla += (squareFeet / projectSquareFeet);
            } else {
                if (!warningShown) {
                    warningShown = true;
                    Swal.fire({
                        icon: 'warning',
                        title: customTranslations.selectProjectFirst,
                        text: customTranslations.pleaseSelectProject
                    });
                }
                squareFeetInput.value = ""; // clear value
                squareFeetInput.blur();
            }
        }

        totalMarlaInput.value = totalMarla > 0 ? totalMarla.toFixed(2) : "0";

        // SQUARE FEET calculation
        let totalSqFt = 0;
        if (projectSquareFeet > 0) {
            totalSqFt =
                (kanal * 20 * projectSquareFeet) + // kanal → sq.ft
                (marla * projectSquareFeet) +      // marla → sq.ft
                squareFeet;                        // direct sq.ft
        } else {
            totalSqFt = squareFeet;
        }

        totalSquareFeetInput.value = totalSqFt > 0 ? totalSqFt.toFixed(2) : "0";
    }

    // 🔹 Total amount calculation
    function calculateTotalAmount() {
        const totalSqFt = parseFloat(totalSquareFeetInput.value) || 0;
        const rate = parseFloat(amountInput.value) || 0;
        const facingId = parseInt(facingSelect.value) || null;

        if (totalSqFt > 0 && rate > 0 && projectSquareFeet > 0) {
            let sumAns = (totalSqFt / projectSquareFeet) * rate;

            if (facingPercentages[facingId]) {
                const percentage = facingPercentages[facingId];
                sumAns += sumAns * (percentage / 100);
            }

            totalAmountInput.value = sumAns.toFixed(2);
        } else {
            totalAmountInput.value = "";
        }
    }

    // 🔹 AJAX project squareFeet fetch
    $('#project').on('change', function () {
        const projectId = this.value;

        if (!projectId) {
            projectSquareFeet = 0;
            calculateTotals();
            calculateTotalAmount();
            return;
        }

        let url = config.routes.getProjectSquareFeet.replace(':id', projectId);

        $.get(url, function (response) {
            projectSquareFeet = parseFloat(response.squareFeet) || 0;
            $('#company_id').val(response.companyId);

            calculateTotals();
            calculateTotalAmount();
        }).fail(function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not fetch project square feet.'
            });
        });
    });

    // 🔹 Event bindings
    squareFeetInput.addEventListener('input', function () {
        calculateTotals();
        calculateTotalAmount();
    });

    kanalInput.addEventListener('input', function () {
        calculateTotals();
        calculateTotalAmount();
    });

    marlaInput.addEventListener('input', function () {
        calculateTotals();
        calculateTotalAmount();
    });

    amountInput.addEventListener("input", function () {
        calculateTotalAmount();
    });

    $('#front_id').on('change', function () {
        calculateTotalAmount();
    });

    $('#project').trigger('change');

});


