let my_unite = [];
let my_unite_modif = [];

// AJOUT AU PANIER

function qteMinUnit(allUnit = [], qte = 0, unite_identification = 0) {

	while (allUnit[unite_identification + 1]) {
		qte *= allUnit[unite_identification + 1].formule;
		unite_identification++;
	}
	return qte
}

// SEUIL D'ALERT 
$(document).on('keyup , change', '.all_unite', function () {
	const nbr = $(this).data('nbr');
	const unite = $(this).val();


	if (unite != '') {
		// teste s'i deja dans l'option 
		let option = $('#seul_unite option[data-self="' + nbr + '"]');

		if (option.length == 0) {

			let content = `
				<option value="${nbr}"  data-self='${nbr}'>${unite}</option>
				` ;
			$('#seul_unite').removeClass('d-none');
			$('#seul_unite').append(content);

			let data = {
				formule: 0
			}
			my_unite.push(data);

		} else {
			$(option).text(unite);

		}
		// teste s'i deja dans l'option 
	}
})

$(document).on('keyup , change', '.myformule', function () {
	const self = $(this).data('nbr');
	const quantite = $(this).val();
	let option = $('#seul_unite option[data-self="' + self + '"]');

	if (my_unite[self]) {
		my_unite[self].formule = parseInt(quantite);
	}

})


// SEUIL D'ALERT 



