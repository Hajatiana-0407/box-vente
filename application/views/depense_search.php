<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <div class="onglet">
                    <a href="<?= base_url('Depense') ?>" id="vente_" class="onglet_btn ">Depense</a>
                    <a href="#" id="vente_" class="onglet_btn active">Recherche</a>
                </div>

                <form action="<?= base_url('Depensesearch/search') ?>" method="get" class="mb-4">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="date_debut" class="form-label">Date : </label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                        </div>
                        <div class="col-4">
                            <label for="heure_debut" class="form-label">Heure : </label>
                            <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="date_fin" class="form-label">Date : </label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                        </div>
                        <div class="col-4">
                            <label for="heure_fin" class="form-label">Heure : </label>
                            <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">
                            <select name="lieu" type="text" class="form-control ui search dropdown">
                                <option value="0">Lieu ...</option>
                                <?php foreach ($pv as $key => $pv_) : ?>
                                    <option value="<?= $pv_->idPointVente  ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <button class="btn btn-info w-100">Rechercher</button>
                        </div>
                    </div>
                </form>




                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6 argent">
                        <div class="thearg  ">
                            <!-- <label class="form-label">Montant :</label>
                            <p></p> -->
                        </div>
                        <div class="thearg " id="depense">
                            <label class="form-label">Montant :</label>
                            <p> <?= (isset($somme)) ? number_three($somme)  : '0 Ar' ?></p>
                        </div>
                    </div>
                </div>
                <div class="_tableau mt-4">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Intituler</th>
                                <th>Date</th>
                                <th>  Dépôt ou point de vente</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $depense = $data['depense'] ?>
                            <?php foreach ($depense as $key => $dep) :  ?>
                                <tr>
                                    <td><?= ucfirst( $dep->raison ) ?></td>
                                    <td><?=  $dep->datedepense ?></td>
                                    <td><?= ucfirst( $dep->denomination_pv ) ?></td>
                                </tr>
                            <?php endforeach ?>
                            <?php
                            // echo '<pre>' ;
                            // var_dump( $etats ) ; 
                            // echo '</pre>' ; die ; 
                            ?>


                        </tbody>
                    </table>
                    <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
                </div>


            </div>
        </div>
    </div>
</div>