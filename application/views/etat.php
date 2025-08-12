<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <h5>Etat</h5>
                <form action="<?= base_url('Etat/search') ?>" method="post" class="mb-4 " id="searchform">
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
                        <label for="type" class="form-label">Type : </label>
                        <div class="mb-3">
                            <select name="type" id="type" class="form-select">
                                <?php if (!isset($_POST['type']) || $_POST['type'] == 'tout') : ?>
                                    <option value="tout">Tout</option>
                                    <option value="entre">Entrée</option>
                                    <option value="sortie">Sortie</option>
                                <?php else : ?>
                                    <?php if ($_POST['type'] == 'sortie') : ?>
                                        <option value="sortie">Sortie</option>
                                        <option value="entre">Entrée</option>
                                    <?php else : ?>
                                        <option value="entre">Entrée</option>
                                        <option value="sortie">Sortie</option>
                                    <?php endif  ?>
                                    <option value="tout">Tout</option>
                                <?php endif  ?>
                            </select>
                        </div>
                    </div>
                    <div class="group_form">
                        <label for="date_fin" class="form-label">  Dépôt ou point de vente : </label>
                        <div class="mb-3">
                            <select name="lieu" type="text" class="form-select " id="the_pv">
                                <option value="0">Lieu</option>
                                <?php foreach ($pv as $key => $pv_) : ?>
                                    <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                <?php endforeach ?>
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
                </form>




                <div class="row">
                    <div class="col-lg-6 col-md-4 col-sm-2 col-xm-0"></div>
                    <div class="col-lg-6 col-md-8 col-sm-10 col-xm-12 argent">
                        <?php if (!isset($_POST['type']) || $_POST['type'] == 'tout') : ?>
                            <div class="thearg " id="solde" style="background-color : rgb(220, 231, 252) ">
                                <label class="form-label"><i class="fas fa-tags"></i> Solde </label>
                                <p> <?= (isset($somme["entrant"]) && isset($somme["sortant"])) ?  number_three($somme["entrant"] -  $somme["sortant"])  : '0 Ar' ?></p>
                            </div>
                        <?php endif  ?>
                        <?php if (!isset($_POST['type']) || $_POST['type'] != 'sortie') : ?>
                            <div class="thearg " id="entrent" style="background-color : rgb(220, 252, 231) ">
                                <label class="form-label"><i class="fas fa-shopping-cart"></i> Entrée </label>
                                <p> <?= (isset($somme["entrant"])) ?  number_three($somme["entrant"])  : '0 Ar' ?></p>
                            </div>
                        <?php endif  ?>
                        <?php if (!isset($_POST['type']) || $_POST['type'] != 'entre') : ?>
                            <div class="thearg " id="depense" style="background-color: rgb(255, 226, 229)">
                                <label class="form-label"><i class="fas fa-hand-holding-usd"></i> Sortie </label>
                                <p> <?= (isset($somme["sortant"])) ? number_three($somme["sortant"])  : '0 Ar' ?></p>
                            </div>
                        <?php endif  ?>
                    </div>
                </div>
                <div class="_tableau mt-4">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Intitulé</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>N° Facture</th>
                                <th>N° BC</th>
                                <th>Type</th>
                                <th>  Dépôt ou point de vente</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $etats = $data['etat'] ?>
                            <?php foreach ($etats as $key => $etat) :  ?>
                                <tr>
                                    <td><?= (isset($etat->Facture)) ? 'Vente'  : ucfirst($etat->raison) ?> </td>
                                    <td><?= number_three($etat->montant) ?> </td>
                                    <td><?= form_date($etat->date)  ?> </td>
                                    <td><?= (isset($etat->Facture)) ? $etat->Facture : '--' ?> </td>
                                    <td><?= (isset($etat->cmfacture)) ? $etat->cmfacture : '--' ?> </td>
                                    <td><?= (isset($etat->Facture)) ? 'Entrée' : 'Sortie' ?> </td>
                                    <td><?= ucfirst($etat->denomination_pv) ?> </td>
                                </tr>
                            <?php endforeach ?>
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

                                                <form action="<?= base_url('Etat/search/' . ($current - 1)) ?>" method="post">
                                                    <div class="row d-none">

                                                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">

                                                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">

                                                        <input type="text" name="type" value="<?= $_POST['type'] ?? '' ?>">

                                                        <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">

                                                    </div>

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                    </button>
                                                </form>

                                            <?php else :  ?>
                                                <a class="page-link" href='<?= base_url('Etat/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Etat/search/' . ($i)) ?>" method="post">
                                                    <div class="row d-none">

                                                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">

                                                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">

                                                        <input type="text" name="type" value="<?= $_POST['type'] ?? '' ?>">

                                                        <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">

                                                    </div>

                                                    <button class="page-link" type="submit">
                                                        <?= $i ?>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Etat/page/' . $i) ?>"><?= $i ?></a>
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
                                                <form action="<?= base_url('Etat/search/' . ($current +  1)) ?>" method="post">
                                                    <div class="row d-none">

                                                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">

                                                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">

                                                        <input type="text" name="type" value="<?= $_POST['type'] ?? '' ?>">

                                                        <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">

                                                    </div>

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Etat/page/' . ($current + 1)) ?>" aria-label="Next">
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