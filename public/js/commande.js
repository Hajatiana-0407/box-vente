// Nouveaux js ************************* 

let real_unite = [];
let data_fournisseur = [];
// qte de chaque produit dans le panier
let qte_produit_panier_commande = {};

// pour stocke toutes les tableaux d'unite deja dans le panier 
let allunite_panier_commande = {};

// quantite par produit dans le panier pour gerer la quantite reste disponible 
var tableau_panier_commande = {};
// changement d'unité


let frais_livraison_ = 0;


function addLocaleStorage(nom = 'panier', tableau) {
	let tableauJSON = JSON.stringify(tableau);
	localStorage.setItem(nom, tableauJSON);
}
function getLocaleStorage(nom = '', init) {
	let tableau = localStorage.getItem(nom);
	if (JSON.parse(tableau) != null) {
		tableau = JSON.parse(tableau);
	}
	else {
		tableau = init;
	}
	return tableau;
}

// reprendre les donner dans le localstorage 
qte_produit_panier_commande = getLocaleStorage('qte_produit_panier_commande', {});
allunite_panier_commande = getLocaleStorage('allunite_panier_commande', {});
tableau_panier_commande = getLocaleStorage('tableau_panier_commande', {});





// affichage des donne dans la localstorage 
const content = append_tableau();
$('#tableau').html(content);



function vider() {
	$('#reference').val('');
	$('#designation').val('');
	$('#unite').html('');
	$('#prix').val('');
	$('#remise').val(0);
	$('#quantite').val('');
	$('#montant').val('');
	$('#montant_show').val(0);
	$('#reference_fournisseur').val('');
	$('#reference_fournisseur').val('');

	real_unite = [];
}

function calcule_total() {
	const prix = parseInt($('#prix').val(), 10);
	const quantite = parseInt($('#quantite').val(), 10);


	if (!isNaN(prix) && !isNaN(quantite)) {
		const montant = prix * quantite;
		$('#montant').val(montant);
		$('#montant_show').val(montant.toLocaleString("fr-FR") + ' Ar');
	}
}

$(document).on('change', '#reference', function () {
	const reference = $(this).val();

	if (reference != '') {
		$.ajax({
			method: 'post',
			url: base_url('Commande/getProduit'),
			data: { reference: reference },
			dataType: 'json',
		}).done(function (recherche_prod) {
			if (recherche_prod.success) {
				$.ajax({
					method: 'post',
					url: base_url('Vente/unite'),
					data: { idProduit: recherche_prod.datas.idProduit },
					dataType: 'json',
				}).done(function (response) {
					console.log(response);
					const produit = recherche_prod.datas;
					const unites_prix = response.data;

					real_unite = unites_prix;

					allunite_panier_commande[produit.refProduit] = unites_prix;
					// localstorage 
					addLocaleStorage('allunite_panier_commande', allunite_panier_commande);





					let prix = 0

					// produit 
					$('#reference').val(produit.refProduit);
					$('#idProduit').val(produit.idProduit);
					$('#designation').val(produit.designation);

					// unite 
					let content = '';
					for (let i = 0; i < unites_prix.length; i++) {
						const element = unites_prix[i];
						if (element.denomination) {
							content += `
														<option class='unites' value="${element.idunite}" data-id='${i}' data-prix='${element.prixProduit}'>${element.denomination}</option>
													`
							$('#unite_container').removeClass('d-none');
						}
						else {
							$('#unite_container').addClass('d-none');
						}
					}


					if (unites_prix.length == 0) {
						$('#unite_container').addClass('d-none');
					}


					// quantiter
					// pour avoir les quantite avec tous les unités


					$('#reference_fournisseur').focus();

					$('#unite').html(content);

				}).fail(function () {
					console.error('erreur dans la recuperation du prix');
				})

			} else {
				vider();
				Myalert.erreur('Cette référence n\'existe pas.');
			}

		}).fail(function (err) {
			console.error(err);
		})
	}
	else {
		// vider 
		vider();
	}
})


$(document).on('keyup', '#quantite, #prix', function () {
	// calculer  le montant total
	if ($(this).val() != '') {
		calcule_total();
	}
	else {
		$('#montant').val('');
		$('#montant_show').val('');
	}
})

$(document).on('change', '#quantite, #prix', function () {
	// calculer  le montant total
	if ($(this).val() != '') {
		calcule_total();
	}
	else {
		$('#montant').val('');
		$('#montant_show').val('');
	}
})


