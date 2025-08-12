<div class="container" id="moncontainer">

    <div class="logo">
        <img src="<?= Myurl('public/images/logo/logo_noire.png') ?>" alt="logo...">
    </div>
    <div class="formlaire">
        <div class="welecome">

        </div>
        <div class="seconnecte">
            <form action="<?= base_url('inscrire') ?>" id="formulaireinscription">
                <div class="title">
                    <h2> Inscription</h2>
                </div>
                <div class="mb-2 relative">
                    <span class="icone">
                        <i class="fas fa-user"></i>
                    </span>
                    <label class="form-label">Nom : </label>
                    <input type="text" id="nom_" name="nom" class="form-control" placeholder="Nom" required>
                </div>
                <div class="mb-2 relative">
                    <span class="icone">
                        <i class="fas fa-user-edit"></i>
                    </span>
                    <label class="form-label">Prenom : </label>
                    <input type="text" id="prenom_" name="prenom" class="form-control" placeholder="Prenom" required>
                </div>
                <div class="mb-2 relative">
                    <span class="icone">
                        <i class="fas fa-envelope-open"></i>
                    </span>
                    <label class="form-label">Email : </label>
                    <input type="mail" id="email_" name="mail" class="form-control" placeholder="exemple@gmail.com" required>
                </div>
                <div class="mb-2 relative">
                    <span class="icone">
                        <i class="fas fa-phone"></i>
                    </span>
                    <label class="form-label">Tél : </label>
                    <input type="text " id="telephone_" name="tel" class="form-control" placeholder="Votre numéro de télephone " required>
                </div>
                <div class="mb-2 relative">
                    <span class="icone">
                        <i class="fas fa-building"></i>
                    </span>
                    <label class="form-label">Entreprise : </label>
                    <input type="mail" id="entrp_" name="entrp" class="form-control" placeholder="Nom de l'entreprise" required>
                </div>
                <div class="relative mb-2">
                    <span class="icone">
                        <i class="fa fa-lock"></i>
                    </span>
                    <label class="form-label">Mot de passe : </label>
                    <input type="password" class="form-control" id="password2" name="pass" placeholder="Votre mot de passe" required>
                    <span onclick="togglePassword2(this)" class="eye spicon" data-show="true">
                        <i class="fa-solid fa-eye-slash" id="icone2"></i>
                    </span>
                </div>
                <div class=" relative">
                    <span class="icone">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <label class="form-label">Confirmer le mot de passe : </label>
                    <input type="password" id="pass_conf" name="pass_conf" class="form-control" placeholder="Confirmation mot de passe" required>
                </div>

                <p class="text-danger d-none" id="incorect">Le mot de passe est incorrect.</p>
                <p class="text-danger d-none" id="court">Le mot de passe est trop court (au moins 6 caractères).</p>

                <div class="boutton mt-2">
                    <div id="spinner_validation" class="d-none">
                        <div class="spinner-border text-light" id="spinner"></div>
                    </div>
                    <button type="button" class="btn btn-primary w-50" id="inscrire">
                        <span>
                            <i class="fas fa-user-plus"></i>
                        </span>
                        S'inscrire
                    </button>
                    <a href="<?= base_url('Auth') ?>">Se connecter</a>
                </div>
            </form>

        </div>
    </div>
</div>