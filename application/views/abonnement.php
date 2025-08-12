<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <!-- MODAL -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('Abonnement/update') ?>" method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Contact :</label>
                                        <input type="text" class="form-control " id="contact_mod" name="contact" require>
                                        <input type="hidden" class="form-control " id="id_mod" name="id" require>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nom :</label>
                                        <input type="text" min="0" class="form-control " id="nom_mod" name="nom" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Date de début :</label>
                                        <input type="date" class="form-control " id="date_mod" name="date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Durée en mois :</label>
                                        <input type="number" min="0" class="form-control " id="dure_mod" name="dure" required>
                                    </div>

                                    <div class="mb-3 _boutton">
                                        <button type="submit" class="btn btn-info" id="valider">
                                            <i class="fas fa-pencil-alt"></i>
                                            Modifier
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



                <h5>Abonnement </h5>

                <form action="<?= base_url('Abonnement/register') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Contact :</label>
                        <input type="text" class="form-control " id="contact" name="contact" require>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom :</label>
                        <input type="text" min="0" class="form-control " id="nom" name="nom" readonly required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date de début :</label>
                        <input type="date" class="form-control " id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durée en mois :</label>
                        <input type="number" min="0" class="form-control " id="dure" name="dure" required>
                    </div>

                    <div class="mb-3 _boutton">
                        <button type="submit" class="btn btn-info" id="valider">
                            <i class="fas fa-check"></i>
                            <div class="spinner-wrapper d-none" id="spinner_validation">
                                <div class="spinner-border"></div>
                            </div>
                            Valider
                        </button>
                    </div>
                </form>

                <?php if ($this->session->flashdata('success')) :  ?>
                    <script>
                        Myalert.added();
                    </script>
                <?php endif ?>
                <?php if ($this->session->flashdata('deleted')) :  ?>
                    <script>
                        Myalert.deleted();
                    </script>
                <?php endif ?>
                <?php if ($this->session->flashdata('updated')) :  ?>
                    <script>
                        Myalert.updated();
                    </script>
                <?php endif ?>

                <h5>Liste </h5>

                <form action="<?= base_url('Abonnement/search') ?>" method="post" class="mb-4 mt-4" id="searchform">

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
                        <label for="date_fin" class="form-label">Recherche : </label>
                        <div class=" mb-3">
                            <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form btn_recherche">

                        <button class="btn btn-info" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                        </button>

                    </div>
                    <!-- </div> -->
                </form>



                <div class="_tableau mt-4">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Contact</th>
                                <th>Nom</th>
                                <th>Date de début</th>
                                <th>Durée</th>
                                <th>Abonnement</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datas as $key => $data) :  ?>
                                <tr>
                                    <td><?= $data->contact ?></td>
                                    <td><?= ($data->nom != '') ? ucfirst($data->nom) : '--' ?></td>
                                    <td><?= only_date($data->date_debut)  ?></td>
                                    <td><?= $data->dure ?></td>
                                    <td>
                                        <?php
                                        $date = new DateTime($data->date_debut);

                                        date_add($date, date_interval_create_from_date_string('14 days'));

                                        if ($date->format('Y-m-d') >= date('Y-m-d')) : ?>
                                            <p class="bg-success p-1 text-light">En cours...</p>

                                        <?php else : ?>
                                            <p class="bg-danger p-1 text-light">Terminé</p>
                                        <?php endif ?>

                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-danger delete" data-id="<?= $data->idabonnement ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-contact="<?= $data->contact ?>" data-id="<?= $data->idabonnement ?>" data-nom='<?= $data->nom ?>' data-date_debut='<?= $data->date_debut ?>' data-dure='<?= $data->dure ?> '>
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach   ?>
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

                                                <form action="<?= base_url('Abonnement/search/' . ($current - 1)) ?>" method="post">
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
                                                <a class="page-link" href='<?= base_url('Abonnement/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Abonnement/search/' . ($i)) ?>" method="post">
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
                                                <a class="page-link" href="<?= base_url('Abonnement/page/' . $i) ?>"><?= $i ?></a>
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
                                                <form action="<?= base_url('Abonnement/search/' . ($current +  1)) ?>" method="post">
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
                                                <a class="page-link" href="<?= base_url('Abonnement/page/' . ($current + 1)) ?>" aria-label="Next">
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