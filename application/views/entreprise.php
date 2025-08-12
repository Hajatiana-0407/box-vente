<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <h5>Entreprise</h5>

                <?php
                // var_dump( $data[0]->entreprise ) ; die  ; 
                ?>

                <div class="form">
                    <form action="<?= base_url('Auth/EditEnt') ?> " method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nom de l'entreprise :</label>
                            <input class="form-control input_form-control" type="text" id="entreprise" name="entreprise" value="<?= (isset($data)) ? $data[0]->entreprise : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse :</label>
                            <input class="form-control input_form-control" type="text" id="adresse" name="adresse" placeholder="Adresse" value="<?= (isset($data)) ? $data[0]->adresse : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tél :</label>
                            <input class="form-control input_form-control" type="text" placeholder="Numero" id="telephone" name="telephone" value="<?= (isset($data)) ? $data[0]->tel : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIF :</label>
                            <input class="form-control input_form-control" type="text" placeholder="Nif" name="nif" id="nif" value="<?= (isset($data)) ? $data[0]->nif : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">STAT :</label>
                            <input class="form-control input_form-control" type="text" placeholder="stat"  id="stat" name="stat" value="<?= (isset($data)) ? $data[0]->stat : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ajouter votre logo :</label>
                            <input type="file" onchange="afficheImage(this)" class="form-control input_form-control" name="photo">
                        </div>
                        <div class="_boutton">
                            <button type="submit" class="btn btn-info" id="valider">
                                <i class="fas fa-check"></i>
                                <div class="spinner-wrapper d-none" id="spinner_validation">
                                    <div class="spinner-dot"></div>
                                    <div class="spinner-dot"></div>
                                    <div class="spinner-dot"></div>
                                </div>
                                Valider
                            </button>
                        </div>
                    </form>
                    <?php if ($this->session->flashdata('success')) : ?>
                        <script>
            Myalert.updated()       
</script>
                    <?php endif; ?>
                    <div class="logo">
                        <h6>Votre logo : </h6>
                        <div class="division2 d-flex justify-content-left align-items-center pt-4" id="spinner_container">
                            <div id="loading" class="d-none">
                                <div class="spinner-border text-primary"></div>
                            </div>
                            <div class="_image">
                                <img id="image" src="<?= ($data[0]->logo != '') ? Myurl($data[0]->logo) :   base_url('public/images/favicon/photo-camera.png') ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>