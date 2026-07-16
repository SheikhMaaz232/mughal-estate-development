$('#project_id').on('change', function () {
    var projectId = $('#project_id :selected').val();

    $("#detail_account_id").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');
    $("#cash_account_id").empty().append('<option selected disabled>' + window.customTranslations.loading + '</option>');

    let url = config.routes.getCashAccountsDetailAccounts.replace(':id', projectId);
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#detail_account_id").empty();
            $("#cash_account_id").empty();

            if (response.status === 'success') {
                // Render Detail Accounts (receivables)
                if (response.data.receivables && Object.keys(response.data.receivables).length > 0) {
                    $("#detail_account_id").append('<option selected disabled>' + window.customTranslations.pleaseSelect + '</option>');
                    $.each(response.data.receivables, function (index, item) {
                        $("#detail_account_id").append($("<option />").val(index).text(item));
                    });
                } else {
                    $("#detail_account_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
                }

                // Render cashAccounts
                if (response.data.cashAccounts && Object.keys(response.data.cashAccounts).length > 0) {
                    $("#cash_account_id").append('<option selected disabled>' + window.customTranslations.pleaseSelect + '</option>');
                    $.each(response.data.cashAccounts, function (index, item) {
                        $("#cash_account_id").append($("<option />").val(index).text(item));
                    });
                } else {
                    $("#cash_account_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
                }

            } else {
                $("#detail_account_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
                $("#cash_account_id").append('<option selected disabled>' + window.customTranslations.noData + '</option>');
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
