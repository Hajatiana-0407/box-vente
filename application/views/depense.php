<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- MODAL -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification du client</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('Depense/edit') ?>" id="modifClient" method="post">
                                    <input type="hidden" id="iddepensemodif" name="id">
                                    <div class="mb-1">
                                        <label class="form-label">Raison :</label>
                                        <input name="raison_" id="raison_" type="text" class="form-control input_form-control">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label">Montant :</label>
                                        <input name="montant_" id="montant_" type="number" min='0' class="form-control input_form-control">
                                    </div>


                                    <div class="mt-2">
                                        <button type="button" class="btn btn-info" id="modifier"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <?php if ($this->session->flashdata('edition')) : ?>
                    <script>
                        Myalert.updated()
                    </script>
                <?php endif; ?>




                <!-- FIN MODAL -->




                <!-- ********************************** -->

                <h5>Dépense</h5>


                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post">
                <?php else :?>
                    <form action="<?= base_url('Depense/register') ?>" method="post">
                <?php endif ?>
                    <div class="mb-3">
                        <label class="form-label">Raison :</label>
                        <input type="text" class="form-control " id="raison" name="raison" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant :</label>
                        <input type="number" min='0' class="form-control " id="montant" name="montant" required>
                    </div>

                    <?php if ($_SESSION['user_type'] == 'admin') :  ?>
                        <div class="mb-3">
                            <label class="form-label">Date et heure :</label>
                            <div class="input-group  mb-3">
                                <input type="date" class="form-control" name="date" value="<?= date("Y-m-d") ?>">
                                <input type="time" class="form-control" name="heure" value="<?= date("H:i") ?>">
                            </div>
                        </div>
                    <?php endif ?>


                    <div class="mb-2">
                        <label class="form-label">  Dépôt ou point de vente :</label>
                        <?php if (count($pv) > 0) : ?>
                            <select name="pv" class="form-select">
                                <?php for ($i = 0; $i < count($pv); $i++) : ?>
                                    <?php if (isset($_SESSION['pv'])) : ?>
                                        <?php if ($pv[$i]->idPointVente == $_SESSION['pv']) : ?>
                                            <option value="<?= $pv[$i]->idPointVente ?>">
                                                <?= $pv[$i]->denomination_pv ?>
                                            </option>
                                        <?php endif  ?>
                                    <?php else : ?>
                                        <option value="<?= $pv[$i]->idPointVente ?>">
                                            <?= $pv[$i]->denomination_pv ?>
                                        </option>
                                    <?php endif ?>

                                <?php endfor; ?>
                            </select>
                        <?php else : ?>
                            <p class="text-danger">Veuillez inserer un   Dépôt s'il vous plaît</p>
                        <?php endif; ?>
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
                        <?php endif  ?>
                    </div>




                    <?php if ($this->session->flashdata('register')) : ?>
                        <script>
                            Myalert.added()
                        </script>
                    <?php endif;  ?>
                </form>







                <h5>Recherche</h5>

                <form action="<?= base_url('Depense/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                    <!-- <div class="row"> -->
                    <div class="group_form row">
                        <label for="date_debut" class="form-label">Date de début : </label>
                        <div class="input-group  mb-3">
                            <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                            <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form row">
                        <label for="date_fin" class="form-label">Date de fin : </label>
                        <div class="input-group  mb-3">
                            <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                            <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form row">
                        <label for="date_fin" class="form-label">  Dépôt ou point de vente : </label>
                        <div class="mb-3">
                            <select name="lieu" type="text" class="form-select" id="the_pv">
                                <option value="0">Lieu</option>
                                <?php foreach ($pv as $key => $pv_) : ?>
                                    <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="group_form row">
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

                <div class="money_container">
                    <div class="argent">
                        <div class="thearg " id="depense" style="background-color: rgb(255, 226, 229)">
                            <label class="form-label"><i class="fas fa-dollar-sign"></i> Dépense </label>
                            <p> <?= (isset($somme)) ? number_three($somme)  : '0 Ar' ?></p>
                        </div>
                    </div>
                </div>
                <div class="_tableau mt-4">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Raison</th>
                                <th>N° BC</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>  Dépôt ou point de vente</th>
                                <th>Editeur</th>
                                <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $depense = $data['depense'] ?>
                            <?php foreach ($depense as $key => $dep) :  ?>
                                <tr>
                                    <td><?= ucfirst($dep->raison) ?></td>
                                    <td><?= ( $dep->cmfacture == '')? '--' : $dep->cmfacture ?></td>
                                    <td><?= number_three($dep->montant) ?></td>
                                    <td><?= form_date(   $dep->datedepense) ?></td>
                                    <td><?= ucfirst($dep->denomination_pv) ?></td>
                                    <td><?= ($dep->prenomUser != '') ? ucfirst($dep->prenomUser) : 'Admin'  ?></td>
                                    <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                        <td>
                                            <button class="btn btn-danger delete" data-id="<?= $dep->iddepense ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-id="<?= $dep->iddepense ?>" data-raison='<?= $dep->raison ?>' data-montant='<?= $dep->montant ?>'>
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach ?>
                            <?php
                            // echo '<pre>' ;
                            // var_dump( $etats ) ; 
                            // echo '</pre>' ; die ; 
                            ?>


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

                                                <form action="<?= base_url('Depense/search/' . ($current - 1)) ?>" method="post">
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
                                                            <label for="date_fin" class="form-label">  Dépôt ou point de vente : </label>
                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <select name="lieu" type="text" class="form-select ui search dropdown" id="the_pv">
                                                                    <option value="0">Lieu ...</option>
                                                                    <?php foreach ($pv as $key => $pv_) : ?>
                                                                        <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                    </button>
                                                </form>

                                            <?php else :  ?>
                                                <a class="page-link" href='<?= base_url('Depense/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Depense/search/' . ($i)) ?>" method="post">
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
                                                            <label for="date_fin" class="form-label">  Dépôt ou point de vente : </label>
                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <select name="lieu" type="text" class="form-select ui search dropdown" id="the_pv">
                                                                    <option value="0">Lieu ...</option>
                                                                    <?php foreach ($pv as $key => $pv_) : ?>
                                                                        <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit">
                                                        <?= $i ?>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Depense/page/' . $i) ?>"><?= $i ?></a>
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
                                                <form action="<?= base_url('Depense/search/' . ($current +  1)) ?>" method="post">
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
                                                            <label for="date_fin" class="form-label">  Dépôt ou point de vente : </label>
                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                            <div class="input-group  mb-3">
                                                                <select name="lieu" type="text" class="form-select ui search dropdown" id="the_pv">
                                                                    <option value="0">Lieu ...</option>
                                                                    <?php foreach ($pv as $key => $pv_) : ?>
                                                                        <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Depense/page/' . ($current + 1)) ?>" aria-label="Next">
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