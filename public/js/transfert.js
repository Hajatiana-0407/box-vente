// Nouveaux js ************************* 
let quantite_dispo_tab = [];
let real_unite = [];
let data_client = [];
// qte de chaque produit dans le panier
let qte_produit_panier = {};

// pour stocke toutes les tableaux d'unite deja dans le panier 
let allunite_panier = {};

// quantite par produit dans le panier pour gerer la quantite reste disponible 
var tableau_panier = {};
// changement d'unité



let quantite_dispo = 0;
let type_produit = '';

function vider() {
    $('#reference').val('');
    $('#designation').val('');
    $('#unite').html('');
    $('#qte_dipo').val('');
    $('#quantite').val('');
}

$(document).on('change', '#reference', function () {
    const reference = $(this).val();
    const id_pv = $("#pv_vente").val();
    vider();
    if (reference != '') {
        $.ajax({
            method: 'post',
            url: base_url('Appro/recherche_produit'),
            data: { ref: reference, id_pv: id_pv },
            dataType: 'json'
        }).done(function (recheche_produit) {
            if (recheche_produit.success) {
                const produit = recheche_produit.produit;
                type_produit = produit.type;
                const type_recherche = recheche_produit.type;
                $('#idProduit').val(produit.idProduit);
                $('#reference').val(produit.refProduit);
                $('#designation').val(produit.designation);
                $('#fiche').val(produit.fiche);

               


                $('#quantite').val(1)

                $('.with_qte').addClass('d-none');
                // traitement 
                if (type_recherche == 'reference') {

                    if (recheche_produit.series) {
                        // des numeros de serie

                        if (recheche_produit.series[0]) {
                            const series = recheche_produit.series;
                            console.log(series);

                            $('.numero_liste_').removeClass('d-none');
                            let content = ``;
                            for (let i = 0; i < series.length; i++) {
                                const element = series[i];
                                content += `
										 <option value="${element.numero}" data-couleur ='${element.couleur}' data-imei1 ='${element.imei1}' data-imei2 ='${element.imei2}' >${element.numero}</option>
										` ;
                            }
                            $('#numero_liste').html(content);


                            $('#couleur').val(series[0].couleur);
                            $('#imei1').val(series[0].imei1);
                            $('#imei2').val(series[0].imei2);
                        } else {
                            // tout les numero de serie sont vente 
                            Myalert.erreur('Tous les numéros de série de ce produit sont déjà vendus.');
                            vider();
                        }

                    }
                    else {
                        // sans numero de serie 
                        $('.numero_liste_').addClass('d-none');
                        // verification du quantite dans le stock 
                        $.ajax({
                            method: 'post',
                            url: base_url('Vente/getStock'),
                            data: { idProduit: produit.idProduit, id_pv: id_pv },
                            dataType: 'json',
                        }).done(function (getStock) {
                            if (getStock.success) {
                                let quantite = getStock.quantite;
                                let in_the_panier = 0;
                                // PRENDRE LES QUANTITE DANS LE PANIER ICI 
                                if (tableau_panier[produit.refProduit] && tableau_panier[produit.refProduit].length > 0) {
                                    in_the_panier = tableau_panier[produit.refProduit][0].quantite;
                                }
                                quantite_dispo = quantite - in_the_panier;
                                $('.with_qte').removeClass('d-none');
                                $('#qte_dipo').val(quantite_dispo);
                                $('#quantite').val(1);
                                $('#montant_show').val(prix.toLocaleString("fr-FR") + ' AR ');

                                $('#quantite').focus();
                            } else {
                                Myalert.erreur('Stock insuffisant. Veuillez approvisionner ce produit.');
                                vider();
                            }
                        }).fail(function (err) {
                        })
                    }
                } else {
                    $('.numero_liste_').removeClass('d-none');
                    let content = `
									 <option value="${produit.numero}">${produit.numero}</option>
									` ;
                    $('#numero_liste').html(content);


                    $('#couleur').val(produit.couleur);
                    $('#imei1').val(produit.imei1);
                    $('#imei2').val(produit.imei2);
                }
            } else {
                Myalert.erreur('Cette référence n\'existe pas.')
                vider();
            }


        }).fail(function () {
            console.error('Erreur dans la recuperation du produit');
        })
    }
    else {
        // vider
        vider();
    }
})


// AJOUT AU PANIER 
$(document.body).on("click", "#valider", function () {
    const id_pv = $("#pv_vente").val();
    const id_pv_destination = $("#pv_vente_destination").val();
    if (id_pv != id_pv_destination) {
        const reference = $("#reference").val();
        const idProduit = $("#idProduit").val();
        const type_produit = $('#type_produit').val();
        const designation = $("#designation").val();
        const prix = parseInt($("#prix").val(), 10);
        const quantite = $("#quantite").val();

        const unite_selectione = $('#unite').find('option:selected');

        const montant = parseInt($("#montant").val(), 10);


        // vérification du quantité 
        const identification = $(unite_selectione).data('id');
        if (reference != '' && idProduit != '' && designation != '' && quantite > 0 && reference != '' && prix != '' && montant != '') {


            let ok_to_add = true;
            if (type_produit == 'autre' && quantite_dispo < quantite) {
                ok_to_add = false;
            }


            if (ok_to_add) {
                $('#real_validation').click();
            }
            else {
                // stock insufisant
                Myalert.erreur('Le stock est insuffisant.');
            }
        }
    } else {
        // point de vente identique 
        Myalert.erreur('Le dépôt source et le dépôt de destination sont identiques.');
    }
});
// AJOUT AU PANIER *


$(document).on('click', '.delete', function () {
    let idtransfert = $(this).data('idtransfert');
    let elem = $(this);
    Myalert.delete();
    $(document).on('click', '#confirmeDelete', function () {

        $.ajax({
            method: 'post',
            url: base_url('Transfert/delete'),
            dataType: 'json',
            data: { idtransfert: idtransfert }
        }).done(function (response) {
            if (response.success) {
                $(elem).closest('tr').remove();
                $('#cancelDelete').click();
                Myalert.deleted();
            }
            else {
                Myalert.erreur('Une erreur c\'est produite');
            }
        }).fail(function (err) {
            console.error('erreur dans la suppression :' + err);
        })
    })

})


$(document).on('click', '.recevoir', function () {
    const idtransfert = $(this).data('idtransfert');
    const elem = $(this);

    Myalert.delete('Êtes-vous sûr de vouloir recevoir ce transfert ?');
    $(document).on('click', '#confirmeDelete', function () {
        $('#cancelDelete').click();
        $.ajax({
            method: 'post',
            url: base_url('Transfert/receive'),
            dataType: 'json',
            data: { idtransfert: idtransfert }
        }).done(function (response) {
            if (response.success) {
                $('#' + idtransfert + '_td').html(`
                    reçu
                    <i class="fas fa-check"></i>
                                                `);
                $('#' + idtransfert + '_action').html(`<button class="btn btn-danger mr-2" type="button" disabled>
                                                            <i class="fa-solid fa-trash"></i>
                                                            
                                                        </button>
                                                        <button type="button" class="btn btn-primary recevoir" disabled>
                                                                <i class="fas fa-hand-holding-medical"></i>
                                                        </button>`);

                Myalert.added('Réception effectuée.')
            } else {
                Myalert.erreur('Une erreur s\'est produite.')
            }
        }).fail(function () {

        })
    });
})

// Nouveaux js ************************* 

