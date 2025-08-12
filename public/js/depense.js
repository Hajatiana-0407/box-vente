$(document).on('click', '#valider', function () {
    shwoSpinner(this, ['raison', 'montant']);
})

$(document).ready(function () {
    $(document.body).on('click', '#valider', function () {
        if ($('#raison').val() != '' && $('#montant').val() != '') {
            $('#valider').attr('type', 'submit');
            $('#valider').click();
        }
    })
    $(document).on('click', '.delete', function () {
        let id = $(this).data('id');

        Myalert.delete() ; 
        $(document.body).on('click', '#confirmeDelete', function () {
            console.log(id);
            $.ajax({
                method: 'POST',
                url: base_url('Depense/deleteit'),
                data: { id: id },
                dataType: 'json'
            }).done(function (data) {
                if (data.success) {
                    window.location.reload();
                }
            }).fail(function () {
                console.error('Erreur dans le suppression');
            })
        })
    })


    $(document.body).on('click', '.edit', function () {
        let montant = $(this).data('montant');
        let raison = $(this).data('raison');
        let id = $(this).data('id');

        $('#iddepensemodif').val(id);
        $('#montant_').val(montant);
        $('#raison_').val(raison);
    })
    $(document.body).on('click', '#modifier', function () {
        let montant = $('#montant_').val()
        let raison = $('#raison_').val()

        if (montant != '' && raison != '') {
            $(this).attr('type', 'submit');
            $(this).click();
        }
    })
})


