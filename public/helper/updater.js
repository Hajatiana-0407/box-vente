$(document).ready(function () {

	$(document.body).on("submit", "#modifProfil", function (e) {
		e.preventDefault();
		var ancienEmail = $("#ancienEmail").val();
		var nouvelleMail = $("#nouvelleMail").val();
		var ancienMdp = $("#ancienMdp").val();
		var nouveauMdp = $("#nouveauMdp").val();
		var confirm = $("#confirm").val();

		if (nouveauMdp != confirm) {
			$("#confirm").css("border", "2px solid red");
			$("#message").removeClass("d-none");
			$("#ancienMdp").css("border", "");
			$("#message-mdp").addClass("d-none");
		} else {
			$.ajax({
				url: base_url("checkProfil"),
				type: "post",
				dataType: "json",
				data: {
					ancienMdp: ancienMdp,  
					ancienEmail: ancienEmail, 
				},
				async: false,
			}).done(function (data) {
				console.log(data);
				if (data.success == true) {
					$.ajax({
						url: base_url("udpateProfil"),
						type: "post",
						dataType: "json",
						data: {
							nouvelleMail: nouvelleMail,
							nouveauMdp: nouveauMdp,
							id: data.id,
						},
						async: false,
					}).done(function (data) {
						if (data.success) {
							let alert = `<div class="volet"></div>
										<div class="_alert">
											<div class="close" id="close">
												<i class="fa-solid fa-x"></i>
											</div>
											<div class="_icon-success">
											<i class="fa-regular fa-circle-check"></i>
											</div>
											<div class="_message">
												<p>Modification du profil</p>
											</div>
											<div class="_btn">
												<button type="button" class="button-succes" id="button">OK</button>
											</div>
										</div>
										`
							$('.corps').append(alert)

							$("#button").on('click', function () {
								$(".volet").remove();
								$("._alert").remove();
								$("#ancienEmail").val("")
								$("#nouvelleMail").val("")
								$("#ancienMdp").val("")
								$("#nouveauMdp").val("")
								$("#confirm").val("")
							})
							$(".volet").on('click', function () {
								$(".volet").remove();
								$("._alert").remove();
								$("#ancienEmail").val("")
								$("#nouvelleMail").val("")
								$("#ancienMdp").val("")
								$("#nouveauMdp").val("")
								$("#confirm").val("")
							})
							$(".close").on('click', function () {
								$(".volet").remove();
								$("._alert").remove();
								$("#ancienEmail").val("")
								$("#nouvelleMail").val("")
								$("#ancienMdp").val("")
								$("#nouveauMdp").val("")
								$("#confirm").val("")
							})
							$("#ancienMdp").css("border", "");
							$("#message-mdp").addClass("d-none");
							$("#confirm").css("border", "");
							$("#message").addClass("d-none");
						}
					});
				} else if (data.success == false){
					$("#ancienMdp").css("border", "2px solid red");
					$("#message-mdp").removeClass("d-none");
					$("#confirm").css("border", "");
					$("#message").addClass("d-none");
				}
			});
		}
	});
});