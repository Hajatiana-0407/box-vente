
function togglePassword1(icone) {
    var passwordField = document.getElementById("an_password");
    var icon = document.querySelector('#an_icon');

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.add("fa-eye");
    } else {
        passwordField.type = "password";
        icon.classList.add("fa-eye-slash");
    }
}
function togglePassword2(icone) {
    var passwordField = document.getElementById("password");
    var icon = document.querySelector('#icon');

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.add("fa-eye");
    } else {
        passwordField.type = "password";
        icon.classList.add("fa-eye-slash");
    }
}

$(document ).on('click', '#modifer' , function (){
    const pass = $('#password').val() ; 
    const passconf = $('#pass_conf').val() ; 
    console.log( pass , passconf );
    

    if ( pass == passconf ){
        $('#modifer').attr('type' , 'submit') ; 
        $('#modifer').click() ;
        $('#confirmation').addClass('d-none');
    }else {
        $('#confirmation').removeClass('d-none');
        $('#modifer').attr('type' , 'button') ; 
    }
})


$(document).on('click', '.ractive', function () {
    const idadmin = $(this).data('id');
    Myalert.delete('Êtes-vous sûr de vouloir réactiver ?');

    $(document).on('click', '#confirmeDelete', function () {
        $.ajax({
            method: 'post',
            url: base_url('Admin/reactive'),
            data: { idAdmin: idadmin },
        }).done(function () {
            location.reload() ; 
        }).fail(function () {
            console.error('Erreur dans la reactivation ');

        })
    })
})

