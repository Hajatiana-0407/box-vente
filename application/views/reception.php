<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <!-- Modal -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title vente">Validation de la reception </h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="modal-body" id="validation" style="font-size: 14px;">

                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" id="to_validate" >
                                    <i class="fas fa-check"></i>
                                    <div class="spinner-wrapper d-none" id="spinner_to_validate">
                                        <div class="spinner-border"></div>
                                    </div>
                                    Valider
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal -->

                <!-- data-bs-dismiss="modal" -->


                <div class="onglet">
                    <a href="<?= base_url('appro') ?>" class="onglet_btn "> Approvisionnement </a>
                    <a href="<?= base_url('commande') ?>" id="panier_" class="onglet_btn"> Commande</a>
                    <a href="<?= base_url('listecommande') ?>" class="onglet_btn">Liste des commandes </a>
                    <a href="#" class="onglet_btn active">Reception de commande </a>
                </div>
                <select id="point_vente" class="d-none form-select">
                    <option value="0"> </option>
                    <?php foreach ($pv as $key => $pointvente):  ?>
                        <option value="<?= $pointvente->idPointVente  ?>"><?= $pointvente->denomination_pv ?></option>
                    <?php endforeach  ?>
                </select>



                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post" class="mb-4 mt-4" id="searchform">
                    <?php else : ?>
                        <form action="<?= base_url('Reception/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                        <?php endif  ?>
                        <div class="group_form w-50">
                            <label for="date_fin" class="form-label">N° de commande : </label>
                            <div class=" mb-3">
                                <input type="text" placeholder="2024-00001" class="form-control input-incarte" name="recherche" value="<?= $_POST['numero'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="group_form button_ ">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                <button class="btn btn-info" type="button" disabled>
                                    <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                                </button>
                            <?php else : ?>
                                <button class="btn btn-info" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                                </button>
                            <?php endif  ?>
                        </div>
                        <!-- </div> -->

                        <div class="group_form button_ <?= (isset($datas->paniers) && count($datas->paniers) && !$datas->recue ) ? '' : 'd-none' ?>" id="sendvalidation">
                            <button type="button" class="btn btn-success" id="valider">
                                <i class="fas fa-check"></i>
                                <div class="spinner-wrapper d-none" id="spinner_validation">
                                    <div class="spinner-border"></div>
                                </div>
                                Valider
                            </button>
                        </div>
                        </form>

                        <?php if (isset( $_POST["reception"] )  && $_POST["reception"]) : ?>
                            <script>
                                Myalert.added() ; 
                            </script>
                        <?php endif  ?>
                        <?php if (isset($datas->paniers) && count($datas->paniers)) : ?>
                            <div class="bg-info p-2 text-light d-inline-block">
                                <p class="m-0">Fournisseur : <b><?= $datas->nom_entr ?></b></p>
                                <input type="number" id="idfournisseur" class="d-none" value="<?= $datas->idfournisseur ?>" >
                            </div>
                            <?php if ($datas->recue ) : ?>
                            <div class="bg-success p-2 text-light d-inline-block">
                                <p class="m-0"><b><i class="fas fa-check"></i> Commande reçu</b></p>
                            </div>
                        <?php endif  ?>
                        <?php endif  ?>

                        <!-- modal reception -->
                        <button type="button" class="btn btn-secondary d-none" id='panier_reception' data-bs-toggle="modal" data-bs-target="#myModal">valider</button>

                        <div class="_tableau mt-4">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Réference</th>
                                        <th>Désignation</th>
                                        <th>Prix Unitaire</th>
                                        <th>Qte commandé</th>
                                        <th>Unité</th>
                                        <th>Qte Réçu</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody id="tableau">

                                    <?php if (isset($datas->paniers) && count($datas->paniers)) : ?>
                                        <?php foreach ($datas->paniers as $key => $panier) :  ?>
                                            <tr
                                                data-frais="<?= $datas->frais ?>"
                                                data-idcommande="<?= $datas->idcommande ?>"
                                                data-idcmfacture="<?= $panier->idcmfacture ?>"
                                                data-idproduit="<?= $panier->idProduit ?>"
                                                data-idunite="<?= $panier->idunite ?>"
                                                data-quantite="<?= $panier->quantite ?>"
                                                data-min_qte="<?= $panier->min_qte ?>"
                                                data-cmfacture="<?= $datas->cmfacture ?>"
                                                data-montant="<?= $panier->quantite  * $panier->prixunitaire ?>"



                                                data-prix=<?= $panier->prixunitaire ?>
                                                data-reference=<?= $panier->refProduit ?>
                                                data-designation=<?= $panier->designation ?>
                                                data-unite=<?= $panier->denomination ?>>
                                                <td><?= $panier->refProduit ?></td>
                                                <td><?= $panier->designation ?></td>
                                                <td><?= number_three($panier->prixunitaire) ?></td>
                                                <td><?= format_number_simple($panier->quantite) ?></td>
                                                <td><?= ($panier->denomination != '') ? $panier->denomination : '-' ?></td>
                                                <td class="quantite_recu">
                                                    <input type="number" min='0' class="form-control qte_recue" value="<?= $panier->quantite ?>" <?= ( $datas->recue )? 'readonly' : '' ?>>
                                                </td>
                                                <td id="<?= $panier->idcmfacture ?>_montant"><?= number_three($panier->prixunitaire * $panier->quantite) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else : ?>
                                        <p class="text-secondary">Aucune commande trouvée.</p>
                                    <?php endif ?>

                                </tbody>
                            </table>
                        </div>
            </div>
        </div>
    </div>
</div>