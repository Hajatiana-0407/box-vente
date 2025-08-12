<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <!-- MODAL -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification de l'Utilisateur</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('Fournisseur/edit') ?> " method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Nom de l'entreprise :</label>
                                        <input class="form-control input_form-control" type="text" id="entreprise_modif" name="entreprise" required>
                                        <input class="form-control input_form-control d-none" type="text" id="idmodif" name="idfournisseur" required>
                                        <p class="d-none text-danger" id="entreprise_erreur_modif"> Cette entreprise est déjà enregistrée.</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Adresse :</label>
                                        <input class="form-control input_form-control" type="text" id="adresse_modif" name="adresse" placeholder="Adresse" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tél :</label>
                                        <input class="form-control input_form-control" type="text" placeholder="Numero" id="telephone_modif" name="telephone" required>
                                        <p class="d-none text-danger" id="telephone_erreur_modif">Ce téléphone existe déjà.</p>

                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email :</label>
                                        <input class="form-control input_form-control" type="email" placeholder="exemple@gmail.com" id="email_modif" name="email" >
                                        <p class="d-none text-danger" id="email_erreur_modif">Ce mail existe déjà.</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">NIF :</label>
                                        <input class="form-control input_form-control" type="text" placeholder="Nif" name="nif" id="nif_modif" >
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">STAT :</label>
                                        <input class="form-control input_form-control" type="text" placeholder="stat" id="stat_modif" name="stat" >
                                    </div>
                                    <div class="_boutton mt-4 mb-4">
                                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                            <button type="submit" class="btn btn-info" id="modification"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                        <?php else : ?>
                                            <button type="button" class="btn btn-info" id="modification_teste"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                            <button type="submit" class="btn btn-info d-none" id="modification"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                        <?php endif  ?>
                                    </div>
                                    <?php if ($this->session->flashdata('edited')) :  ?>
                                        <script>
                                            Myalert.updated();
                                        </script>
                                    <?php endif  ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN MODAL -->
                <h5>Fournisseur</h5>
                <form action="<?= base_url('Fournisseur/register') ?> " method="post">
                    <div class="mb-3">
                        <label class="form-label">Nom de l'entreprise :</label>
                        <input class="form-control input_form-control" type="text" id="entreprise" name="entreprise" required>
                        <p class="d-none text-danger" id="entreprise_erreur"> Cette entreprise est déjà enregistrée.</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse :</label>
                        <input class="form-control input_form-control" type="text" id="adresse" name="adresse" placeholder="Adresse" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tél :</label>
                        <input class="form-control input_form-control" type="text" placeholder="Numero" id="telephone" name="telephone" required>
                        <p class="d-none text-danger" id="telephone_erreur">Ce téléphone existe déjà.</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email :</label>
                        <input class="form-control input_form-control" type="email" placeholder="exemple@gmail.com" id="email" name="email">
                        <p class="d-none text-danger" id="email_erreur">Ce mail existe déjà.</p>
                        
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIF :</label>
                        <input class="form-control input_form-control" type="text" placeholder="Nif" name="nif" id="nif">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">STAT :</label>
                        <input class="form-control input_form-control" type="text" placeholder="stat" id="stat" name="stat">
                    </div>
                    <div class="_boutton mt-4 mb-4">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <button type="button" class="btn btn-info" disabled>
                                <i class="fas fa-check"></i>
                                Valider
                            </button>
                        <?php else : ?>
                            <button type="button" class="btn btn-info" id="teste_validation"><i class="fas fa-check"></i> Valider</button>
                            <button type="submit" class="btn btn-info d-none" id="valider">
                                <i class="fas fa-check"></i>
                                <div class="spinner-wrapper d-none" id="spinner_validation">
                                    <div class="spinner-border"></div>
                                </div>
                                Valider
                            </button>
                        <?php endif  ?>
                    </div>
                    <?php if ($this->session->flashdata('is_used')) :  ?>
                        <script>
                            Myalert.erreur('Vous ne pouvez pas supprimer un fournisseur déjà utilisé.');
                        </script>
                    <?php endif  ?>
                    <?php if ($this->session->flashdata('added')) :  ?>
                        <script>
                            Myalert.added();
                        </script>
                    <?php endif  ?>

                    <?php if ($this->session->userdata('isdelete')) :  ?>
                        <script>
                            Myalert.deleted();
                        </script>
                    <?php endif  ?>
                    <?php $this->session->unset_userdata('isdelete') ?>

                </form>




                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="get">
                    <?php else : ?>
                        <form action="<?= base_url('Fournisseur/search') ?>" method="get">
                        <?php endif  ?>
                        <div class="input-group mt-4 mb-3">
                            <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                <button class="btn btn-info" type="button" disabled>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            <?php else : ?>
                                <button class="btn btn-info" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                        </form>

                        <div class="_tableau mt-4">
                            <table class="table table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Nom de l'entreprise</th>
                                        <th>Adresse</th>
                                        <th>Tél</th>
                                        <th>Email</th>
                                        <th>NIF </th>
                                        <th>STAT</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="search-results">
                                    <?php foreach ($datas as $key => $data):  ?>
                                        <tr>
                                            <td><?= $data->nom_entr ?></td>
                                            <td><?= $data->adresse_fournisseur ?></td>
                                            <td><?= $data->tel_fournisseur ?></td>
                                            <td><?= ($data->mail_fournisseur == '') ? '--' : $data->mail_fournisseur  ?></td>
                                            <td><?= ($data->nif_fournisseur  == '') ? '--' : $data->nif_fournisseur   ?></td>
                                            <td><?= ($data->stat_fournisseur == '') ? '--' : $data->stat_fournisseur  ?></td>
                                            <td>
                                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                    <button class="btn btn-danger" type="button" disabled>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-warning " disabled>
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <button class="btn btn-danger delete" type="button" data-idfournisseur="<?= $data->idfournisseur ?>" data-tel_fournisseur="<?= $data->tel_fournisseur ?>">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-idfournisseur="<?= $data->idfournisseur ?>" data-nom='<?= $data->nom_entr ?>' data-adress='<?= $data->adresse_fournisseur ?>' data-tel='<?= $data->tel_fournisseur ?>' data-mail='<?= $data->mail_fournisseur ?>' data-nif='<?= $data->nif_fournisseur ?>' data-stat='<?= $data->stat_fournisseur ?>'>
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach  ?>
                                </tbody>
                            </table>
                            <p class="pagination pagination-sm"><?= $lien; ?></p>
                        </div>


            </div>
        </div>
    </div>
</div>