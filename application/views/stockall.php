<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title vente">Details</h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <div id="loader_stock" style="display:flex; align-items:center; justify-content: center; width:100%; height:500px;">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="_tableau mt-4 d-none" id="stock_details">
                                    <table class="table">
                                        <thead class="table-info">
                                            <tr>
                                                <th>Numéro de série</th>
                                                <th>Couleur</th>
                                                <th>EMEI 1 </th>
                                                <th>EMEI 2 </th>
                                            </tr>
                                        </thead>
                                        <tbody id="stock-detail">

                                        </tbody>
                                    </table>
                                    <div id="pagination_js_"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <div class="mt-4">
                        <form action="" method="GET">
                            <div class="input-group">
                                <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">

                                <button class="btn btn-info" type="button" disabled>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="mt-4">
                        <form action="<?= base_url('Stock-all/search') ?>" method="GET">
                            <div class="input-group">
                                <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $_POST['post'] ?? '' ?>">

                                <button class="btn btn-info" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif  ?>
                <div class="entete">
                    <h5 class="mb-3">Inventaire</h5>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                        <div>
                            <a type="button" class="btn btn-info" disabled>
                                <i class="fas fa-eye"></i>
                                Stock par dépôt
                            </a>
                            <a href="#" type="button" class="btn btn-success" disabled>
                                <i class="fas fa-file-excel"></i>
                                Exporter
                            </a>
                        </div>
                    <?php else : ?>
                        <div>
                            <a href="<?= base_url('stock') ?>" type="button" class="btn btn-info ">
                                <i class="fas fa-eye"></i>
                                Stock par dépôt
                            </a>
                            <a href="<?= base_url('ExportationExel/stockAll') ?>" type="button" class="btn btn-success ">
                                <i class="fas fa-file-excel"></i>
                                Exporter
                            </a>
                        </div>
                    <?php endif  ?>
                </div>

                <div class="_tableau mt-4" id="stock_tableau">
                    <table class="table">
                        <thead class="table-info">
                            <tr class="to_filtre">
                                <th>
                                    <div class="container_filter">
                                        <span>Réference</span>

                                        <form action="<?= base_url('filtre-all') ?> " method="get">
                                            <input type="text" class="d-none" name="type" value="reference">
                                            <input type="text" class="d-none" name="ref" value="<?= $_POST['reference'] ?? 'asc' ?>">
                                            <?php
                                            if (isset($_POST['reference'])) : ?>
                                                <?php if ($_POST['reference'] == 'desc') : ?>
                                                    <button type="submit" id="Quantite_f" class="filter">
                                                        <i class="fas fa-sort-alpha-down-alt"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <button type="submit" id="Quantite_f" class="filter">
                                                        <i class="fas fa-sort-alpha-down"></i>
                                                    </button>
                                                <?php endif  ?>
                                            <?php else : ?>
                                                <button type="submit" id="Quantite_f" class="filter">
                                                    <i class="fas fa-sort-alpha-down"></i>
                                                </button>
                                            <?php endif  ?>
                                        </form>

                                    </div>
                                </th>
                                <th>
                                    <div class="container_filter">
                                        <span>Désignation</span>

                                        <form action="<?= base_url('filtre-all') ?>" method="get">
                                            <input type="text" class="d-none" name="type" value="designation">
                                            <input type="text" class="d-none" name="design" value="<?= $_POST['designation'] ?? 'asc' ?>">
                                            <?php
                                            if (isset($_POST['designation'])) : ?>
                                                <?php if ($_POST['designation']  == 'desc') : ?>
                                                    <button type="submit" id="Quantite_f" class="filter">
                                                        <i class="fas fa-sort-alpha-down-alt"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <button type="submit" id="Quantite_f" class="filter">
                                                        <i class="fas fa-sort-alpha-down"></i>
                                                    </button>
                                                <?php endif  ?>
                                            <?php else : ?>
                                                <button type="submit" id="Quantite_f" class="filter">
                                                    <i class="fas fa-sort-alpha-down"></i>
                                                </button>
                                            <?php endif  ?>
                                        </form>

                                    </div>
                                </th>
                                <th>Fiche technique </th>
                                <th>Quantité </th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($datas)) : ?>
                                <?php foreach ($datas as $key => $data) : ?>
                                    <tr>
                                        <td><?= $data->refProduit ?></td>
                                        <td><?= $data->designation ?></td>
                                        <td><?= $data->fiche ?></td>
                                        <td><?= $data->quantite_stk ?></td>
                                        <td>
                                            <?php if ( $data->type == 'telephone' && (int)$data->quantite_stk  > 0): ?>
                                                <button class="btn btn-secondary detail" data-reference='<?= $data->refProduit ?>' data-id='<?= $data->idProduit ?>' data-idpointvente='' data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-list"></i></button>
                                            <?php else : ?>
                                                --
                                            <?php endif  ?>

                                        </td>
                                    </tr>
                                <?php endforeach  ?>
                            <?php endif  ?>

                        </tbody>
                    </table>
                    <p class="pagination pagination-sm"><?= $lien; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>