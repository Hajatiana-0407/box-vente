function togglePassword() {
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
function togglePassword2(icone) {
    var passwordField = document.getElementById("password2");
    var icon = document.querySelector('#icone2');

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.add("fa-eye");
    } else {
        passwordField.type = "password";
        icon.classList.add("fa-eye-slash");
    }
}


$(document).on('click', '#sinscrire', function () {
    let class_ = $('#formulaireconnexion').attr('class');
    if (class_ != "d-none") {
        $('#formulaireconnexion').addClass('d-none');
        $('#formulaireinscription').removeClass('d-none');
    }
})

$(document).on('click', '#seconnecter ', function () {
    shwoSpinner(this, ['mail', 'password']);
    localStorage.clear(); 
})

$(document).on('click', '#inscrire', function () {
    let btn = $(this);
    let nom = $('#nom_').val();
    let prenom = $('#prenom_').val();
    let mail = $('#email_').val();

    let motpass = $('#password2').val()
    let pass_conf = $('#pass_conf').val();
    let entreprise = $('#entrp_').val();


    localStorage.clear();


    let telephone = $('#telephone_').val();

    if (nom != '' && prenom != '' && mail != '' && motpass != '' && pass_conf != '' && telephone != '') {
        if (motpass != pass_conf) {
            $('#incorect').removeClass('d-none');
            $('#court').addClass('d-none');
            $('#pass_conf').css({
                "box-shadow": '0 0 0 0.2rem rgba(220, 53, 69, 0.5)'
            });
            $('#spinner_validation').addClass('d-none');


        }
        else {
            if (motpass.length < 6) {
                $('#court').removeClass('d-none');
                $('#pass_conf').val('');
                $('#password2').css({
                    "box-shadow": '0 0 0 0.2rem rgba(220, 53, 69, 0.5)'
                });
                $('#spinner_validation').addClass('d-none');
            }
            else {
                $('#spinner_validation').removeClass('d-none');
                $.ajax({
                    method: 'post',
                    url: base_url('inscrire'),
                    data: { nom: nom, prenom: prenom, mail: mail, pass: motpass, pass_conf: pass_conf, entreprise: entreprise, tel: telephone },
                    dataType: 'json',
                    async: false
                }).done(function (reponse) {
                    let test = true;
                    let notification;
                    if (reponse.success) {
                        $('#spinner_validation').removeClass('d-none');
                        $.ajax({
                            method: 'post',
                            url: base_url('Auth/connexion'),
                            data: { mail: mail, pass: motpass },
                            async: false
                        }).done(function () {
                            
                            location.href = base_url('vente');
                        }).fail(function () {
                            $('#spinner_validation').addClass('d-none');
                            console.error('erreur l\'ors de connexion');
                        })
                    }
                    else {
                        test = false;
                        if (reponse.type == 'exist') {
                            notification = `
                            <div class="notification">
                                <div class="notificon">
                                    <span class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                </div>
                                <div class="notiftext">
                                    <p class="text-danger">Ce mail est déjà utilisé par un autre utilisateur.</p>
                                </div>
                            </div>
                            `
                            $('#spinner_validation').addClass('d-none');

                        }
                        else {
                            notification = `
                            <div class="notification">
                                <div class="notificon">
                                    <span class="text-danger">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </span>
                                </div>
                                <div class="notiftext">
                                    <p class="text-danger">Veuillez vérifier votre mot de passe.</p>
                                </div>
                            </div>
                            `
                            $('#spinner_validation').addClass('d-none');
                        }
                        
                    }
                    
                    
                    $('#moncontainer').prepend(notification);
                    $('.notification').animate({ "top": "20px" }, 150);
                    if (test) {
                        $('#seconnecter').click();
                        $('#nom_').val('');
                        $('#prenom_').val('');
                        $('#email_').val('');

                        $('#password2').val('')
                        $('#pass_conf').val('');

                    }
                    setTimeout(function () {
                        $('.notification').animate({ "top": "-150px" }, 150);

                    }, 3000, function () {
                        $('.notification').remove();
                    })
                }).fail(function () {
                    Error('Erreur dans l\'inscription ')
                })
            }
        }
    } else {
        $('#spinner_validation').addClass('d-none');
        var notification = `
                            <div class="notification">
                                <div class="notificon">
                                    <span class="text-danger">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </span>
                                </div>
                                <div class="notiftext">
                                    <p class="text-danger">Veuillez remplir tous les champs. </p>
                                </div>
                            </div>
                            ` ;
        $('#moncontainer').prepend(notification);
        $('.notification').animate({ "top": "20px" }, 150);
        setTimeout(function () {
            $('.notification').animate({ "top": "-150px" }, 150);

        }, 3000, function () {
            $('.notification').remove();
        })
    }
})
