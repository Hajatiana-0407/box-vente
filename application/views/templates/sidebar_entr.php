<div class="sidebar">
    <div class="mt-2">
        <div class="pt-2">
            <span class="sidebar-title mt-2 mb-2">
                <span>Info</span>
            </span>
            <a href="<?= base_url('entreprise') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'entreprise') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-cogs"></i></span>
                <span class="ms-1">Entreprise</span>
            </a>
            <span class="sidebar-title mt-2 mb-2">
                <span>Etat</span>
            </span>
            <a href="<?= base_url('depense') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'depense') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>
                <span class="ms-1">Dépense</span>
            </a>
            <a href="<?= base_url('etat') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'etat') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-check-square"></i></span>
                <span class="ms-1">Etat</span>
            </a>


            
            <a href="<?= base_url('vente') ?>" class="sidebar-link mt-4" id="sortire_">
                <span class="icon"><i class="fas fa-reply"></i></span>
                <span class="ms-1">Retour</span>
            </a>

            </span>
        </div>
    </div>
</div>