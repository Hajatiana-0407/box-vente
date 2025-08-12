<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">









                <!-- <div class="container mt-5">
                    <label for="languageSelect" class="form-label">Language</label>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="languageSelect" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://flagpedia.net/data/flags/h80/mg.png" alt="Current Language" class="dropdown-icon"> Malgache
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="languageSelect">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <img src="https://flagpedia.net/data/flags/h80/mg.png" alt="Malgache"> Malgache
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <img src="https://flagpedia.net/data/flags/h80/fr.png" alt="Français"> Français
                                </a>
                            </li>
                        </ul>
                    </div>
                </div> -->



                <h5 class="">Tutoriels</h5>

                <div class="titre">
                    <div class="dropdown">
                        <button type="button" class="btn  dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="https://flagpedia.net/data/flags/h80/fr.png" alt="Current Language" class="dropdown-icon">
                            Français
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item " href="<?= base_url('emploiMl') ?>"><img src="https://flagpedia.net/data/flags/h80/mg.png" alt="Current Language" class="dropdown-icon">
                                    Malagasy</a>
                            </li>
                            <li>
                                <a class="dropdown-item active" href="#"><img src="https://flagpedia.net/data/flags/h80/fr.png" alt="Current Language" class="dropdown-icon">
                                    Français</a>
                            </li>
                        </ul>
                    </div>
                    <div class="recherche">
                        <form action="<?= base_url('Emploi/search') ?>" method="post">
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
                                    <div class="video-title"> <?= $tuto->titre ?></div>
                                    <div class="video-description">Tuto N° : <?= $tuto->numero ?> </div>
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

                                            <form action="<?= base_url('Emploi/search/' . ($current - 1)) ?>" method="post">
                                                <input type="hidden" name="query" value="<?= $_POST['query'] ?? "" ?>">
                                                <button class="page-link" type="submit" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </button>
                                            </form>

                                        <?php else :  ?>
                                            <a class="page-link" href='<?= base_url('Emploi/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </li>

                                <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                    <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                        <?php if (isset($_POST['query'])) :  ?>
                                            <form action="<?= base_url('Emploi/search/' . ($i)) ?>" method="post">
                                                <input type="hidden" name="query" value="<?= $_POST['query'] ?? "" ?>">

                                                <button class="page-link" type="submit">
                                                    <?= $i ?>
                                                </button>
                                            </form>
                                        <?php else :  ?>
                                            <a class="page-link" href="<?= base_url('Emploi/page/' . $i) ?>"><?= $i ?></a>
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
                                            <form action="<?= base_url('Emploi/search/' . ($current +  1)) ?>" method="post">
                                                <input type="hidden" name="query" value="<?= $_POST['query'] ?? "" ?>">

                                                <button class="page-link" type="submit" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                </button>
                                            </form>
                                        <?php else :  ?>
                                            <a class="page-link" href="<?= base_url('Emploi/page/' . ($current + 1)) ?>" aria-label="Next">
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