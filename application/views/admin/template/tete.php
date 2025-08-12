<div class="voilet"></div>
<div class="backdrop d-none" id="backdrop"></div>
<div class="header">
    <div class="wrapper">
        <div class="_navbar">
            <div class="_navbar-logo">
                <span role="button" class="menu-hamborger">
                    <i class="fa-solid fa-bars"></i>
                </span>
                <div class="logo_temp">
                    <img src="<?= Myurl('public/images/logo/logo_blanc.png') ?>" alt="logo...">
                </div>

            </div>
            <div class="d-flex justify-content-center align-items-center">
                <div class="position-relative">
                    <div class="icon-droit">
                        <span class="mode-menu" role="button" onclick="toggleMode( event )">
                            <i class="fas fa-palette"></i>
                        </span>
                        <span class="profil-menu ms-3" onclick="toggleUserMenu( event )">
                            <i class="fa-solid fa-user-tie"></i>
                        </span>
                    </div>
                    <div class="user-menu-wrapper shadow shadow-sm py-2 d-none">
                        <span class="user-menu-link" id="">
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="ms-2" id="">Admin</span>
                        </span>

                        <a href="<?= base_url('Admin/editProfil') ?>" class="user-menu-link ">
                            <i class="fa-solid fa-marker"></i><span class="ms-2">Modifier mon profil</span>
                        </a>

                        <a id="deconnexion_" href="<?= base_url('deconnexion') ?>" class="user-menu-link">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="ms-2">Se deconnecter</span>
                        </a>
                    </div>
                    <div class="user-theme shadow shadow-sm py-2 d-none" >
                        

                        <div class="contain_theme" id="light" data-theme="light" 
                            <?php if(isset($_SESSION['mode']) && !is_null($_SESSION['mode']) && $_SESSION['mode'] == 'light' ) : ?>
                                style="outline: 2px solid #4bc2f2; 
                                transform: scale(1.08)"
                            <?php else: ?>
                                style="outline: none; 
                                transform: scale(1)"
                            <?php endif; ?>
                        ></div>
                        <div class="contain_theme" id="dark" data-theme="dark" 
                            <?php if(isset($_SESSION['mode']) && !is_null($_SESSION['mode']) && $_SESSION['mode'] == 'dark' ) : ?>
                                style="outline: 2px solid #4bc2f2; 
                                transform: scale(1.08)"
                            <?php else: ?>
                                style="outline: none; 
                                transform: scale(1)"
                            <?php endif; ?>
                        ></div>
                        <div class="contain_theme" id="sapin" data-theme="sapin" 
                            <?php if(isset($_SESSION['mode']) && !is_null($_SESSION['mode']) && $_SESSION['mode'] == 'sapin' ) : ?>
                                style="outline: 2px solid #4bc2f2; 
                                transform: scale(1.08)"
                            <?php else: ?>
                                style="outline: none; 
                                transform: scale(1)"
                            <?php endif; ?>
                        ></div>
                        <div class="contain_theme" id="discrete" data-theme="discrete" 
                            <?php if(isset($_SESSION['mode']) && !is_null($_SESSION['mode']) && $_SESSION['mode'] == 'discrete' ) : ?>
                                style="outline: 2px solid #4bc2f2; 
                                transform: scale(1.08)"
                            <?php else: ?>
                                style="outline: none; 
                                transform: scale(1)"
                            <?php endif; ?>
                        ></div>
                        <div class="contain_theme" id="anime" data-theme="anime" 
                            <?php if(isset($_SESSION['mode']) && !is_null($_SESSION['mode']) && $_SESSION['mode'] == 'anime' ) : ?>
                                style="outline: 2px solid #4bc2f2; 
                                transform: scale(1.08)"
                            <?php else: ?>
                                style="outline: none; 
                                transform: scale(1)"
                            <?php endif; ?>
                        ></div>
                        <div class="contain_theme" id="brun" data-theme="brun" 
                            <?php if(isset($_SESSION['mode']) && !is_null($_SESSION['mode']) && $_SESSION['mode'] == 'brun' ) : ?>
                                style="outline: 2px solid #4bc2f2; 
                                transform: scale(1.08)"
                            <?php else: ?>
                                style="outline: none; 
                                transform: scale(1)"
                            <?php endif; ?>
                        ></div>
                        <div class="contain_theme" id="illimite" data-theme="illimite" 
                            <?php if(isset($_SESSION['mode']) && !is_null($_SESSION['mode']) && $_SESSION['mode'] == 'illimite' ) : ?>
                                style="outline: 2px solid #4bc2f2; 
                                transform: scale(1.08)"
                            <?php else: ?>
                                style="outline: none; 
                                transform: scale(1)"
                            <?php endif; ?>
                        ></div>
                        <div class="contain_theme" id="classic" data-theme="classic" 
                            <?php if(isset($_SESSION['mode']) && !is_null($_SESSION['mode']) && $_SESSION['mode'] == 'classic' ) : ?>
                                style="outline: 2px solid #4bc2f2; 
                                transform: scale(1.08)"
                            <?php else: ?>
                                style="outline: none; 
                                transform: scale(1)"
                            <?php endif; ?>
                        ></div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>