class Actions {
	delete(event, element) {
		event.preventDefault();
		console.log(element.getAttribute("data-action"));
		this.id = element.getAttribute("data-id");
		this.action = element.getAttribute("data-action");
		// this.elem = element.getAttribute();
		this.table = element.getAttribute("data-table");
		this.anchor = element.getAttribute("data-anchor");
		this.getBack = element.getAttribute("data-getback");
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
			if (result.isConfirmed) {
				$.ajax({
					url: this.action,
					type: "POST",
					data: { id: this.id },
					dataType: "json",
					success: function (response) {
						if (response.success) {
							Swal.fire({
								title: "Effectué !",
								text: "Suppression effectuée.",
								icon: "success",
							});
							console.log(response); // Ajouté pour vérifier la réponse
							window.location.reload();
						}
					}
				});
			}
		})
	}

	showAll(table) { }
}
