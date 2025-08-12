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

function create() {
	const form = document.querySelector("#modal-form");
	if (!form) {
		alert("undefined modal ...");
		return false;
	}
	const modal = bootstrap.Modal.getOrCreateInstance(form);

	modal.show();
}
function update(id, self) {
	const modal = bootstrap.Modal.getOrCreateInstance(
		document.querySelector("#modal-form")
	);
	$.get($(self).data("url"), { id: id }, function (data, textStatus, jqXHR) {
		$("#form-content").html(data);
		modal.show();
	});
}

$(document.body).on("click", "#modifier", function () {
	$.ajax({
		url: base_url("verify_client_if_exist"),
		type: "post",
		dataType: "json",
		data: {
			numero: $("#numero-donne").val(),
			email: $("#email-donne").val(),
			idClient: $("#idClient-donne").val(),
		},
	}).done(function (data) {
		if (data.success) {
			$("#modification").click();
		} else {
			if (data.numeroExiste) {
				$("#numero-donne").css("border", "1px solid red");
				$("#msg-num-agents").removeClass("d-none");
				$("#msg-mail-agents").addClass("d-none");
			} else {
				$("#numero-donne").css("border", "");
			}

			if (data.emailExiste) {
				$("#email-donne").css("border", "1px solid red");
				$("#msg-mail-agents").removeClass("d-none");
				$("#msg-num-agents").addClass("d-none");
			} else {
				$("#email-donne").css("border", "");
			}

		}
	}).fail(function (jqXHR, textStatus, errorThrown) {
		console.error("Erreur lors de la vérification de l'existence de l'agent :", textStatus, errorThrown);
	});
});

$(document.body).on("submit", "#modifClient", function (a) {
	a.preventDefault();
	const form = $(this);
	$.ajax({
		url: $(this).attr("action"),
		type: "post",
		data: form.serialize(),
		dataType: "json",
	}).done(function (data) {
		console.log(data);
		$("#editModal").modal("hide");
		$("#modifClient")[0].reset();
		if (data.success) {
			Swal.fire({
				title: "Effectué !",
				text: "Modification réussi.",
				icon: "success",
			}).then((result) => {
				if (result.isConfirmed) {
					location.href = base_url("clients");
				}
			});
		}
	});
});

function _submit(e, form) {
	e.preventDefault();
	const modal = bootstrap.Modal.getOrCreateInstance(
		document.querySelector("#modal-form")
	);
	const submitLoader = $("#submit-loader");
	$(submitLoader).removeClass("d-none");
	$(submitLoader).parent("button").prop("disabled", true);
	$.ajax({
		type: "post",
		url: $(form).attr("action"),
		data: new FormData(form),
		contentType: false,
		cache: false,
		processData: false,
		dataType: "json",
	})
		.done((res) => {
			if (res.success) {
				$(".content").html(res.page);
				showSuccessAlert();
				showTooltip();
				modal.hide();
			} else {
				$("#form-content").html(res.page);
			}
		})
		.always(() => {
			$(submitLoader).addClass("d-none");
			$(submitLoader).parent("button").prop("disabled", false);
		});
}

function _delete(id, self) {
	const alert = new Alert();

	alert.confirm(() => {
		$.post($(self).data("url"), { id: id }, function (data, textStatus, jqXHR) {
			$(".content").html(data);
			showTooltip();
		});
	});
}
function sendMessageToUser(e, form) {
	e.preventDefault();

	const inputs = $(form).find("input.form-control,textarea.form-control");
	$(inputs).removeClass("is-invalid");
	$.post(
		$(form).attr("action"),
		$(form).serialize(),
		function (data, textStatus, jqXHR) {
			if (data.success === false) {
				console.log(data);
				for (let input of inputs) {
					if (data.errors.includes($(input).attr("name"))) {
						$(input).addClass("is-invalid");
					}
				}
			} else {
				const modal = bootstrap.Modal.getOrCreateInstance(
					document.querySelector("#modal-new-message")
				);
				modal.hide();
				$("#assist-link").click();
			}
		},
		"json"
	);
}

function makaDonnees(elem) {
	$.ajax({
		url: base_url("donnerclient"),
		type: "post",
		data: {
			client: elem.getAttribute("data-id"),
		},
		dataType: "json",
	})
		.done(function (data) {
			console.log(data);
			$("#idClient-donne").val(data.idClient);
			$("#nom-donne").val(data.nomClient);
			$("#prenom-donne").val(data.prenomClient);
			$("#adresse-donne").val(data.adresseClient);
			$("#numero-donne").val(data.telClient);
			$("#email-donne").val(data.mailClient);

			$("#nif_mod").val(data.nif);
			$("#stat_mod").val(data.stat);
			$("#r_social_mod").val(data.r_social);
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
				url: base_url("deleteclient"),
				type: "POST",
				data: { id: id },
				dataType: "json",
				success: function (response) {
					if (response.success == false) {
						console.log(response.success);
						Swal.fire({
							title: "Attention !",
							text: "Vous ne pouvez pas supprimer ce client car il est déjà utilisé.",
							icon: "warning",
						}).then(() => {
							window.location.reload();
						});
					}
					if (response.success) {
						Swal.fire({
							title: "Effectué !",
							text: "Suppression effectuée.",
							icon: "success",
						}).then(() => {
							window.location.reload();
						});
						location.href = base_url("clients");
					} else if (response.success) {
						Swal.fire({
							title: "Effectué !",
							text: "Suppression effectuée.",
							icon: "success",
						}).then(() => {
							window.location.reload();
						});
					}
					// $(elem).closest("tr").remove();
				},
				error: function (xhr, status, error) {
					console.error("Erreur lors de la suppression :", error);
					// location.reload();
				},
			});
		}
		$(".succes").addClass("d-none");
		$(".error").addClass("d-none");
	});
}

