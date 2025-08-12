<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card mt-5 corps ">
                <div class="card-body">
                    <div class="logo">
                            <img src="<?= base_url('public/images/logo/logo_noire.png') ?>" alt="logo...">
                    </div>

                    <form action="<?= base_url('connexion') ?>" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="mail" placeholder="Votre Email"
                                value="<?= set_value("mail"); ?>" required>
                        </div>
    
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="pass">
                                <input type="password" class="form-control" id="password" name="pass"
                                    placeholder="Entrez votre mot de passe" required>
                                <span onclick="togglePassword(this)" class="eye" data-show="true">
                                    <i class="fa-solid fa-eye-slash" id="icon"></i>
                                </span>
                            </div>
    
                            <?php if (!is_null($error)) : ?>
                                <?= '<p class="text-danger">' . $error . ' </p>' ?>
                            <?php endif; ?>
                        </div>
    
                        <div class="d-grid">
                            <button id="connexion_" type="submit" class="btn btn-primary btn-block">Se Connecter</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="container login-container" id="moncontainer">
    <div class="formlaire">
        <div class="welecome">

        </div>
        <div class="seconnecte">
            <form action="<?= base_url('connexion') ?>" method="post" id="formulaireconnexion" class="">
                <?php if ($this->session->flashdata('delai')) : ?>
                    <div class="alert alert-info">
                        <strong><i class="fas fa-exclamation-triangle"></i> Desoler!</strong> La durée du teste est maintenant terminée !
                    </div>
                <?php endif ?>

                <div class="title">
                    <h2>Connexion</h2>
                </div>

                <div class="mb-2 relative">
                    <span class="icone">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <label class="form-label">Email : </label>
                    <input type="email" id="mail" name="mail" class="form-control" placeholder="Votre Email" required>
                </div>
                <div class="mb-2">
                    <div class="pass relative ">
                        <span class="icone">
                            <i class="fa fa-lock"></i>
                        </span>
                        <label class="form-label">Mot de passe : </label>
                        <input type="password" class="form-control" id="password" name="pass" placeholder="Votre mot de passe" required>
                        <span onclick="togglePassword(this)" class="eye spicon" data-show="true">
                            <i class="fa-solid fa-eye-slash" id="icon"></i>
                        </span>
                    </div>
                </div>

                <?php if ($this->session->flashdata('error')) : ?>
                    <p class="text-danger">
                        Mot de passe ou e-mail incorrect. Veuillez réessayer.
                    </p>
                <?php endif ?>



                <div class="boutton mt-3">
                    <div id="spinner_validation" class="d-none">
                        <div class="spinner-border " id="spinner"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="seconnecter"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
                </div>

                <div class="inscription">
                    <p> Pas de compte ?<a href="<?= base_url('inscription') ?>"> Inscrivez-vous.</a></p>
                </div>
            </form>

        </div>
    </div>
</div>