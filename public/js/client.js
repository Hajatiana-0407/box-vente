
$( document ).on('click' , '#valider' , function (){
	shwoSpinner( this , ['address' , 'num' ]) ; 
})

$( document ).on('click'  , '#client_type option' , function (){
	let type = $( this ).val() ; 
	if( type == 1 ){
		$('.is_public').addClass('d-none') ; 
		$('.not_public').removeClass('d-none') ; 
	} 
	else {
		$('.not_public').addClass('d-none') ; 
		$('.is_public').removeClass('d-none') ; 
	}
})
$(document.body).on("click", "#validation", function () {
	$.ajax({
		url: base_url("Clients/validationClient"),
		type: "post",
		dataType: "json",
		data: {
			numero: $("#numero").val(),
		},
	})
		.done(function (data) {
			if (data.success) {
				$("#valider").attr('type' , 'submit') ; 
				$("#valider").click();
			} else {
				if (data.numeroInsertExiste) {
					$("#numero").css("border", "1px solid red");
					$("#msg-numero").removeClass("d-none");
				}
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.error(
				"Erreur lors de la vérification de l'existence de l'agent :",
				textStatus,
				errorThrown
			);
	});
});

function Donneclient(elem) {
	$.ajax({
		url: base_url("Clients/donnerClient"),
		type: "post",
		data: {
			client: elem.getAttribute("data-id"),
		},
		dataType: "json",
	})
		.done(function (data) {
			$("#idClient_modif").val(data.idClient);
			$("#nom_modif").val(data.nomClient);
			$("#prenom_modif").val(data.prenomClient);
			$("#adresse_modif").val(data.adresseClient);
			$("#numero_modif").val(data.telClient);
			$("#email_modif").val(data.emailClient);

			if( data.stat || data.nif || data.r_social){
				$("#stat_modif").val(data.stat);
				$("#nif_modif").val(data.nif);
				$("#r_social_modif").val(data.r_social);
				$('#public_modif').removeClass('d-none')
				$('#private_modif').addClass('d-none')
			}
			else {
				$('#private_modif').removeClass('d-none')
				$('#public_modif').addClass('d-none')
			}
		})
		.fail(function (errorMessage) {
			console.log(errorMessage);
		});
}

function deleteIt(elem) {
	const id = elem.getAttribute("data-id");

	Myalert.delete()
	$('#confirmeDelete').on('click', function () {
		$.ajax({
			url: base_url("Clients/deleteClient"),
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

$(document.body).on("click", "#modifier", function () {
	$.ajax({
		url: base_url("Clients/verifiClient"),
		type: "post",
		dataType: "json",
		data: {
			emailModif: $("#email_modif").val(),
			numeroModif: $("#numero_modif").val(),
			old: $("#idClient_modif").val(),
		},
	}).done(function (data) {
		if (data.success) {
			$("#modification").attr('type','submit');
			$("#modification").click();
		} else if (data.numero) {
			$("#numero_modif").css({"border-color": "red"});
			$("#msg-num-modif").removeClass("d-none");
			$("#email_modif").css({"border-color": ""});
			$("#msg-mail-modif").addClass("d-none");
		} else if (data.email) {
			$("#email_modif").css({"border-color": "red"});
			$("#msg-mail-modif").removeClass("d-none");
			$("#numero_modif").css({"border-color": ""});
			$("#msg-num-modif").addClass("d-none");
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
});


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
