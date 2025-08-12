<div class="voilet"></div>
<div class="backdrop d-none" id="backdrop"></div>
<div class="header">
    <div class="wrapper">
        <div class="_navbar">
            <div class="_navbar-logo">
                <span role="button" class="menu-hamborger">
                    <i class="fa-solid fa-bars"></i>
                </span>
                <div class="header-title">
                    <h2>Gestion</h2>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <div class="position-relative">
                    <div class="icon-droit">
                        <?php if (isset($_SESSION['clavier'])) : ?>
                            <!-- <span class="mode-menu" style="border: none ;" role="button" onclick="togglekeyboard( event , this  )">
                                <i class="fas fa-keyboard text-secondary"></i>
                                <div id="clavier_activ">
                                    <i class="fas fa-check"></i>
                                </div>
                            </span> -->
                        <?php else : ?>
                            <!-- <span class="mode-menu" style="border: none ;" role="button" onclick="togglekeyboard( event , this  )">
                                <i class="fas fa-keyboard"></i>
                            </span> -->
                        <?php endif ?>
                        <span class="profil-menu ms-3" onclick="toggleUserMenu( event )">
                            <i class="fa-solid fa-user-tie"></i>
                        </span>
                    </div>
                    <div class="user-menu-wrapper shadow shadow-sm py-2 d-none">
                        <!-- <span class="user-menu-link" id="username">
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="ms-2" id="thenameuser">Utilisateur</span>
                        </span> -->

                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') : ?>
                            <a href="<?= base_url('entreprise') ?>" class="user-menu-link ">
                                <i class="fas fa-building"></i><span class="ms-2">Mon entreprise</span>
                            </a>

                        <?php endif  ?>
                        <a href="<?= base_url('profilEdit') ?>" class="user-menu-link ">
                            <i class="fas fa-lock"></i><span class="ms-2">Sécurité</span>
                        </a>
                        <a id="deconnexion_" href="<?= base_url('deconnexion') ?>" class="user-menu-link">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="ms-2">Se deconnecter</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>