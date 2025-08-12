// Nouveaux js ************************* 
// alert modal
let mode = [];
let the_mode = 0;
let frais = 0;
// mode de payement 
$.ajax({
	method: 'post',
	url: base_url('Vente/getMode'),
	dataType: 'json',
	async: false
}).done(function (response) {
	mode = response.mode
})



$(document).on('click', ' .hidde_modale_stock', function (e) {
	$('#alertModal').remove();
})
$(document).on('click', '#alertModal', function (event) {
	$('#alertModal').remove();
})
$(document).on('click', '#alertModal .modal-content', function (event) {
	event.stopPropagation();
})
// alert modal

let quantite_dispo = 0;
let type_produit = '';

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
qte_produit_panier = getLocaleStorage('qte_produit_panier', {});
allunite_panier = getLocaleStorage('allunite_panier', {});
tableau_panier = getLocaleStorage('tableau_panier', {});

// affichage des donne dans la localstorage 
const content = append_tableau();
$('#tableau').html(content);




function vider() {
	$('#reference').val('');
	$('#designation').val('');
	$('#prix').val('');
	$('#prix_show').val('');
	$('#remise').val(0);
	$('#qte_dipo').val('');
	$('#quantite').val('');
	$('#montant').val('');
	$('#fiche').val('');
	$('#couleur').val('');
	$('#imei1').val('');
	$('#imei2').val('');
	$('#montant_show').val(0);

	$('#numero_liste').html('');
	$('.numero_liste_').addClass('d-none');



	$('.with_qte').addClass('d-none');

	quantite_dispo_tab = [];
	real_unite = [];
}

