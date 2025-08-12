$(document).ready(function (){
    $(document).on('click' , '.volet' , function ( ){
        $('._alert').remove() ; 
        $('.volet').remove() ; 
    })
    $(document).on('click' , '.close' , function ( ){
        $('._alert').remove() ; 
        $('.volet').remove() ; 
    })
    $(document).on('click' , '#button' , function ( ){
        $('._alert').remove() ; 
        $('.volet').remove() ; 
    }) ; 

    $(document).on('click' , '#cancelDelete' , function ( ){
        $('._alert').remove() ; 
        $('.volet').remove() ; 
    })
})


// * vider le local storage * //
$( document).on('click', '#connexion_ , #deconnexion_' , function() {
    let tableauJSON = JSON.stringify({});
	let allJson = JSON.stringify([]);
	let remJson = JSON.stringify({});
    
	localStorage.setItem('monTableau', tableauJSON);
	localStorage.setItem('the_remise', remJson);
	localStorage.setItem('all_num', allJson);
})
	