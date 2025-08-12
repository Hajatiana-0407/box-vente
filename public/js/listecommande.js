$(document.body).on('click', ".supprimer", function () {

	let idcommande = $(this).data('idcommande');
	const elem = $(this);

	Myalert.delete();

	$('#confirmeDelete').on('click', function () {
		$('.close').click();
		$.ajax({
			method: 'post',
			url: base_url('Listecommande/deleteListe'),
			data: { idcommande: idcommande },
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

$(document.body).on("click", ".detail", function () {
	var idcommande = $(this).data("idcommande");
	$.ajax({
		url: base_url("Listecommande/getDetails"),
		type: "post",
		data: {
			idcommande: idcommande,
		},
	}).done(function (data) {
		$("#tab").html(data);
	});
});

$(document.body).on("click", ".imprim", function () {

	Myalert.spinnerB();
	$('#affichefacture').click();

	let facture = $(this).data("cmfacture");
	let url = base_url('Commande/facture/' + facture);

	$('#pdfFrame').attr('src', url);

	setTimeout(function () {
		Myalert.removeSpinnerB();
	}, 100)
	
	$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');
	setTimeout(function () {
		$('#loaderFacture').addClass('d-none');
		$('#pdfFrame').removeClass('d-none');
	}, 5000)
});