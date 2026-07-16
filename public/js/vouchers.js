$('#project_id').on('change', function () {
    var projectId = $('#project_id :selected').val();

    $("#detail_account_id").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');
    $("#bank_id").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');

    let url = config.routes.getBanksDetailAccounts.replace(':id', projectId);
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#detail_account_id").empty();
            $("#bank_id").empty();

            if (response.status === 'success') {
                // Render Detail Accounts (Payables)
                if (response.data.payables && Object.keys(response.data.payables).length > 0) {
                    $("#detail_account_id").append('<option selected disabled>' + window.customTranslations.pleaseSelect + '</option>');
                    $.each(response.data.payables, function (index, item) {
                        $("#detail_account_id").append($("<option />").val(index).text(item));
                    });
                } else {
                    $("#detail_account_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
                }

                // Render Banks
                if (response.data.banks && Object.keys(response.data.banks).length > 0) {
                    $("#bank_id").append('<option selected disabled>' + window.customTranslations.pleaseSelect + '</option>');
                    $.each(response.data.banks, function (index, item) {
                        $("#bank_id").append($("<option />").val(index).text(item));
                    });
                } else {
                    $("#bank_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
                }

            } else {
                $("#detail_account_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
                $("#bank_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
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
