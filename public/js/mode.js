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
function donnermode(elem) {
	$.ajax({
		url: base_url('DonneMode'),
		type: "post",
		data: {
			modepaiement: elem.getAttribute("data-id"),
		},
		dataType: "json",
	})
		.done(function (data) {
			console.log(data);
			$("#modeId").val(data.idModePaiement);
			$("#nom-mode").val(data.denom);
			$("#num-mode").val(data.numeroCompte);
		})
		.fail(function (errorMessage) {
			console.log(errorMessage);
		});
}

$(document.body).on("click", "#modifier", function () {
	$.ajax({
		url: base_url("verify_mode_if_exist"),
		type: "post",
		dataType: "json",
		data: {
			nom: $("#nom-mode").val(),
			numero: $("#num-mode").val(),
			idMode: $("#modeId").val(),
		},
	})
		.done(function (data) {

			if (data.success) {
				$("#modification").click();
			} else {
				let error_ = data.type;
				if (error_ == 'nom') {
					$("#num-mode").css("border", "");

					$("#nom-mode").css("border", "1px solid red");
					$("#msg-nom-mode").removeClass("d-none");
					$("#msg-num-mode").addClass("d-none");
				} else if (error_ == 'num') {
					$("#nom-mode").css("border", "");

					$("#num-mode").css("border", "1px solid red");
					$("#msg-num-mode").removeClass("d-none");
					$("#msg-nom-mode").addClass("d-none");
				}
				else {
					$("#nom-mode").css("border", "1px solid red");
					$("#num-mode").css("border", "1px solid red");
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

$(document.body).on("submit", "#modepaiement", function (a) {
	a.preventDefault();
	const form = $(this);
	$.ajax({
		url: $(this).attr("action"),
		type: $(this).attr("method"),
		data: form.serialize(),
		dataType: "json",
	}).done(function (data) {
		// $("#editModal").modal("hide");
		// $("#modepaiement")[0].reset();
		location.href = base_url("mode_de_paiment");
	});
});

function deleteIt(elem) {
	const id = elem.getAttribute("data-id");

	Myalert.delete();

	$('#confirmeDelete').click(function () {
		$.ajax({
			url: base_url('deleteMode'),
			type: "POST",
			data: { id: id },
			dataType: "json",
			success: function (response) {
				// $(elem).closest('tr').remove();
				location.reload();
			},
			error: function (xhr, status, error) {
				console.error("Erreur lors de la suppression :", error);
			},
		});
	})

}

$(document.body).on("submit", ".delete", function (e) {
	e.preventDefault();
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
				text: "Suppression effectuée.",
				icon: "success",
			});
			$("#tbody-pv").html(data.data);
		} else {
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

// conn.onmessage = function (e) {
//     let res = JSON.parse(e.data)
//     if (in_message_panel && Number(res.idService) === Number($("#id-service-messenger").val()) && Number(res.sender.id) === Number($("#id-user-messenger").val())) {
//         $.post(base_url('admin/messenger/markAsRead'), { idService: res.idService }, function (data, textStatus, jqXHR) { });

//         let piece_jointe = "";
//         if (res.pieceJointe) {
//             piece_jointe = `<div class="message-piece-jointe">
//                                 <img src="${base_url('public/piece_jointe/' + res.pieceJointe)}" onclick="zoomIn(this)">
//                             </div>`
//         }
//         $(".message-wrapper").append(`<div class="alert message-list d-flex" role="alert">
//             <div>
//             <img src="${res.sender.photo ? base_url('public/images/profils/'.res.sender.photo) : base_url('public/images/avatar.png')}" class="photo-messenger">
//             </div>
//             <div class="ps-3 w-100">
//                 <div class="d-flex justify-content-between">
//                     <strong class="alert-heading">${res.sender.pseudo}</strong>
//                     <span class="text-muted">${res.date_}</span>
//                 </div>
//                 ${res.message ? res.message : ""}
//                 ${piece_jointe}
//             </div>
//         </div>`)

//         scrollTobottom('.message-wrapper');

//     } else {
//         $.getJSON(base_url('admin/messenger/getUnreadCount'),
//             function (data, textStatus, jqXHR) {
//                 if (data.count > 0) {
//                     $("#unread-message-count").text(data.count)
//                 } else {
//                     $("#unread-message-count").text(null)
//                 }
//             }
//         );
//     }
// }
