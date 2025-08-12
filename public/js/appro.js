//************************* */ Nouveau js appro 
$(document.body).on("change", "#reference", function () {
	const ref = $(this).val();
	$.ajax({
		url: base_url("Appro/recherche_produit"),
		type: "post",
		dataType: "json",
		data: {
			ref: ref,
		},
	}).done(function (data) {
		if (data.success == true) {
			const produits = data.produit;
			const unites = data.unites;
			$('#couleur').focus();
			$("#idProduit").val(produits.idProduit);
			$("#reference").val(produits.refProduit);
			$("#designation").val(produits.designation);
			$("#fiche").val(produits.fiche);
			// numero de serie  
			$('#type').val(produits.type);

			if (produits.type == 'telephone') {
				// telephone 
				$('.telephone').removeClass('d-none');
				$('.autre').addClass('d-none');
				

				$('#numSerie').attr('required', '');
				$('#imei1').attr('required', '');
				$('#imei2').attr('required', '');
				$('.autre_hidde').addClass('d-none');

				// *************
				$('#quantite').removeAttr('required');
				$('#montant').removeAttr('required');

				$("#prix").val("")
				$("#quantite").val("")
				$("#montant").val("")
			} else {
				// Autre 
				$('.telephone').addClass('d-none');
				$('.autre').removeClass('d-none')

				

				$('.numero_liste_').addClass('d-none');
				$('#numSerie').removeAttr('required');
				$('#imei1').removeAttr('required');
				$('#imei2').removeAttr('required');
				$('.autre_hidde').removeClass('d-none');

				// *************
				$('#quantite').attr('required', '');
				$('#montant').attr('required', '');
			}
			// numero de serie  
			if (unites.length > 0) {
				let content = '';
				for (let i = 0; i < unites.length; i++) {
					content += `<option value="` + unites[i].idunite + `">` + unites[i].denomination + `</option>`
				}
				$('#unite').html(content);
				$('#unite_container').removeClass('d-none');
			} else {
				$('#unite_container').addClass('d-none');
			}

		} else {
			$("#designation").val("")
			$("#reference").val("")
			$("#fiche").val("")
			$("#prix").val("")
			$("#quantite").val("")
			$("#montant").val("")
			$('#unite').html('');
			$('#qte').removeAttr('readonly');
			Myalert.erreur("Cette Réference n'existe pas.")

			$('.telephone').removeClass('d-none');
			$('.autre').addClass('d-none');
		}
	})
});

$(document.body).on('click', '.delete', function () {
	Myalert.delete()
	const idappro = $(this).data('idappro');


	$('#confirmeDelete').on('click', function () {
		$.ajax({
			method: 'post',
			url: base_url("deleteAppro"),
			data: { idappro: idappro },
			dataType: 'json',
			success: function (response) {
				if (response.success == true) {
					window.location.reload()
				}
			}
		})
	})
})

$(document).on('click', '#valider', function () {
	const idfournisseur = $('#fournisseur').val();
	if ($("#type").val() == 'autre') {
		shwoSpinner(this, ['designation', 'prix', 'quantite', 'montant']);
	} else {

		shwoSpinner(this, ['designation', 'prix', 'numSerie', 'imei1', 'imei2']);
	}
});

$(document).on('change , keyup', '#quantite , #prix', function () {
	const quantite = $('#quantite').val();
	const prix = $('#prix').val();

	console.log('ato');

	let montant = 0;

	if (quantite != '' && prix != '') {
		montant = parseInt(quantite) * parseInt(prix);
	}

	$('#montant_show').val(montant.toLocaleString("fr-FR") + ' AR ');
	$('#montant').val(montant);
})

// verification dans la base de donne
function verify_numserie_base(numero) {
	let result = false;
	$.ajax({
		method: 'post',
		url: base_url('Appro/verifyNumserie'),
		data: { numero: numero },
		async: false,
		dataType: 'json',
	}).done(function (response) {
		result = response.success;
	}).fail(function (err) {
		console.error('erreur dans la verification du numero de series '.err);
	})
	return result;
}
function verify_imei_base(imei) {
	let result = false;
	$.ajax({
		method: 'post',
		url: base_url('Appro/verifyImei'),
		data: { imei: imei },
		async: false,
		dataType: 'json',
	}).done(function (response) {
		result = response.success;
	}).fail(function (err) {
		console.error('erreur dans la verification du numero de series '.err);
	})
	return result;
}

