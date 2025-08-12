if ( localStorage.getItem('mode') === 'dark'){
    let body = document.querySelector('body') ; 
    // body.setAttribute('class' , 'dark') ; 
    $('.mode-menu').html('<i class="fa-solid fa-sun"></i>');
    // localStorage.setItem("mode", "dark");
    $('.mode-menu').data("light" , false) 
}
else {
    $('.mode-menu').html('<i class="fa-solid fa-moon"></i>');
    $('.mode-menu').data("light" , true )  
}

var anim = setInterval(function () {
	var val = $("#quantiter").val();
	var valeurAmultiplier = 0;
	if (val == "") {
		valeurAmultiplier = 0;
	} else {
		valeurAmultiplier = parseInt(val, 10);
		var prixUnitaire = $("#prix").val();
		$("#montant").val(parseInt(prixUnitaire, 10) * valeurAmultiplier);
	}
}, 100);

var anim = setInterval(function () {
	var val = $("#quantiter_modif").val();
	var valeurAmultiplier = 0;
	if (val == "") {
		valeurAmultiplier = 0;
	} else {
		valeurAmultiplier = parseInt(val, 10);
		var prixUnitaire = $("#prix_modif").val();
		$("#montant_modif").val(parseInt(prixUnitaire, 10) * valeurAmultiplier);
	}
}, 1000);

$(document.body).on("click", "#edit", function () {
	var idFacture = $(this).attr("data-idFacture");
	var idUnite = $(this).attr("data-idUnite");
	var idClient = $(this).attr("data-idClient");
	var refProduit = $(this).attr("data-refProduit");
	var designation = $(this).attr("data-designation");
	var prix = $(this).attr("data-prix");
	var telClient = $(this).attr("data-telClient");
	var quantiter = $(this).attr("data-quantiter");
	var Unite = $(this).attr("data-Unite");
	var montant = $(this).attr("data-montant");
	var nomClient = $(this).attr("data-nomClient");

	$("#idFacture_modif").val(idFacture);
	$("#idUnite_modif").val(idUnite);
	$("#idClient_modif").val(idClient);
	$("#designation_modif").val(designation);
	$("#num_modif").val(telClient);
	$("#reference_modif").val(refProduit);
	$("#prix_modif").val(prix);
	$("#quantiter_modif").val(quantiter);
	$("#unite_modif").val(Unite);
	$("#montant_modif").val(montant);
	$("#nomClient_modif").val(nomClient);
});


$(document.body).on("change", "#reference", function (e) {
	e.preventDefault();
	const ref = $(this).val();
	$.ajax({
		url: base_url("AjouterProduit/rechercherUniteProd"),
		type: "post",
		data: {
			ref: ref,
		},
		dataType: "json",
	}).done(function (data) {
		if (data.success) {
			const infos = data.data;
			$("#reference").val(infos.refProduit);

			$("#idMateriel").val(infos.idProduit);
			$("#designation").val(infos.designation);
			$("#reference").attr('readonly', true);
			$('#quantiter').attr('readonly', false);
			$("#prix").val(data.prix.prixProduit);

			var uniter = "";
			for (const key in data.uniterList) {
				if (Object.hasOwnProperty.call(data.uniterList, key)) {
					const element = data.uniterList[key];
					uniter += '<option value="' + data.uniterList[key]["nomUniter"] + '">' + data.uniterList[key]["nomUniter"] + '</option>';
				}
			}

			$('#unite').html(uniter);

		} else {
			Swal.fire({
				title: "Attention !",
				text: "Ce produit n'existe pas ou n'a pas encore de Prix.",
				icon: "warning",
			});
			$("#designation").val("");
			$("#prix").val("");
		}
	});
});

$(document.body).on("change", "#unite", function (e) {
	e.preventDefault();
	const unite = $(this).val();
	const ref = $('#idMateriel').val();
	$.ajax({
		url: base_url("AjouterProduit/rechercherPrixProd"),
		type: "post",
		data: {
			ref: ref,
			unite: unite,
		},
		dataType: "json",
	}).done(function (data) {
		if (data.success) {
			const infos = data.data;
			$("#prix").val(infos.prixProduit);
			$('#interval').val("De " + infos.debut + " à " + infos.fin);
		} else {
			Swal.fire({
				title: "Attention !",
				text: "Ce produit n'existe pas ou n'a pas encore de Prix.",
				icon: "warning",
			});
			$("#prix").val("");
		}
	});
});