// AJOUT AU PANIER

function qteMinUnit(allUnit = [], qte = 0, unite_identification = 0) {

	while (allUnit[unite_identification + 1]) {
		qte *= allUnit[unite_identification + 1].formule;
		unite_identification++;
	}
	return qte
}


function addToPanierTab(idProduit, qte_min, quantite, reference, reference_fournisseur, designation, montant, prix, unite_texte, identification, id_unite) {
	let texte = '';
	if (tableau_panier_commande[reference] && tableau_panier_commande[reference].length != 0) {
		texte = `${reference}_${tableau_panier_commande[reference].length}`;

		// teste si l'utite est deja dans le panier ( si oui , on additionne )
		let teste_unite = false;

		// deja dans le panier 
		for (let i = 0; i < tableau_panier_commande[reference].length; i++) {
			const element = tableau_panier_commande[reference][i];
			element.qte_min = parseInt(element.qte_min) + parseInt(qte_min);

			qte_produit_panier_commande[reference] = element.qte_min;
			// localsotorage 
			addLocaleStorage('qte_produit_panier_commande', qte_produit_panier_commande);
			if (element.identification == identification) {
				// on additionne
				teste_unite = true;
				if (parseInt(element.quantite) < 2 && unite_texte) {
					element.unite_texte += 's';
				}
				element.quantite = parseInt(element.quantite) + parseInt(quantite);
			}
		}
		if (!teste_unite) {
			qte_produit_panier_commande[reference] = tableau_panier_commande[reference][0].qte_min;
			let data = {
				count: texte,
				idProduit: idProduit,
				qte_min: tableau_panier_commande[reference][0].qte_min,
				quantite: parseInt(quantite, 10),
				reference: reference,
				reference_fournisseur: reference_fournisseur,
				designation: designation,
				montant: montant,
				prix: prix,
				unite_texte: unite_texte,
				identification: identification,
				id_unite: id_unite,
			}
			if (parseInt(quantite, 10) > 1 && unite_texte) {
				data.unite_texte += 's';
			}
			tableau_panier_commande[reference].push(data);
		}

	}
	else {
		// pas encore dans le panier
		qte_produit_panier_commande[reference] = parseInt(qte_min, 10);
		texte = `${reference}_0`;
		tableau_panier_commande[reference] = [];
		tableau_panier_commande[reference][0] = {
			count: texte,
			idProduit: idProduit,
			qte_min: parseInt(qte_min, 10),
			quantite: parseInt(quantite, 10),
			reference: reference,
			reference_fournisseur: reference_fournisseur,
			designation: designation,
			montant: montant,
			prix: prix,
			unite_texte: unite_texte,
			identification: identification,
			id_unite: id_unite,
		}

		if (parseInt(quantite, 10) > 1 && unite_texte) {
			tableau_panier_commande[reference][0].unite_texte += 's';
		}
	}
	// localstorage 
	addLocaleStorage('tableau_panier_commande', tableau_panier_commande)

}

function append_tableau() {

	let point_vente_content = getLocaleStorage('pointventecontent', null);
	if (point_vente_content != null) {
		$('#pv_vente').html(point_vente_content);
	}
	let content = '';

	$("#validerPanier").addClass("d-none");

	console.log(tableau_panier_commande);


	for (const reference in tableau_panier_commande) {
		const par_ref = tableau_panier_commande[reference];
		for (let i = 0; i < par_ref.length; i++) {
			$("#validerPanier").removeClass("d-none");
			const element = par_ref[i];

			content += `
				<tr id='${element.count}' data-idProduit='${element.idProduit}' data-reference='${reference}' data-designation='${element.designation}' data-prix='${element.prix}' data-quantite='${element.quantite}' data-id_unite='${element.id_unite}' data-unite_texte='${element.unite_texte}' data-montant='${element.montant}' data-remise='${element.remise}' data-unite_identification='${element.identification}'>
						<td>${element.reference}</td>
						<td>${element.reference_fournisseur}</td>
						<td>${element.designation}</td>
						<td>${element.prix.toLocaleString("fr-FR")} Ar</td> `;
			if (quantite > 1) {
				content += `
							<td>${element.quantite.toLocaleString("fr-FR")} ${element.unite_texte}s </td>
							`
			} else {
				content += `
							<td>${element.quantite.toLocaleString("fr-FR")} ${element.unite_texte} </td>
							`
			}
			content += `
						<td>${element.montant.toLocaleString("fr-FR")} Ar</td>
						<td>
								<button e class="btn btn-danger delete" data-id='${element.count}'><i class="fa-solid fa-trash"></i></button>
						</td>
				</tr>
			`;
		}
	}
	return content;

}