// verification dans les input ( standar )
function verify_input(numero, the_class, self) {
	let input = $('.'.the_class);
	let teste = true;
	for (let i = 0; i < input.length; i++) {
		const element = input[i];

		let elem_self = $(element).data('self');
		if (elem_self != self && $(element).val() == numero) {
			teste = false
		}
	}
	return teste;
}

function exist(elem) {
	$(elem).css({
		'border': '1px solid red'
	})
	$(elem).val('');
}
function ok(elem) {
	$(elem).css({
		'border': ''
	});
}


$(document).on('change', '#numSerie', function () {
	const numero = $(this).val();
	const self = $(this).data('self');
	if ($('#designation').val() != '' && numero != '') {
		if (verify_numserie_base(numero)) {
			ok(this);
			$('#numero_mss').addClass('d-none');
		} else {
			exist(this);
			$('#numero_mss').removeClass('d-none');
		}
	} else {
		$(this).val('');
		$('#numero_mss').addClass('d-none');
	}
})
$(document).on('change', '#imei1', function () {
	const imei = $(this).val();
	const self = $(this).data('self');
	if ($('#designation').val() != '' && imei != '') {
		if (verify_imei_base(imei) && $('#imei2').val() != imei) {
			ok(this);
			$('#imei1_mss').addClass('d-none');
		} else {
			exist(this);
			$('#imei1_mss').removeClass('d-none');
			$(this).focus();
		}
	} else {
		$(this).val('');
		$('#imei1_mss').addClass('d-none');
	}
})
$(document).on('change', '#imei2', function () {
	const imei = $(this).val();
	const self = $(this).data('self');
	if ($('#designation').val() != '' && imei != '') {
		if (verify_imei_base(imei) && $('#imei1').val() != imei) {
			ok(this);
			$('#imei2_mss').addClass('d-none');
		} else {
			exist(this);
			$('#imei2_mss').removeClass('d-none')
			$(this).focus();;
		}
	} else {
		$(this).val('');
		$('#imei2_mss').addClass('d-none');
	}
})







// // * pagination 
// let qte_test = true;


// function pagination_js(page, date, ref) {
// 	$.ajax({
// 		method: 'post',
// 		url: base_url('Appro/getSousPr/') + page,
// 		data: {
// 			date: date,
// 			ref: ref,
// 		},
// 		dataType: 'json'
// 	}).done(function (reponse) {
// 		console.log(reponse);
// 		let tab = '';
// 		for (let i = 0; i < reponse.data.length; i++) {
// 			tab += `
// 				<tr>
// 					<td>${reponse.data[i].refProduit}</td>
// 					<td>${reponse.data[i].numero_serie}</td>
// 					<td>${reponse.data[i].etat_vente}</td>
// 					<td>${reponse.data[i].date_num}</td>
// 					<td>
// 						<a href=" ` + base_url('CodeBarre/creatCode/') + `${reponse.data[i].numero_serie}/${reponse.data[i].refProduit}" download>
// 							<img src="` + base_url('CodeBarre/creatCode/') + `${reponse.data[i].numero_serie}/${reponse.data[i].refProduit}" alt="">
// 						</a>
// 					</td>
// 				</tr>
// 			` ;
// 			$('#tab_sousP').html(tab);
// 		}
// 		if (reponse.pagin == 'oui') {
// 			let pagin_html = `
// 				<nav id="pagination">
// 					<ul class="pagination pagination-sm">
// 			`  ;

// 			if (reponse.nbr > 22) {
// 				let start = 0;
// 				let end = 0;
// 				let page = parseInt(reponse.page);

// 				start = page - 11;
// 				if (start < 0) {
// 					end = page + (-1 * start) + 11;
// 					start = 1;
// 				}
// 				else {
// 					end = page + 11;
// 				}

// 				if (end > reponse.nbr) {
// 					end = reponse.nbr;
// 				}