// IMAGE
$('#close_editModal').click(() => {
	$('#editModal').click();
})
$(document).ready(function () {


	// * Nouveau js 
	$(document).on('click', '#add_unite', function () {

		let this_nbr = $(this).data('nbr');


		let ok_to_add = true;

		if (this_nbr != undefined) {
			let the_formule = $('input.myformule[data-nbr="' + this_nbr + '"] ').val();
			if (the_formule == '') {
				ok_to_add = false;
			}
		}


		const nbr_all_unite = $('.all_unite').length;
		const reference = $('#referenceMat').val();
		const designation = $('#designationMat').val();
		const parent_unit = $('#unite_parent').val();

		const nbr = parseInt($('#unite_parent').data('nbr'));

		const the_unite = $('#unite_parent').val() ; 

		if (reference != '' && designation != '' && parent_unit != '' && ok_to_add && nbr_all_unite < 4) {

			$('#unite_parent').removeAttr('id');
			$('#delete_it').remove();

			let containt = `
			<div class="mb-3 unit_contain"  data-nbr="${nbr + 1}" data-nbr_parent = "${nbr}">
				<label class="form-label" data-nbr="${nbr + 1}">Sous unité de : ${the_unite} </label>
				<div class="input-group">
					<input class="form-control input_form-control sous_unite all_unite" type="text" id="unite_parent" name="sousUnite[${nbr}]" data-nbr="${nbr + 1}" data-nbr_parent = "${nbr}">
					<button type='button' class="btn btn-danger" id='delete_it' data-nbr="${nbr + 1}" data-nbr_parent = "${nbr}"><i class="fa fa-x"></i></button>
				</div>
			</div>
			`;
			$('#sous_unite_container').append(containt);
			$('#sous_unite_container').removeClass('d-none');
			$('.add_unite_container').remove();
			$(this).remove();
			$('#sous-unite-alert').remove();
		} else {
			if (nbr_all_unite >= 4) {
				let content = `<p id="sous-unite-alert" class="text-danger">Vous ne pouvez ajouter que 3 sous-unités.</p>`;
				$('#sous_unite_container').append(content);
			} else {
				$('#valider').click();
				$('#spinner_validation').addClass('d-none');
				$('#valider .fa-check').removeClass('d-none');
			}
		}
	})

	$(document).on('keyup', '.sous_unite', function () {
		const valeur = $(this).val();
		const nbr_parent = parseInt($(this).data('nbr_parent'))
		let valeur_parent = '';
		if ($(this).data('modif')) {
			valeur_parent = $('input#unite_modif_parent[data-nbr="' + nbr_parent + '"]').val()
		} else {
			valeur_parent = $('input[data-nbr="' + nbr_parent + '"]').val()
		}

		const nbr = parseInt($(this).data('nbr'));

		// if (valeur != '') {

		if ($('div.formule[data-nbr="' + nbr + '"]').length == 0) {
			let containt = `
				<div class="mb-3 formule" data-nbr="${nbr}" data-nbr-parent="${nbr_parent}">
					<label class="form-label">Formule :</label>
					<div class="input-group">
						<input class="form-control input_form-control myparent" type="text" data-nbr="${nbr}" data-nbr_parent="${nbr_parent}" required readonly>
						<input class="form-control input_form-control myformule " min='0' type="number" name="formule[${nbr_parent}]" data-nbr="${nbr}" data-nbr_parent="${nbr_parent}" required>
						<input class="form-control input_form-control my" type="text" data-nbr="${nbr}" data-nbr_parent="${nbr_parent}" required readonly>
					</div>
				</div>
				<div class="_boutton">
					<button class="btn btn-info" type='button' id='add_unite' data-nbr="${nbr}"><i class="fas fa-plus"></i>  Sous unité</button>
				</div>
				`
			$('#sous_unite_container').append(containt)
		}
		$('input.myparent[data-nbr="' + nbr + '"]').val('1 ' + valeur_parent + ' = ')
		$('input.my[data-nbr="' + nbr + '"]').val(valeur + '(s)');
		// } 
		// else {
		// 	let delete_nbr = nbr + 1;
		// 	$('div.formule[data-nbr="' + nbr + '"]').remove();
		// 	while ($('div.unit_contain[data-nbr="' + delete_nbr + '"]').length != 0) {
		// 		$('div.formule[data-nbr="' + delete_nbr + '"]').remove();
		// 		$('div.unit_contain[data-nbr="' + (delete_nbr) + '"] ').remove();
		// 		delete_nbr++;
		// 	}
		// 	$('.add_unite_container').remove()
		// 	// $('#add_unite').remove();
		// 	$("#sous_unite_container ._boutton").remove();
		// 	let indice = $('.sous_unite').length - 1;
		// 	let sous_unite = $('.sous_unite');

		// 	$(sous_unite[indice]).attr('id', 'unite_parent');
		// }
	})

	// delete_it 
	$(document).on('click', '#delete_it', function () {
		const nbr = parseInt($(this).data('nbr'));
		$('div.formule[data-nbr="' + nbr + '"]').remove();
		$('div.unit_contain[data-nbr="' + (nbr) + '"] ').remove();
		$('#add_unite').remove();

		let btn = `<button class="btn btn-info" type='button' id='add_unite' data-nbr="${nbr - 1}"><i class="fas fa-plus"></i>  Sous unité</button>`;
		const unites = $('.all_unite');


		if (unites.length > 1) {
			btn = `<div class="_boutton ">` + btn + `</div>`;
			$('input.all_unite[data-nbr="' + (nbr - 1) + '"] ').attr('id', 'unite_parent');
			$('#sous_unite_container').append(btn);
			$('div.unit_contain[data-nbr="' + (nbr - 1) + '"] .input-group').append(`
				<button type='button' class="btn btn-danger" id='delete_it' data-nbr="${nbr - 1}" data-nbr_parent = "${nbr}"><i class="fa fa-x"></i></button>`)
		}
		else {
			$('#unite_parent_container').append(btn);
			$('#unite_parent_container input').attr('id', 'unite_parent')
			$('#sous_unite_container').addClass('d-none');
		}

		let option = $('#seul_unite option[data-self="' + nbr + '"]');
		$(option).remove();
		my_unite.splice(nbr, 1);




	})

	// all unite 
	$(document).on('keyup , change', '.all_unite', function () {
		const nbr = parseInt($(this).data('nbr'));
		const valeur = $(this).val();
		$('input.myparent[data-nbr_parent="' + nbr + '"] ').val('1 ' + valeur + ' = ');
		$('label[data-nbr="' + (nbr + 1 ) + '"] ').text('Sous unité de : ' + valeur );
	})









	// * Nouveau js 

	$("#editMaterielForm").on("submit", function (e) {
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: "<?=base_url('controller/editMateriel')?>",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function (response) {
				if (response.success) {
					alert("Materiel modifié avec succès");
				} else {
					alert("Erreur lors de la modification du materiel");
				}
			},
		});



	});
	$(document).on('click', '.imprim', function () {
		window.open(base_url('AjouterProduit/imprimer'));
	})

	$(document).on('click', '#image_ok', function () {
		$('#imageModal').click();
	})
});





