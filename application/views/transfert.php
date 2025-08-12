<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <div>
                    <h5>Transfert</h5>

                    <form action="<?= base_url('Transfert/register') ?>" method="post">
                        <div class="mb-2 <?= (isset($_SESSION['pv'])) ? 'd-none' : '' ?>">
                            <label class="form-label">Dépôt source :</label>
                            <?php if (count($pv) > 0) : ?>

                                <select class="form-select  point_vente" id="pv_vente" name="pv_source">
                                    <?php if (!isset($_SESSION['pv'])) :  ?>
                                        <?php for ($i = 0; $i < count($pv); $i++) : ?>
                                            <option class="pv" data-idpv="<?= $pv[$i]->idPointVente ?>" value="<?= $pv[$i]->idPointVente ?>"><?= $pv[$i]->denomination_pv ?></option>
                                        <?php endfor; ?>
                                    <?php else : ?>
                                        <?php for ($i = 0; $i < count($pv); $i++) :
                                            if ($pv[$i]->idPointVente ==  $_SESSION['pv']) : ?>
                                                <option class="pv" data-idpv="<?= $pv[$i]->idPointVente ?>" value="<?= $pv[$i]->idPointVente ?>"><?= $pv[$i]->denomination_pv ?></option>
                                        <?php endif;
                                        endfor; ?>
                                    <?php endif  ?>
                                </select>
                            <?php else : ?>
                                <p class="text-danger">Veuillez inserer un Dépôt s'il vous plaît</p>
                            <?php endif  ?>
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
                            <label class="form-label">Couleur :</label>
                            <input class="form-control input_form-control " type="text" id="couleur" name="couleur" readonly>
                        </div>
                        <div class="mb-2 numero_liste_ d-none">
                            <label class="form-label">IMEI 1 :</label>
                            <input class="form-control input_form-control " type="text" id="imei1" name="imei1" readonly>
                        </div>
                        <div class="mb-2 numero_liste_ d-none">
                            <label class="form-label">IMEI 2 :</label>
                            <input class="form-control input_form-control " type="text" id="imei2" name="imei2" readonly>
                        </div>
                        <!-- <div class="mb-2">
                            <label class="form-label">Prix :</label>
                            <input class="form-control input_form-control d-none" type="text" id="prix" name="prix_unitaire" readonly>
                            <input class="form-control input_form-control" type="text" id="prix_show" readonly>
                        </div> -->


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
                            <label class="form-label">Dépôt déstination :</label>
                            <?php if (count($pv) > 0) : ?>

                                <select class="form-select  point_vente" id="pv_vente_destination" name="pv_destination">
                                    <?php if (!isset($_SESSION['pv'])) :  ?>
                                        <?php for ($i = 0; $i < count($pv); $i++) : ?>
                                            <option class="pv" data-idpv="<?= $pv[$i]->idPointVente ?>" value="<?= $pv[$i]->idPointVente ?>"><?= $pv[$i]->denomination_pv ?></option>
                                        <?php endfor; ?>
                                    <?php else : ?>
                                        <?php for ($i = 0; $i < count($pv); $i++) :
                                            if ($pv[$i]->idPointVente !=  $_SESSION['pv']) : ?>
                                                <option class="pv" data-idpv="<?= $pv[$i]->idPointVente ?>" value="<?= $pv[$i]->idPointVente ?>"><?= $pv[$i]->denomination_pv ?></option>
                                        <?php endif;
                                        endfor; ?>
                                    <?php endif  ?>
                                </select>
                            <?php else : ?>
                                <p class="text-danger">Veuillez inserer un Dépôt s'il vous plaît</p>
                            <?php endif  ?>
                        </div>

                        <div class="_boutton">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                <button type="button" class="btn btn-info" disabled>
                                    <i class="fas fa-check"></i>
                                    Valider
                                </button>
                            <?php else : ?>
                                <button type="submit" class="d-none" id="real_validation">ok</button>
                                <button type="button" class="btn btn-info" id="valider">
                                    <i class="fas fa-check"></i>
                                    <div class="spinner-wrapper d-none" id="spinner_validation">
                                        <div class="spinner-border"></div>
                                    </div>
                                    Valider
                                </button>
                            <?php endif  ?>
                        </div>
                    </form>

                    <?php if ($this->session->flashdata('success')) : ?>
                        <script>
                            Myalert.added();
                        </script>
                    <?php endif ?>



                    <form action="<?= base_url('Transfert/search') ?>" method="post" class="mb-4 mt-4 " id="searchform">
                        <!-- <div class="row"> -->
                        <div class="group_form">
                            <label for="date_debut" class="form-label">Date de début : </label>
                            <div class="input-group  mb-3">
                                <input type="date" class="form-control w-75" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                <input type="time" class="form-control w-25" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="group_form">
                            <label for="date_fin" class="form-label">Date de fin : </label>
                            <div class="input-group  mb-3">
                                <input type="date" class="form-control w-75" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                <input type="time" class="form-control w-25" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="group_form">
                            <label for="type" class="form-label">Recherche : </label>
                            <div class="input-group  mb-3">
                                <input type="text" placeholder="Recherche" id="motclet" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="group_form">
                            <label for="date_fin" class="form-label">Etat : </label>
                            <div class="mb-3">
                                <select name="etat" type="text" class="form-select" id="the_pv">
                                    <option value="0">Tout</option>
                                    <option value="recu">Reçu</option>
                                    <option value="attente">En attente</option>
                                </select>
                            </div>
                        </div>
                        <div class="group_form btn_rechreche">
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
                        <?php if ($this->session->flashdata('delete')) : ?>
                            <script>
                                Myalert.deleted()
                            </script>
                        <?php endif  ?>
                    </form>

                    <div class="_tableau mt-4">
                        <table class="table">
                            <thead class="table-info">
                                <tr>
                                    <th>Transfert</th>
                                    <th>Réference</th>
                                    <th>Désignation</th>
                                    <th>Couleur</th>
                                    <th>Numéro de série</th>
                                    <th>IMEI</th>
                                    <th>Quantité</th>
                                    <th>Date de transfert</th>
                                    <th>Etat </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableau">
                                <?php foreach ($datas as $key => $data) : ?>
                                    <tr>
                                        <td><?= $data->pv_1  ?> <i class="fas fa-arrow-right"></i> <?= $data->pv_2  ?></td>
                                        <td><?= $data->refProduit  ?> </td>
                                        <td><?= $data->designation  ?> </td>
                                        <td><?= ( $data->couleur  == '')?'--' : $data->couleur  ?> </td>
                                        <td><?= ($data->numero != '') ? $data->numero : '--'  ?> </td>
                                        <td>
                                            <?php if ($data->imei1 != '') : ?>
                                                IMEI_1 : <span class="m-0" style="text-decoration: underline;"><?= $data->imei1 ?></span> <br>
                                                IMEI_2 : <span class="m-0" style="text-decoration: underline;"><?= $data->imei2 ?></span>

                                            <?php else : ?>
                                                --
                                            <?php endif  ?>
                                        </td>
                                        <td><?= $data->qunatite_transfert ?> <?= $data->denomination  ?><?= ($data->qunatite_transfert > 1) ? 's' : '' ?></td>
                                        <td><?= form_date($data->date_transfert)  ?> </td>
                                        <td class="text-info reception_td" id="<?= $data->idtransfert ?>_td">
                                            <?php if (!$data->reception_transfert) :  ?>
                                                en attente...
                                            <?php else : ?>
                                                reçu
                                                <i class="fas fa-check"></i>
                                            <?php endif; ?>
                                        </td>

                                        <td id="<?= $data->idtransfert ?>_action">
                                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                <button class="btn btn-danger" type="button" disabled>
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>

                                                <button type="button" class="btn btn-primary" disabled>
                                                    <i class="fas fa-hand-holding-medical"></i>
                                                </button>
                                            <?php else : ?>
                                                <?php if (!$data->reception_transfert) :  ?>
                                                    <button class="btn btn-danger delete" type="button" data-idtransfert="<?= $data->idtransfert ?>">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>


                                                    <?php if ((isset($_SESSION['pv']) && $_SESSION['pv'] == $data->id_2)) :  ?>
                                                        <button type="button" class="btn btn-primary recevoir" data-idtransfert="<?= $data->idtransfert ?>">
                                                            <i class="fas fa-hand-holding-medical"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <button class="btn btn-danger" type="button" disabled>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                    <?php if ((isset($_SESSION['pv']) && $_SESSION['pv'] == $data->id_2)) :  ?>
                                                        <button type="button" class="btn btn-primary recevoir" disabled>
                                                            <i class="fas fa-hand-holding-medical"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach  ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (isset($nPages) && $nPages > 1) :  ?>
                        <div class="__pagination">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center pagination-sm">
                                    <li class="page-item">
                                        <?php if ($current == 1) :  ?>
                                            <span class="page-link __disabled" aria-label="Previous">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-left"></i></small>
                                            </span>
                                        <?php else :  ?>
                                            <?php if (isset($_POST['date_debut'])) :  ?>

                                                <form action="<?= base_url('Transfert/search/' . ($current - 1)) ?>" method="post">
                                                    <div class="row d-none">
                                                        <div class="col-4 row">
                                                            <label for="date_debut" class="form-label">Date de début : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4 row">
                                                            <label for="date_fin" class="form-label">Date de fin : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="date_fin" class="form-label"> Dépôt ou point de vente : </label>
                                                            <input type="text" name="etat" class="form-control" value="<?= $_POST['etat'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                    </button>
                                                </form>

                                            <?php else :  ?>
                                                <a class="page-link" href='<?= base_url('Transfert/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Transfert/search/' . ($i)) ?>" method="post">
                                                    <div class="row d-none">
                                                        <div class="col-4 row">
                                                            <label for="date_debut" class="form-label">Date de début : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4 row">
                                                            <label for="date_fin" class="form-label">Date de fin : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="date_fin" class="form-label"> Dépôt ou point de vente : </label>
                                                            <input type="text" name="etat" class="form-control" value="<?= $_POST['etat'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit">
                                                        <?= $i ?>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Transfert/page/' . $i) ?>"><?= $i ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endfor; ?>

                                    <li>
                                        <?php if ($current == $nPages) :  ?>
                                            <span class="page-link __disabled" aria-label="Next">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                                            </span>
                                        <?php else :  ?>
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Transfert/search/' . ($current +  1)) ?>" method="post">
                                                    <div class="row d-none">
                                                        <div class="col-4 row">
                                                            <label for="date_debut" class="form-label">Date de début : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4 row">
                                                            <label for="date_fin" class="form-label">Date de fin : </label>
                                                            <div class="input-group  mb-3">
                                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="date_fin" class="form-label"> Dépôt ou point de vente : </label>
                                                            <input type="text" name="etat" class="form-control" value="<?= $_POST['etat'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Transfert/page/' . ($current + 1)) ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                </ul>
                            </nav>
                        </div>
                    <?php endif;  ?>
                </div>
            </div>
        </div>
    </div>
</div>