// 				if (page > 11) {
// 					pagin_html += `
// 								<li class="page-item"><a class="page-link js_pagination"  data-ref = '${ref}'  data-date='${date}'  data-page = ''>First...</a></li>
// 							`
// 				}
// 				for (let i = start; i <= end; i++) {
// 					if (i == 1) {
// 						if (reponse.page == 0) {
// 							pagin_html += `
// 								<li class="page-item"><a class="page-link js_pagination active"  data-ref = '${ref}'  data-date='${date}'  data-page = ''>${i}</a></li>
// 							`
// 						} else {
// 							pagin_html += `
// 								<li class="page-item"><a class="page-link js_pagination" data-ref = '${ref}'  data-date='${date}'  data-page = ''>${i}</a></li>
// 							`
// 						}
// 					}
// 					else {
// 						if (reponse.page == i) {
// 							pagin_html += `
// 							<li class="page-item"><a class="page-link js_pagination active" data-ref = '${ref}'  data-date='${date}'  data-page = '${i}'>${i}</a></li>
// 							`
// 						} else {
// 							pagin_html += `
// 							<li class="page-item"><a class="page-link js_pagination" data-ref = '${ref}'  data-date='${date}'  data-page = '${i}'>${i}</a></li>
// 							`
// 						}
// 					}
// 				}

// 				if (page < reponse.nbr - 11) {
// 					pagin_html += `
// 								<li class="page-item"><a class="page-link js_pagination"  data-ref = '${ref}'  data-date='${date}'  data-page = '${reponse.nbr}'>...Last</a></li>
// 							`
// 				}
// 			}
// 			else {
// 				for (let i = 1; i <= reponse.nbr; i++) {
// 					if (i == 1) {
// 						if (reponse.page == 0) {
// 							pagin_html += `
// 								<li class="page-item"><a class="page-link js_pagination active"  data-ref = '${ref}'  data-date='${date}'  data-page = ''>${i}</a></li>
// 							`
// 						} else {
// 							pagin_html += `
// 								<li class="page-item"><a class="page-link js_pagination" data-ref = '${ref}'  data-date='${date}'  data-page = ''>${i}</a></li>
// 							`
// 						}
// 					}
// 					else {
// 						if (reponse.page == i) {
// 							pagin_html += `
// 							<li class="page-item"><a class="page-link js_pagination active" data-ref = '${ref}'  data-date='${date}'  data-page = '${i}'>${i}</a></li>
// 							`
// 						} else {
// 							pagin_html += `
// 							<li class="page-item"><a class="page-link js_pagination" data-ref = '${ref}'  data-date='${date}'  data-page = '${i}'>${i}</a></li>
// 							`
// 						}
// 					}
// 				}
// 			}

// 			pagin_html += `
// 					</ul>
// 				</nav>
// 			`
// 			$('#soup_tab').html(pagin_html);
// 		}
// 		else {
// 			$('#pagination').remove();
// 		}
// 	}).fail(function () {
// 		console.error('erreur sur  la recuperation des sous prosuit ');
// 	})
// }
// $(document).on('click', '.js_pagination', function () {
// 	let page = $(this).data('page');
// 	let date = $(this).data('date');
// 	let ref = $(this).data('ref');
// 	pagination_js(page, date, ref);
// })
// $(document).on('click', '.num_serie_info', function () {
// 	let date = $(this).data('date');
// 	let page = '';
// 	let ref = $(this).data('ref');
// 	pagination_js(page, date, ref);
// })



// $(document).on('change', '#numSerie', function () {
// 	if ($(this).data('numero') == 1) {
// 		$(this).attr('name', 'numSerie[1]')
// 	}
// 	let valeur = $(this).val();
// 	let this_inp = $(this)
// 	let num = $(this).data('numero')
// 	$.ajax({
// 		url: base_url("verifNumSerie"),
// 		type: "post",
// 		dataType: "json",
// 		async: false,
// 		data: {
// 			input: valeur,
// 		},
// 	}).done(function (data) {

// 		let num_input = $('#numSerie:not(#numSerie[data-numero="' + num + '"])');
// 		//$('#qte').val( num_input.length + 1 ) ; 
// 		$('#qte').attr('readonly', '');