$(document).on('click', '#valider', function () {
	let seuil = parseInt($('#seuil').val());
	let identification = parseInt($('#seul_unite').val());

	let seuil_min = qteMinUnit(my_unite, seuil, identification);

	$("#seul_min").val(seuil_min);

	$('#real_validation').attr('type', 'submite');
	$('#real_validation').click();

	shwoSpinner(this, ["referenceMat", "prixMat", 'seuil' ,'marque' , 'type' , 'fiche']);
})

function afficheImage(input) {
	Myalert.spinnerB() ; 
	if (input.files && input.files[0]) {
		$('#loading').removeClass('d-none');
		var reader = new FileReader();

		reader.onload = function (e) {
			$("#image").attr("src", e.target.result);
		};

		$("#btn_show_image").click();

		reader.readAsDataURL(input.files[0]);

		setTimeout(function () {
			$('#loading').addClass('d-none');
		}, 100)
	}
	Myalert.removeSpinnerB() ; 
}

$(document.body).on("click", "#modifier", function () {
	$.ajax({
		url: base_url("verifProd"),
		type: "post",
		dataType: "json",
		data: {
			reference: $("#reference-modif").val(),
			designation: $("#designation-modif").val(),
			id: $("#idMateriel-modif").val(),
		},
	}).done(function (data) {

		if (data.success) {

			let seuil = parseInt($('#seuil_modif').val());
			let identification = parseInt($('#seul_unite_modif').val());

			let seuil_min = qteMinUnit(my_unite_modif, seuil, identification);

			$("#seuil_min_modif").val(seuil_min);

			$('#real_validation').attr('type', 'submite');
			$('#real_validation').click();


			$("#modification").click();
		} else {
			if (data.referenceExiste) {
				$("#reference-modif").css("border", "1px solid red");
				$("#msg-ref").removeClass("d-none");
				$("#msg-designation").addClass("d-none");
			} else {
				$("#reference-modif").css("border", "");
			}

			if (data.designationExiste) {
				$("#designation-modif").css("border", "1px solid red");
				$("#msg-designation").removeClass("d-none");
				$("#msg-ref").addClass("d-none");
			} else {
				$("#designation-modif").css("border", "");
			}

		}
	}).fail(function (jqXHR, textStatus, errorThrown) {
		console.error("Erreur lors de la vérification de l'existence de l'agent :", textStatus, errorThrown);
	});
});


