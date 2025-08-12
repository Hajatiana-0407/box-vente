// EN LIGNE 
//  const BASE_URL = "https://phone.tuto-info.com/";

// EN SOFTIO 
// const BASE_URL = 'https://phone.softio-app.com/index.php/';

// EN LOCAL
const BASE_URL = "http://localhost/box-vente/";

function myUrl(uri = '') {
    // Récupère l'URL de base
    let url = BASE_URL;

    // Vérifie si l'URL contient 'index.php'
    if (url.includes('index.php/')) {
        // Remplace 'index.php/' par une chaîne vide
        url = url.replace('index.php/', '');
    }
    return url + uri;
}

//EN LIGNE NON TUTO 
$(document).ready(function () {
	if (document.querySelector(".ui.dropdown")) {
		$('.ui.dropdown').dropdown();
	}
})
function base_url(uri = null) {
	if (uri === null) {
		return BASE_URL;
	}
	return BASE_URL + uri;
}

function pagination() {
	return 5;
}
function shwoSpinner(button, inputs = []) {
	let test = true;
	for (let i = 0; i < inputs.length; i++) {
		const identifiant = inputs[i];
		if ($('#' + identifiant).val() == '') {
			test = false;
		}
	}
	if (test) {
		$('#spinner_validation').removeClass('d-none')
		$(button).find('.fa-check').addClass('d-none');
	}
	else {
		$('#spinner_validation').addClass('d-none')
		$(button).find('.fa-check').removeClass('d-none');
	}
}



// php_admin_value[memory_limit] = 8192M
// php_admin_value[post_max_size] = 1024M
// php_admin_value[upload_max_filesize] = 1024M