$(document).on('click', '.delete', function () {
	const id = $(this).data('id');

	const tr = $(this).closest('tr');

	Myalert.delete();

	$(document).on('click', '#confirmeDelete', function () {
		$('#cancelDelete').click();
		let identification = id.split('_');
		const unite_identification = $(tr).data('unite_identification');
		const quantite = $(tr).data('quantite');
		const reference = $(tr).data('reference');

		// teste si il est bien dans le panier 
		if (tableau_panier_commande[identification[0]]) {
			tableau_panier_commande[identification[0]].splice(identification[1], 1);
			// pour l'affichage du qte disponible 



			if (allunite_panier_commande[reference]) {
				let qte_deleted = qteMinUnit(allunite_panier_commande[reference], quantite, unite_identification);
				// elevé les quatite effacer 
				qte_produit_panier_commande[reference] = parseInt(qte_produit_panier_commande[reference]) - parseInt(qte_deleted);

				for (let i = 0; i < tableau_panier_commande[reference].length; i++) {
					const element = tableau_panier_commande[reference][i];
					element.qte_min = parseInt(element.qte_min) - parseInt(qte_deleted)
				}

			}
		}
		addLocaleStorage('tableau_panier_commande', tableau_panier_commande);
		addLocaleStorage('qte_produit_panier_commande', qte_produit_panier_commande);
		$('#tableau').html(append_tableau());
	})

})


// AJOUT AU PANIER 
$(document.body).on("click", "#valider", function () {


	const reference = $("#reference").val();
	const reference_fournisseur = $("#reference_fournisseur").val();
	const idProduit = $("#idProduit").val();
	const designation = $("#designation").val();
	const prix = parseInt($("#prix").val(), 10);
	const quantite = $("#quantite").val();
	const id_unite = $('#unite').val();

	const unite_selectione = $('#unite').find('option:selected');
	const unite_texte = $(unite_selectione).text();

	const montant = parseInt($("#montant").val(), 10);


	// vérification du quantité 
	const identification = $(unite_selectione).data('id');

	if (reference != '' && idProduit != '' && designation != '' && quantite > 0 && reference != '' && prix != '' && montant != '') {
		// ajout dans le tableau_panier_commande
		addToPanierTab(idProduit, qteMinUnit(real_unite, quantite, identification), quantite, reference, reference_fournisseur, designation, montant, prix, unite_texte, identification, id_unite);

		const content = append_tableau();
		$('#tableau').html(content);
		vider();
	}

	console.log(tableau_panier_commande)
});
// AJOUT AU PANIER 


