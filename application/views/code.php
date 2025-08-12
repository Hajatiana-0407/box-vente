<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <div class="onglet">
                    <a href="<?= base_url('produit') ?>" id="vente_" class="onglet_btn "> Produit</a>
                    <a href="<?= base_url('prix') ?>" id="panier_" class="onglet_btn"> Prix</a>
                    <a href="#" class="onglet_btn active">Code-barre</a>
                </div>
                <form action="<?= base_url('CodeBarre/search') ?>" method="get">
                    <div class="input-group mt-4 mb-3">
                        <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $_POST['keyword'] ?? "" ?>">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <button class="btn btn-info" type="button" disabled>
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        <?php else : ?>
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                <button class="btn btn-info" type="button" disabled>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            <?php else : ?>
                                <button class="btn btn-info" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            <?php endif  ?>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="_boutton">
                    <a class="btn btn-info" href="<?= base_url('codeBarre-num') ?>"><i class="fas fa-eye"></i> Par numéro de série </a>
                    <button class="btn btn-info printCode" ><i class="fas fa-print "></i> Tout imprimer</button>
                </div>

                <div class="mt-2">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Référence</th>
                                <th>Désignation</th>
                                <th>Code-barre</th>
                            </tr>
                        </thead>
                        <tbody id="tableauCode" class="search-results">
                            <?php if (isset($data)) :
                                for ($i = 0; $i < count($data); $i++) : ?>
                                    <tr data-reference="<?= $data[$i]->refProduit ?>">
                                        <td><?= $data[$i]->refProduit ?></td>
                                        <td><?= $data[$i]->designation ?></td>
                                        <td>
                                            <a href="<?= base_url('CodeBarre/creatCode/'. $data[$i]->refProduit) ?>" download>
                                                <img src="<?= base_url('CodeBarre/creatCode/' . $data[$i]->refProduit) ?>" alt="">
                                            </a>
                                        </td>
                                    </tr>
                                <?php endfor;  ?>
                            <?php endif;  ?>
                        </tbody>
                    </table>
                    <p class="pagination pagination-sm"><?= $lien ?></p>
                </div>
            </div>
        </div>
    </div>
</div>