$(document.body).on("submit", ".delete", function (e) {
	e.preventDefault();

	// Requete ajax:
	const url = $(this).attr("action");
	$.ajax({
		url: url,
		type: $(this).attr("method"),
		data: {
			id: $(this).attr("data-id"),
		},
		dataType: "json",
	}).done(function (data) {
		if (data.success) {
			Swal.fire({
				title: "Effectué !",
				text: "Le clients a été supprimé.",
				icon: "success",
			});
			$("#tbody-agent").html(data.data);
		} else {
			// nisy erreur oh !
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

//const conn = new WebSocket('ws://localhost:9001');
const conn = new WebSocket("wss://qitkif.com/ws2/");
conn.onopen = function (e) {
	const data = {
		type: "register",
		userId: -1,
	};
	conn.send(JSON.stringify(data));
};

conn.onmessage = function (e) {
	let res = JSON.parse(e.data);
	if (
		in_message_panel &&
		Number(res.idService) === Number($("#id-service-messenger").val()) &&
		Number(res.sender.id) === Number($("#id-user-messenger").val())
	) {
		$.post(
			base_url("admin/messenger/markAsRead"),
			{ idService: res.idService },
			function (data, textStatus, jqXHR) {}
		);

		let piece_jointe = "";
		if (res.pieceJointe) {
			piece_jointe = `<div class="message-piece-jointe">
                                <img src="${base_url(
																	"public/piece_jointe/" + res.pieceJointe
																)}" onclick="zoomIn(this)">
                            </div>`;
		}
		$(".message-wrapper")
			.append(`<div class="alert message-list d-flex" role="alert">
            <div>
            <img src="${
							res.sender.photo
								? base_url("public/images/profils/".res.sender.photo)
								: base_url("public/images/avatar.png")
						}" class="photo-messenger">
            </div>
            <div class="ps-3 w-100">
                <div class="d-flex justify-content-between">
                    <strong class="alert-heading">${res.sender.pseudo}</strong>
                    <span class="text-muted">${res.date_}</span>
                </div>
                ${res.message ? res.message : ""}
                ${piece_jointe}
            </div>
        </div>`);

		scrollTobottom(".message-wrapper");
	} else {
		$.getJSON(
			base_url("admin/messenger/getUnreadCount"),
			function (data, textStatus, jqXHR) {
				if (data.count > 0) {
					$("#unread-message-count").text(data.count);
				} else {
					$("#unread-message-count").text(null);
				}
			}
		);
	}
};

var valider = document.querySelector("#valider");
var fermer = document.querySelector(".croix");
var validation = document.querySelector(".validation");
var voilet = document.querySelector(".voilet");
var enregistrer = document.querySelector("#enregister");

valider.onclick = function () {
	validation.classList.add("block");
	voilet.classList.add("block");
};

fermer.onclick = function () {
	validation.classList.remove("block");
	voilet.classList.remove("block");
};

var reference = document.querySelector("#reference");
var quantiter = document.querySelector("#quantiter");
var prix = document.querySelector("#prix");
var montant = document.querySelector("#montant");
var tableau = document.querySelector("#tableau");

// FONCTION DE MULTIPLICATION
function multiplication() {
	var num1 = document.getElementById("prix").value;
	var num2 = document.getElementById("quantiter").value;
	var mult = num1 * num2;
	document.getElementById("montant").value = mult;
}
//FIN FONCTION DE MULTIPLICATION

valider.onclick = function () {
	var p = parseFloat(prix.value) || 0;
	var q = parseInt(quantiter.value, 10) || 0;
	var total = p * q;

	var ligne = document.createElement("tr");
	ligne.innerHTML = `
        <td>${reference.value}</td>
        <td>${q}</td>
        <td>${p}</td>
        <td>${total}</td>
    `;

	if (reference.value != "" && quantiter.value != "" && prix.value != "") {
		tableau.appendChild(ligne);
		validation.classList.add("block");
		voilet.classList.add("block");
	}
	reference.value = "";
	quantiter.value = "";
	prix.value = "";
	montant.value = "";
};

enregistrer.onclick = function () {
	validation.classList.remove("block");
	voilet.classList.remove("block");
};
