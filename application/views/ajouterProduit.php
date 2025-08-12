<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <div id="specifique">
                </div>
                <div class="onglet">
                    <a href="#" id="vente_" class="onglet_btn active"> Produit</a>
                    <a href="<?= base_url('prix') ?>" id="panier_" class="onglet_btn"> Prix</a>
                    <a href="<?= base_url('codeBarre') ?>" class="onglet_btn">Code-barre</a>
                </div>
                <?php if ($_SESSION['user_type'] == 'admin') : ?>


                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                        <form action="" method="post">
                        <?php else : ?>
                            <form action="<?= base_url('registerMat') ?>" method="post" enctype="multipart/form-data">
                            <?php endif  ?>
                            <div class="">
                                <div class="mb-3 d-none">
                                    <label class="form-label ">Type :</label>
                                    <select name="type" id="type" class="form-select">
                                        <option value="telephone">Télephone</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Réference :</label>
                                    <input class="form-control input_form-control" type="text" id="referenceMat"
                                        name="referenceMat" required>
                                </div>

                                <!-- <div id="keyboard">ito</div> -->
                                <div class="mb-3">
                                    <label class="form-label">Désignation :</label>
                                    <input type="text" class="form-control input_form-control" id="designationMat"
                                        name="designationMat" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fiche technique :</label>
                                    <textarea name="fiche" id="fiche" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Seuil d'alert :</label>
                                    <div class="input-group">
                                        <input class="form-control input_form-control" type="number" id="seuil"
                                            name="seuil" required>
                                        <select name="seul_unite" id="seul_unite" class="form-select d-none">
                                        </select>
                                    </div>

                                    <input type="number" class="d-none" id="seul_min" name="seuil_min">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ajouter une Photo :</label>
                                    <input type="file" onchange="afficheImage(this)" class="form-control input_form-control"
                                        name="photo" id="choose_photo">
                                </div>

                                <div class="_boutton mt-3">

                                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                        <button type="button" class="btn btn-info" disabled>
                                            <i class="fas fa-check"></i>
                                            <div class="spinner-wrapper d-none" id="spinner_validation">
                                                <div class="spinner-border"></div>
                                            </div>
                                            Valider
                                        </button>
                                    <?php else : ?>
                                        <button type="button" class="d-none" id="real_validation">ok</button>
                                        <button type="button" class="btn btn-info" id="valider">
                                            <i class="fas fa-check"></i>
                                            <div class="spinner-wrapper d-none" id="spinner_validation">
                                                <div class="spinner-border"></div>
                                            </div>
                                            Valider
                                        </button>
                                    <?php endif  ?>
                                </div>
                            </div>
                            </form>

                        <?php endif; ?>


                        <?php if ($this->session->flashdata('ref')) : ?>
                            <script>
                                Myalert.erreur('Cette référence est déjà utilisée')
                            </script>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('designation')) : ?>
                            <script>
                                Myalert.erreur('Cette référence est déjà utilisée')
                            </script>
                        <?php endif; ?>
                        <?php if ($this->session->userdata('delete')) : ?>
                            <script>
                                Myalert.deleted();
                            </script>
                        <?php endif; ?>
                        <?php $this->session->unset_userdata('delete'); ?>
                        <?php if ($this->session->userdata('produit_add')) : ?>
                            <script>
                                Myalert.added();
                            </script>
                        <?php endif; ?>
                        <?php $this->session->unset_userdata('produit_add'); ?>
                        <?php if ($this->session->flashdata('edit')) : ?>
                            <script>
                                Myalert.updated();
                            </script>
                        <?php endif; ?>


                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <form action="" method="GET">
                                <div class="input-group mt-3 mb-3">
                                    <input type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">
                                    <button class="btn btn-info " type="button" disabled>
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            <?php else : ?>
                                <form action="<?= base_url('recherche') ?>" method="GET">
                                    <div class="input-group mt-3 mb-3">
                                        <input name="recherche" type="text" class="form-control" placeholder="Recherche"
                                            value="<?= $post ?? '' ?>">
                                        <button class="btn btn-info " type="submit">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </div>
                                <?php endif  ?>
                                </form>



                                <div class="entete">
                                    <h5 class="mt-5 mb-3">Liste des produits</h5>
                                </div>
                                <table class="table table">
                                    <thead class="table-info">
                                        <tr>
                                            <th>Réference</th>
                                            <th>Désignation</th>
                                            <th>Fiche technique</th>
                                            <th>Image</th>
                                            <th>Seuil d'alert </th>
                                            <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                                <th>Actions</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $produit = $data['produit'];
                                        for ($i = 0; $i < count($produit); $i++) : ?>
                                            <tr>
                                                <td>
                                                    <?= $produit[$i]->refProduit; ?>
                                                </td>

                                                <td>
                                                    <?= $produit[$i]->designation; ?>
                                                </td>
                                                <td>
                                                    <?= ($produit[$i]->fiche == '') ? '--' : $produit[$i]->fiche; ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    if ($produit[$i]->photo != 'upload/') : ?>
                                                        <img style="width: 30px; height: 30px; object-fit: cover;"
                                                            src="<?php echo Myurl(); ?>public/<?php echo $produit[$i]->photo; ?>"
                                                            alt="">
                                                    <?php else : ?>
                                                        <img style="width: 30px; height: 30px; object-fit: cover;"
                                                            src="<?= Myurl('public/images/mode-paysage.png') ?>" alt="">
                                                    <?php endif  ?>

                                                </td>

                                                <td>
                                                    <?= $produit[$i]->seuil  ?>
                                                </td>

                                                <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                                    <td>
                                                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                            <button class="btn btn-danger " type="button" disabled>
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>

                                                            <button type="button" class="btn btn-warning " disabled>
                                                                <i class="fa-solid fa-edit"></i>
                                                            </button>
                                                        <?php else :  ?>

                                                            <?php $unites =  $produit[$i]->unites;
                                                            $idunites = '' ?>
                                                            <?php foreach ($unites as $key => $unite) :  ?>
                                                                <?php $idunites .= $unite->idunite . ','; ?>
                                                            <?php endforeach ?>
                                                            <?php $idunites = trim($idunites, ',') ?>


                                                            <button class="btn btn-danger  delete" type="button" onclick="deleteIt(this)"
                                                                data-id="<?= $produit[$i]->idProduit; ?>"
                                                                data-ref="<?= $produit[$i]->refProduit; ?>">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>

                                                            <button type="button" class="btn btn-warning edit" onclick="DonnerProduit(this)"
                                                                data-toggle="modal" data-target="#editModal"
                                                                data-id="<?= $produit[$i]->idProduit; ?>"
                                                                data-ref="<?= $produit[$i]->refProduit; ?>" data-unite='<?= $idunites ?>'>
                                                                <i class="fa-solid fa-edit"></i>
                                                            </button>
                                                        <?php endif  ?>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                                <button class="btn btn-success d-none" id="btn_show_image" data-toggle="modal"
                                    data-target="#imageModal">show</button>
                                <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modification du Produit</h5>
                    <button type="button" id="close_editModal"
                        class="btn-close d-flex justify-content-center alin-items-center" data-bs-dismiss="modal"><i
                            class="fas fa-x"></i></button>
                </div>
                <form enctype="multipart/form-data" action="<?= base_url('editProd') ?>" method="post"
                    id="modifMateriel">
                    <div class="container">
                        <!-- Modal body -->
                        <div class="modal-body" id="validation">
                            <input type="hidden" name="id" id="idMateriel-modif">
                            <div class="mb-3 d-none">
                                <label class="form-label">Type :</label>
                                <select name="type" id="type-modif" class="form-select">

                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Réference:</label>
                                <input class="form-control input_form-control" type="text" id="reference-modif"
                                    name="reference_modif" required>
                                <p class="text-danger d-none" id="msg-ref">Cette référence est déjà utilisée</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Désignation:</label>
                                <input class="form-control input_form-control" id="designation-modif" type="text"
                                    name="designation_modif" required>
                                <p class="text-danger d-none" id="msg-designation">Ce désignation existe déjà</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fiche technique :</label>
                                <textarea name="fiche_modif" id="fiche-modif" class="form-control input_form-control"></textarea>

                            </div>

                            <div class="mb-3" id="sous_unite_modif">

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Seuil d'alert :</label>
                                <div class="input-group">
                                    <input class="form-control input_form-control" type="number" id="seuil_modif"
                                        name="seuil_modif" required>
                                    <select name="seul_unite_modif" id="seul_unite_modif" class="form-select d-none">

                                    </select>
                                </div>

                                <input type="number" class="d-none" id="seuil_min_modif" name="seuil_min_modif">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ajouter une Photo :</label>
                                <input type="file" id="photo-modif" onchange="afficheImages(this)"
                                    class="form-control input_form-control" name="photo">
                            </div>

                            <div class="image d-flex justify-content-center align-items-center" id="spinner_container">
                                <div id="loading_modif" class="d-none">
                                    <div class="spinner-border text-primary"></div>
                                </div>
                                <img id="images" src="#" alt=""
                                    style="border: 1px solid #dedede;width: 200px;height: 200px; object-fit: cover ;">
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer _button">

                        <button type="submit" class="btn btn-info d-none" id="modification"><i
                                class="fas fa-pencil-alt"></i> Modifier</button>
                        <a href="#" class="btn btn-info" id="modifier"><i class="fas fa-pencil-alt"></i> Modifier</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="validation">
                    <div class="container">
                        <!-- Modal body -->
                        <div class="image_container">
                            <div class="_image">
                                <img id="image" src="<?= base_url('public/images/favicon/photo-camera.png') ?>" alt="">
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer _button">

                            <a href="#" class="btn btn-info" id="image_ok"><i class="fas fa-check"></i> OK</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>