$(document.body).on("change", "#reference_modif", function (e) {
	e.preventDefault();
	const ref = $(this).val();
	$.ajax({
		url: base_url("AjouterProduit/rechercherPrixProd"),
		type: "post",
		data: {
			ref: ref,
		},
		dataType: "json",
	}).done(function (data) {
		if (data.success) {
			const infos = data.data;
			$("#idProd").val(infos.idProduit);
			$("#designation_modif").val(infos.designation);
			$("#prix_modif").val(infos.prixProduit);
		} else {
			Swal.fire({
				title: "Attention !",
				text: "Ce produit n'existe pas ou n'a pas encore de Prix.",
				icon: "warning",
			});
			$("#designation_modif").val("");
			$("#prix_modif").val("");
		}
	});
});

$(document.body).on("change", "#num", function (e) {
	e.preventDefault();
	const ref = $(this).val();
	$.ajax({
		url: base_url("Clients/rechercherClient"),
		type: "post",
		data: {
			numClient: ref,
		},
		dataType: "json",
	}).done(function (data) {
		if (data.success) {
			const infos = data.data;
			$("#idClient").val(infos.idClient);
			$("#nomClient").val(infos.nomClient);
		} else {
			Swal.fire({
				title: "Attention !",
				text: "Ce Client n'existe pas.",
				icon: "warning",
			});
			$("#nomClient").val("");
		}
	});
});

$(document.body).on("change", "#num_modif", function (e) {
	e.preventDefault();
	const ref = $(this).val();
	$.ajax({
		url: base_url("Clients/rechercherClient"),
		type: "post",
		data: {
			numClient: ref,
		},
		dataType: "json",
	}).done(function (data) {
		if (data.success) {
			const infos = data.data;
			$("#idClient_modif").val(infos.idClient);
			$("#nomClient_modif").val(infos.nomClient);
		} else {
			Swal.fire({
				title: "Attention !",
				text: "Ce Client n'existe pas.",
				icon: "warning",
			});
			$("#nomClient_modif").val("");
		}
	});
});

$(document.body).on("click", "#valider", function () {
	const reference = $("#reference").val();
	const designation = $("#designation").val();
	const prix = $("#prix").val();
	const quantiter = $("#quantiter").val();
	const montant = $("#montant").val();
	const idMateriel = $("#idMateriel").val();
	const unite = $("#unite").val();
	const interval = $("#interval").val();

	// verification si le produit est incoherent:
	const tableau = $("#tableau tr");
	var status = false;
	for (var i = 0; i < tableau.length; i++) {
		var ref = tableau[i].getAttribute("data-reference");
		var qte = parseInt(tableau[i].getAttribute("data-quantite"));
		var px = parseFloat(tableau[i].getAttribute("data-prix"));
		// var unitePanier = tableau[i].getAttribute("data-unite");

		if (ref == reference) {
			// if (unitePanier != unite) status = true;
		}
	}

	if (status == false) {
		if (
			reference != "" &&
			designation != "" &&
			quantiter != "" &&
			montant != "" &&
			prix != ""
		) {
			$("#validerPanier").removeClass("d-none");
			$(".panier").removeClass("d-none");
			var idCounter = 0;
			var affichage = `
            <tr id='data-${idCounter}' data-reference='${reference}' data-designation='${designation}' data-quantite='${quantiter}' data-prix='${prix}' data-montant='${montant}' data-idMateriel='${idMateriel}' data-unite='${unite}'>
                <td>${reference}</td>
                <td>${designation}</td>
                <td>${quantiter}</td>
                <td>${prix}</td>
                <td>${montant}</td>
                <td>
                    <a data-id='${idCounter}' href="#" class="btn btn-danger delete"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
        `;

			$("#tableau").append(affichage);
			idCounter++;

			$("#reference").attr('readonly', false);
			$('#quantiter').attr('readonly', true);
			$('#unite').html("");
			$("#quantiter").val("");
			$("#montant").val(0);
			$("#reference").val("");
			$("#designation").val("");
			$("#prix").val("");
			$("#quantiter_dispo_shadow_reformed").val("");
			$("#quantiter_dispo_shadow").val("");
			$("#montant").val("");
			$("#reference").focus();


			// $("#quantiter").focus();
		}
	} else {
		Swal.fire({
			title: "Attention !",
			text: "Vous devez vérifier l'unité",
			icon: "warning",
		});
	}
});