// VALIDER LE PANIER 
function panier_modal_content(nom_fournisseur = '', tel_fournisseur = '') {
	let content = `
	<div class="_tableau mt-4">
			<table class="table">
				<thead class="table-info">
					<tr>
						<th>Réference</th>
						<th>Réference fournisseur</th>
						<th>Désignation</th>
						<th>Quantité</th>
						<th>Montant total</th>
					</tr>
				</thead>
				<tbody >
	` ;
	let montant_total = 0;
	let remise_total = 0;
	for (const reference in tableau_panier_commande) {
		$("#validerPanier").removeClass("d-none");
		const par_ref = tableau_panier_commande[reference];


		if (par_ref.length > 0) {
			let tab = {
				reference: par_ref[0].reference,
				reference_fournisseur: par_ref[0].reference_fournisseur,
				designation: par_ref[0].designation,
				quantite: '',
				min_qte: 0,
				montant: 0,
			}
			// l'unite est seulement 4 au max 
			for (let j = 0; j < 4; j++) {
				for (let i = 0; i < par_ref.length; i++) {
					const element = par_ref[i];

					if (element.identification == j) {
						// addition par reference 
						if (tab.quantite == '') {
							tab.quantite = `${tab.quantite} ${element.quantite} ${element.unite_texte} `;

						}
						else {
							tab.quantite = `${tab.quantite} + ${element.quantite} ${element.unite_texte} `;
						}

						tab.min_qte = parseInt(tab.min_qte) + parseInt(element.qte_min);
						tab.montant = parseInt(tab.montant) + parseInt(element.montant);
					}


					// sans unite 
					if (element.identification == undefined) {
						j = 4
						tab.quantite += parseInt(element.quantite, 10);
						tab.min_qte = parseInt(tab.min_qte) + parseInt(element.qte_min);
						tab.montant = parseInt(tab.montant) + parseInt(element.montant);
					}
				}
			}

			content += `
				<tr >
						<td>${tab.reference}</td>
						<td>${tab.reference_fournisseur}</td>
						<td>${tab.designation}</td>
						<td>${tab.quantite}</td>
						<td>${tab.montant.toLocaleString("fr-FR")} Ar</td>
				</tr>
			`;

			montant_total = parseInt(montant_total, 10) + parseInt(tab.montant);
		}
	}
	content += `
				</tbody>
			</table>
		</div>
	` ;
	// en tete de l'affichage 
	let entete = `
		<div class="mb-2">
			<label class="form-label">Montant total :</label>
			<input class="form-control input_form-control" type="text" readonly value='${montant_total.toLocaleString("fr-FR")} Ar'>
			<input id='montant_total' class="form-control input_form-control d-none" type="text" readonly value='${montant_total}'>
        </div>
		<div class="mb-2">
			<label class="form-label">Frais de livraison :</label>
			<input class="form-control input_form-control" id='frais_livraison' type="number" min='0'  value='${frais_livraison_}'>
        </div>
	` ;
	if (tel_fournisseur == '' && nom_fournisseur == '') {
		entete += `
		<div  class="mt-2">
			<label class="form-label">Fournisseur :</label></br>
			<button id='ajout' class="btn btn-sm btn-primary mb-2">Ajouter</button>
			<button id="recherche"  class="btn btn-sm btn-info mb-2">Rechercher</button>
		</div>
	` ;
	}
	else {
		entete += `
		<div >
			<label class="form-label">Fournisseur :</label></br>
			<div class="input-group mb-2">
				<input id="nom_fournisseur" type="text" class="form-control" readonly value='${nom_fournisseur}' >
				<button id='annuler_fournisseur' class="btn btn-danger" type="submit">
				<i class="fa-solid fa-x"></i>
				</button>
				<input id="numfournisseur" type="hidden" class="form-control" value='${tel_fournisseur}'>
			</div>
		</div>
		` ;
	}

	entete += `<input id="id_pointdevente" type="text" class="form-control d-none" readonly value='${$("#pv_vente").val()}' ></input>`

	content = entete + content;
	// en tete de l'affichage 
	return content;
}
$(document).on('click', '#validerPanier', function () {
	$('#validation').html(panier_modal_content());
	$('.modal-footer').removeClass('d-none');
})
// VALIDER LE PANIER 




