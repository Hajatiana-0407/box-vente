<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

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
                                <form action="<?= base_url('Dashtuto/modifier') ?>" id="modifClient" method="post" enctype="multipart/form-data">
                                    <div class="pb-2">
                                        <label for="titre" class="form-label">Titre : </label>
                                        <textarea class="form-control textarea-2-lines" id="titre_modif" placeholder="Entrez le titre " name="titre" required></textarea>
                                    </div>
                                    <div class="pb-2">
                                        <label for="titre" class="form-label">Tuto N° : </label>
                                        <input class="form-control " id="numero_modif" placeholder="N° " name="numero" required>
                                        <input class="form-control d-none" id="id_modif" placeholder="N° " name="id" required>
                                    </div>
                                    <div class="pb-2">
                                        <label for="titre" class="form-label">Tuto N° : </label>
                                        <select name="langue" id="langue_modif" class="form-select"></select>
                                    </div>
                                    <div class="pb-2">
                                        <label for="video" class="form-label">Video : </label>
                                        <input type="text" placeholder="Iframe" class="form-control" name="video">
                                    </div>
                                    <div class="_boutton">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> Modifer </button>
                                    </div>
                                </form>
                                <div id="image_video">
                                    <div class="division2 d-flex justify-content-center align-items-center pt-4">
                                        <div class="_image">
                                            <video id="video_modif" controls></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN MODAL -->

                <div id="formulaire_video">
                    <form action="<?= base_url('Dashtuto/register') ?>" method="post" id="form_video" enctype="multipart/form-data">
                        <h5>Ajouter</h5>
                        <div class="pb-2">
                            <label for="titre" class="form-label">Titre : </label>
                            <textarea class="form-control textarea-2-lines" id="titre" placeholder="Entrez le titre " name="titre" required></textarea>
                        </div>
                        <div class="pb-2">
                            <label for="langue" class="form-label">Langue : </label>
                            <select name="langue" id="langue" class="form-select">
                                <option value="1">Malagasy</option>
                                <option value="2">Français</option>
                            </select>
                        </div>
                        <div class="pb-2">
                            <label for="video" class="form-label">Video : </label>
                            <input id="the_video" type="text" class="form-control" name="video" required placeholder="Iframe">
                        </div>
                        <div class="_boutton">

                            <button type="submit" class="btn btn-info" id="valider">
                                <i class="fas fa-check"></i>
                                <div class="spinner-wrapper d-none" id="spinner_validation">
                                    <div class="spinner-border"></div>
                                </div>
                                Valider
                            </button>
                        </div>
                    </form>
                    <div id="image_video ">
                        <div class="division2 d-flex justify-content-left align-items-center pt-4">
                            <div class="_image">
                                <video id="video" controls></video>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="titre">
                    <h5 class="">Tutoriels</h5>
                    <div class="recherche">
                        <form action="<?= base_url('Dashtuto/search') ?>" method="post">
                            <div class="input-group mt-4 mb-3">
                                <input name="query" type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? "" ?>">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($this->session->flashdata('success')) : ?>
                    <script>
                        Myalert.added()
                    </script>
                <?php endif  ?>
                <?php if ($this->session->flashdata('deleted')) : ?>
                    <script>
                        Myalert.deleted()
                    </script>
                <?php endif  ?>
                <?php if ($this->session->flashdata('update')) : ?>
                    <script>
                        Myalert.updated()
                    </script>
                <?php endif  ?>



                <ul class="video-list">
                    <?php if (isset($tutos)) : ?>
                        <?php foreach ($tutos as $key => $tuto) : ?>
                            <li class="video-item">
                                <div class="video_container">
                                    <?= $tuto->video ?>
                                </div>
                                <div class="video-info">
                                    <div class="video-title">
                                        <?= $tuto->titre ?>
                                    </div>
                                    <div class="video-description">Tuto N° : <?= $tuto->numero ?> </div>
                                    <div class="video-language">Langue : <?= $tuto->langue ?></div>
                                </div>
                                <div class="video-controls">
                                    <button class="btn btn-outline-primary btn-custom editer" data-toggle="modal" data-target="#editModal" title="Modifier" data-title="<?= $tuto->titre ?>" data-num="<?= $tuto->numero ?>" data-langue="<?= $tuto->langue ?>" data-id='<?= $tuto->idtuto ?>'><i class="fas fa-edit"></i></button>

                                    <button class="btn btn-outline-danger btn-custom supprimer" data-id='<?= $tuto->idtuto ?>' title="Supprimer"><i class="fas fa-trash"></i></button>
                                </div>
                            </li>
                        <?php endforeach ?>
                        <?php if (count($tutos) == 0) : ?>
                            <div class="no-videos text-center p-4 ">
                                Aucune vidéo trouvée.
                            </div>
                        <?php endif ?>
                    <?php else : ?>
                        <div class="no-videos text-center p-4 ">
                            Aucune vidéo trouvée.
                        </div>
                    <?php endif ?>
                </ul>


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
                                        <?php if (isset($_POST['query'])) :  ?>

                                            <form action="<?= base_url('Dashtuto/search/' . ($current - 1)) ?>" method="post">
                                                <input type="hidden" name="query" value="<?= $_POST['query'] ?? "" ?>">
                                                <button class="page-link" type="submit" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </button>
                                            </form>

                                        <?php else :  ?>
                                            <a class="page-link" href='<?= base_url('Dashtuto/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </li>

                                <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                    <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                        <?php if (isset($_POST['query'])) :  ?>
                                            <form action="<?= base_url('Dashtuto/search/' . ($i)) ?>" method="post">
                                                <input type="hidden" name="query" value="<?= $_POST['query'] ?? "" ?>">

                                                <button class="page-link" type="submit">
                                                    <?= $i ?>
                                                </button>
                                            </form>
                                        <?php else :  ?>
                                            <a class="page-link" href="<?= base_url('Dashtuto/page/' . $i) ?>"><?= $i ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php endfor; ?>

                                <li>
                                    <?php if ($current == $nPages) :  ?>
                                        <span class="page-link __disabled" aria-label="Next">
                                            <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                                        </span>
                                    <?php else :  ?>
                                        <?php if (isset($_POST['query'])) :  ?>
                                            <form action="<?= base_url('Dashtuto/search/' . ($current +  1)) ?>" method="post">
                                                <input type="hidden" name="query" value="<?= $_POST['query'] ?? "" ?>">

                                                <button class="page-link" type="submit" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                </button>
                                            </form>
                                        <?php else :  ?>
                                            <a class="page-link" href="<?= base_url('Dashtuto/page/' . ($current + 1)) ?>" aria-label="Next">
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