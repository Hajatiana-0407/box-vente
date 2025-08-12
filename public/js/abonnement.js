$( document ).ready( function (){
    $( document ).on('click' , '.delete' , function () {
        const id = $( this ).data('id') ; 
        Myalert.delete() ;
        $( document ).on('click' , '#confirmeDelete' , function (){
            $.ajax({
                method : 'post' , 
                url : base_url('Abonnement/delete') , 
                data : { id :  id }
            }).done( function ( ){
                window.location.reload() ; 
            }).fail( function (){
                console.error('Erreur dans la suppression');
                
            })
        })
    })

    $( document ).on('click' ,'.edit' , function (){
        const id = $( this ).data('id') ; 
        const contact = $( this ).data('contact') ; 
        const nom = $( this ).data('nom') ; 
        const date_debut = $( this ).data('date_debut') ; 
        const dure = parseInt( $( this ).data('dure') ) ; 

        
        

        $('#id_mod').val( id ) ; 
        $('#contact_mod').val( contact ) ; 
        $('#nom_mod').val( nom ) ; 
        $('#date_mod').val( date_debut ) ; 
        $('#dure_mod').val( dure ) ; 
    })

    $( document ).on('change' , '#contact' , function (){
        const contact = $( this ).val() ; 
        $.ajax({
            method : 'post' , 
            url : base_url('Abonnement/getAdmin') , 
            data : { contact : contact } , 
            dataType : 'json'
        }).done( function ( response ){
            if ( response.success ){
                const admin = response.data ; 

                const nom = admin.nom.toUpperCase() + ' ' + ucfirst( admin.prenom) 
                var today = new Date();
                
                // Formater la date au format yyyy-mm-dd
                var year = today.getFullYear();
                var month = ('0' + (today.getMonth() + 1)).slice(-2); // Les mois sont de 0 à 11
                var day = ('0' + today.getDate()).slice(-2);
                var formattedDate = year + '-' + month + '-' + day;
                

                
                $('#nom').val( nom ) ; 
                $('#date').val( formattedDate  ) ; 

            }
            else {
                Myalert.erreur('Aucun numéro correspondant trouvé.')
            }

        }).fail( function (){
            Myalert.erreur('Veuillez vérifier le numéro saisi pour vous assurer \'il est correct..')
        })
    })
})

function ucfirst(str) {
    if (!str) return str;  // Retourne la chaîne telle quelle si elle est vide ou nulle
    return str.charAt(0).toUpperCase() + str.slice(1);
}