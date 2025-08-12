function togglePassword(elem , nbr ) {
    
    var passwordField = document.getElementById("password" + nbr );
    var icon = document.querySelector('#icon' + nbr  );

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.add("fa-eye");
    } else {
        passwordField.type = "password";
        icon.classList.add("fa-eye-slash");
    }
}

$( document ).on('click' , '#modifier' , function (){
    const pass =$('#password1').val() ; 
    const new_pass = $('#password2').val() ; 
    const confirm = $('#password3').val() ; 


    if ( new_pass != confirm ){ 
        $("#message").removeClass('d-none') ; 
    }else {
        $("#message").addClass('d-none') ; 
        $.ajax( {
            method : 'post' , 
            url : base_url('Profil/verification') , 
            data  : { pass  : pass } , 
            dataType : 'json'
        }).done( function ( response ){
            if( response.success ){
                $('#message-mdp').addClass('d-none') ; 
                $('#modifier__').attr('type' , 'submit') ; 
                $('#modifier__').click() ; 
            }else {
                $('#message-mdp').removeClass('d-none') ;
                $('#modifier__').attr('type' , 'button') ;  
            }
        }).fail( function (){
            console.error('erreur dans la verification ');
        })
    }
})
$( document ).on('click' , '#modifier_mail' , function (){
    const en_email = $('#en_email').val() ;
    if ( en_email != '' || pass != ''){
        $.ajax( {
            method : 'post' , 
            url : base_url('Profil/verification') , 
            data :{ mail : en_email  } , 
            dataType : 'json'
        }).done( function (response ){
            if ( response.success ){
                $('#message-md_mail').addClass('d-none') ;
                $('#modifier_mail').attr('type' , 'submit') ; 
                $('#modifier_mail').click() ; 
            }else {
                $('#message-md_mail').removeClass('d-none') ; 
                $('#modifier_mail').attr('type' , 'button') ;
            }
        }).fail( function (){
            console.error('erreur dans verification');
            
        })
    }
})