$(document.body).on("click", ".delete", function () {
	var id = $(this).attr("data-id");
	$("#data-" + id).remove();
});

$(document.body).on("click", "#validerPanier", function () {
	const data = $("#tableau tr");
	var totauxParReference = {};

	// Consolidation des quantités et des montants pour chaque référence de produit
	for (var i = 0; i < data.length; i++) {
		var reference = data[i].getAttribute("data-reference");
		var interval = data[i].getAttribute("data-interval");
		var quantite = parseInt(data[i].getAttribute("data-quantite"));
		var prix = parseFloat(data[i].getAttribute("data-prix"));
		var unitePanier = data[i].getAttribute("data-unite");

		// Utiliser une clé unique pour chaque référence et unité
		var key = reference + "_" + unitePanier;

		if (totauxParReference[key]) {
			totauxParReference[key].quantite += quantite;
			totauxParReference[key].montant += quantite * prix;
		} else {
			totauxParReference[key] = {
				quantite: quantite,
				prix: prix,
				montant: quantite * prix,
				unite: unitePanier,
			};
		}
	}

	var modepaiement;
	$.ajax({
		url: base_url("get-modepaiement"),
		type: "post",
		dataType: "json",
		data: {},
		async: false,
	}).done(function (data) {
		modepaiement = data;
	});

	// var pv;
	// $.ajax({
	// 	url: base_url("getpv"),
	// 	type: "post",
	// 	dataType: "json",
	// 	data: {},
	// 	async: false,
	// }).done(function (data) {
	// 	pv = data;
	// });
	// $("#sendvalidation").addClass("d-none");

	var affiche = `
	        <div>
	            <label class="form-label">Numéro du client :</label>
	            <input id='numClient' type="text" class="form-control mb-2"/>
				<p><input type='checkbox' id='repertoire' /> Choisir à partir du répértoire client</p>
				<p style='font-size: 12px; font-weight: bold' class='text-danger d-none' id='numClientNotifText'>Ce client n'existe pas</p>
				<label class="form-label">Remise :</label>
	            <input id='remise' type="number" min='0' max='100' value='0' class="form-control mb-2"/>
				<label class="form-label">Montant a payer :</label>
	            <input id='montantPayer' type="number" min='0' max='100' value='0' class="form-control mb-2"/>
				<label class="form-label">Montant total :</label>
	            <input id='montantTotal' type="number" min='0' max='100' value='0' class="form-control mb-2" readonly/>
	            `;

	// Construction du tableau avec les données consolidées
	affiche += `
        <div class="_table">
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Référence du matériel</th>
                        <th>Quantité total</th>
                        <th>Intervalle</th>
                        <th>Prix unitaire</th>
                        <th>Montant total</th>
                    </tr>
                </thead>
                <tbody>
    `;

	for (var key in totauxParReference) {
		var total = totauxParReference[key];
		var interval = key.split("_")[0];
		var reference = key.split("_")[0]; // Séparer la référence de l'unité
		var formatprix = total.prix.toLocaleString("fr-FR");
		var formatQuantite = total.quantite.toLocaleString("fr-FR");
		var formatMontant = total.montant.toLocaleString("fr-FR");
		affiche += `
            <tr class='toValidate' data-ref='${reference}' data-qte='${total.quantite}' data-px='${total.prix}' data-mont='${total.montant}' data-interval='${interval}'>
                <td>${reference}</td>
                <td>${formatQuantite}</td>
                <td>${interval}</td>
                <td>${formatprix}</td>
                <td>${formatMontant}</td>
            </tr>
        `;
	}

	affiche += `
                </tbody>
            </table>
        </div>
    `;

	$("#validation").append(affiche);

	$("#myModal").on("hidden.bs.modal", function () {
		$("#validation").empty();
	});
});

//******************************************************* FIN PANNIER**************************************************

