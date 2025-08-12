<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <div class="modal fade" id="showDetails" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title vente">Détails</h4>
                                <button type="button" id="btn-fermer" class="btn " data-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="modal-body" id="tab">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="showDetails" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Détails</h5>
                            </div>
                            <div class="modal-body" id="tab">

                            </div>
                        </div>
                    </div>
                </div>

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
                                    <div class="">
                                        <div id="loaderFacture" style="display:flex; align-items:center; justify-content: center; width:100%; height:500px;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <iframe src="" id="pdfFrame" style="width:100%; height:500px;" frameborder="0"></iframe>
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
                <button id="affichefacture" class="btn  btn-info d-none" data-bs-toggle="modal" data-bs-target="#modalfacture">Afficher la facture</button>

                <div class="onglet">
                    <a href="<?= base_url('appro') ?>" class="onglet_btn "> Approvisionnement </a>
                    <a href="<?= base_url('commande') ?>" id="panier_" class="onglet_btn"> Commande</a>
                    <a href="#" class="onglet_btn active">Liste des commandes </a>
                    <a href="<?= base_url('reception') ?>" class="onglet_btn">Reception de commande </a>
                </div>
                <form action="<?= base_url('Listecommande/search') ?>" method="post" class="mb-4 " id="searchform">
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
                                <th>N° de commande </th>
                                <th>Date de commande</th>
                                <th>Montant total</th>
                                <th>Fournisseur</th>
                                <th>Contact fournisseur</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="search-results">

                            <?php for ($i = 0; $i < count($data); $i++) : ?>
                                <tr>
                                    <td><?= $data[$i]->cmfacture ?></td>
                                    <td><?= form_date($data[$i]->datecommande)  ?></td>
                                    <td><?= ($data[$i]->montant_total != '') ? number_three($data[$i]->montant_total + $data[$i]->frais) : '0 Ar' ?></td>
                                    <td><?= ($data[$i]->nom_entr == '') ? '--' :  strtoupper($data[$i]->nom_entr)  ?></td>
                                    <td><?= ($data[$i]->tel_fournisseur == '') ? '...' :  $data[$i]->tel_fournisseur  ?></td>

                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#showDetails" class="btn btn-secondary detail" data-idcommande="<?= $data[$i]->idcommande ?>" data-num="<?= $data[$i]->tel_fournisseur ?>"><i class="fas fa-list"></i></a>

                                        <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                            <a href="#" data-idcommande='<?= $data[$i]->idcommande ?>' class="btn btn-danger supprimer"><i class="fa-solid fa-trash"></i></a>
                                        <?php endif; ?>

                                        <!-- <a  class="btn btn-primary imprim">Imprimer</a> -->
                                        <button type="button" data-idcommande='<?= $data[$i]->idcommande ?>' data-cmfacture='<?= $data[$i]->cmfacture ?>' class="btn btn-primary imprim"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>

                    <?php if ($nPages && $nPages > 1) :  ?>
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

                                                <form action="<?= base_url('Listecommande/search/' . ($current - 1)) ?>" method="post">
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
                                                <a class="page-link" href='<?= base_url('Listecommande/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Listecommande/search/' . ($i)) ?>" method="post">
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
                                                <a class="page-link" href="<?= base_url('Listecommande/page/' . $i) ?>"><?= $i ?></a>
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
                                                <form action="<?= base_url('Listecommande/search/' . ($current +  1)) ?>" method="post">
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
                                                <a class="page-link" href="<?= base_url('Listecommande/page/' . ($current + 1)) ?>" aria-label="Next">
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