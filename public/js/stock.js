$(document).on('click', '#disimss', function () {
	$('#showDetails').click();
})

// ? num serie 

function numserie(page, date = '', ref, pv = '') {
	$.ajax({
		method: 'post',
		url: base_url('Stock/getSousPr/') + page,
		data: {
			date: date,
			ref: ref,
			pv: pv,
		},
		dataType: 'json',
		async: false
	}).done(function (reponse) {
		let tab = '';
		for (let i = 0; i < reponse.data.length; i++) {
			$('#the_prodlist').text(reponse.data[i].refProduit);
			tab += `
				<tr>
					<td>${reponse.data[i].refProduit}</td>
					<td>${reponse.data[i].numero_serie}</td>
					<td>${reponse.data[i].date_num}</td>
					<td >
						<a href=" ` + base_url('CodeBarre/creatCode/') + `${reponse.data[i].numero_serie}/${reponse.data[i].refProduit}" download>
							<img src="` + base_url('CodeBarre/creatCode/') + `${reponse.data[i].numero_serie}/${reponse.data[i].refProduit}" alt="">
						</a>
					</td>
				</tr>
			` ;
			$('#tab_sousP').html(tab);
		}
		if (reponse.pagin == 'oui') {
			let pagin_html = `
				<nav id="pagination">
					<ul class="pagination pagination-sm">
			`  ;

			if (reponse.nbr > 22) {
				let start = 0;
				let end = 0;
				let page = parseInt(reponse.page);

				start = page - 11;
				if (start < 0) {
					end = page + (-1 * start) + 11;
					start = 1;
				}
				else {
					end = page + 11;
				}

				if (end > reponse.nbr) {
					end = reponse.nbr;
				}

				if (page > 11) {
					pagin_html += `
								<li class="page-item"><a class="page-link js_pagination"  data-ref = '${ref}'  data-pv='${pv}'  data-page = ''>First...</a></li>
							`
				}
				for (let i = start; i <= end; i++) {
					if (i == 1) {
						if (reponse.page == 0) {
							pagin_html += `
								<li class="page-item"><a class="page-link js_pagination active"  data-ref = '${ref}'  data-pv='${pv}'  data-page = ''>${i}</a></li>
							`
						} else {
							pagin_html += `
								<li class="page-item"><a class="page-link js_pagination" data-ref = '${ref}'  data-pv='${pv}'  data-page = ''>${i}</a></li>
							`
						}
					}
					else {
						if (reponse.page == i) {
							pagin_html += `
							<li class="page-item"><a class="page-link js_pagination active" data-ref = '${ref}'  data-pv='${pv}'  data-page = '${i}'>${i}</a></li>
							`
						} else {
							pagin_html += `
							<li class="page-item"><a class="page-link js_pagination" data-ref = '${ref}'  data-pv='${pv}'  data-page = '${i}'>${i}</a></li>
							`
						}
					}
				}

				if (page < reponse.nbr - 11) {
					pagin_html += `
								<li class="page-item"><a class="page-link js_pagination"  data-ref = '${ref}'  data-pv='${pv}'  data-page = '${reponse.nbr}'>...Last</a></li>
							`
				}
			}
			else {
				for (let i = 1; i <= reponse.nbr; i++) {
					if (i == 1) {
						if (reponse.page == 0) {
							pagin_html += `
								<li class="page-item"><a class="page-link js_pagination active"  data-ref = '${ref}'  data-pv='${pv}'  data-page = ''>${i}</a></li>
							`
						} else {
							pagin_html += `
								<li class="page-item"><a class="page-link js_pagination" data-ref = '${ref}'  data-pv='${pv}'  data-page = ''>${i}</a></li>
							`
						}
					}
					else {
						if (reponse.page == i) {
							pagin_html += `
							<li class="page-item"><a class="page-link js_pagination active" data-ref = '${ref}'  data-pv='${pv}'  data-page = '${i}'>${i}</a></li>
							`
						} else {
							pagin_html += `
							<li class="page-item"><a class="page-link js_pagination" data-ref = '${ref}'  data-pv='${pv}'  data-page = '${i}'>${i}</a></li>
							`
						}
					}
				}
			}

			pagin_html += `
					</ul>
				</nav>
			`
			$('#soup_tab').html(pagin_html);
		}
		else {
			$('#pagination').remove();
		}
	}).fail(function () {
		console.error('erreur sur  la recuperation des sous prosuit ');
	})
}
// ? num serie 

$(document).on('click', '.imprim', function () {
	window.open(base_url('Stock/imprimer'));
})

$(document).on('click', '.filter', function (e) {
	e.stopPropagation();
	let who = $(this).data('id');
	$('.' + who).removeClass('d-none');
})
$(document.body).on('click', function (e) {
	$('.filtrage').addClass('d-none');
})

$(document).on('change', '.link_filtre', function () {
	$('#input_all').removeAttr('checked');
})
$(document).on('click', '#Quantite_f', function () {
	let data = $(this).data('data');
	console.log(data);
})

$(document.body).on('click', '.numserie', function () {
	const ref = $(this).data('ref');
	let pv = $(this).data('pv');

	numserie('', "", ref, pv);
})








$(document).on('click', '.js_pagination', function () {
	let page = $(this).data('page');
	let pv = $(this).data('pv');
	let ref = $(this).data('ref');
	numserie(page, "", ref, pv);
})

$(document.body).on('click', '.detail', function () {
	const idProduit = $( this ).data('id') ; 
	const reference = $( this ).data('reference') ; 
	const idPointVente = $( this ).data('idpointvente') ; 

	$('#loader_stock').removeClass('d-none');
	$('#stock_details').addClass('d-none');



	pagination_js( idProduit , idPointVente  ) ; 

})


function pagination_js(idProduit = '', the_pv = '', page = 1) {
	$('#loader_stock').removeClass('d-none');
	$('#stock_details').addClass('d-none');
	$.ajax({
		method: 'post',
		url: base_url('Stock/numeroSerie/') + page,
		data: {
			idProduit: idProduit,
			idPointVente: the_pv
		},
		dataType: 'json'
	}).done(function (response ) {
		if ( response.success ){
			const datas = response.data ; 
			let content = '' ; 
			for (let i = 0; i < datas.length; i++) {
				const element = datas[i];
				content += `<tr>
								<td>${ element.numero}</td>
								<td>${ element.imei1}</td>
								<td>${ element.imei2}</td>
        					</tr>
				` ; 
			}
			$('#stock-detail').html( content ) ; 
			$('#pagination_js_').html( response.lien ) ; 

			$('#loader_stock').addClass('d-none');
			$('#stock_details').removeClass('d-none');
		}else {
			$('#stock-detail').html(`<p class='text-secondary'>Aucun numéro de série ....</p>`) ; 
		}
	}).fail(function () {
		console.error('erreur sur  la recuperation des sous prosuit ');
	})
}
$( document ).on('click' , '.my_link' , function(){
	const idProduit = $( this ).data('idproduit') ; 
	const idpointvente = $( this ).data('idpointvente') 
	const page = $( this ).data('page') ; 
	pagination_js( idProduit , idpointvente , page   ) ; 
})

$(document).on('click', '.js_pagination', function () {
	let page = $(this).data('page');
	let idpv = $(this).data('pvid');

	let keyword = $('#stock_search').val();
	if (keyword != '') {
		if (idpv == '') {
			pagination_js(page, keyword);
		}
		else {
			pagination_js(page, keyword, idpv)
		}
	}
	else {

		if (idpv == '') {
			pagination_js(page);
		}
		else {
			pagination_js(page, '', idpv);
		}
	}
})


