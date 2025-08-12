$(document).on('click', '#valider', function () {
	shwoSpinner(this, ['nom', 'prenom', 'adresse' ,  'email', 'numero']);
})

function deleteIt(elem) {
	const id = elem.getAttribute("data-id");

	Myalert.delete("Cette action va supprimer toutes les données insérées par cet utilisateur. Êtes-vous sûr de vouloir continuer ?")

	$('#confirmeDelete').on('click', function () {
		$.ajax({
			url: base_url("deleteUser"),
			type: "POST",
			data: { id: id },
			dataType: "json",
			success: function (response) {
				if (response.success == true) {
					window.location.reload()
				}
			}
		});
	})
}

function reinitialize(elem) {
	const id = elem.getAttribute("data-id");

	Myalert.delete('Etes-vous sur de réinitialiser se profil ?');

	$('#confirmeDelete').on('click', function () {
		$.ajax({
			url: base_url("mdpUser"),
			type: "POST",
			data: { id: id },
			dataType: "json",
			success: function (response) {
				if (response.success == true) {
					window.location.reload()
				}
			},
		});
	})
}

function DonnerUser(elem) {
	const form = $(this);
	$.ajax({
		url: base_url("DonnerUser"),
		type: "post",
		data: {
			id: elem.getAttribute("data-id"),
		},
		dataType: "json",

	}).done(function (response) {

		if (response.success) {

			const data = response.data[0];
			const pv = response.pv;
			$("#id-User").val(data.idUser);
			$("#nom_modif").val(data.nomUser);
			$("#prenom_modif").val(data.prenomUser);
			$("#numero_modif").val(data.contact);
			$("#adresse_modif").val(data.adress);
			$("#type").val(data.typeUser);
			$("#email_modif").val(data.mail);

			let content = ``;

			for (let i = 0; i < pv.length; i++) {
				const pointvente = pv[i];
				if (pointvente.idPointVente == data.idPointVente) {
					content = `
					<option class="pv" value="${pointvente.idPointVente}">
						${pointvente.denomination_pv}
					</option>
					` ;
				}
			}
			for (let i = 0; i < pv.length; i++) {
				const pointvente = pv[i];
				if (pointvente.idPointVente != data.idPointVente) {
					content += `
					<option class="pv" value="${pointvente.idPointVente}">
						${pointvente.denomination_pv}
					</option>
					` ;
				}
			}

			$('#pvModif').html( content ) ; 
		} else {
			Myalert.erreur();
		}

	})
		.fail(function (errorMessage) {
			console.log(errorMessage);
		});
}

$(document.body).on("click", "#modifier", function () {
	$.ajax({
		url: base_url("verifUser"),
		type: "post",
		dataType: "json",
		data: {
			numero: $("#numero_modif").val(),
			email: $("#email_modif").val(),
			id: $("#id-User").val(),
		},
	}).done(function (data) {
		if (data.success) {
			$("#modification").click();
		} else {
			if (data.numExiste) {
				$("#numero_modif").css("border", "1px solid red");
				$("#msg-num-modif").removeClass("d-none");
				$("#msg-mail-modif").addClass("d-none");
			} else {
				$("#numero_modif").css("border", "");
			}

			if (data.mailExiste) {
				$("#email_modif").css("border", "1px solid red");
				$("#msg-mail-modif").removeClass("d-none");
				$("#msg-num-modif").addClass("d-none");
			} else {
				$("#email_modif").css("border", "");
			}

		}
	}).fail(function (jqXHR, textStatus, errorThrown) {
		console.error("Erreur lors de la vérification de l'existence de l'agent :", textStatus, errorThrown);
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

});
