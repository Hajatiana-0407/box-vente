<div class="sidebar">
    <div class="mt-2">
        <div class="pt-2">
            <span class="sidebar-title mb-3">
                <span>Vente et facturation </span>
            </span>
            <a href="<?= base_url('vente') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'vente' || basename($_SERVER['PHP_SELF']) == 'liste') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                <span class="ms-1">Vente</span>
            </a>

            <a href="<?= base_url('mode_de_paiment') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'mode_de_paiment') ? 'active' : '' ?>">
                <span class="icon"><i class="fa fa-credit-card"></i></span>
                <span class="ms-1">Mode de paiement</span>
            </a>
            <span class="sidebar-title mt-4 mb-2">
                <span>Gestion de Stock</span>
            </span>

            <a href="<?= base_url('produit') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'produit' || basename($_SERVER['PHP_SELF']) == "prix" ||  basename($_SERVER['PHP_SELF']) == "codeBarre") ? 'active' : '' ?>">
                <span class="icon"><i class="fa-solid fa-boxes-packing"></i></span>
                <span class="ms-1">Produit</span>
            </a>

            <a href="<?= base_url('appro') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'appro') ? 'active' : '' ?>">
                <span class="icon"><i class="fa-solid fa-truck-ramp-box"></i></span>
                <span class="ms-1">Approvisionnement</span>
            </a>

            <a href="<?= base_url('stock') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'stock') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-warehouse"></i></span>
                <span class="ms-1">Inventaire</span>
            </a>
            <!-- <a href="<?= base_url('pointDeVente') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'pointDeVente') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-store"></i></span>
                <span class="ms-1">Dépôt</span>
            </a> -->

            <span class="sidebar-title mt-4 mb-2">
                <span>Gestion de clients</span>
            </span>
            <a href="<?= base_url('client') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'client') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-user-plus"></i></span>
                <span class="ms-1">Liste</span>
            </a>
            <a href="<?= base_url('suivi-client') ?>" class="sidebar-link <?= (strpos(basename($_SERVER['PHP_SELF']), 'suivi-client') > -1) ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-history"></i></span>
                <span class="ms-1">Suivi</span>
            </a>
            <span class="sidebar-title mt-4 mb-2">
            </span>
        </div>
    </div>
</div>