// Modification algorithme 
$(document).on('keyup', '.unite_name', function () {
	const self = parseInt($(this).data('self'));
	const parent = $(this).data('parent');
	const unite = $(this).val()

	const valeur = $(this).val();
	if (valeur != '') {
		const unite_parent = $('input.unite_name[data-self="' + parent + '"] ').val();
		if (parent != 'no_parent') {
			// pas l'unite parent
			if ($('#sous_unite_modif div.formule_contain[data-self="' + self + '"] ').length == 0) {
				let containt = `
				<div class="mb-3 formule_contain" data-self="${self}" data-parent="${parent}">
					<label class="form-label">Formule :</label>
					<div class="input-group">
						<input class="form-control input_form-control myparent" type="text" data-self="${self}" data-parent="${parent}" required readonly value='1 ${unite_parent} = '>
						<input class="form-control input_form-control myformule " type="number" name="formule[${parent}]" data-self="${self}" min='0' data-parent="${parent}" required>
						<input class="form-control input_form-control my" type="text" data-self="${self}" data-parent="${parent}" value='${unite}(s)' required readonly  >
					</div>
				</div>
				<div class="_boutton">
					<button class="btn btn-info" type='button' id='add_unite_modif' data-self="${self}"><i class="fas fa-plus"></i>  Sous unité</button>
				</div>
				`
				$('#sous_unite_modif').append(containt);
			}
			else {
				// changer la valeur de l'input readonly
				$("#sous_unite_modif .my[data-self='" + self + "'] ").val(unite + '(s)');
				$("#sous_unite_modif .myparent[data-parent='" + self + "'] ").val('1 ' + unite + ' = ');
			}
		} else {
			// l'unite parent 
			// changer la valeur de l'input readonly
			$("#sous_unite_modif .my[data-self='" + self + "'] ").val(unite + '(s)');
			$("#sous_unite_modif .myparent[data-parent='" + self + "'] ").val('1 ' + unite + ' = ');
		}
	} else {
		let i = self + 1;
		$('#sous_unite_modif div.formule_contain[data-self="' + self + '"] ').remove();
		while ($('#sous_unite_modif div.unit_contain[data-self="' + i + '"] ').length != 0) {
			$('#sous_unite_modif div.unit_contain[data-self="' + i + '"] ').remove();
			$('#sous_unite_modif div.formule_contain[data-self="' + i + '"] ').remove();
			i++;
		}
		// supprimer le boutton
		$('#sous_unite_modif ._boutton').remove();

		if (parent == 'no_parent' && $('#add_unite_modif').length == 0) {
			// si c'est le parent ,  on ajoute une boutton pour ajouter des sous-unitées
			let containt = `
				<div class="_boutton">
					<button class="btn btn-info" type='button' id='add_unite_modif' data-self="${self}"><i class="fas fa-plus"></i>  Sous unité</button>
				</div>
			` ;
			$('#sous_unite_modif').html(containt);
		}

	}

	$('label[data-self="'+( self + 1 ) +'"] ').text('Sous unité de ' + unite + ' :') ;

	
})

// ajouter une sous unité pour la modification

$(document).on('click', '#add_unite_modif', function () {
	const btn = $(this);
	const self = $(this).data('self');
	let ok_to_add = true;
	if (self == 0) {
		if ($('input.unite_name[data-self="' + self + '"] ').val() == '') {
			ok_to_add = false;
		}
	}
	else {
		if ($('input.myformule[data-self="' + self + '"]').val() == '') {
			ok_to_add = false;
			$('input.myformule[data-self="' + self + '"]').css({
				'outline': '3px solid rgba(255, 0, 0, 0.436)'
			});
			$('input.myformule[data-self="' + self + '"]').focus();
		}
	}

	let nbr_uniter = $('.unite_name').length;

	if (ok_to_add && nbr_uniter < 4) {

		$('#delete_it_modif').remove();


		$(btn).remove();

		let parent = self - 1;
		if (parent < 0) {
			parent = 'no_parent';
		}
		let content = `
			<div class="mb-3 unit_contain"  data-self="${self + 1}" data-parent = "${self}">
				<label class="form-label">Sous unité N° ${self + 1} :</label>
				<div class="input-group">
					<input class="form-control input_form-control unite_name" type="text"  data-modif ='true' name="sousUnite[${self}]" data-self="${self + 1}" data-parent = "${self}" >
					<button type='button' class="btn btn-danger" id='delete_it_modif' data-self="${self + 1}"><i class="fa fa-x"></i></button>
				</div>
			</div>
		` ;
		$('#sous_unite_modif').append(content);
		$('#sous-unite-alert').remove();

		// effacer le outline sus la formule s'il ya 
		$('input.myformule[data-self="' + self + '"]').css({
			'outline': 'none'
		});
	}
	else if (nbr_uniter >= 4) {
		let content = `<p id="sous-unite-alert" class="text-danger">Vous ne pouvez ajouter que 3 sous-unités.</p>`;
		$('#sous_unite_modif').append(content);
	}
})


// SEUIL D'ALERT 
$(document).on('keyup , change', '.unite_name', function () {
	const self = $(this).data('self');
	const unite = $(this).val();


	if (unite != '') {
		// teste s'i deja dans l'option 
		let option = $('#seul_unite_modif option[data-self="' + self + '"]');

		if (option.length == 0) {

			let content = `
				<option value="${self}"  data-self='${self}'>${unite}</option>
				` ;
			$('#seul_unite_modif').removeClass('d-none');
			$('#seul_unite_modif').append(content);

			let data = {
				formule: 0
			}
			my_unite_modif.push(data);

		} else {
			$(option).text(unite);
		}
		// teste s'i deja dans l'option 
	}
})