// CLIENT DANS LE PANIER
function ajout_fournisseur_content() {
	let ajout = `
		<form  id="registerFournisseur" method="post">
			<div class="mb-3">
				<label class="form-label">Nom de l'entreprise :</label>
				<input class="form-control input_form-control" type="text" id="entreprise" name="entreprise" required>
			</div>
			<div class="mb-3">
				<label class="form-label">Adresse :</label>
				<input class="form-control input_form-control" type="text" id="adresse" name="adresse" placeholder="Adresse" required>
			</div>
			<div class="mb-3">
				<label class="form-label">Tél :</label>
				<input class="form-control input_form-control" type="text" placeholder="Numero" id="telephone" name="telephone" required>
				<p class="d-none text-danger" id="telephone_erreur">Ce téléphone existe déjà.</p>
			</div>
			<div class="mb-3">
				<label class="form-label">Email :</label>
				<input class="form-control input_form-control" type="email" placeholder="exemple@gmail.com" id="email" name="email">
			</div>
			<div class="mb-3">
				<label class="form-label">NIF :</label>
				<input class="form-control input_form-control" type="text" placeholder="Nif" name="nif" id="nif">
			</div>
			<div class="mb-3">
				<label class="form-label">STAT :</label>
				<input class="form-control input_form-control" type="text" placeholder="stat" id="Stat" name="stat">
			</div>
			<div class="mt-2">
				<button type="submit" class="btn btn-sm btn-info">Valider</button>
				<button type="button" class="btn btn-sm btn-secondary" id="annuler">Annuler l'ajout</button>
			</div>
		</form>` ;


	return ajout;
}
function recherche_fournisseur_content(recherche = '') {
	let content = '';
	let recherche_tab = `
					<form>
						<div class="input-group mt-3 mb-1">
							<input name="recherche" id= 'client_search' type="text" class="form-control" placeholder="Recherche" value='${recherche}'>
							<a class="btn btn-info" id='recherche_client'>
								<i class="fa-solid fa-magnifying-glass"></i>
							</a>
						</div>
						<p class="text-secondary d-none mb-0"id="msg-search">Aucun résultat...</p>
						<p class="text-danger d-none mb-0"id="msg-search_vide">Veuillez selectionner un client</p>
						
					</form>
					<form>
					<table class="table table">

						<thead class="table-info">
							<tr >
								<th>choix</th>
								<th>Nom</th>
								<th>Adresse</th>
								<th>Tél</th>
								<th>Email</th>
							</tr>
						</thead>
		`;
	$.ajax({
		url: base_url('Commande/fournisseur'),
		type: "post",
		data: { recherche: recherche },
		dataType: 'json',
		async: false
	}).done(function (reponse) {
		const fournisseur = reponse.datas;

		console.log(fournisseur);

		recherche = recherche_tab;
		recherche += `	
					<tbody id= 'tableau_fournisseur'">
					`
		for (var i = 0; i < fournisseur.length; i++) {
			const element = fournisseur[i];
			recherche += `
						<tr >
							<td> 
								<input class='choix_fournisseur' type="radio" name='client' data-tel='${element.tel_fournisseur}' data-nom = '${element.nom_entr}'>
							</td>
							<td>${element.nom_entr}</td>
	
							<td>${element.adresse_fournisseur}</td>
	
							<td>${element.tel_fournisseur}</td>`;

			if (element.mail_fournisseur != '') {
				recherche += `<td>${element.mail_fournisseur}</td></tr>
					
				`
			}
			else {
				recherche += `<td>--</td></tr>
				`
			}
		}
		recherche += `	
					</tbody>
					</table>
					</form>
					<button data-nom="" data-prenom="" data-tel="" class="btn btn-info btn-sm " id="search_valide">VALIDER</button>
					<button type="button" class="btn btn-sm btn-secondary" id="annuler">Annuler</button>`  ;
		content = recherche;
	});

	return content;
}



$(document).on('click', '#ajout', function () {
	frais_livraison_ = $('#frais_livraison').val();
	$('#validation').html(ajout_fournisseur_content());
	$('.modal-footer').addClass('d-none');
})
$(document).on('click', '#recherche', function () {
	frais_livraison_ = $('#frais_livraison').val();
	$('#validation').html(recherche_fournisseur_content());
	$('.modal-footer').addClass('d-none');
})
$(document).on('click', '#annuler , #annuler_fournisseur', function () {
	$('#validation').html(panier_modal_content());
	$('.modal-footer').removeClass('d-none');
})
$(document).on('click', '#recherche_client', function () {
	if ($('#client_search').val() != '') {
		$('#validation').html(recherche_fournisseur_content($('#client_search').val()));
	}
	else {
		$('#client_search').css({ 'border': '1px solid red' })
	}
})

// valider la recherche client 
$(document).on('change', '.choix_fournisseur', function () {
	$('#search_valide').attr('data-tel', $(this).data('tel'));
	$('#search_valide').attr('data-nom', $(this).data('nom'));
})

$(document).on('click', '#search_valide', function () {
	const telephone = $(this).data('tel');
	const nom = $(this).data('nom');
	let contenue = '';
	contenue = nom.toUpperCase();

	$('#validation').html(panier_modal_content(contenue, telephone));
	$('.modal-footer').removeClass('d-none');
})


// Ajouter client 
$(document).on('click', '#client_type option', function () {
	let type = $(this).val();
	if (type == 1) {
		$('.is_public').addClass('d-none');
		$('.not_public').removeClass('d-none');
	}
	else {
		$('.not_public').addClass('d-none');
		$('.is_public').removeClass('d-none');
	}
})


