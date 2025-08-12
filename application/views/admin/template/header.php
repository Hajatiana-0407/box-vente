<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" type="image/x-icon" href="<?php echo Myurl('public/images/favicon/favicon.jpg'); ?>"> -->
    <title>
        <?php echo $title ?? 'Haute Zone'; ?> | Administration
    </title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- <link rel="stylesheet" href="<?= Myurl('public/css/bootstrap.css') ?>"> -->
    <link rel="stylesheet" href="<?= Myurl('public/css/all.css') ?>">
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.0/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">

    <!-- Auto-complet -->
    <link rel="stylesheet" href="<?= Myurl('public/auto_complete/css/autocomplete/dropdown.min.css') ?>">
    <link rel="stylesheet" href="<?= Myurl('public/auto_complete/css/autocomplete/form.min.css') ?>">
    <link rel="stylesheet" href="<?= Myurl('public/auto_complete/css/autocomplete/transition.min.css') ?>">
    <!-- Auto-complet -->


    <script src="<?php echo Myurl('public/js/sweetalert.min.js') ?>"></script>
    <script src="<?php echo Myurl('public/js/sweetalert2@11.js') ?>"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- <script src="<?php echo Myurl('public/js/jquery.min.js'); ?>"></script> -->
    <script src="<?php echo Myurl('public/js/allalert.js') ?>"></script>
    <?php if (isset($title) &&  $title  == 'Admin') :  ?>
        <link rel="stylesheet" href="<?php echo Myurl('public/css/connexionstyle3.css'); ?>">
    <?php else :  ?>
        <link rel="stylesheet" href="<?php echo Myurl('public/css/stock.css'); ?>">
        <link rel="stylesheet" href="<?php echo Myurl('public/css/pagination.css'); ?>">
        <link rel="stylesheet" href="<?php echo Myurl('public/css/alert.css'); ?>">
        <link rel="stylesheet" href="<?php echo Myurl('public/css/' . $css); ?>">
    <?php endif  ?>
</head>

<body>