function calcule_total() {
	const prix = parseInt($('#prix').val(), 10);
	const quantite = parseInt($('#quantite').val(), 10);

	if (prix != '' && quantite != '') {
		const montant = prix * quantite;
		$('#montant').val(montant);
		$('#montant_show').val(montant.toLocaleString("fr-FR") + ' AR ');
	}
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
				$.ajax({
					method: 'post',
					url: base_url('Vente/recheche_prix'),
					data: { idProduit: produit.idProduit },
					dataType: 'json',
				}).done(function (recheche_prix) {
					const prix = parseInt(recheche_prix.data.prixProduit);
					quantite_dispo = 0;
					if (recheche_prix.success) {
						$('#idProduit').val(produit.idProduit);
						$('#reference').val(produit.refProduit);
						$('#designation').val(produit.designation);
						$('#fiche').val(produit.fiche);

						$('#prix_show').val(prix.toLocaleString("fr-FR") + " Ar");
						$('#prix').val(prix);


						$('#quantite').val(1)
						$('#montant').val(prix)
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
										Myalert.erreur('Stock insuffisant. Veuillez faire l\'approvisionnement de ce produit dans ce point de vente.');
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
						Myalert.erreur('Ce produit n\'a pas encore de prix.')
						vider();
					}
				}).fail(function (err) {
					console.log('erreur dans la recherche du prix du produit ');
				});
			} else {
				Myalert.erreur('Cette référence ( Numéro de série ) n\'existe pas.')
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

$(document).on('change', '#numero_liste', function () {
	// TRAITEMENT SUR LE CHANGEMENT DES NUMERO DE SERIE 	
	let active_option = $(this).find('option:selected');

	const couleur = $(active_option).data('couleur');
	const imei1 = $(active_option).data('imei1');
	const imei2 = $(active_option).data('imei2');

	$('#couleur').val(couleur);
	$('#imei1').val(imei1);
	$('#imei2').val(imei2);

})

$(document).on('change', '#unite', function () {

	const option = $('#unite').find('option:selected')
	const id = $(option).data('id');
	const prix = $(option).data('prix');

	// changer le prix par unité
	$('#prix_show').val(prix);
	$('#prix').val(prix);

	if (prix) {
		calcule_total();
	}
})

$(document).on('keyup , change', '#quantite', function () {
	// calculer  le montant total
	if ($(this).val() != '') {
		calcule_total();
	}
	else {
		$('#montant').val('');
		$('#montant_show').val('');
	}
})

$(document).on('change', '#pv_vente', function () {
	vider();
})

// AJOUT AU PANIER




function addToPanierTab(idProduit, type_produit, quantite, reference, designation, montant, prix, remise, numero, couleur, imei1, imei2) {
	// TESTE SI LE PRODUIT EST DEJA DANS LE TABLEAU PANIER 
	if (tableau_panier[reference] && tableau_panier[reference].length != 0) {
		// TESTE DU TYPE DE PRODUIT 
		if (type_produit == 'autre') {
			tableau_panier[reference][0].quantite++;
			tableau_panier[reference][0].montant += montant;
		} else {
			let ok_to_add = true;
			for (let i = 0; i < tableau_panier[reference].length; i++) {
				const element = tableau_panier[reference][i];
				if (element.numero != '' && element.numero == numero) {
					ok_to_add = false;
				}
			}
			if (ok_to_add) {
				data = {
					type_produit: type_produit,
					idProduit: idProduit,
					quantite: parseInt(quantite, 10),
					reference: reference,
					designation: designation,
					montant: montant,
					prix: prix,
					remise: remise,
					numero: numero,
					couleur: couleur,
					imei1: imei1,
					imei2: imei2,
				}
				tableau_panier[reference].push(data);
			}
			else {
				Myalert.erreur('Ce produit est déjà dans le panier.');
			}
		}
	}
	else {
		// pas encore dans le panier 
		tableau_panier[reference] = [];
		tableau_panier[reference][0] = {
			type_produit: type_produit,
			idProduit: idProduit,
			quantite: parseInt(quantite, 10),
			reference: reference,
			designation: designation,
			montant: montant,
			prix: prix,
			remise: remise,
			numero: numero,
			couleur: couleur,
			imei1: imei1,
			imei2: imei2,
		}
	}
	// localstorage 
	addLocaleStorage('tableau_panier', tableau_panier);

	console.log(tableau_panier);




}

function append_tableau() {

	let point_vente_content = getLocaleStorage('pointventecontent', null);
	if (point_vente_content != null) {
		$('#pv_vente').html(point_vente_content);
	}
	let content = '';

	$("#validerPanier").addClass("d-none");

	let montant_total = 0;

	for (const reference in tableau_panier) {
		const par_ref = tableau_panier[reference];
		for (let i = 0; i < par_ref.length; i++) {
			$("#validerPanier").removeClass("d-none");
			const element = par_ref[i];

			content += `
				<tr id='${reference}_${i}' data-idProduit='${element.idProduit}' data-reference='${reference}' data-designation='${element.designation}' data-prix='${element.prix}' data-quantite='${element.quantite}' data-id_unite='${element.id_unite}' data-unite_texte='${element.unite_texte}' data-montant='${element.montant}' data-remise='${element.remise}' data-unite_identification='${element.identification}'>
						<td>${element.reference}</td>
						<td>${element.designation}</td>
						` ;
			if (element.numero) {
				content += `
							<td>${element.numero}</td>`;
				content += `<td>IMEI_1 : ${element.imei1} </br> IMEI_2 : ${element.imei2} </td>
							` ;
			} else {
				content += `
							<td>--</td>
							<td>--</td>` ;
			}

			content += `
						<td>${element.prix.toLocaleString("fr-FR")} Ar</td> 
						<td>${element.quantite.toLocaleString("fr-FR")}</td>
						` ;
			content += `
						<td>${element.montant.toLocaleString("fr-FR")} Ar</td>
						<td>${element.remise.toLocaleString("fr-FR")} Ar</td>
						<td>
								<button  class="btn btn-danger delete" data-id='${reference}_${i}'><i class="fa-solid fa-trash"></i></button>
						</td>
				</tr>
			`;

			montant_total = montant_total + parseFloat(element.montant) - parseFloat(element.remise);
		}
	}
	$('#total_in_panier').text(montant_total.toLocaleString("fr-FR") + ' Ar ');
	return content;

}

$(document).on('click', '.delete', function () {
	Myalert.delete();
	const id = $(this).data('id');
	const tr = $(this).closest('tr');
	$(document).on('click', '#confirmeDelete', function () {
		$('#cancelDelete').click();
		let identification = id.split('_');
		const unite_identification = $(tr).data('unite_identification');
		// teste si il est bien dans le panier 
		if (tableau_panier[identification[0]]) {
			tableau_panier[identification[0]].splice(identification[1], 1);
		}
		addLocaleStorage('tableau_panier', tableau_panier);
		addLocaleStorage('qte_produit_panier', qte_produit_panier);
		$('#tableau').html(append_tableau());
	})
})


// AJOUT AU PANIER 
$(document.body).on("click", "#valider", function () {

	const id_pv = $("#pv_vente").val();
	const idProduit = $("#idProduit").val();
	const reference = $("#reference").val();
	const designation = $("#designation").val();
	const prix = parseInt($("#prix").val(), 10);
	const quantite = parseInt($("#quantite").val());
	const montant = parseInt($("#montant").val(), 10);
	const remise = parseInt($("#remise").val(), 10);


	const numero = $('#numero_liste').val();
	const couleur = $('#couleur').val();
	const imei1 = $('#imei1').val();
	const imei2 = $('#imei2').val();

	// fixed le pv
	const pv_active = $("#pv_vente").find('option:selected');
	const pv_texte = $(pv_active).text();
	$('#pv_vente').html('');
	let poindevente_content = `
	<option class="pv"  value="${id_pv}">${pv_texte}</option>
	`
	$('#pv_vente').html(poindevente_content);

	// localstorage 
	addLocaleStorage('pointventecontent', poindevente_content);


	// vérification du quantité 
	const qte_dispo = quantite_dispo;
	if (reference != '' && idProduit != '' && designation != '' && reference != '' && prix != '' && montant != '') {
		let ok_to_add = true;
		if (qte_dispo < quantite && type_produit == 'autre') {
			ok_to_add = false;
		}
		if (ok_to_add) {
			// ajout dans le tableau_panier
			addToPanierTab(idProduit, type_produit, quantite, reference, designation, montant, prix, remise, numero, couleur, imei1, imei2);
			const content = append_tableau();
			$('#tableau').html(content);
			vider();
		}
		else {
			// stock insufisant
			Myalert.erreur(' Le stock est insuffisant.');
		}
	}
	else {
		console.log(idProduit, type_produit, quantite, reference, designation, montant, prix, remise, numero, couleur, imei1, imei2);

	}
});
// AJOUT AU PANIER 

// VALIDER LE PANIER 
function panier_modal_content(nom_client = '', tel_client = '') {

	let content = `
	<div class="_tableau mt-4">
			<table class="table">
				<thead class="table-info">
					<tr>
						<th>Réference</th>
						<th>Désignation</th>
						<th>Numéro de série</th>
						<th>IMEI</th>
						<th>Quantité</th>
						<th>Montant total</th>
						<th>Remise total</th>
					</tr>
				</thead>
				<tbody >
	` ;
	let montant_total = 0;
	let remise_total = 0;
	for (const reference in tableau_panier) {
		$("#validerPanier").removeClass("d-none");
		const par_ref = tableau_panier[reference];

		if (par_ref.length > 0) {

			for (let i = 0; i < par_ref.length; i++) {
				const element = par_ref[i];
				let numero = element.numero;
				let imei;
				if (!numero) {
					numero = '--';
					imei = '--';
				}
				else {
					imei = `IMEI_1 : ${element.imei1} </br> IMEI_2 : ${element.imei2}`;
				}


				content += `
				<tr >
						<td>${element.reference}</td>
						<td>${element.designation}</td>
						<td>${numero}</td>
						<td>${imei}</td>
						<td>${element.quantite}</td>
						<td>${element.montant.toLocaleString("fr-FR")} Ar</td>
						<td>${element.remise.toLocaleString("fr-FR")} Ar</td>
				</tr>
			`;
				remise_total = parseInt(remise_total, 10) + parseInt(element.remise);
				montant_total = parseInt(montant_total, 10) + parseInt(element.montant);
			}
		}
	}
	montant_payer = parseInt(montant_total, 10) - parseInt(remise_total);
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
			<label class="form-label">Montant à payer :</label>
			<input class="form-control input_form-control" type="text" readonly value='${montant_payer.toLocaleString("fr-FR")} Ar'>
			<input id='montant_payer' class="form-control input_form-control d-none" type="number" readonly value='${parseFloat(montant_payer)}'>
        </div>
		
	` ;

	entete += `<div class="mb-2 ">
                        <label class="form-label">Frais de livraison :</label>
                        <input class="form-control input_form-control " type="number" min='0' id="frais" name="frais" value='${frais}' >
                    </div>` ;

	if (mode.length > 0) {

		entete += `
		<div class="mb-2">
		<label class="form-label">Mode de paiement :</label>
		<select class="form-select " id="mode_de_pay">` ;

		for (let i = 0; i < mode.length; i++) {
			const element = mode[i];
			if (the_mode == element.idModePaiement) {
				entete += `
				<option   value="${element.idModePaiement}">${element.denom}</option>
		`
			}
		}
		for (let i = 0; i < mode.length; i++) {
			const element = mode[i];
			if (the_mode != element.idModePaiement) {
				entete += `
				<option   value="${element.idModePaiement}">${element.denom}</option>
		`
			}
		}
		entete += `
		</div>
		</select>
		 
		` ;
	}
	if (tel_client == '' && nom_client == '') {
		entete += `
		<div id='client_vide' class=" mt-2">
			<label class="form-label">Client :</label></br>
			<button id='ajout' class="btn btn-sm btn-primary mb-2">Ajouter</button>
			<button id="recherche"  class="btn btn-sm btn-info mb-2">Rechercher</button>
		</div>
	` ;
	}
	else {
		entete += `
		<div id='client_set'>
			<label class="form-label">Client :</label></br>
			<div class="input-group mb-2">
				<input id="nom_client" type="text" class="form-control" readonly value='${nom_client}' >
				<button id='annuler_client' class="btn btn-danger" type="submit">
				<i class="fa-solid fa-x"></i>
				</button>
				<input id="numClient" type="hidden" class="form-control" value='${tel_client}'>
			</div>
		</div>
		` ;
	}

	entete += `<input id="id_pointdevente" type="text" class="form-control d-none" readonly value='${$("#pv_vente").val()}' ></input>`;



	content = entete + content;
	// en tete de l'affichage 
	return content;
}
$(document).on('click', '#validerPanier', function () {
	$('#validation').html(panier_modal_content());
	$('.modal-footer').removeClass('d-none');
})

$(document).on('keyup , change', '#frais', function () {
	frais = $(this).val();
})
// VALIDER LE PANIER 


$(document).on('change', '#mode_de_pay', function () {
	the_mode = $(this).val();
})




// CLIENT DANS LE PANIER
function ajout_client_content() {
	let ajout = `
		   <form action="haja" id="registerClient" method="post" enctype="multipart/form-data">
			   <input type="hidden" name="id_modif" id="idClient_modif">
			   <div class="mb-2 d-none">
				   <label class="form-label">Type : </label>
				   <select class="form-select" id="client_type" name="client_type">
					   <option value="1">Particulier </option>
					   <option value="2">Entreprise </option>
				   </select>
			   </div>
			   <div class="not_public">
				   <div class="mb-1">
					   <label class="form-label">Nom :</label>
					   <input type="text" id="nom" class="form-control" name="nom" >
				   </div>
				   <div class="mb-1">
					   <label class="form-label">Prénom :</label>
					   <input name="prenom" id="prenom" type="text" class="form-control input_form-control" >
				   </div>
			   </div>
			   <div class="is_public d-none">
				   <div class="mb-2">
					   <label class="form-label">Raison social :</label>
					   <input name="raison" id="raison" type="text" class="form-control input_form-control " >
				   </div>
				   <div class="mb-2">
					   <label class="form-label">NIF :</label>
					   <input name="nif" id="nif" type="text" class="form-control input_form-control " >
				   </div>
				   <div class="mb-2">
					   <label class="form-label">STAT :</label>
					   <input name="stat" id="stat" type="text" class="form-control input_form-control " >
				   </div>
			   </div>
			   <div class="mb-1">
				   <label class="form-label">Adresse :</label>
				   <input name="adresse" id="adresse" type="text" class="form-control input_form-control" >
			   </div>
			   <div class="mb-1">
				   <label class="form-label">Numéro Télephone : </label>
				   <input name="numero" id="numero" type="tel" class="form-control input_form-control" >
				   <p class="text-danger d-none" id="msg-num-modif">Ce numéro existe déjà</p>
			   </div>
			   <div class="mb-1">
				   <label class="form-label">Email : </label>
				   <input name="email" id="email" type="email" class="form-control input_form-control" >
				   <p class="text-danger d-none" id="msg-mail-modif">Cet email existe déjà</p>
			   </div>
			   <div class="mb-1">
				   <label class="form-label">Photocopie CIN (recto) :</label>
				   <input type="file" class="form-control" id='cin_recto'  accept="image/*" />
			   </div>
			   <div class="mb-1">
				   <label class="form-label">Photocopie CIN (verso) :</label>
				   <input type="file" class="form-control" id='cin_verso' accept="image/*" />
			   </div>
			   <div class="mb-1">
				   <label class="form-label">Image de profil :</label>
				   <input type="file" class="form-control" id="image_profil" accept="image/*" />
			   </div>
			   <div class="mt-2">
				   <button type="submit" class="btn btn-sm btn-info">Valider</button>
				   <button type="button" class="btn btn-sm btn-secondary" id="annuler">Annuler l'ajout</button>
			   </div>
		   </form>` ;


	return ajout;
}
function recherche_client_content(recherche = '') {
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
								<th>Raison</th>
								<th>Nom</th>
								<th>Prénom</th>
								<th>Adresse</th>
								<th>Numéro Télephone</th>
								<th>Email</th>
							</tr>
						</thead>
		`;
	$.ajax({
		url: base_url('rechercheClient'),
		type: "post",
		data: { recherche: recherche, page_: 'vente' },
		dataType: 'json',
		async: false
	}).done(function (reponse) {
		var cl = reponse.data;

		recherche = recherche_tab;
		recherche += `	
					<tbody id= 'tableau_client'">
					`
		for (var i = 0; i < cl.length; i++) {
			recherche += `
						<tr >
							<td> 
								<input class='choix_client' type="radio" name='client' data-tel='${cl[i].telClient}' data-nom = '${cl[i].nomClient}' data-prenom = '${cl[i].prenomClient}' data-raison ='${cl[i].r_social}' >
							</td>
							<td>${cl[i].r_social}</td>
	
							<td>${cl[i].nomClient}</td>
	
							<td>${cl[i].prenomClient}</td>
	
							<td>${cl[i].adresseClient}</td>
	
							<td>${cl[i].telClient}</td>`;
			if (cl[i].emailClient != '') {
				recherche += `<td>${cl[i].emailClient}</td></tr>
					
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
	$('#validation').html(ajout_client_content());
	$('.modal-footer').addClass('d-none');
})
$(document).on('click', '#recherche', function () {
	$('#validation').html(recherche_client_content());
	$('.modal-footer').addClass('d-none');
})
$(document).on('click', '#annuler , #annuler_client', function () {
	$('#validation').html(panier_modal_content());
	$('.modal-footer').removeClass('d-none');
})
$(document).on('click', '#recherche_client', function () {
	if ($('#client_search').val() != '') {
		$('#validation').html(recherche_client_content($('#client_search').val()));
	}
	else {
		$('#client_search').css({ 'border': '1px solid red' })
	}
})

// valider la recherche client 
$(document).on('change', '.choix_client', function () {
	$('#search_valide').attr('data-tel', $(this).data('tel'));
	$('#search_valide').attr('data-nom', $(this).data('nom'));
	$('#search_valide').attr('data-prenom', $(this).data('prenom'));
	$('#search_valide').attr('data-raison', $(this).data('raison'));
})

$(document).on('click', '#search_valide', function () {
	const telephone = $(this).data('tel');
	const nom = $(this).data('nom');
	const prenom = $(this).data('prenom');
	const raison = $(this).data('raison');
	let contenue = '';
	if (raison != '') {
		contenue = raison.toUpperCase()
	} else {
		contenue = nom.toUpperCase() + ' ' + prenom;
	}
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

let formData = new FormData();
$(document.body).on("submit", "#registerClient", function (e) {
	e.preventDefault();

	let isok = true;

	let nom = $('#nom').val();
	let prenom = $('#prenom').val();
	let adresse = $('#adresse').val();
	let numero = $('#numero').val();
	let email = $('#email').val();
	let nif = $('#nif').val();
	let stat = $('#stat').val();
	let raison = $('#raison').val();


	if (((nom != '' && prenom != '') || (raison != '')) && numero != '') {
		isok = true;
	} else {
		isok = false;
	}

	if (isok == true) {
		// Préparer FormData pour envoyer les fichiers et les infos client

		formData.append('nom', nom);
		formData.append('prenom', prenom);
		formData.append('adress', adresse);
		formData.append('email', email);
		formData.append('numero', numero);
		formData.append('nif', nif);
		formData.append('stat', stat);
		formData.append('r_social', raison);
		formData.append('page_', 'vente');

		// Ajout fichiers si présents
		let cin_recto = document.getElementById('cin_recto') ? document.getElementById('cin_recto').files[0] : null;
		let cin_verso = document.getElementById('cin_verso') ? document.getElementById('cin_verso').files[0] : null;
		let image_profil = document.getElementById('image_profil') ? document.getElementById('image_profil').files[0] : null;
		if (cin_recto) formData.append('cin_recto', cin_recto);
		if (cin_verso) formData.append('cin_verso', cin_verso);
		if (image_profil) formData.append('image_profil', image_profil);


		$.ajax({
			type: 'post',
			dataType: 'json',
			url: base_url('Clients/verify_client_js'),
			data: {
				nom: nom,
				prenom: prenom,
				adress: adresse,
				email: email,
				numero: numero,
				page_: 'vente_test',
			},
		}).done(function (data) {
			if (data.success == true) {
				if (nom != '' && prenom != '') {
					// Récupérer les fichiers du dernier formulaire client affiché (par id)
					let cin_recto = document.getElementById('cin_recto') ? document.getElementById('cin_recto').files[0] : null;
					let cin_verso = document.getElementById('cin_verso') ? document.getElementById('cin_verso').files[0] : null;
					let image_profil = document.getElementById('image_profil') ? document.getElementById('image_profil').files[0] : null;
					if (cin_recto) formData.append('cin_recto', cin_recto);
					if (cin_verso) formData.append('cin_verso', cin_verso);
					if (image_profil) formData.append('image_profil', image_profil);


					$('#validation').html(panier_modal_content(nom.toUpperCase() + ' ' + prenom, numero));
					$('.modal-footer').removeClass('d-none');
				}
				else {
					$('#validation').html(panier_modal_content(raison.toUpperCase(), numero));
					$('.modal-footer').removeClass('d-none');
				}
			} else {
				Myalert.delete('Ce client existe déjà.Voulez-vous utiliser l\'existant ?');

				$('#confirmeDelete').on('click', function () {
					const info = data.data;
					let client_nom = info.nomClient.toUpperCase() + ' ' + info.prenomClient;
					let num_client = info.telClient;
					$('#validation').html(panier_modal_content(client_nom, num_client));
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
	// Myalert.spinnerB();
	// $('#loaderFacture').removeClass('d-none');
	// $('#pdfFrame').addClass('d-none');

	let canRegister = true;
	// Log détaillé du contenu de formData
	for (let pair of formData.entries()) {
		console.log('formData-send', pair[0] + ':', pair[1]);
		if (pair[0] === 'numero' && pair[1] === '') {
			canRegister = false;
			break;
		}
	}



	// eregistrer le client 
	if (canRegister) {
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: base_url('registerClient'),
			data: formData,
			processData: false,
			contentType: false,
		})
	}

	const numClient = $("#numClient").val();
	const id_pointdevente = $("#id_pointdevente").val();
	const montant_total = $('#montant_total').val();
	const montant_payer = $('#montant_payer').val();
	const idmode = $('#mode_de_pay').val();
	const frais = $('#frais').val();

	let numFacture = "";
	let idfacture = "";
	let tva = '';

	if ($('#tva_').data('status') == 'on') {
		tva = true;
	}
	else {
		tva = false;
	}

	$.ajax({
		url: base_url("facturation"),
		type: "post",
		data: {
			numClient: numClient,
			id_pointdevente: id_pointdevente,
			tva: tva,
			montant_total: montant_total,
			montant_payer: montant_payer,
			idmode: idmode,
			frais: frais,
		},
		async: false,
		dataType: "json",

	}).done(function (data) {
		numFacture = data.facture;
		idfacture = data.idfacture;
	});



	let data = [];
	for (const reference in tableau_panier) {
		const par_ref = tableau_panier[reference];
		for (let i = 0; i < par_ref.length; i++) {
			const element = par_ref[i];
			let donner = {
				'idfacture': idfacture,
				'idProduit': element.idProduit,
				'idPointVente': id_pointdevente,
				'prixunitaire': element.prix,
				'quantite': element.quantite,
				'couleur': element.couleur,
				'numero': element.numero,
				'imei1': element.imei1,
				'imei2': element.imei2,
				'min_qte': element.qte_min,
				'remise': element.remise,
			}
			data.push(donner);
		}
	}
	// reinitialiser le localstorage
	addLocaleStorage('pointventecontent', null);
	addLocaleStorage('tableau_panier', {});
	addLocaleStorage('allunite_panier', {});
	addLocaleStorage('qte_produit_panier', {});

	// envoyer les données 
	$.ajax({
		url: base_url("validate"),
		type: "post",
		data: { data: data },
	}).done(function (data) {
		tableau_panier = [];
		$('#pdfFrame').attr('src', base_url('Vente/facture/' + numFacture));
		$('#affichefacture').click();
		$('#tableau').html(append_tableau());
		Myalert.removeSpinnerB();


		setTimeout(() => {
			$('#loaderFacture').addClass('d-none');
			$('#pdfFrame').removeClass('d-none');
		}, 5000);

	}).fail(function () {
		alert('error')
		console.error('Erreur sur la validation de la vente !!');
		window.location.reload();
	});
});
// EVOYER LE PANIER 

// format 
$(document).on('change', '#format', function () {
	$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');
	const format = $(this).val();

	let src = $('#pdfFrame').attr('src');
	let new_src = '';


	if (format == 'A4') {
		new_src = src.replace('tiquet', 'facture');
	} else {
		new_src = src.replace('facture', 'tiquet');
	}

	$('#pdfFrame').attr('src', new_src);

	setTimeout(function () {
		$('#loaderFacture').addClass('d-none');
		$('#pdfFrame').removeClass('d-none');
	}, 5000)
})



// Nouveaux js ************************* 
