$( document).on('click' ,'#valider' , function (){
	shwoSpinner( this , [ "entreprise" , "adresse" , 'telephone' , 'nif' , 'stat'] )
})
function afficheImage(input) {
	if (input.files && input.files[0]) {
		$('#loading').removeClass('d-none');
		var reader = new FileReader();

		reader.onload = function (e) {
			$("#image").attr("src", e.target.result);
			$("#image").css({
				width: "100%",
				height: "100%",
				objectFit: 'contain',
				position: 'absolute'
			});
		};
		reader.readAsDataURL(input.files[0]);
		setTimeout(function () {
			$('#loading').addClass('d-none');
		}, 100)
	}
}