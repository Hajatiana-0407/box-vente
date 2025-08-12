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
                                <form action="<?= base_url('editClient') ?>" id="modifClient" method="post">
                                    <input type="hidden" name="id_modif" id="idClient_modif">
                                    <div class="mb-1">
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

                                    <div class="mb-1">
                                        <label class="form-label">Email:</label>
                                        <input name="email_modif" id="email_modif" type="tel" class="form-control input_form-control" >
                                        <p class="text-danger d-none" id="msg-mail-modif">Cet email existe déjà</p>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">NIF :</label>
                                        <input name="nif_modif" id="nif_modif" type="tel" class="form-control input_form-control" >
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">STAT :</label>
                                        <input name="stat_modif" id="stat_modif" type="tel" class="form-control input_form-control" >
                                    </div>

                                    <div class="mt-2">
                                        <button type="button" class="btn btn-info d-none" id="modification"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                        <button type="button" class="btn btn-info" id="modifier"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FIN MODAL -->
                <h5 class="mb-3">Ajouter des clients</h5>
                <form action="<?= base_url('registerClient') ?>" method="post">
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
                        <input name="adress" id="adresse" type="text" class="form-control input_form-control " required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email :</label>
                        <input name="email" id="email" type="text" class="form-control input_form-control " >
                    </div>

                    <div class="mb-2">
                        <label class="form-label">NIF :</label>
                        <input name="nif" id="nif" type="text" class="form-control input_form-control " >
                    </div>

                    <div class="mb-2">
                        <label class="form-label">STAT :</label>
                        <input name="stat" id="stat" type="text" class="form-control input_form-control " >
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Numéro Téléphone :</label>
                        <input name="numero" id="numero" type="tel" class="form-control input_form-control" required>
                    </div>
                    <div class="_boutton">
                        <button type="button" class="btn btn-info d-none" id="valider"><i class="fas fa-check"></i> Valider</button>
                        <a href="#" id="validation" class="btn btn-info"><i class="fas fa-check"></i> Valider</a>
                    </div>

                    <?php if ($this->session->flashdata('success')) : ?>
                        <script>
            Myalert.added()       
</script>
                    <?php elseif ($this->session->flashdata('edit')) : ?>
                        <script>
            Myalert.updated()       
</script>
                    <?php elseif ($this->session->flashdata('num')) : ?>
                        <div class="volet"></div>
                        <div class="_alert">
                            <div class="close" id="close">
                                <i class="fa-solid fa-x"></i>
                            </div>
                            <div class="_icon-warning">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                            <div class="_message">
                                <p>Ce Numéro existe déjà</p>
                            </div>
                            <div class="_btn">
                                <button type="button" class="button-war" id="button">OK</button>
                            </div>
                        </div>
                    <?php elseif ($this->session->flashdata('mail')) : ?>
                        <div class="volet"></div>
                        <div class="_alert">
                            <div class="close" id="close">
                                <i class="fa-solid fa-x"></i>
                            </div>
                            <div class="_icon-warning">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                            <div class="_message">
                                <p>Cet email existe déjà</p>
                            </div>
                            <div class="_btn">
                                <button type="button" class="button-war" id="button">OK</button>
                            </div>
                        </div>
                    <?php elseif ($this->session->flashdata('delete')) : ?>
                        <script>
            Myalert.deleted()       
</script>
                    <?php endif; ?>
                </form>

                <form action="<?= base_url('rechercheClient') ?>" method="GET">
                    <div class="input-group mt-3 mb-3">
                        <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $post ?>">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                        <button class="btn btn-info" type="button" disabled >
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
                                <th>Numéro Télephone</th>
                                <th>Email</th>
                                <th>Nif</th>
                                <th>Stat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="search-results">

                            <?php

                            // echo '<pre>' ; 

                            // var_dump( $data['client'] ) ; 
                            
                            // echo '</pre>'  ; 

                            // die ; 
                            $clients = $data['client'];
                            for ($i = 0; $i < count($clients); $i++) : ?>

                                <tr>
                                    <td>
                                        <?= $clients[$i]->nomClient ?>
                                    </td>

                                    <td>
                                        <?= $clients[$i]->prenomClient ?>
                                    </td>

                                    <td>
                                        <?= $clients[$i]->adresseClient ?>
                                    </td>

                                    <td>
                                        <?= $clients[$i]->telClient ?>
                                    </td>

                                    <td>
                                        <?=  ($clients[$i]->emailClient == '' )? '...' : $clients[$i]->emailClient ?>
                                    </td>

                                    <td>
                                        <?=  ($clients[$i]->nif == '' )? '...' : $clients[$i]->nif ?>
                                    </td>

                                    <td>
                                        <?=  ($clients[$i]->stat == '' )? '...' : $clients[$i]->stat ?>
                                    </td>

                                    <td>
                                        <button class="btn btn-danger delete" type="button" onclick="deleteIt(this)" data-id="<?= $clients[$i]->idClient; ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        <button type="button" id="edit" class="btn btn-warning edit" onclick="Donneclient(this)" data-toggle="modal" data-target="#editModal" data-id="<?= $clients[$i]->idClient; ?>">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
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