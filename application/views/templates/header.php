<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" type="image/x-icon" href="<?php echo Myurl('public/images/favicon/favicon.jpg'); ?>"> -->
    <title>
        <?php echo $title ?? 'Softio'; ?> | Administration
    </title>

    <!-- a decommenter -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <link rel="stylesheet" href="<?= Myurl('public/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= Myurl('public/css/all.css') ?>">
    <link rel="stylesheet" href="<?= Myurl('public/css/keyboard.css') ?>">

    <!-- Auto-complet -->
    <link rel="stylesheet" href="<?= Myurl('public/auto_complete/css/autocomplete/dropdown.min.css') ?>">
    <link rel="stylesheet" href="<?= Myurl('public/auto_complete/css/autocomplete/form.min.css') ?>">
    <link rel="stylesheet" href="<?= Myurl('public/auto_complete/css/autocomplete/transition.min.css') ?>">
    <!-- Auto-complet -->


    <script src="<?php echo Myurl('public/js/sweetalert.min.js') ?>"></script>
    <script src="<?php echo Myurl('public/js/sweetalert2@11.js') ?>"></script>
    <script src="<?php echo Myurl('public/js/Myalert.js') ?>"></script>

    <?php if (isset($title) &&  ($title  == 'Connexion' || $title  == 'Inscription')) :  ?>
        <link rel="stylesheet" href="<?php echo Myurl('public/css/connexionstyle.css'); ?>">
    <?php else :  ?>
        <link rel="stylesheet" href="<?php echo Myurl('public/css/stock.css'); ?>">
        <link rel="stylesheet" href="<?php echo Myurl('public/css/pagination.css'); ?>">
        <link rel="stylesheet" href="<?php echo Myurl('public/css/alert.css'); ?>">
        <link rel="stylesheet" href="<?php echo Myurl('public/css/' . $css); ?>">

    <?php endif  ?>


    <!-- jquery -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
    <script src="<?php echo Myurl('public/js/jquery.min.js') ?>"></script>
    


    <!-- Inclure jQuery UI -->
    <script
        src="https://code.jquery.com/ui/1.14.0/jquery-ui.min.js"
        integrity="sha256-Fb0zP4jE3JHqu+IBB9YktLcSjI1Zc6J2b6gTjB0LpoM="
        crossorigin="anonymous"></script>
    <!-- Inclure le CSS de jQuery UI -->
    <!-- <link rel="stylesheet" href="https://releases.jquery.com/git/ui/jquery-ui-git.css"> -->
    <!-- jquery -->

    <?php if (isset($_SESSION['clavier'])) : ?>
        <script src="<?php echo Myurl('public/js/jquery.keyboard.js') ?>"></script>
        <script src="<?php echo Myurl('public/js/clavier.js') ?>"></script>
    <?php endif ?>
</head>

<body
    class="
    <?php if (isset($_SESSION['mode']) && !is_null($_SESSION['mode'])) : ?>
        <?= $_SESSION['mode'] ?>
    <?php else: ?>
        light
    <?php endif;
    ?>
    ">

    <?php 
     Myurl() ; 
    ?>

    