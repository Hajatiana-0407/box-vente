

$(document.body).on('click', '.printCode', function () {
	window.open( base_url('CodeBarre/impression')) ; 
})

// $(document.body).on("change", "#reference", function () {
// 	const ref = $(this).val();
// 	$.ajax({
// 		url: base_url("rechercher_appro"),
// 		type: "post",
// 		dataType: "json",
// 		data: {
// 			ref: ref,
// 		},
// 	}).done(function (data) {
// 		console.log(data);
// 		if (data.success == true) {
// 			const infos = data.data[0];
// 			$("#reference").val(data.data[0].refProduit);
// 			$("#codeBarre").val(data.data[0].refProduit);
// 			$("#designation").val(data.data[0].designation);
// 		} else {
// 			$("#designation").val("");
// 			$("#codeBarre").val("");
// 			var alert =
// 				`<div class="volet"></div>
// 						<div class="_alert">
// 							<div class="close" id="close">
// 								<i class="fa-solid fa-x"></i>
// 							</div>
// 							<div class="_icon-warning">
// 								<i class="fa-solid fa-circle-exclamation"></i>
// 							</div>
// 							<div class="_message">
// 								<p>Cette Réference n'existe </p>
// 							</div>
// 							<div class="_btn">
// 								<button type="button" class="button-war" id="button">OK</button>
// 							</div>
// 						</div>`
// 			$('.corps').append(alert);
// 		}
// 	})
// });

// $(document.body).on("submit", "#register", function (e) {
// 	e.preventDefault();
// 	var ref = $("#reference").val();
// 	var designation = $("#designation").val();
// 	var codeBarre = $("#codeBarre").val();

// 	$.ajax({
// 		url: base_url("validRef"),
// 		type: "post",
// 		dataType: "json",
// 		data: {
// 			ref: ref,
// 		},
// 	}).done(function (data) {
// 		if (data.success == false) {
// 			var alert =
// 				`<div class="volet"></div>
// 						<div class="_alert">
// 							<div class="close" id="close">
// 								<i class="fa-solid fa-x"></i>
// 							</div>
// 							<div class="_icon-warning">
// 								<i class="fa-solid fa-circle-exclamation"></i>
// 							</div>
// 							<div class="_message">
// 								<p>Cette référence obtient déjà son code-barres</p>
// 							</div>
// 							<div class="_btn">
// 								<button type="button" class="button-war" id="button">OK</button>
// 							</div>
// 						</div>`
// 			$('.corps').append(alert);
// 		} else {
// 			$.ajax({
// 				url: base_url("registerCode"),
// 				type: "post",
// 				dataType: "json",
// 				data: {
// 					reference: ref,
// 					designation: designation,
// 					codeBarre: codeBarre,
// 				},
// 			}).done(function (data) {
// 				if (data.success == true) {
// 					window.location.reload();
// 				}
// 			})
// 		}
// 	})
// })

function deleteIt(elem) {
	const id = elem.getAttribute("data-id");

	Myalert.delete()
	$('#confirmeDelete').on('click', function () {
		$.ajax({
			url: base_url("CodeBarre/deleteCode"),
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

let tableau = $('#tableauCode tr');
let imprimer = $('#imprimer');

if (tableau.length == 0) {
	imprimer.addClass('d-none');
} else {
	imprimer.removeClass('d-none');
}

let info;

$(document).on('click', '#imprimer', function () {
	for (let i = 0; i < tableau.length; i++) {
		info = tableau[i].getAttribute('data-reference');
		console.log(info);
		$.ajax({
			url: base_url("infoCode"),
			type: "POST",
			data: { info: info },
			dataType: "json",
		}).done(function (data) {
			if (data.success == true) {
				// console.log('qsdqsd');
			}
		})
	}
	window.open(base_url('impression'));
})