$(document).on('keyup , change', '#sous_unite_modif .myformule', function () {
	const self = $(this).data('self');
	const quantite = $(this).val();
	let option = $('#seul_unite option[data-self="' + self + '"]');

	if (my_unite_modif[self]) {
		my_unite_modif[self].formule = parseInt(quantite);
	}
})

$(document).on('click', '#delete_it_modif', function () {
	const self = parseInt($(this).data('self'));
	$('#sous_unite_modif div.formule_contain[data-self="' + self + '"] ').remove();
	$('#sous_unite_modif div.unit_contain[data-self="' + self + '"] ').remove();
	$('#add_unite_modif').remove();
	const unites = $('.unite_name');
	if (unites.length > 1) {
		let containt = `
		<div class="_boutton">
		<button class="btn btn-info" type='button' id='add_unite_modif' data-self="${self - 1}"><i class="fas fa-plus"></i>  Sous unité</button>
		</div>
		` ;
		$('#sous_unite_modif').append(containt);


		$('#sous_unite_modif div.unit_contain[data-self="' + (self - 1) + '"] .input-group').append(`
			<button type='button' class="btn btn-danger" id='delete_it_modif' data-self="${self - 1}"><i class="fa fa-x"></i></button>
			`);

	} else {
		let containt = `
			<button class="btn btn-info" type='button' id='add_unite_modif' data-self="${self - 1}"><i class="fas fa-plus"></i>  Sous unité</button>
	` ;
		$('#unite_modif_parent_container').append(containt);
	}


	let option = $('#seul_unite_modif option[data-self="' + self + '"]');
	$(option).remove();
	my_unite_modif.splice(self, 1);


})


// ajouter une sous unité pour la modification


// Modification algorithme 



