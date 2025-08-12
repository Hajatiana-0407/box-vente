<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card mt-5 corps ">
                <form action="<?= base_url('Admin/connexion') ?>" method="post">
                    <div class="card-body">
                        <h3 class="card-title text-center">Connexion</h3>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" name="mail" placeholder="Votre Email" value="<?= $_SESSION['post']['mail'] ?? ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="pass">
                                <input type="password" class="form-control" id="password" name="pass" value="<?php echo $_SESSION['post']['pass'] ?? ''; ?>" placeholder="Entrez votre mot de passe" required>
                                <span onclick="togglePassword(this)" class="eye" data-show="true">
                                    <i class="fa-solid fa-eye-slash" id="icon"></i>
                                </span>
                            </div>
                        </div>
                        <?php if ($this->session->flashdata('error')) : ?>
                            <p class="text-danger">Mot de passe ou e-mail incorrect. Veuillez réessayer.</p>
                        <?php endif  ?>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>