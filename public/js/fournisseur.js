$(document).on('click', '#teste_validation', function () {
    shwoSpinner('#valider', ["entreprise", "adresse", 'telephone']);
    const tel = $('#telephone').val();
    const entreprise = $('#entreprise').val();
    const email = $('#email').val();
    $.ajax({
        method: 'post',
        url: base_url('Fournisseur/duplicate'),
        data: { telephone: tel, entreprise: entreprise, email: email },
        dataType: 'json'
    }).done(function (response) {
        if (response.success) {
            $('#valider').removeClass('d-none');
            $('#teste_validation').addClass('d-none');
            $('#valider').click();

            $('#telephone_erreur').addClass('d-none');
        } else {
            let type = response.type;
            if (type == 'email') {
                $('#email_erreur').removeClass('d-none');
                $('#email').focus();
            } else if (type == 'entreprise') {
                $('#entreprise_erreur').removeClass('d-none');
                $('#entreprise').focus();
            } else {
                $('#telephone_erreur').removeClass('d-none');
                $('#telephone').focus();
            }
            $('#spinner_validation').addClass('d-none');
            $('.fas.fa-check').removeClass('d-none');

        }
    }).fail(function (err) {
        console.error(err);
    })


})

$(document).ready(function () {
    $(document).on('click', '.delete', function () {
        Myalert.delete('Cette action va supprimer tous les approvisionnements et les commandes liés à ce fournisseur. Êtes-vous sûr de vouloir continuer ');
        const idfournisseur = $(this).data('idfournisseur');
        const tel_fournisseur = $(this).data('tel_fournisseur');
        $(document).on('click', '#confirmeDelete', function () {
            $.ajax({
                method: 'post',
                url: base_url('Fournisseur/delete'),
                data: { idfournisseur: idfournisseur, tel_fournisseur: tel_fournisseur },
                dataType: 'json',
            }).done(function (result) {
                if (result) {
                    location.reload();
                }
            }).fail(function () {
                console.error('Erreur dans la suppression du fournisseur ');
            })
        })

    })

    $(document).on('click', '.edit', function () {
        const idmodif = $(this).data('idfournisseur');
        const nom = $(this).data('nom');
        const adress = $(this).data('adress');
        const tel = $(this).data('tel');
        const mail = $(this).data('mail');
        const nif = $(this).data('nif');
        const stat = $(this).data('stat');

        $('#idmodif').val(idmodif);
        $('#entreprise_modif').val(nom);
        $('#adresse_modif').val(adress);
        $('#telephone_modif').val(tel);
        $('#email_modif').val(mail);
        $('#nif_modif').val(nif);
        $('#stat_modif').val(stat);

        $('#telephone_erreur_modif').addClass('d-none');


    })
    $(document).on('click', '#modification_teste', function () {
        const id_not = $('#idmodif').val();
        const tel = $('#telephone_modif').val();
        const entreprise = $('#entreprise_modif').val();
        const email = $('#email_modif').val();
        $.ajax({
            method: 'post',
            url: base_url('Fournisseur/duplicate'),
            data: { telephone: tel, id_not: id_not, entreprise: entreprise, email: email },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                $('#modification').click();
                $('#telephone_erreur_modif').addClass('d-none');
            } else {
                let type = response.type;
                if (type == 'email') {
                    $('#email_erreur_modif').removeClass('d-none');
                    $('#email_modif').focus();
                } else if (type == 'entreprise') {
                    $('#entreprise_erreur_modif').removeClass('d-none');
                    $('#entreprise_modif').focus();
                } else {
                    $('#telephone_erreur_modif').removeClass('d-none');
                    $('#telephone').focus();
                }
            }
        }).fail(function (err) {
            console.error(err);
        })
    })
})