function DonnerProduit(elem) {
	const form = $(this);
	$.ajax({
		url: base_url("DonnerProduit"),
		type: "post",
		data: {
			idProduit: elem.getAttribute("data-id"),
			ref: elem.getAttribute("data-ref"),
		},
		dataType: "json",

	}).done(function (response) {

		if (response.success) {
			const data = response.data;
			const is_used = response.is_used;
			const unites = data.unites;

			my_unite_modif = unites;


			$("#idMateriel-modif").val(data.idProduit);
			$("#reference-modif").val(data.refProduit);
			$("#designation-modif").val(data.designation);

			let content = `<option value="telephone">Télephone</option>
                        	<option value="autre">Autre</option>` ; 
			if ( data.type == 'autre' ){
				content = `
				<option value="autre">Autre</option>
				<option value="telephone">Télephone</option>
				` ; 
			}

			$("#type-modif").html( content );


			$("#fiche-modif").val(data.fiche);
			$("#seuil_modif").val(data.seuil);
			$("#idunite").val($(elem).data('unite'))

			$('#add_unite_modif').remove();
			let seul_content = '';
			if (unites.length > 0) {
				// $("#unite_modif_parent").val(unites[0].denomination);
				// let content = '';
				// let i;
				// for (i = 1; i < unites.length; i++) {
				// 	const element = unites[i];
				// 	content += `
				// 	<div class="mb-3 unit_contain"  data-self="${i}" data-parent = "${i - 1}">
				// 		<label class="form-label" data-self="${i}">Sous unité de  ${unites[i - 1 ].denomination } :</label>
				// 		<div class="input-group">
				// 			<input class="form-control input_form-control unite_name" type="text"  data-modif ='true' name="sousUnite[${i - 1}]" data-self="${i}" data-parent = "${i - 1}" value="${element.denomination}">
				// 		` ;

				// 	if (i == (unites.length - 1)) {
				// 		content += `<button type='button' class="btn btn-danger" id='delete_it_modif' data-self="${i}"><i class="fa fa-x"></i></button>`
				// 	}

				// 	content += `
				// 	</div>	
				// 	</div>
				// 	<div class="mb-3 formule_contain" data-self="${i}" data-nbr-parent="${i - 1}">
				// 		<label class="form-label">Formule :</label>
				// 		<div class="input-group">
				// 			<input class="form-control input_form-control myparent" type="text" data-self="${i}" data-parent="${i - 1}" required readonly value="1  ${unites[i - 1].denomination} = ">
				// 			<input class="form-control input_form-control myformule " min='0' type="number" name="formule[${i - 1}]" data-self="${i}" data-parent="${i - 1}" required value="${element.formule}">`;
				// 	content += `
				// 			<input class="form-control input_form-control my" type="text" data-self="${i}" data-parent="${i - 1}" required readonly value="${element.denomination}(s)">
				// 		</div>
				// 	</div>
				// 	` ;
				// }
				// for (i = 0; i < unites.length; i++) {
				// 	const element = unites[i];
				// 	if (element.idunite == data.idunite) {
				// 		seul_content += `
				// 		 <option value="${i}" data-self='${i}'>${element.denomination}</option>
				// 		` ;

				// 		$('#seul_unite_modif').removeClass('d-none');
				// 	}
				// }
				// for (i = 0; i < unites.length; i++) {
				// 	const element = unites[i];
				// 	if (element.idunite != data.idunite) {
				// 		seul_content += `
				// 		 <option value="${i}" data-self='${i}'>${element.denomination}</option>
				// 		` ;
				// 	}
				// }

				// $('#seul_unite_modif').html(seul_content);


				// content += `
				// 	<div class="_boutton">
				// 		<button class="btn btn-info" type='button' id='add_unite_modif' data-self="${i - 1}"><i class="fas fa-plus"></i>  Sous unité</button>
				// 	</div>
				// `
				// $('#sous_unite_modif').html(content);

				// $('#seuil_modif').val(data.seuil);
			}
			else {
				$('#sous_unite_modif').html('');
				$("#unite_modif_parent").val('');
				// 
				if (!is_used) {
					let content = '';
					content += `
						<button class="btn btn-info" type='button' id='add_unite_modif' data-self="0"><i class="fas fa-plus"></i>  Sous unité</button>
				`
					$('#unite_modif_parent_container').append(content);
				}
			}
			if (data.photo == 'upload/') {
				$("#images").attr("src", myUrl() + 'public/images/mode-paysage.png');
				$("#images").css({
					'border': 'none'
				})
			}
			else {
				$("#images").attr("src", myUrl() + "public/" + data.photo);
			}

			if (is_used) {
				$('#unite_modif_parent').attr('readonly', '');
				$('.unite_name').attr('readonly', '');
				$('.myformule').attr('readonly', '');
				$('#msg-uniter_').removeClass('d-none');
			}
			else {
				$('#msg-uniter_').addClass('d-none');
				$('#unite_modif_parent').removeAttr('readonly');
				$('.unite_name').removeAttr('readonly');
				$('.myformule').removeAttr('readonly');
				$('#msg-uniter_').addClass('d-none');
			}
		} else {
			Myalert.erreur('Une erreur s\'est produite.');
		}

	}).fail(function (errorMessage) {
		console.log(errorMessage);
	});


}

function afficheImages(input) {
	if (input.files && input.files[0]) {
		$('#loading_modif').removeClass('d-none');
		var reader = new FileReader();

		reader.onload = function (e) {
			$("#images").attr("src", e.target.result);
		};

		reader.readAsDataURL(input.files[0]);
		setTimeout(function () {
			$('#loading_modif').addClass('d-none');
		}, 100)
	}
}

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
});



function deleteIt(elem) {
	const id = elem.getAttribute("data-id");
	const ref = elem.getAttribute("data-ref");

	Myalert.delete('Cette action va supprimer toutes les ventes , les prix et les approvisionnements liés à ce produit. Êtes-vous sûr de vouloir continuer ?');

	$('#confirmeDelete').on('click', function () {
		$.ajax({
			url: base_url("deleteProd"),
			type: "POST",
			data: {
				id: id,
				ref: ref
			},
			dataType: "json",
			success: function (response) {
				if (response.success == true) {
					window.location.reload()
				}
			}
		});
	})
}