// 		let ok_outside = true;
// 		if (num_input.length != 0) {
// 			for (let i = 0; i < num_input.length; i++) {
// 				if ($(num_input[i]).val().toLowerCase() == valeur.toLowerCase()) {
// 					ok_outside = false;
// 				}
// 			}
// 		}

// 		if ((data.success != 'there_is') && (ok_outside == true)) {

// 			if (valeur != '') {
// 				$('#numSerie').attr('readonly', '');
// 				$('#numSerie button.btn-warning ').removeClass('d-none');
// 			}
// 			// else {
// 			// 	$('#numSerie').removeAttr('readonly' , '') ; 
// 			// }


// 			$('#numSerie button.btn-danger ').removeClass('d-none');
// 			$('#numSerie').css({ 'border': '' });

// 		}
// 		else {

// 			if (valeur != '') {
// 				$(this_inp[0]).css({ 'border': '1px solid red' });
// 				$(this_inp[0]).val('');
// 				$(this_inp[0]).focus();

// 				Myalert.erreur(`Ce numéro de série <strong>${valeur}</strong> existe déjà`)

// 				$('#button').click(function () {
// 					$(num_input[num_input.length - 1]).focus();
// 				})
// 				$('#close').click(function () {
// 					$(num_input[num_input.length - 1]).focus();
// 				})
// 				$('.volet').click(function () {
// 					$(num_input[num_input.length - 1]).focus();
// 				})
// 			}
// 		}
// 	})
// });

// $(document).on('click', '#button_plus', function () {
// 	qte_test = true;
// 	let num_input = $('#numSerie');

// 	let valeur = $(num_input[num_input.length - 1]).val();

// 	$.ajax({
// 		url: base_url("verifNumSerie"),
// 		type: "post",
// 		dataType: "json",
// 		data: {
// 			input: valeur,
// 		},
// 	}).done(function (data) {
// 		let num_input = $('#numSerie');
// 		let ok_outside = true;
// 		if (num_input.length != 1) {
// 			for (let i = 0; i < num_input.length - 2; i++) {
// 				if ($(num_input[i]).val().toLowerCase() == valeur.toLowerCase()) {
// 					ok_outside = false;
// 				}
// 			}
// 		}

// 		if ((data.success != 'there_is') && (ok_outside == true)) {

// 			if ($(num_input[num_input.length - 1]).val() != '') {

// 				var numero = $(num_input[num_input.length - 1]).data('numero');
// 				numero = numero + 1;

// 				$('#numSerie').append(`
// 					<div class="input-group  mb-3">
// 						<input  type="text" class="form-control numSerie" placeholder="N° de serie..." data-numero="${numero}" name="numSerie[${numero}]" required>
// 						<button class="btn btn-warning d-none" type='button' data-numero="${numero}" >
// 							<i class="fa-solid fa-edit"></i>
// 						</button>
// 						<button class="btn btn-danger" type='button'  >
// 							<i class="fa-solid fa-x"></i>
// 						</button>
// 					</div>
// 				`);
// 			}
// 		}
// 	})
// });

// $(document).on('click', '#numSerie button.btn-danger', function () {
// 	let num_input = $('#numSerie');

// 	$('#numSerie').css({ 'border': '' });



// 	if (num_input.length != 1) {
// 		$(this).closest('div.input-group').remove();

// 		let new_length = num_input.length - 1;
// 		$('#qte').val(new_length);
// 	}
// 	else {
// 		$(this).addClass('d-none');
// 		$('.btn-warning').addClass('d-none');
// 		let inp = document.querySelector('#numSerie');
// 		inp.removeAttribute('readonly');
// 		inp.removeAttribute('name');
// 		inp.value = '';

// 		$('#qte').removeAttr('readonly');
// 		$('#qte').val('');

// 	}





// })

// $(document).on('click', '#numSerie button.btn-warning', function () {
// 	let num = $(this).data('numero');
// 	$('#numSerie[ data-numero="' + num + '"]').attr('required', '');
// 	$('#numSerie[ data-numero="' + num + '"]').removeAttr('readonly');
// 	$('#numSerie[ data-numero="' + num + '"]').focus();
// 	$(this).addClass('d-none');
// })







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


	$(".ui.dropdown").dropdown();
});