$(document.body).on("submit", "#registerFournisseur", function (e) {
	e.preventDefault();

	let isok = true;

	let nom = $('#entreprise').val();
	let adresse = $('#adresse').val();
	let numero = $('#telephone').val();
	let email = $('#email').val();
	let nif = $('#nif').val();
	let stat = $('#Stat').val();

	if (adresse != '' && numero != '') {
		isok = true;
	} else {
		isok = false;
	}

	if (isok == true) {



		$.ajax({
			type: 'post',
			dataType: 'json',
			url: base_url('Fournisseur/duplicate'),
			data: {
				telephone: numero,
			},
		}).done(function (data) {

			if (data.success) {
				
				data_fournisseur['nom'] = nom
				data_fournisseur['adresse'] = adresse
				data_fournisseur['numero'] = numero
				data_fournisseur['email'] = email
				data_fournisseur['nif'] = nif
				data_fournisseur['stat'] = stat,

					$('#validation').html(panier_modal_content(nom.toUpperCase(), numero));
				$('.modal-footer').removeClass('d-none');
			} else {
				Myalert.delete('Ce client existe déjà.Voulez-vous utiliser l\'existant ?');

				$('#confirmeDelete').on('click', function () {
					const info = data.data;
					let fournisseur = info.nom_entr.toUpperCase();
					let num_fournisseur = info.tel_fournisseur;
					$('#validation').html(panier_modal_content(fournisseur, num_fournisseur));
					$('.modal-footer').removeClass('d-none');
					$('#close').click();
				})
			}
		}).fail(function () {
			console.log('erreur sur l\'enregistrement du client !');
		})
	} else if (isok == false) {
		Myalert.erreur('Veuiller remplir tout les champs');
	}
})

$(document).on('click', '#tva_', function () {
	let status = $(this).data('status');

	if (status == 'off') {
		$('#tva_').data('status', 'on');
	}
	else {
		$('#tva_').data('status', 'off');
	}
})
// Ajouter client 
// CLIENT DANS LE PANIER 

//mdp shcool Em10U1&00hmt$)E4hm


// EVOYER LE PANIER 
$(document.body).on("click", "#sendvalidation", function () {
	// eregistrer le client 
	if (data_fournisseur.numero != undefined) {
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: base_url('Fournisseur/register'),
			async: false,
			data: {
				entreprise: data_fournisseur.nom,
				adresse: data_fournisseur.adresse,
				email: data_fournisseur.email,
				telephone: data_fournisseur.numero,
				nif: data_fournisseur.nif,
				stat: data_fournisseur.stat,
				page_: 'appro',
			},
		})
	}





	const numfournisseur = $("#numfournisseur").val();
	const montant_total = $('#montant_total').val();
	const frais_livraison = $('#frais_livraison').val();

	if (numfournisseur) {
		Myalert.spinnerB();
	}


	let tva = '';

	if ($('#tva_').data('status') == 'on') {
		tva = true;
	}
	else {
		tva = false;
	}

	$.ajax({
		url: base_url("Commande/facturation"),
		type: "post",
		data: {
			numfournisseur: numfournisseur,
			tva: tva,
			montant_total: montant_total,
			frais: frais_livraison,
		},
		dataType: "json",

	}).done(function (response) {
		const data = response;

		let numFacture = "";
		let idcmfacture = "";



		if (data.success) {
			numFacture = response.facture;
			idcommande = response.idfacture;


			let data = [];
			for (const reference in tableau_panier_commande) {
				const par_ref = tableau_panier_commande[reference];
				for (let i = 0; i < par_ref.length; i++) {
					const element = par_ref[i];
					let donner = {
						'idcommande': idcommande,
						'idProduit': element.idProduit,
						'reference_fournisseur': element.reference_fournisseur,
						'prixunitaire': element.prix,
						'quantite': element.quantite,
						'idunite': element.id_unite,
						'min_qte': element.qte_min,
					}
					data.push(donner);
				}
			}


			// reinitialiser le localstorage
			addLocaleStorage('pointventecontent', null);
			addLocaleStorage('tableau_panier_commande', {});
			addLocaleStorage('allunite_panier_commande', {});
			addLocaleStorage('qte_produit_panier_commande', {});

			// envoyer les données 
			$.ajax({
				url: base_url("Commande/validate"),
				type: "post",
				data: { data: data },
			}).done(function (data) {
				// window.open(base_url('Commande/facture/') + numFacture);
				$('#pdfFrame').attr('src', base_url('Commande/facture/' + numFacture));
				tableau_panier_commande = {};
				$('#affichefacture').click();
				$('#tableau').html(append_tableau());

				Myalert.removeSpinnerB();
				// window.location.reload();
			}).fail(function () {
				console.error('Erreur sur la validation de la vente !!');
				window.location.reload();
			});
		} else {
			Myalert.erreur('Veuillez choisir le fournisseur. ');
		}

	});




});
// EVOYER LE PANIER
// Nouveaux js ************************* 
