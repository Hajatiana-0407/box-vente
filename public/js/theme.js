
function toggleUserMenu( e ) {
	e.stopPropagation() ; 
	$(".user-menu-wrapper").toggleClass("d-none");
	$(".user-theme").addClass("d-none");
}
function toggleMode( e ){
	e.stopPropagation() ; 
	$(".user-theme").toggleClass("d-none");
	$(".user-menu-wrapper").addClass("d-none");
}
function togglekeyboard( e , element  ){
	$.ajax({
		method : 'post' , 
		url : base_url('Utility/clavier')
	}).done( function (){
		location.reload() ; 
	})
}

// let mode = localStorage.getItem("mode") ; 
// if ( mode == undefined ){
// 	mode = 'light' ; 
// 	$('#light').css({
// 		'outline' : '2px solid #4bc2f2', 
// 		'transform': 'scale(1.08)'
// 	})
// }else {
// 	$('.contain_theme#' + mode ).css({
// 		'outline' : '2px solid #4bc2f2', 
// 		'transform': 'scale(1.08)'
// 	})
// }
	

// $( document.body ).removeAttr('class') ;
// $( document.body ).attr('class' , mode ) ;

$( document.body ).on( 'click' , function ( e ){ 
	$(".user-theme").addClass("d-none");
	$(".user-menu-wrapper").addClass("d-none");
})

$( document ).on ('click' , '.contain_theme' , function ( e ) {
	e.stopPropagation() ; 
	let mode = $(this).data("theme") ;

	// localStorage.setItem("mode", mode ) ; 

	$.ajax({ 
		method : 'post' , 
		url : base_url('Profil/updateMode') , 
		data : { mode : mode }
	}).done( function ( ){

		$( document.body ).removeAttr('class') ;
		$( document.body ).attr('class' , mode ) ;
	
		$('.contain_theme' ).css({
			'outline' : '' , 
			'transform': 'scale(1)'
		})
		$('.contain_theme#' + mode ).css({
			'outline' : '2px solid #4bc2f2', 
			'transform': 'scale(1.08)'
		})

		$(".user-theme").removeClass("d-none");

	})


})

$( document ).on("click", '.user-theme' , function ( e ){
	e.stopPropagation() ; 
	$( this ).removeClass('d-none') ; 
})



