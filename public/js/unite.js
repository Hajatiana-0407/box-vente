$( document ).ready( function (){
    $( document ).on('change' , '#reference' , function (){
        const reference = $( this ).val() ; 
        $.ajax({
            method: 'post' , 
            url : base_url('Unite/getProduit'),
            data : { reference : reference } , 
            dataType : 'json'
        }).done( function ( response ){
            console.log( response );
            
            if ( response.success ){
                $('#designation').val( response.data[0].designation )
                $('#reference').val( response.data[0].refProduit )
            }else {
                Myalert.erreur('Veuillez vérifier la référence que vous avez saisie.') ; 
            }
        }).fail( function (){
            console.error("Erreur dans la recuperation du produit ");
        })
    })
})