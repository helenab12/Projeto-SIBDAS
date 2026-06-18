<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEBA - Área Privada</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>node_modules/simple-datatables/dist/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>node_modules/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/1240961.css">
</head>

<body class="private-area overflow-hidden">
    <!-- Spinner de Carregamento -->
    <div id="page-loading-overlay"
        class="page-loading-overlay position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
        <div class="page-loading-spinner"></div>
    </div>
    <?php ob_flush();
    flush(); ?>

    <div class="d-flex">