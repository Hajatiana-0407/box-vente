$(document.body).on('click', ".supprimer", function () {
	let idfacture = $(this).data('idfacture');
	const elem = $(this);
	Myalert.delete();
	$('#confirmeDelete').on('click', function () {
		$('.close').click();
		$.ajax({
			method: 'post',
			url: base_url('Liste/deleteListe'),
			data: { idfacture: idfacture },
			dataType: 'json',

		}).done(function (response) {
			if (response.success == true) {
				$(elem).closest('tr').remove();
				Myalert.deleted();
			}
		}).fail(function () {
			console.log('error');
		})
	})
});

$(document).on('click', '.imprim', function () {
	$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');
	$('#affichefacture').click();
	let facture = $(this).data("facture");
	let url = base_url('Vente/facture/' + facture);

	$('#pdfFrame').attr('src', url);

	setTimeout(function () {
		Myalert.removeSpinnerB();
	}, 100) ; 

	$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');
	setTimeout(function () {
		$('#loaderFacture').addClass('d-none');
		$('#pdfFrame').removeClass('d-none');
	}, 5000)
})
$(document).on('change', '#format', function () {
	$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');

	const format = $(this).val();

	let src = $('#pdfFrame').attr('src');
	let new_src = '';
	$('#pdfFrame').attr('src', '');

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
$(document.body).on("click", ".detail", function () {
	var idfacture = $(this).data("idfacture");
	$.ajax({
		url: base_url("getDetails"),
		type: "post",
		data: {
			idfacture: idfacture,
		},
	}).done(function (data) {
		$("#tab").html(data);
	});
});
