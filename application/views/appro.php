<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <?php if ($_SESSION['user_type'] == 'admin') : ?>
                    <!-- <div class="onglet">
                        <a href="#" class="onglet_btn active"> Approvisionnement </a>
                        <a href="<?= base_url('commande') ?>" id="panier_" class="onglet_btn"> Commande</a>
                        <a href="<?= base_url('listecommande') ?>" class="onglet_btn">Liste des commandes </a>
                        <a href="<?= base_url('reception') ?>" class="onglet_btn">Reception de commande </a>
                    </div> -->

                    <h5>Approvisionnement</h5>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                        <form action="" method="post">
                        <?php else : ?>
                            <form action="<?= base_url('registerAppro') ?>" method="post">
                            <?php endif  ?>

                            <div class="mb-2">
                                <label class="form-label">Réference du produit :</label>
                                <input class="form-control " name="reference" id="reference" required>
                                <input class="" type="hidden" id="type">
                            </div>

                            <input name="idProduit" id="idProduit" type="hidden" class="form-control input_form-control" readonly required>

                            <div class="mb-2">
                                <label class="form-label">Désignation du produit :</label>
                                <input name="designation" id="designation" type="text" class="form-control input_form-control" readonly required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Fiche technique : </label>
                                <textarea name="fiche" id="fiche" class="form-control" style="resize: none;" readonly required></textarea>
                            </div>
                            
                            <div class="mb-2 d-none">
                                <label class="form-label">Fournisseur :</label>
                                <select name="fournisseur" class="form-select" id="fournisseur">
                                    <option value="0">Aucun</option>
                                    <?php foreach ($fournisseurs as $key => $fournisseur) : ?>
                                        <option value="<?= $fournisseur->idfournisseur ?>"><?= strtoupper($fournisseur->nom_entr) ?></option>
                                    <?php endforeach  ?>
                                </select>
                            </div>

                            <div class="mb-2  ">
                                <label class="form-label">Prix unitaire :</label>
                                <input name="prix" id="prix" type="number" min="0" class="form-control input_form-control" required>
                            </div>

                            <div class="mb-2  autre d-none">
                                <label class="form-label">Quantité :</label>
                                <input name="quantite" id="quantite" type="number" min="0" class="form-control input_form-control " required>
                            </div>
                            <div class="mb-2  autre d-none">
                                <label class="form-label">Montant :</label>
                                <input name="montant_show" id="montant_show" type="text" class="form-control input_form-control" readonly >
                                <input name="montant" id="montant" type="text" class="form-control input_form-control d-none">
                            </div>

                            <div class="mb-2 telephone" id="numero_de_serie">
                                <label class="form-label">Numéro de série :</label>
                                <input type="text" class="form-control mb1 mb-2" id="numSerie" data-self="0" name="numSerie">
                                <p class="text-danger m-0 d-none" id="numero_mss">Ce numéro de série existe déjà.</p>
                                <div class="" >
                                    <label class=" form-label ">IMEI  :</label>
                                </div>
                                <div class="input-flex">
                                    <input type="text" id="imei1" placeholder="IMEI 1 . . ." class="form-control" data-self='0' name="imei1">
                                    <input type="text" id="imei2" placeholder="IMEI 2 . . ." class="form-control" data-self='0' name="imei2">
                                </div>
                                <p class="text-danger d-none m-0" id="imei1_mss">Cet IMEI 1 existe déjà.</p>
                                <p class="text-danger d-none m-0" id="imei2_mss">Cet IMEI 2 existe déjà.</p>
                            </div>
                            <div class="mb-2 d-none">
                                <label class="form-label">Dépôt :</label>
                                <?php if (count($pv) > 0) : ?>
                                    <select name="pv" class="form-select">
                                        <?php for ($i = 0; $i < count($pv); $i++) : ?>
                                            <option value="<?= $pv[$i]->idPointVente ?>">
                                                <?= $pv[$i]->denomination_pv ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                <?php else : ?>
                                    <p class="text-danger">Veuillez inserer un Dépôt s'il vous plaît</p>
                                <?php endif; ?>
                            </div>

                            <div class="_boutton mt-4 mb-4">
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
                                <?php endif  ?>
                            </div>

                            <?php if ($this->session->flashdata('added')) : ?>
                                <script>
                                    Myalert.added()
                                </script>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('delete')) : ?>
                                <script>
                                    Myalert.deleted()
                                </script>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('adrres')) : ?>
                                <script>
                                    Myalert.deleted('Veuillez inseret un   Dépôt')
                                </script>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('erreur')) : ?>
                                <script>
                                    Myalert.erreur('Veuillez vérifier les données que vous avez saisies.');
                                </script>
                            <?php endif; ?>
                            </form>
                        <?php endif; ?>


                        <h5>Recherche</h5>
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <form action="" method="post" class="mb-4 mt-4" id="searchform">
                            <?php else : ?>
                                <form action="<?= base_url('Appro/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                                <?php endif  ?>
                                <!-- <div class="row"> -->
                                <div class="group_form ">
                                    <label for="date_debut" class="form-label">Date de début : </label>
                                    <div class="input-group  mb-3">
                                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="group_form ">
                                    <label for="date_fin" class="form-label">Date de fin : </label>
                                    <div class="input-group  mb-3">
                                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="group_form ">
                                    <label for="date_fin" class="form-label"> Recherche : </label>
                                    <div class=" mb-3">
                                        <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
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
                                </form>

                                <div class="_tableau mt-4">
                                    <table class="table table">
                                        <thead class="table-info">
                                            <tr>
                                                <th>Réference</th>
                                                <th>Désignation</th>
                                                <th>Numéro de série</th>
                                                <th>EMEI</th>
                                                <th>Prix unitaire </th>
                                                <th>Quantité </th>
                                                <th>Montant</th>
                                                <th>Date</th>
                                                <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                                    <th>Action</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody class="search-results">
                                            <?php foreach ($datas as $key  => $data) :  ?>
                                                <tr>
                                                    <td><?= $data->refProduit ?></td>
                                                    <td><?= $data->designation ?></td>
                                                    <td><?= ($data->numero == 0) ? '--' : $data->numero ?></td>
                                                    <td>
                                                        <?php if ($data->imei1 != '') : ?>
                                                            IMEI 1 : <span class="m-0" ><?= $data->imei1 ?></span> <br>
                                                            IMEI 2 : <span class="m-0" ><?= $data->imei2 ?></span>

                                                        <?php else : ?>
                                                            --
                                                        <?php endif  ?>
                                                    </td>
                                                    <td><?= number_three($data->prix_unitaire) ?></td>
                                                    <td><?= $data->quantite ?></td>
                                                    <td><?= number_three($data->montant) ?></td>
                                                    <td><?= form_date($data->dateAppro) ?></td>
                                                    <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                                        <td>
                                                            <button class="btn btn-danger delete" type="button" data-idappro='<?= $data->idAppro  ?>'>
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    <?php endif; ?>

                                                </tr>
                                            <?php endforeach  ?>
                                        </tbody>
                                    </table>
                                    <?php if ($nPages > 1) :  ?>
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

                                                                <form action="<?= base_url('Appro/search/' . ($current - 1)) ?>" method="post">
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
                                                                            <label for="date_fin" class="form-label"> Dépôt : </label>
                                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
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
                                                                <a class="page-link" href='<?= base_url('Appro/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </li>

                                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                                <form action="<?= base_url('Appro/search/' . ($i)) ?>" method="post">
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
                                                                            <label for="date_fin" class="form-label"> Dépôt : </label>
                                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
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
                                                                <a class="page-link" href="<?= base_url('Appro/page/' . $i) ?>"><?= $i ?></a>
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
                                                                <form action="<?= base_url('Appro/search/' . ($current +  1)) ?>" method="post">
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
                                                                            <label for="date_fin" class="form-label"> Dépôt : </label>
                                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
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
                                                                <a class="page-link" href="<?= base_url('Appro/page/' . ($current + 1)) ?>" aria-label="Next">
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