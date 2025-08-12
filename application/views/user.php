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
                                <form action="<?= base_url('editUser') ?>" id="modifClient" method="post">
                                    <div class="mb-1">
                                        <input type="hidden" name="id_modif" id="id-User">
                                        <label class="form-label">Nom :</label>
                                        <input type="text" id="nom_modif" class="form-control" name="nom_modif" require>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label">Prénom :</label>
                                        <input name="prenom_modif" id="prenom_modif" type="text" class="form-control input_form-control" require>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">Adresse :</label>
                                        <input name="adresse_modif" id="adresse_modif" type="text" class="form-control input_form-control" require>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">Numéro Télephone:</label>
                                        <input name="numero_modif" id="numero_modif" type="tel" class="form-control input_form-control" required>
                                        <p class="text-danger d-none" id="msg-num-modif">Ce numéro existe déjà</p>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Fonction de l'Utilisateur :</label>
                                        <input name="type_modif" id="type" type="text" class="form-control input_form-control " >
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label"> Dépôt ou point de vente :</label>
                                        <select class="form-select" id="pvModif" name="idPv_modif">
                                            
                                        </select>

                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">Email:</label>
                                        <input name="email_modif" id="email_modif" type="email" class="form-control input_form-control" required>
                                        <p class="text-danger d-none" id="msg-mail-modif">Cet email existe déjà</p>
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-info d-none" id="modification"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                        <a href="#" id="modifier" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Modifier</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN MODAL -->

                <h5 class="mb-3">Ajout d'utilisateur</h5>
                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('registerUser') ?>" method="post">
                        <?php endif; ?>
                        <div class="mb-2">
                            <label class="form-label">Nom :</label>
                            <input name="nom" id="nom" type="text" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Prénom :</label>
                            <input name="prenom" id="prenom" type="text" class="form-control input_form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Adresse :</label>
                            <input name="adresse" id="adresse" type="text" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Fonction de l'Utilisateur :</label>
                            <input name="typeUser" id="type" type="text" class="form-control input_form-control " >
                        </div>
                        <div class="mb-2">
                            <label class="form-label"> Dépôt ou point de vente :</label>
                            <?php if (count($pv) > 0) : ?>
                                <select class="form-select" id="pvModif" name="pv">
                                    <?php for ($i = 0; $i < count($pv); $i++) : ?>
                                        <option class="pv" value="<?= $pv[$i]->idPointVente  ?>">
                                            <?= $pv[$i]->denomination_pv ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            <?php else : ?>
                                <p class="text-danger">Veuillez inserer un Dépôt s'il vous plaît</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email :</label>
                            <input name="email" id="email" type="email" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Numéro Téléphone :</label>
                            <input name="numero" id="numero" type="tel" class="form-control input_form-control" required>
                            <p class="text-danger d-none" id="msg-numero">Ce numéro existe déjà</p>
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

                        <?php if ($this->session->flashdata('ajout')) : ?>
                            <script>
                                Myalert.added()
                            </script>
                        <?php elseif ($this->session->flashdata('edit')) : ?>
                            <script>
                                Myalert.updated()
                            </script>
                        <?php elseif ($this->session->flashdata('num')) : ?>
                            <script>
                                Myalert.erreur('Ce Numéro existe déjà')
                            </script>
                        <?php elseif ($this->session->flashdata('mail')) : ?>
                            <script>
                                Myalert.erreur('Ce mail est déjà utilisé par un autre utilisateur.')
                            </script>
                        <?php elseif ($this->session->flashdata('adrres')) : ?>
                            <script>
                                Myalert.updated('Veuillez inseret un   Dépôt ou point de vente')
                            </script>
                        <?php elseif ($this->session->flashdata('delete')) : ?>
                            <script>
                                Myalert.deleted()
                            </script>
                        <?php elseif ($this->session->flashdata('effectuer')) : ?>
                            <script>
                                Myalert.deleted('Réinitialisation Réussi')
                            </script>
                        <?php endif; ?>
                    </form>
                    <?php if ($this->session->userdata('reinitialiser')) : ?>
                        <script>
                            Myalert.deleted('Réinitialisation Réussi')
                        </script>
                    <?php endif; ?>
                    <?php $this->session->unset_userdata('reinitialiser') ?>

                        <form action="<?= base_url('rechercheUser'); ?>" method="get">
                            <div class="input-group mt-3 mb-3">
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
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Adresse</th>
                                        <th>Fonction</th>
                                        <th>Email</th>
                                        <th> Dépôt ou point de vente</th>
                                        <th>Numéro Télephone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php
                                    $user = $data['user'];
                                    for ($i = 0; $i < count($user); $i++) : ?>
                                        <tr>
                                            <td><?= $user[$i]->nomUser ?></td>

                                            <td><?= $user[$i]->prenomUser ?></td>

                                            <td><?= $user[$i]->adress ?></td>

                                            <td><?= $user[$i]->typeUser ?></td>

                                            <td><?= $user[$i]->mail ?></td>

                                            <td><?= $user[$i]->denomination_pv ?></td>

                                            <td><?= $user[$i]->contact ?></td>

                                            <td>

                                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                    <button class="btn btn-danger" type="button" disabled>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-warning " disabled>
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-primary" disabled>
                                                        <i class="fa-solid fa-rotate-left"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <button class="btn btn-danger delete" type="button" onclick="deleteIt(this)" data-id="<?= $user[$i]->idUser ?>">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" id="edit" class="btn btn-warning edit" onclick="DonnerUser(this)" data-toggle="modal" data-target="#editModal" data-id="<?= $user[$i]->idUser ?>">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>

                                                    <button type="button" id="reinitialize" class="btn btn-primary edit" onclick="reinitialize(this)" data-id="<?= $user[$i]->idUser ?>">
                                                        <i class="fa-solid fa-rotate-left"></i>
                                                    </button>
                                                <?php endif; ?>

                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                            <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
                        </div>
            </div>
        </div>
    </div>
</div>