<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEBA - Área Privada</title>
    <!-- CSS DataTables -->
    <link rel="stylesheet" href="<?= BASE_URL ?>node_modules/simple-datatables/dist/style.css">
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <!-- CSS Flatpickr -->
    <link rel="stylesheet" href="<?= BASE_URL ?>node_modules/flatpickr/dist/flatpickr.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/1240961.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/img/logo.png" type="image/x-icon">

    <!-- Script Base -->
    <script>
        window.SITE_BASE_URL = "<?= BASE_URL ?>";
    </script>
</head>

<body class="private-area overflow-hidden">
    <!-- Overlay Carregamento -->
    <div id="page-loading-overlay"
        class="page-loading-overlay position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
        <!-- Spinner -->
        <div class="page-loading-spinner"></div>
    </div>
    <?php ob_flush();
    flush(); ?>

    <!-- Wrapper Principal -->
    <div class="d-flex">