// Recherche de client:
$(document.body).on("focusout", "#numClient", function () {
	var val = $(this).val();
	var check = $("#repertoire").prop("checked");
	// sendvalidation
	if (val == "") {
		$("#sendvalidation").removeClass("d-none");
		$("#numClientNotifText").addClass("d-none");
		$("#numClient").css({ "border-color": "#dee2e6" });
	} else {
		if (check) {
			$.ajax({
				url: base_url("verifier-client"),
				type: "post",
				data: {
					numClient: val,
				},
				dataType: "json",
			}).done(function (data) {
				if (data.success == true) {
					$("#sendvalidation").removeClass("d-none");
					$("#numClientNotifText").addClass("d-none");
					$("#numClient").css({ "border-color": "#dee2e6" });
				} else {
					$("#sendvalidation").addClass("d-none");
					$("#numClientNotifText").removeClass("d-none");
					$("#numClient").css({ "border-color": "red" });
				}
			});
		}
	}
});

$(document.body).on("change", "#repertoire", function () {
	var check = $("#repertoire").prop("checked");
	var clt = $("#numClient").val();
	if (check) {
		$.ajax({
			url: base_url("verifier-client"),
			type: "post",
			data: {
				numClient: clt,
			},
			dataType: "json",
		}).done(function (data) {
			if (data.success == true) {
				$("#sendvalidation").removeClass("d-none");
				$("#numClientNotifText").addClass("d-none");
				$("#numClient").css({ "border-color": "#dee2e6" });
			} else {
				$("#sendvalidation").addClass("d-none");
				$("#numClientNotifText").removeClass("d-none");
				$("#numClient").css({ "border-color": "red" });
			}
		});
	} else {
		$("#sendvalidation").removeClass("d-none");
		$("#numClientNotifText").addClass("d-none");
		$("#numClient").css({ "border-color": "#dee2e6" });
	}
});

// Envoi validation:
$(document.body).on("click", "#sendvalidation", function () {
	/**
	 *  Maka ny panier eto oh
	 *
	 */

	var dataPanier = $("#tableau tr");
	var tableau = [];
	for (let a = 0; a < dataPanier.length; a++) {
		var tab = {
			ref: dataPanier[a].getAttribute("data-reference"),
			designation: dataPanier[a].getAttribute("data-designation"),
			quantite: dataPanier[a].getAttribute("data-quantite"),
			prix: dataPanier[a].getAttribute("data-prix"),
			montant: dataPanier[a].getAttribute("data-montant"),
			idMateriel: dataPanier[a].getAttribute("data-idMateriel"),
			unite: dataPanier[a].getAttribute("data-unite"),
		};
		tableau = [...tableau, tab];
	}
	var numFacture = "";
	$.ajax({
		url: base_url("validate"),
		type: "post",
		data: {
			option: "panier",
			panier: tableau,
		},
		async: false,
		dataType: "json",
	}).done(function (data) {
		console.log(data);
		numFacture = data.facture;
	});

	var elem = $(".toValidate");
	for (var i = 0; i < elem.length; i++) {
		var ref = elem[i].getAttribute("data-ref");
		var qte = elem[i].getAttribute("data-qte");
		var prix = elem[i].getAttribute("data-px");
		var montant = elem[i].getAttribute("data-mont");
		var numClient = $("#numClient").val();
		var modep = $("#modep").val();
		var pvente = $("#pvente").val();
		var remise = $("#remise").val();

		$.ajax({
			url: base_url("validate"),
			type: "post",
			data: {
				option: "vm",
				ref: ref,
				qte: qte,
				prix: prix,
				montant: montant,
				numClient: numClient,
				modep: modep,
				pvente: pvente,
				numFacture: numFacture,
				remise: remise,
				// panier: tableau
			},
			dataType: "json",
		}).done(function (data) {
			$("#reference").val("");
			$("#designation").val("");
			$("#quantiter").val("");
			$("#tableau").html("");
			$("#prix").val("");
			$("#montant").val("");
			Swal.fire({
				title: "Enregistrement !",
				text: "Enregistrement effectué",
				icon: "success",
			});

			window.open(base_url(data.href));
		});
	}
});


