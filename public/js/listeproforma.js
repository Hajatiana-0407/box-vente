$( document).ready( function (){
	$(document.body).on('click', ".supprimer", function () {

		let idproforma = $(this).data('idproforma');
		const elem = $( this ) ; 
	
		Myalert.delete();
	
		$('#confirmeDelete').on('click', function () {
			$('.close').click() ; 
			$.ajax({
				method: 'post',
				url: base_url('Listeproforma/deleteListe'),
				data: { idproforma: idproforma },
				dataType: 'json',
	
			}).done(function (response) {
				if (response.success == true) {
					$( elem ).closest('tr').remove() ; 
					Myalert.deleted() ; 
				}
			}).fail(function () {
				console.log('error');
			})
		})
	}) ; 
	
	$(document.body).on("click", ".detail", function () {
		var idproforma = $(this).data("idproforma");
		$.ajax({
			url: base_url("Listeproforma/getDetails"),
			type: "post",
			data: {
				idproforma: idproforma ,
			},
		}).done(function (data) {
			$("#tab").html(data);
		});
	});
	
	$( document).on('click' , '.imprim' , function (){
		Myalert.spinnerB() ; 
		$('#affichefacture').click() ; 
		
		let facture = $( this ).data("facture") ; 
		let url = base_url('Proforma/facture/'+ facture  );
	
		$('#pdfFrame').attr('src', url ) ; 
	
		setTimeout( function (){
			Myalert.removeSpinnerB() ; 
		} , 100)
		
		$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');
	setTimeout(function () {
		$('#loaderFacture').addClass('d-none');
		$('#pdfFrame').removeClass('d-none');
	}, 5000) ; 
	})
})

// function create() {
// 	const form = document.querySelector("#modal-form");
// 	if (!form) {
// 		alert("undefined modal ...");
// 		return false;
// 	}
// 	const modal = bootstrap.Modal.getOrCreateInstance(form);

// 	modal.show();
// }
// function update(id, self) {
// 	const modal = bootstrap.Modal.getOrCreateInstance(
// 		document.querySelector("#modal-form")
// 	);
// 	$.get($(self).data("url"), { id: id }, function (data, textStatus, jqXHR) {
// 		$("#form-content").html(data);
// 		modal.show();
// 	});
// }

// let window_width = window.innerWidth;

// $(document).ready(function () {
// 	if (window_width <= 768) {
// 		$(".sidebar").addClass("hide");
// 	}
// 	$(window).on("resize", function () {
// 		if ($(this).innerWidth() <= 768) {
// 			$(".sidebar").addClass("hide");
// 		} else {
// 			$(".sidebar").removeClass("hide");
// 		}
// 	});
// 	const elemtooltips = document.querySelectorAll(".btn-tooltip");
// 	for (let elem of elemtooltips) {
// 		new bootstrap.Tooltip(elem);
// 	}
	

//

// function toggleSidebar() {
// 	$(".sidebar").toggleClass("hide");
// 	$(".backdrop").toggleClass("d-none");
// }

// function showSuccessAlert() {
// 	$("#message-success").addClass("show");
// 	let t_out = setTimeout(() => {
// 		hideSuccessAlert();
// 		clearTimeout(t_out);
// 	}, 5000);
// }
// function hideSuccessAlert() {
// 	$("#message-success").removeClass("show");
// }