$(document).ready(function () {
	

	$(document.body).click(function () {
		$("#movable").remove();
		$("#movable_notest").remove();
		$.ajax({
			method: 'post',
			url: base_url('Utility/session'),
			dataType: 'json',
			async: false
		}).done(function (response) {
			const url = location.href;
			if (!response.success) {
				if (url != base_url() && url != base_url('Auth')) {
					location.href = base_url('Auth');
				}
			}
		})
	})

	$(function () {
		$("#movable").draggable();
		$("#movable_notest").draggable();
	});

	setInterval(() => {
		$("#movable").animate({
			'outlineWidth': '8px'
		}, 500);
		setTimeout(() => {
			$("#movable").animate({
				'outlineWidth': '0px'
			})
		}, 500);
	}, 1000);
	setInterval(() => {
		$("#movable_notest").animate({
			'outlineWidth': '8px'
		}, 500);
		setTimeout(() => {
			$("#movable_notest").animate({
				'outlineWidth': '0px'
			})
		}, 500);
	}, 1000);
})

function shwoSpinner(button, inputs = []) {
	let test = true;

	for (let i = 0; i < inputs.length; i++) {
		const identifiant = inputs[i];
		if ($('#' + identifiant).val() == '') {
			test = false;
		}
	}

	if (test) {
		$('#spinner_validation').removeClass('d-none')
		$(button).find('.fa-check').addClass('d-none');
	}
	else {
		$('#spinner_validation').addClass('d-none')
		$(button).find('.fa-check').removeClass('d-none');
	}
}


function covertion(all_unite = [], min_qte = 0) {
	let by_unite;
	let reste = 0;
	// ajouter la valeur pour l'unité le plus petite
	let data = [];

	if (all_unite.length > 0) {
		data = [{
			'unite': all_unite[all_unite.length - 1].denomination,
			'quantite': min_qte,
			'reste': reste
		}];
	} else {
		data = [{
			'unite': '',
			'quantite': min_qte,
			'reste': reste
		}];

	}
	for (let i = all_unite.length - 2; i >= 0; i--) {

		if (all_unite[i + 1] != undefined) {
			const element = all_unite[i];
			const unite = element.denomination;

			// verification si il' a une virgule ( reste )
			reste = min_qte % all_unite[i + 1].formule;
			if (reste != 0) {
				if (reste > 1) {
					reste = reste + ' ' + all_unite[i + 1].denomination + 's';
				} else {
					reste = reste + ' ' + all_unite[i + 1].denomination;
				}
			}

			min_qte = parseInt(min_qte / all_unite[i + 1].formule);

			by_unite = {
				'unite': unite,
				'quantite': min_qte,
				'reste': reste
			};

			data.unshift(by_unite);
		}
	}

	return data;
}

function stock_texte(unite = [], id = 0) {
	let texte = '';
	const concat = '+';
	for (let i = id; i < unite.length; i++) {
		const element = unite[i];
		if (i == id) {
			// on affiche rien si la quantité est 0 
			if (element.quantite > 0) {
				texte = `${element.quantite} ${element.unite}`;
				if (element.quantite > 1) {
					// pour mettre le s
					texte = `${element.quantite} ${element.unite}s`;
				}
				// sans unite 
				if (!element.unite) {
					texte = `${element.quantite}`;
				}
			}
		}
		if (element.reste != 0) {
			// reste
			if (element.quantite > 0) {
				texte += ` ${concat} ${element.reste}`;
			}
			else {
				texte += ` ${element.reste}`;
			}
		}
	}
	return texte;
}
function qteMinUnit(allUnit = [], qte = 0, unite_identification = 0) {

	while (allUnit[unite_identification + 1]) {
		qte *= allUnit[unite_identification + 1].formule;
		unite_identification++;
	}
	return qte
}







