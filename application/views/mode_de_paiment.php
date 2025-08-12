<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <?php if ($_SESSION['user_type'] == 'admin')  :  ?>
                    <!-- MODAL -->
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Modification Mode de Paiement</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?= base_url('editMode') ?>" id="modepaiement" method="post">
                                        <input type="hidden" name="id" id="modeId">
                                        <div class="mb-3">
                                            <label class="form-label">Désignation  : </label>
                                            <input name="nom" class="form-control input_form-control " type="text" id="nom-mode">
                                            <p class="text-danger d-none" id="msg-nom-mode">Ce Désignation existe déjà</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Numero de compte  : </label>
                                            <input type="text" min="0" name="numero" class="form-control input_form-control " id="num-mode">
                                            <p class="text-danger d-none" id="msg-num-mode">Ce numéro existe déjà</p>
                                        </div>
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-info d-none" id="modification"><i class="fas fa-pen"></i> Modifier</button>
                                            <a href="#" id="modifier" class="btn btn-info"><i class="fas fa-pen"></i> Modifier</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN MODAL -->

                    <h5 class="mb-3"> Mode de Paiement</h5>
                    <form action="<?= base_url('enregistrer-mode') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Désignation  : </label>
                            <input name="nom" class="form-control input_form-control " type="text" id="nom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Numéro de compte  : </label>
                            <input type="text" name="numero" min="0" class="form-control input_form-control " id="num" required>
                        </div>

                        <?php if ($this->session->flashdata('success'))  :  ?>
                            <script>
                                Myalert.added() ; 
                            </script>
                        <?php elseif ($this->session->flashdata('nom'))  :  ?>
                            <script>
                                Myalert.erreur(' Ce mode de paiement est déjà enregistré.'); 
                            </script>
                        <?php elseif ($this->session->flashdata('sup_erreur'))  :  ?>
                            <script>
                                 Myalert.erreur(' Ce mode de paiement est déjà utilisé.');
                            </script>
                        <?php elseif ($this->session->flashdata('sup_success'))  :  ?>
                            <script>
                                 Myalert.deleted();
                            </script>
                        <?php elseif ($this->session->flashdata('updated'))  :  ?>
                            <script>
                                 Myalert.updated();
                            </script>
                        <?php endif; ?>

                        <div class="_boutton">
                            <button type="submit" id="valider" class="btn btn-info"><i class="fas fa-check"></i>  Valider</button>
                        </div>

                    </form>

                <?php endif; ?>

                <div class="_tableau mt-4">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Désignation  </th>
                                <th>Numero de compte</th>
                                <?php if ($_SESSION['user_type'] == 'admin')  :  ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($list))  :  ?>
                                <?php for ($i = 0; $i < count($list); $i++)  :  ?>
                                    <tr>
                                        <td>
                                            <?php echo $list[$i]->denom; ?>
                                        </td>
                                        <td>
                                            <?php echo $list[$i]->numeroCompte; ?>
                                        </td>
                                        <?php if ($_SESSION['user_type'] == 'admin')  :  ?>

                                            <td>
                                                <?php if ($list[$i]->idModePaiement != -1)  :  ?>
                                                    <button class="btn btn-danger delete" data-id="<?php echo  $list[$i]->idModePaiement; ?>" onclick="deleteIt(this)">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button data-id="<?php echo  $list[$i]->idModePaiement; ?>" type="button" id="edit" class="btn btn-warning edit" onclick="donnermode(this)" data-toggle="modal" data-target="#editModal" data-id="<?php echo  $list[$i]->idModePaiement; ?>">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                    <?php $id = array('id' => 'form-' . $list[$i]->idModePaiement, "class" => "delete", 'data-id' => $list[$i]->idModePaiement);
                                                    echo form_open('delete-mode', $id) ?>
                                                    <input type="hidden" name="id" value="<?php echo $list[$i]->idModePaiement; ?>">
                                                    <?php echo form_close() ?>
                                                <?php else  :  ?>
                                                    -
                                                <?php endif; ?>
                                            </td>

                                        <?php endif; ?>
                                    </tr>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if ($nPages > 1)  :  ?>
                        <div class="__pagination">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center pagination-sm">

                                    <li class="page-item">
                                        <?php if ($current == 1)  :  ?>
                                            <span class="page-link __disabled" aria-label="Previous">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-left"></i></small>
                                            </span>
                                        <?php else  :  ?>
                                            <?php if (isset($query))  :  ?>

                                                <form action="<?= base_url('mode/filtre/' . ($current - 1)) ?>" method="post">
                                                    <input name="query" type="text" class="d-none" placeholder="Recherche" value="<?= (isset($query)) ? $query  :  ''; ?>">

                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                    </button>
                                                </form>

                                            <?php else  :  ?>
                                                <a class="page-link" href='<?= base_url('mode/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++)  :  ?>
                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                            <?php if (isset($query))  :  ?>
                                                <form action="<?= base_url('mode/filtre/' . $i) ?>" method="post">
                                                    <input name="query" type="text" class="d-none" placeholder="Recherche" value="<?= (isset($query)) ? $query  :  ''; ?>">

                                                    <button class="page-link" type="submit">
                                                        <?= $i ?>
                                                    </button>
                                                </form>
                                            <?php else  :  ?>
                                                <a class="page-link" href="<?= base_url('mode/page/' . $i) ?>"><?= $i ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endfor; ?>

                                    <li>
                                        <?php if ($current == $nPages)  :  ?>
                                            <span class="page-link __disabled" aria-label="Next">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                                            </span>
                                        <?php else  :  ?>
                                            <?php if (isset($query))  :  ?>
                                                <form action="<?= base_url('mode/filtre/' . ($current + 1)) ?>" method="post">
                                                    <input name="query" type="text" class="d-none" placeholder="Recherche" value="<?= (isset($query)) ? $query  :  ''; ?>">

                                                    <button class="page-link" type="submit" aria-label="Next">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                    </button>
                                                </form>
                                            <?php else  :  ?>
                                                <a class="page-link" href="<?= base_url('mode/page/' . ($current + 1)) ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>