class Myalert {
    constructor(parameters) {
        this.container = '.corps';
    }
    static updated(texte = 'Modification effectuée.') {
        let the_content = `
            <div class="volet"></div>
            <div class="_alert">
                <div class="close" id="close">
                    <i class="fa-solid fa-x"></i>
                </div>
                <div class="_icon-success">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <div class="_message">
                    <p>${texte} </p>
                </div>
                <div class="_btn">
                    <button type="button" class="button-succes" id="button">OK</button>
                </div>
            </div>
        `
        $('.corps').append(the_content)
    }
    static deleted(texte = 'Suppression effectuée.') {
        let the_content = `
            <div class="volet"></div>
            <div class="_alert">
                <div class="close" id="close">
                    <i class="fa-solid fa-x"></i>
                </div>
                <div class="_icon-success">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <div class="_message">
                    <p>${texte} </p>
                </div>
                <div class="_btn">
                    <button type="button" class="button-succes" id="button">OK</button>
                </div>
            </div>
        `
        $('.corps').append(the_content)
    }
    static added(texte = 'Enregistrement effectué.') {
        let the_content = `
            <div class="volet"></div>
            <div class="_alert">
                <div class="close" id="close">
                    <i class="fa-solid fa-x"></i>
                </div>
                <div class="_icon-success">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <div class="_message">
                    <p>${texte} </p>
                </div>
                <div class="_btn">
                    <button type="button" class="button-succes" id="button">OK</button>
                </div>
            </div>
        `
        $('.corps').append(the_content)
    }


    static delete(texte = 'Êtes-vous sûr de vouloir supprimer ?') {
        var alert = `<div class="volet"></div>
				<div class="_alert">
					<div class="close" id="close">
						<i class="fa-solid fa-x"></i>
					</div>
					<div class="_icon-question">
						<i class="fa-regular fa-circle-question"></i>
					</div>
					<div class="_message">
						<p>${texte} </p>
					</div>
					<div class="_btn-delete">
						<button type="button" class="button-warning" id="confirmeDelete">Oui</button>
						<button type="button" class="button-succes" id="cancelDelete">Non</button>
					</div>
				</div>`
        $('.corps').append(alert);
    }

    static erreur(texte = 'Une erreur s`\'est produite.') {
        let alert = `
            <div class="volet"></div>
            <div class="_alert">
                <div class="close" id="close">
                    <i class="fa-solid fa-x"></i>
                </div>
                <div class="_icon-warning">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div class="_message">
                    <p>${texte} </p>
                </div>
                <div class="_btn">
                    <button type="button" class="button-war" id="button">OK</button>
                </div>
            </div>
        `
        $('.corps').append(alert);
    }

    static spinnerB() {
        let spinner = `
                 <div id="spiner_big_container">
                    <div class="spinner-border text-muted"></div>
                </div>
        `  ;
        $('.corps').append(spinner);
    }

    static removeSpinnerB() {
        $('#spiner_big_container').remove();
    }
}