function DonneAjout(elem) {
	$.ajax({
		url: base_url("AjouterProduit/donnerAjout"),
		type: "post",
		data: {
			Ajout: elem.getAttribute("data-id"),
		},
		dataType: "json",
	})
		.done(function (data) {
			console.log(data);
			$("#produitId").val(data.idProduit);
			$("#ref_modif").val(data.refProduit);
			$("#designation_modif").val(data.designation);
		})
		.fail(function (errorMessage) {
			console.log(errorMessage);
		});
}

function deleteIt(elem) {
	const id = elem.getAttribute("data-id");
	Swal.fire({
		title: "Attention",
		text: "Etes-vous sur de bien vouloir supprimer ?",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Oui, bien sûr",
		cancelButtonText: "Annuler",
	}).then((result) => {
		$("#agentId").val();
		if (result.isConfirmed) {
			$.ajax({
				url: base_url("Facture/deleteFacture"),
				type: "POST",
				data: { id: id },
				dataType: "json",
				success: function (response) {
					if (response.success) {
						$(elem).closest("tr").remove();
						Swal.fire({
							title: "Effectué !",
							text: "Suppression effectuée.",
							icon: "success",
						});
					}
				},
				error: function (xhr, status, error) {
					console.error("Erreur lors de la suppression :", error);
					location.reload();
				},
			});
		}
	});
}

$(document.body).on("click", "#modifier", function () {
	$.ajax({
		url: base_url("AjouterProduit/verifiAjout"),
		type: "post",
		dataType: "json",
		data: {
			refModif: $("#ref_modif").val(),
			designationModif: $("#designation_modif").val(),
			oldId: $("#produitId").val(),
		},
	}).done(function (data) {
		// console.log(data);
		if (data.success) {
			$("#modification").click();
		} else {
			if (data.refEditExiste) {
				$("#ref_modif").css("border", "1px solid red");
				$("#designation_modif").css("border", "");
				$("#msg-ref_modif").removeClass("d-none");
				$("#msg-des-modif").addClass("d-none");
			}

			if (data.designationEditExiste) {
				$("#designation_modif").css("border", "1px solid red");
				$("#ref_modif").css("border", "");
				$("#msg-des-modif").removeClass("d-none");
				$("#msg-ref_modif").addClass("d-none");
			}
		}
	});
});

let window_width = window.innerWidth;

$(document).ready(function () {
	if (window_width <= 768) {
		$(".sidebar").addClass("hide");
	}
	$(window).on("resize", function () {
		if ($(this).innerWidth() <= 768) {
			$(".sidebar").addClass("hide");
		} else {
			$(".sidebar").removeClass("hide");
		}
	});
	const elemtooltips = document.querySelectorAll(".btn-tooltip");
	for (let elem of elemtooltips) {
		new bootstrap.Tooltip(elem);
	}

	if (localStorage.length === 0) {
		// $("body").removeClass("dark");
		$(".mode-menu")
			.html('<i class="fa-solid fa-moon"></i>')
			.data("light", true);
	} else {
		if (localStorage.getItem("mode") === "light") {
			// $("body").removeClass("dark");
			$(".mode-menu")
				.html('<i class="fa-solid fa-moon"></i>')
				.data("light", true);
		} else {
			// $("body").addClass("dark");
			$(".mode-menu")
				.html('<i class="fa-solid fa-sun"></i>')
				.data("light", false);
		}
	}
});

function toggleSidebar() {
	$(".sidebar").toggleClass("hide");
	$(".backdrop").toggleClass("d-none");
}

function toggleMode(self) {
	let value = $(self).data("light");
	$("body").toggleClass("dark");

	if (value) {
		$(self).html('<i class="fa-solid fa-sun"></i>');
		// localStorage.setItem("mode", "dark");
	} else {
		$(self).html('<i class="fa-solid fa-moon"></i>');
		// localStorage.setItem("mode", "light");
	}

	$(self).data("light", !value);
}
function toggleUserMenu() {
	$(".user-menu-wrapper").toggleClass("d-none");
}

function showSuccessAlert() {
	$("#message-success").addClass("show");
	let t_out = setTimeout(() => {
		hideSuccessAlert();
		clearTimeout(t_out);
	}, 5000);
}
function hideSuccessAlert() {
	$("#message-success").removeClass("show");
}
