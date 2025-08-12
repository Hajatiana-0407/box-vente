<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <form action="<?= base_url('suivi-client/filtre') ?>" method="get">
                    <div class="input-group mt-3 mb-3">
                        <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $_POST['post'] ?? '' ?>">
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
                                <th>Raison social </th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Adresse</th>
                                <th>Numéro Télephone</th>
                                <th>Email</th>
                                <th>Nombre d'achats</th>
                                <th>Total acheté</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($data['client'])) :;
                                $list = $data['client'];
                            ?>
                                <?php for ($i = 0; $i < count($list); $i++) :  ?>
                                    <tr>
                                        <td><?= ($list[$i]->r_social != '') ?  ucfirst($list[$i]->r_social) : '--'; ?></td>
                                        <td><?= ($list[$i]->nomClient != '') ? strtoupper($list[$i]->nomClient)  : '--'; ?></td>
                                        <td><?= ($list[$i]->prenomClient != '') ? ucfirst($list[$i]->prenomClient)  : '--'; ?></td>
                                        <td><?= $list[$i]->adresseClient; ?></td>
                                        <td><?= $list[$i]->telClient; ?></td>
                                        <td><?= $list[$i]->emailClient; ?></td>
                                        <td><?= $list[$i]->nbr_ventes; ?></td>
                                        <td><?= number_three($list[$i]->total_montant); ?></td>
                                        <td class="">
                                            <?php if ($list[$i]->nbr_ventes > 0) :  ?>
                                                <form action="<?= base_url('suivi-client/details') ?>" method="post">
                                                    <input type="hidden" value="<?= $list[$i]->telClient ?>" name="telClient">
                                                    <input type="hidden" value="<?= ($list[$i]->nomClient == '') ? (($list[$i]->r_social == '') ? '...' :  $list[$i]->r_social)  :  $list[$i]->nomClient . ' ' . $list[$i]->prenomClient   ?>" name="nom">
                                                    <button type="submit" class="btn btn-info">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                --
                                            <?php endif  ?>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>