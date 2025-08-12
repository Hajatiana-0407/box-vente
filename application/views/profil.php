<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <h5 class="mb-3">Modifier votre e-mail</h5>
                <form action="<?= base_url('Profil/editmail') ?>" method="post" class="col-6">
                    <div class="mb-2">
                        <label class="form-label">Ancien email :</label>
                        <input type="email" class="form-control" id="en_email" name="en_email" placeholder="Entrez votre email" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nouveau email :</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Votre nouveau email" required>
                    </div>

                    <div class="_boutton mt-4 mb-4">
                        <button type="button" class="btn btn-info" id="modifier_mail"><i class="fas fa-pencil-alt"></i> Modifier</button>
                        <button type="button" class="btn btn-info d-none" id="modifier_mail"><i class="fas fa-pencil-alt"></i> Modifier</button>
                    </div>


                </form>
                <h5 class="mb-3">Modifier votre mot de passe</h5>
                <form action="<?= base_url('Profil/edit') ?>" method="post" class="col-6">
                    <div class="mb-2">
                        <label class="form-label">Ancien mot de passe :</label>
                        <div class="pass">
                            <input type="password" class="form-control" id="password1" name="ancienMdp" placeholder="Entrez votre mot de passe" required>
                            <span onclick="togglePassword(this , 1 )" class="eye" data-show="true">
                                <i class="fa-solid fa-eye-slash" id="icon1"></i>
                            </span>
                        </div>
                        <p class="text-danger d-none" id="message-mdp">Ancienne mot de passe inccorecte</p>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Nouveau mot de passe :</label>
                        <div class="pass">
                            <input type="password" class="form-control" id="password2" name="nouveauMdp" placeholder="Votre nouveau mot de passe" required>
                            <span onclick="togglePassword(this , 2 )" class="eye" data-show="true">
                                <i class="fa-solid fa-eye-slash" id="icon2"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Confirmation mot de passe :</label>
                        <div class="pass">
                            <input type="password" class="form-control" id="password3" name="confirm" placeholder="Confirmez votre mot de passe" required>
                            <span onclick="togglePassword(this , 3 )" class="eye" data-show="true">
                                <i class="fa-solid fa-eye-slash" id="icon3"></i>
                            </span>
                        </div>
                        <p class="text-danger d-none" id="message">Les deux mots de passe ne sont pas identiques</p>
                    </div>

                    <div class="_boutton mt-4 mb-4">
                        <button type="button" class="btn btn-info" id="modifier"><i class="fas fa-pencil-alt"></i> Modifier</button>
                        <button type="button" class="btn btn-info d-none" id="modifier__"><i class="fas fa-pencil-alt"></i> Modifier</button>
                    </div>

                    <?php if ($this->session->flashdata('modfier')) : ?>
                        <script>
                            updated();
                        </script>
                    <?php endif; ?>
                </form>

                <?php if ($this->session->userdata('updated')) : ?>
                    <script>
                        Myalert.updated() ; 
                    </script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('updated'); ?>
                <?php if ($this->session->userdata('mail_exist')) : ?>
                    <script>
                        Myalert.erreur('Ce mail est déjà utilisé par un autre utilisateur.') ; 
                    </script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('mail_exist'); ?>
                <?php if ($this->session->userdata('error_mdp')) : ?>
                    <script>
                        Myalert.erreur('Mot de pass incorrect.') ; 
                    </script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('error_mdp'); ?>

            </div>
        </div>
    </div>
</div>