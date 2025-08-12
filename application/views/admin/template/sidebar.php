<div class="sidebar">
    <div class="mt-2">
        <div class="pt-2">
            <span class="sidebar-title mb-3">
                <span>Dashboard</span>
            </span>
            <a href="<?= base_url('Admin/client') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'client') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-user-tie"></i></span>
                <span class="ms-1">Client </span>
            </a>
            <a href="<?= base_url('abonnement') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'abonnement' || strpos(  $_SERVER['PHP_SELF'] , 'Abonnement'  ) > -1 ) ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                <span class="ms-1">Abonnement</span>
            </a>

            <a href="<?= base_url('Admin/tuto') ?>" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) == 'tuto') ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-chalkboard-teacher"></i></span>
                <span class="ms-1">Tutoriel</span>
            </a>

            <span class="sidebar-title mt-4 mb-2">
            </span>

        </div>
    </div>
</div>