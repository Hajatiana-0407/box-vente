let all_unite = {};
let tableau = [];
let idcommande = 0;
let montant_total = 0;
let frais = 0;
$(document).on('focus', '.qte_recue', function () {
    const idunite = $(this).closest('tr').data('idunite');
    const idProduit = $(this).closest('tr').data('idproduit');
    const idcmfacture = $(this).closest('tr').data('idcmfacture');

    $('#valider').attr('disabled', '');
    $('#valider #spinner_validation').removeClass('d-none');
    $('#valider .fa-check').addClass('d-none');
    let identifiant = 0;
    let unites = [];

    if (idunite != '') {
        $.ajax({
            method: 'post',
            url: base_url('Reception/getUnite'),
            data: { idProduit: idProduit },
            dataType: 'json',
            async: false
        }).done(function (response) {
            unites = response.datas
            if (response.success) {

                for (let i = 0; i < unites.length; i++) {
                    const element = unites[i];
                    if (element.idunite == idunite) {
                        identifiant = i;
                    }
                }

            }
        }).fail(function (err) {
            console.error('erreur lors de la recherche d\'unite ');
        })
    }
    all_unite[idcmfacture] = {
        id: identifiant,
        unites: unites
    };

    $('#valider').removeAttr('disabled');
    $('#valider #spinner_validation').addClass('d-none');
    $('#valider .fa-check').removeClass('d-none');
})

$(document).on('keyup , change', '.qte_recue', function () {
    const idcmfacture = $(this).closest('tr').data('idcmfacture');
    const prix = $(this).closest('tr').data('prix');

    $('#valider').attr('disabled', '');
    $('#valider #spinner_validation').removeClass('d-none');
    $('#valider .fa-check').addClass('d-none');

    const tr = $(this).closest('tr');
    let new_qte = $(this).val();



    if (new_qte == '') {
        new_qte = 0;
        $(this).val(0);
    }



    let new_montant = parseInt(new_qte) * parseInt(prix);

    let content = `${new_montant.toLocaleString("fr-FR")} Ar`;

    $('#' + idcmfacture + '_montant').text(content);


    const min_qte = qteMinUnit(all_unite[idcmfacture].unites, new_qte, all_unite[idcmfacture].identifiant);



    $(tr).attr('data-min_qte', min_qte);
    $(tr).attr('data-quantite', new_qte);
    $(tr).attr('data-montant', new_montant);

    setTimeout(function () {
        $('#valider').removeAttr('disabled');
        $('#valider #spinner_validation').addClass('d-none');
        $('#valider .fa-check').removeClass('d-none');
    }, 100)
})


$(document).on('click', '#valider', function () {
    let table = $('#tableau tr');
    console.log(table);

    if (table.length > 0) {
        $('#panier_reception').click();
    }
})

function reception_panier_content() {

    montant_total = 0;

    let tr = $('#tableau tr');
    let idfournisseur = $("#idfournisseur").val();

    let pv = $('#point_vente').html();

    pv = pv.toLocaleString()

    console.log(pv);


    let content = `
    <div class="_tableau mt-4">
            <table class="table">
                <thead class="table-info">
                    <tr>
                        <th>Réference</th>
                        <th>Désignation</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody >` ;

    for (let i = 0; i < tr.length; i++) {
        const ligne = tr[i];

        if (ligne.getAttribute('data-quantite') > 0) {
            let data = {
                'idcmfacture': ligne.getAttribute('data-idcmfacture'),
                'idProduit': ligne.getAttribute('data-idproduit'),
                'idunite': ligne.getAttribute('data-idunite'),
                'quantite': ligne.getAttribute('data-quantite'),
                'min_qte': ligne.getAttribute('data-min_qte'),
                'prix_unitaire': ligne.getAttribute('data-prix'),
                'idfournisseur': idfournisseur,
            }

            idcommande = ligne.getAttribute('data-idcommande');


            tableau.push(data);
            data = {};

            let montant = parseInt(ligne.getAttribute('data-prix')) * parseInt(ligne.getAttribute('data-quantite'));
            montant_total += montant;

            frais = parseInt(ligne.getAttribute('data-frais'));

            content += `<tr>
                <td>${ligne.getAttribute('data-reference')}</td>
                <td>${ligne.getAttribute('data-designation')}</td>
                <td>${ligne.getAttribute('data-prix')}</td>
                <td>${ligne.getAttribute('data-quantite')}</td>
                <td>${(ligne.getAttribute('data-unite') != '') ? ligne.getAttribute('data-unite') : '--'}</td>
                <td>${montant.toLocaleString("fr-FR")} Ar</td>
                </tr>
            ` ;
        }
    }

    content += `
            </tbody>
        </table>
    </div>
    `

    let entete = `
    <div class="mb-3">
        <label class="form-label">Montant total :</label>
        <input type="text" class="form-control" value='${montant_total.toLocaleString("fr-FR")} Ar' readonly >
    </div>
    <div class="mb-3">
        <label class="form-label">Frais de livraison :</label>
        <input type="text" class="form-control" value='${frais.toLocaleString("fr-FR")} Ar' readonly >
    </div>
    <div class="mb-3">
        <label class="form-label">Dépôt :</label>
        <select  id="idpv" class="form-select">
                ${pv}
        </select>
    </div>
    ` ;
    content = entete + content;

    return content;

}
$(document).on('click', '#panier_reception', function () {
    let content = reception_panier_content();
    $('#validation').html(content);
})

$(document).on('click', '#to_validate', function () {
    let idPointVente = $("#idpv").val();

    if (idPointVente > 0) {
        $('#to_validate #spinner_to_validate').removeClass('d-none');
        $('#to_validate .fa-check').addClass('d-none');
        $.ajax({
            method: 'post',
            url: base_url('Appro/cmregister'),
            data: { datas: tableau, idPointVente: idPointVente, idcommande: idcommande, montant_total: montant_total, frais: frais },
            // dataType: 'json'
        }).done(function (response) {
            // if (response.success) {
                location.href = base_url('Appro') ; 
                // Myalert.added() ; 
                // $("#button").click(function (){
                //      ; 
                // })
            // }
        }).fail(function (err) {
            console.error(err);
        })
    } else {
        Myalert.erreur("Veuillez choisir un point de vente.");
    }

})


