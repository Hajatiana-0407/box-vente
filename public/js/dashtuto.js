$(document).ready(function () {

    $(document).on('click', '#valider', function () {
        shwoSpinner(this, ["titre", "the_video"])
    })


    $(document).on('click', '.supprimer', function () {
        const id = $(this).data('id');
        var alert = `<div class="volet"></div>
                    <div class="_alert">
                        <div class="close" id="close">
                            <i class="fa-solid fa-x"></i>
                        </div>
                        <div class="_icon-question">
                            <i class="fa-regular fa-circle-question"></i>
                        </div>
                        <div class="_message">
                            <p>Etes-vous sur de bien vouloir supprimer ?</p>
                        </div>
                        <div class="_btn-delete">
                            <button type="button" class="button-warning" id="confirmeDelete">Oui</button>
                            <button type="button" class="button-succes" id="cancelDelete">Non</button>
                        </div>
                    </div>`
        $('.corps').append(alert);
        $('#confirmeDelete').on('click', function () {
            $.ajax({
                method: 'post',
                url: base_url('Dashtuto/delete'),
                data: { id: id },
                dataType: 'json',
            }).done(function (reponse) {
                window.location.reload()
            }).fail(function () {
                console.error('erreur dans la suppression du vidéo');
            })
        })
    });


    $(document).on('click', '.editer', function () {
        const title = $(this).data('title');
        const numero = $(this).data('num');
        const src = $(this).data('src');
        const langue = $(this).data('langue');
        const id = $(this).data('id');
        let option;
        $('#titre_modif').val(title);
        $('#numero_modif').val(numero);

        if (langue == 'Malagasy') {
            option = `
                <option value="1">Malagasy</option>
                <option value="2">Français</option>
            `
        }
        else {
            option = `
                <option value="2">Français</option>
                <option value="1">Malagasy</option>
            `
        }
        $('#langue_modif').html(option);
        $('#video_modif').attr('src', base_url(src));
        $('#id_modif').val(id);

    })




})

function afficheVideo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var videoElement = document.getElementById("video");

            // Définir l'attribut src pour afficher la vidéo
            videoElement.src = e.target.result;

            // Optionnel : Vous pouvez définir les attributs pour que la vidéo soit lue automatiquement
            videoElement.controls = true; // Afficher les contrôles de la vidéo
            videoElement.autoplay = false;  // Lire la vidéo automatiquement
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function afficheVideoModif(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var videoElement = document.getElementById("video_modif");

            // Définir l'attribut src pour afficher la vidéo
            videoElement.src = e.target.result;

            // Optionnel : Vous pouvez définir les attributs pour que la vidéo soit lue automatiquement
            videoElement.controls = true; // Afficher les contrôles de la vidéo
            videoElement.autoplay = false;  // Lire la vidéo automatiquement
        };
        reader.readAsDataURL(input.files[0]);
    }
}


