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
                                <h4 class="modal-title vente">Validation du Panier</h4>
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
                                <div class="form-check" id="tva_include">
                                    <input class="form-check-input" type="checkbox" id="tva_" data-status='off'>
                                    <label class="form-check-label" for="tva_">
                                        Inclure le TVA
                                    </label>
                                </div>
                                <button type="button" class="btn btn-info" id="sendvalidation" data-bs-dismiss="modal"><i class="fas fa-check"></i> Valider</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STOCK ALERT  -->
                <?php if (isset($stock_alerts)) : ?>
                    <div id="alertModal" class="modal" style="display: block ; background : #00000045  ">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title vente">Produit en cours de rupture de stock </h4>
                                    <button type="button" class="btn-close d-flex hidde_modale_stock" data-bs-dismiss="modal">
                                        <i class="fa-solid fa-x"></i>
                                    </button>
                                </div>
                                <div class="modal-body" style="font-size: 14px;" id="tab_alert_stock">
                                    <div class="_tableau mt-4">
                                        <table class="table">
                                            <thead class="table-info">
                                                <tr class="to_filtre">
                                                    <th>Réference</th>
                                                    <th>Désignation</th>
                                                    <th>Quantité </th>
                                                    <th>Dépôt ou point de vente
                                            </thead>
                                            <tbody>
                                                <?php foreach ($stock_alerts as $key => $stock_alert) : ?>
                                                    <tr class="table-danger">
                                                        <td><?= $stock_alert->refProduit ?></td>
                                                        <td><?= $stock_alert->designation ?></td>
                                                        <td><?= $stock_alert->quantite_stk ?></td>
                                                        <td><?= $stock_alert->denomination_pv ?></td>
                                                    </tr>
                                                <?php endforeach  ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="container">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info hidde_modale_stock" data-bs-dismiss="modal"><i class="fas fa-check "></i> Ok</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- alert pour l'abonnement  -->
                    <div id="<?= (isset($_SESSION['let_test']) && !$_SESSION['let_test']) ? 'movable_notest' : 'movable'  ?>" class=" animate__animated  animate__zoomInDown " style="height: <?= (isset($_SESSION['let_test']) && !$_SESSION['let_test']) ? '100px' : '60px'  ?>;">
                        <span id="mouvalble_icon">
                            <i class="fas fa-arrows-alt "></i>
                        </span>
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                            <p class="text-danger m-0"> <i class="fas fa-info-circle "></i> <?= (isset($_SESSION['abonne']) && !$_SESSION['abonne']) ? 'Votre abonnement est terminé. ' : 'Vos 2 semaines d\'essai gratuit sont terminées. : ' ?> Contactez le concepteur du logiciel au <span style="text-decoration: underline;">+261 34 70 840 46</span></p>
                        <?php elseif (isset($_SESSION['time_rest'])) : ?>
                            <p class="text-info m-0"> <?= (isset($_SESSION['abonne']) && $_SESSION['abonne']) ? '<i class="fas fa-credit-card"></i> Abonnement : ' : '<i class="fas fa-info-circle  "></i> Essai gratuit : ' ?> <?= ($_SESSION['time_rest'] == 1) ? $_SESSION['time_rest'] . ' jour restant ' : $_SESSION['time_rest'] . ' jours restants' ?> </p>
                        <?php endif  ?>
                    </div>

                <?php endif  ?>
                <?php if (isset($time_alert)) :  ?>
                    <!-- alert pour l'abonnement  -->
                    <div id="<?= (isset($_SESSION['let_test']) && !$_SESSION['let_test']) ? 'movable_notest' : 'movable'  ?>" class=" animate__animated  animate__zoomInDown " style="height: <?= (isset($_SESSION['let_test']) && !$_SESSION['let_test']) ? '100px' : '60px'  ?>;">
                        <span id="mouvalble_icon">
                            <i class="fas fa-arrows-alt "></i>
                        </span>
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                            <p class="text-danger m-0"> <i class="fas fa-info-circle "></i> <?= (isset($_SESSION['abonne']) && !$_SESSION['abonne']) ? 'Votre abonnement est terminé. ' : 'Vos 2 semaines d\'essai gratuit sont terminées. : ' ?> Contactez le concepteur du logiciel au <span style="text-decoration: underline;">+261 34 70 840 46</span></p>
                        <?php elseif (isset($_SESSION['time_rest'])) : ?>
                            <p class="text-info m-0"> <?= (isset($_SESSION['abonne']) && $_SESSION['abonne']) ? '<i class="fas fa-credit-card"></i> Abonnement : ' : '<i class="fas fa-info-circle  "></i> Essai gratuit : ' ?> <?= ($_SESSION['time_rest'] == 1) ? $_SESSION['time_rest'] . ' jour restant ' : $_SESSION['time_rest'] . ' jours restants' ?> </p>
                        <?php endif  ?>
                    </div>

                <?php endif  ?>


                <!-- Fin Modal -->
                <!-- Modal -->
                <div class="modal fade" id="modalfacture">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title vente">Facture </h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="" id="validation" style="font-size: 14px;">
                                    <p class="text-info">Veuillez désactiver IDM si vous en avez.</p>
                                    <div class="mb-2">
                                        <label class="form-label">Format : </label>
                                        <select class="form-select" name="" id="format">
                                            <option value="A4">A4</option>
                                            <option value="tiquet">Tiquet de caisse</option>
                                        </select>
                                    </div>
                                    <div class="">
                                        <div id="loaderFacture" style="display:flex; align-items:center; justify-content: center; width:100%; height:500px;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <iframe src="" class="d-none" id="pdfFrame" style="width:100%; height:500px;" frameborder="0"></iframe>
                                    </div>

                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal -->
                <!-- Modal panier -->
                <div class="modal fade" id="PanierModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title vente">Panier</h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="modal_body" id="panier_body" style="font-size: 14px;">
                                    <div class="_tableau mt-4">
                                        <table class="table">
                                            <thead class="table-info">
                                                <tr class="to_filtre">
                                                    <th>
                                                        Désignation
                                                    </th>
                                                    <th>
                                                        Déscription
                                                    </th>
                                                    <th>
                                                        Quantité
                                                    </th>
                                                    <th>
                                                        Prix unitaire
                                                    </th>
                                                    <th>
                                                        Prix Total
                                                    </th>
                                                    <th>
                                                        Remise
                                                </tr>
                                            </thead>
                                            <tbody id="table_panier_validation">
                                            </tbody>
                                        </table>
                                        <input type="text" class="d-none" id="pointdeventPanier">
                                    </div>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" data-bs-dismiss="modal" id="panierValide" data-bs-dismiss="modal"><i class="fas fa-check"></i> Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal panier -->
                <div>
                    <button id="affichefacture" class="btn  btn-info d-none" data-bs-toggle="modal" data-bs-target="#modalfacture">Afficher la facture</button>
                    <div class="onglet">
                        <a href="<?= base_url('vente') ?>" id="panier_" class="onglet_btn active">Nouvelle vente</a>
                        <a href="<?= base_url('liste') ?>" class="onglet_btn">Liste des ventes</a>
                    </div>
                    <div class="mb-2" id="denomination_pv">
                        <div class="row d-none">
                            <div class="col-12 point_vente">
                                <label class="form-label"> Dépôt ou point de vente :</label>
                                <?php if (count($pv) > 0) : ?>

                                    <?php if (!isset($_SESSION['pv'])) :  ?>
                                        <select class="form-select  point_vente" id="pv_vente">
                                            <?php for ($i = 0; $i < count($pv); $i++) : ?>
                                                <option class="pv" data-idpv="<?= $pv[$i]->idPointVente ?>" value="<?= $pv[$i]->idPointVente ?>"><?= $pv[$i]->denomination_pv ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    <?php else : ?>
                                        <select class="form-select  point_vente" id="pv_vente">
                                            <?php for ($i = 0; $i < count($pv); $i++) :
                                                if ($pv[$i]->idPointVente ==  $_SESSION['pv']) : ?>
                                                    <option class="pv" data-idpv="<?= $pv[$i]->idPointVente ?>" value="<?= $pv[$i]->idPointVente ?>"><?= $pv[$i]->denomination_pv ?></option>
                                            <?php endif;
                                            endfor; ?>
                                        </select>
                                    <?php endif  ?>
                                <?php else : ?>
                                    <p class="text-danger">Veuillez inserer un Dépôt s'il vous plaît</p>
                                <?php endif  ?>
                            </div>
                        </div>
                    </div>


                    <div class="mb-2">
                        <label class="form-label">Référence ou Numéro de série : </label>
                        <input type="text" class="form-control form-control-lg" id="reference" name="reference" required>
                        <input type="number" class="form-control form-control-lg d-none" id="idProduit" name="idProduit">
                        <input type="text" class="form-control form-control-lg d-none" id="type_produit" name="type_produit">
                    </div>

                    <div class="mb-2 numero_liste_ d-none">
                        <label class="form-label">Numéro de série : </label>
                        <select class="form-select " id="numero_liste" name="numero_liste">
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Désignation : </label>
                        <input type="text" class="form-control form-control-lg" id="designation" name="designation" readonly>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Fiche technique : </label>
                        <textarea name="fiche" id="fiche" class="form-control" style="resize: none;" readonly required></textarea>
                    </div>

                    <div class="mb-2 numero_liste_ d-none">
                        <label class="form-label">IMEI 1 :</label>
                        <input class="form-control input_form-control " type="text" id="imei1" name="imei1" readonly>
                    </div>
                    <div class="mb-2 numero_liste_ d-none">
                        <label class="form-label">IMEI 2 :</label>
                        <input class="form-control input_form-control " type="text" id="imei2" name="imei2" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Prix :</label>
                        <input class="form-control input_form-control d-none" type="text" id="prix" name="prix_unitaire" readonly>
                        <input class="form-control input_form-control" type="text" id="prix_show" readonly>
                    </div>


                    <div class="with_qte d-none">
                        <div class="mb-2">
                            <label class="form-label">Quantité disponible :</label>
                            <input class="form-control input_form-control" type="text" id="qte_dipo" readonly>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Quantité :</label>
                            <input class="form-control input_form-control" type="number" min='1' id="quantite" name="quantite" value="1" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Montant :</label>
                            <input class="form-control input_form-control" type="text" id="montant_show" name="montant_show" value="0" readonly>
                            <input class="form-control input_form-control d-none" type="text" id="montant" name="montant" value="1" required>
                        </div>
                    </div>





                    <div class="mb-2">
                        <label class="form-label">Remise (en MGA)</label>
                        <input id='remise' type="number" value="0" min="0" max="100" class="form-control mb-1" />
                    </div>


                    <div class="_boutton">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                            <button type="button" data-self='vente' class="btn btn-info" disabled><i class="fas fa-check"></i> Ajouter au panier</button>
                            <button type="button" class="btn btn-success d-none" disabled><i class="fas fa-check"></i> Valider le panier</button>
                            <button class="btn btn-danger annnuler_panier btn_container"><i class="fas fa-ban"></i> Annuler le panier</button>
                        <?php else :   ?>
                            <button type="submit" data-self='vente' class="btn btn-info" id="valider"><i class="fas fa-check"></i> Ajouter au panier</button>
                            <button type="button" class="btn btn-success d-none" data-bs-toggle="modal" data-bs-target="#myModal" id="validerPanier"><i class="fas fa-check"></i> Valider le panier</button>
                            <button class="btn btn-danger annnuler_panier btn_container"><i class="fas fa-ban"></i> Annuler le panier</button>
                        <?php endif  ?>
                    </div>

                    <?php if ($this->session->flashdata('success')) : ?>
                        <script>
                            Myalert.added('Vente effectuée');
                        </script>
                    <?php endif ?>

                    <div class="panier">
                        <div class="thearg" id="solde" style="background-color : rgb(220, 231, 252) ">
                            <label class="form-label"><i class="fas fa-tags"></i> Montant total </label>
                            <p id="total_in_panier">0 Ar</p>
                        </div>

                        <div class="_tableau mt-4">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Réference</th>
                                        <th>Désignation</th>
                                        <th>Numéro de série</th>
                                        <th>EMEI</th>
                                        <th>Prix Unitaire</th>
                                        <th>Quantité</th>
                                        <th>Montant</th>
                                        <th>Remise</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tableau">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>