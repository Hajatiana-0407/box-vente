$(document).on('click', '#valider', function () {

	const prixUnite = $('.prix_Unite');
	let ok_to_submit = true;
	for (let i = 0; i < prixUnite.length; i++) {
		const element = prixUnite[i];
		if ($(element).val() == '') {
			ok_to_submit = false;
		}
	}

	if (ok_to_submit) {
		shwoSpinner(this, ['reference', 'designation', 'prix']);
	}
})

$(document.body).on("change", "#reference", function (e) {
	e.preventDefault();
	const ref = $(this).val();
	$.ajax({
		url: base_url('rechercherProd'),
		type: "post",
		data: {
			ref: ref,
		},
		dataType: "json",
	}).done(function (data) {
		if (data.success) {
			const produit = data.produit;
			const unites = data.unites;


			$("#reference").val(produit.refProduit);
			$("#designation").val(produit.designation);
			$("#fiche").val(produit.fiche);
			$("#idproduit").val(produit.idProduit);

			let parent_content = '';
			if (unites.length > 0) {
				parent_content += `
				<div class="mb-3">
				   <label class="form-label">Prix :</label>
				   <div class="input-group">
					   <div type="text" class="form-control w-25" id="uniteparent"  readonly required>
					   ${unites[0].denomination}
                        </div>
					   <input type="number" name="prixUnite[${0}]" min='0' class="form-control w-75 prix_Unite" id="unitParentval"  required >
					   <input type="hidden" class='d-none' name="idUnite[${0}]" min='0'  value='${unites[0].idunite}'   required >
				   </div>
			   </div>
	   			`
				if (unites.length > 1) {
					for (let i = 1; i < unites.length; i++) {
						const element = unites[i];
						parent_content += `
						<div class="mb-3">
						   <div class="input-group">
							   <div type="text" class="form-control w-25" id="uniteparent"  readonly required>
								${unites[i].denomination}
								</div>
							   <input type="number" min='0' class="form-control w-75 prix_Unite"  name="prixUnite[${i}]"  required >
							   <input type="hidden" class='d-none' name="idUnite[${i}]" min='0'  value='${unites[i].idunite}'   required >
						   </div>
					   </div>
						   ` ;
					}
				}
			}
			else {
				parent_content = `
				<div class="mb-3">
						<label class="form-label">Prix :</label>
						<input type="text" class="form-control " id="prix_produit" name="prix_produit">
				</div>
				` ; 
				
			}
			$("#unite_container").html(parent_content);
			$("#unitParentval").focus();
			$('#prix_produit').focus() ; 

		} else if (data.error == true) {
			$("#designation").val('')
			$("#reference").val('')
			$("#prix").val('')
			Myalert.erreur('Cette Réference n\'existe pas.')
		}
	})
});


$(document.body).on("click", ".edit", function () {
	var id = $(this).attr('data-id');
	var idProduit = $(this).attr('data-idProduit');
	var prixProduit = $(this).attr('data-prixProduit');
	var designation = $(this).attr('data-designation');
	var refProduit = $(this).attr('data-refProduit');
	var uniter = $(this).attr('data-nomUniter');

	$("#idPrix_modif").val(id);
	$("#idProduit_modif").val(idProduit);
	$("#prix_modif").val(prixProduit);
	$("#designation_modif").val(designation);
	$("#uniter_modif").val(uniter);
	$("#reference_modif").val(refProduit);

});

function deleteIt(elem) {
	const groupe = elem.getAttribute("data-groupe");
	const idProduit = elem.getAttribute("data-idproduit");

	Myalert.delete()
	$('#confirmeDelete').on('click', function () {
		$.ajax({
			method: 'post',
			url: base_url('Prix/deletePrix'),
			data: { groupe: groupe, idProduit: idProduit },
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					window.location.reload()
				}
			}
		})
	})
}

let window_width = window.innerWidth;

$(document).ready(function () {
	if (window_width <= 768) {
		$('.sidebar').addClass('hide');
	}
	$(window).on('resize', function () {
		if ($(this).innerWidth() <= 768) {
			$('.sidebar').addClass('hide');
		} else {
			$('.sidebar').removeClass('hide');
		}
	})
	const elemtooltips = document.querySelectorAll('.btn-tooltip')
	for (let elem of elemtooltips) {
		new bootstrap.Tooltip(elem)
	}
});

function toggleSidebar() {
	$('.sidebar').toggleClass('hide');
	$('.backdrop').toggleClass('d-none');
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


// $(document).on('click', '#valider', function () {
// 	let prix = $('.prix');

// 	for (let i = 0; i < prix.length; i++) {
// 		if (prix[i].value != '') {
// 			$.ajax({
// 				method: 'post',
// 				url: base_url('registerPrix'),
// 				data: {
// 					reference: $('#reference').val(),
// 					idMat: $('#idPrix').val(),
// 					designation: $('#designation').val(),
// 					prix: prix[i].value,
// 					idUniter: prix[i].getAttribute('data-idUniter'),
// 				},
// 			}).done(function (msg) {
// 				if (i == prix.length - 1 || prix[i + 1].value == '') {
// 					$('#actualise').click();
// 				}
// 			})
// 		}
// 	}
// })




