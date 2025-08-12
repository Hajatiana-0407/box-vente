<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <h5>Modifier votre profil</h5>

                <form action="<?= base_url('Admin/upadteprof') ?> " method="post">

                    <div class="mb-3">
                        <label for="email" class="form-label">Ancien email : </label>
                        <input type="mail" class="form-control" name="an_mail" placeholder="Votre Email" value="<?= $_SESSION['post']['mail'] ?? ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Ancien mot de passe : </label>
                        <div class="pass">
                            <input type="password" class="form-control" id="an_password" name="an_pass" placeholder="Entrez votre mot de passe" required>
                            <span onclick="togglePassword1(this)" class="eye" data-show="true">
                                <i class="fa-solid fa-eye-slash" id="an_icon"></i>
                            </span>
                        </div>
                    </div>
                    <?php if ($this->session->flashdata('error')) : ?>
                        <p class="text-danger">
                            Mot de passe ou e-mail incorrect
                        </p>
                    <?php endif ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Nouvel e-mail : </label>
                        <input type="text" class="form-control" name="mail" placeholder="Nouvel e-mail">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe : </label>
                        <div class="pass">
                            <input type="password" class="form-control" id="password" name="pass" placeholder="Entrez votre mot de passe" required>
                            <span onclick="togglePassword2(this)" class="eye" data-show="true">
                                <i class="fa-solid fa-eye-slash" id="icon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Confirmer le Mot de passe : </label>
                        <div class="pass">
                            <input type="password" class="form-control" id="pass_conf" placeholder="Entrez votre mot de passe" required>
                        </div>
                    </div>

                    <p class="text-danger d-none" id="confirmation">
                        Mot de passe  incorrect !
                    </p>

                    <div class="_boutton">
                        <button type="button" id="modifer" class="btn btn-primary">Modifier</button>
                    </div>

                </form>


                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="volet"></div>
                    <div class="_alert">
                        <div class="close" id="close">
                            <i class="fa-solid fa-x"></i>
                        </div>
                        <div class="_icon-success">
                            <i class="fa-regular fa-circle-check"></i>
                        </div>
                        <div class="_message">
                            <p>Modification réussie</p>
                        </div>
                        <div class="_btn">
                            <button type="button" class="button-succes" id="button">OK</button>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>