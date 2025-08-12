<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">





                <div class="onglet">
                    <a href="<?= base_url('produit') ?>" id="vente_" class="onglet_btn "> Produit</a>
                    <a href="#" id="panier_" class="onglet_btn active"> Prix</a>
                    <a href="<?= base_url('codeBarre') ?>" class="onglet_btn"> Code-barre</a>
                </div>
                <?php if ($_SESSION['user_type'] == 'admin') : ?>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                        <form action="" method="post">
                        <?php else : ?>
                            <form action="<?= base_url('registerPrix') ?>" method="post" id="prix_modif">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Réference :</label>
                                <input type="text" class="form-control " name="reference" id="reference" required>

                                <p class="text-danger d-none" id="msg-des">Cet reférence n'existe pas</p>
                                <input type="hidden" id="idproduit" name="idproduit">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Désignation :</label>
                                <input type="text" class="form-control " id="designation" name="designation" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fiche technique :</label>
                                <textarea name="fiche" id="fiche" class="form-control"></textarea>
                            </div>

                            <div id="unite_container">
                                <div class="mb-3">
                                    <label class="form-label">Prix :</label>
                                    <div class="input-group">
                                        <!-- <div type="text" class="form-control w-25" id="uniteparent" name="uniteparent" readonly required>
                                        </div> -->
                                        <input type="number" min='0' class="form-control w-75" id="unitParentval" name="unitParentval" required>
                                    </div>
                                </div>
                            </div>

                            <div class="_boutton">
                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                    <button type="button" class="btn btn-info" disabled>
                                        <i class="fas fa-check"></i>
                                        Valider
                                    </button>
                                <?php else : ?>
                                    <button type="submit" class="btn btn-info" id="valider">
                                        <i class="fas fa-check"></i>
                                        <div class="spinner-wrapper d-none" id="spinner_validation">
                                            <div class="spinner-border"></div>
                                        </div>
                                        Valider
                                    </button>
                                <?php endif; ?>

                            </div>

                            <?php if ($this->session->flashdata('success')) : ?>
                                <script>
                                    Myalert.added()
                                </script>
                            <?php elseif ($this->session->userdata('delete')) : ?>
                                <script>
                                    Myalert.deleted()
                                </script>
                            <?php elseif ($this->session->flashdata('notExiste')) : ?>
                                <script>
                                    Myalert.erreur("Cette Référence n'existe pas")
                                </script>
                            <?php endif; ?>
                            <?php $this->session->unset_userdata('delete') ?>
                            </form>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <form action="" method="get">
                                <div class="input-group mt-4 mb-3">
                                    <input type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">
                                    <button class="btn btn-info" type="button" disabled>
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                        <?php else : ?>
                            <form action="<?= base_url('recherchePrix') ?>" method="get">
                                <div class="input-group mt-4 mb-3">
                                    <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">
                                    <button class="btn btn-info" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                        <?php endif ?>
                        <h5 class="mb-3">Liste des prix</h5>
                        <div class="_tableau mt-4">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Réference</th>
                                        <th>Désignation</th>
                                        <th>Fiche technique </th>
                                        <th>Prix Unitaire</th>
                                        <th>Date</th>
                                        <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                            <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $prix = $data['prix'];
                                    for ($i = 0; $i < count($prix); $i++) : ?>

                                        <!-- affichage des unite et prix du produit  -->
                                        <?php

                                        $content = '';
                                        if (isset($prix[$i]->unite_prix)) {
                                            $prix_unites = $prix[$i]->unite_prix;
                                            foreach ($prix_unites as $key => $prix_unite) {
                                                if ($prix_unite->denomination != '') {
                                                    $content .= '<i class="fas fa-minus-square text-info"></i> 1 ' . $prix_unite->denomination . ' = ' . number_three($prix_unite->prixProduit) . '</br>';
                                                } else {
                                                    $content =  number_three($prix_unite->prixProduit);
                                                }
                                            }
                                        } else {
                                            $content = number_three($prix[$i]->prixProduit);
                                        }

                                        ?>
                                        <!-- affichage des unite et prix du produit  -->



                                        <tr>
                                            <td><?= $prix[$i]->refProduit ?></td>

                                            <td><?= $prix[$i]->designation ?></td>
                                            <td><?= $prix[$i]->fiche ?></td>

                                            <td><?= $content ?></td>

                                            <td><?= form_date($prix[$i]->dateAjoutPrix) ?></td>
                                            <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                                <td>
                                                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                        <button class="btn btn-danger " disabled>
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button class="btn btn-danger delete" onclick="deleteIt(this)" data-groupe="<?= $prix[$i]->groupe ?>" data-idProduit="<?= $prix[$i]->idProduit ?>">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    <?php endif  ?>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endfor ?>


                                </tbody>
                            </table>
                            <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
                        </div>
            </div>
        </div>
    </div>
</div>