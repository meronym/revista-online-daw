<!doctype html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Revistă Online') ?></title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-body-tertiary">
    <?php require __DIR__ . '/partials/nav.php'; ?>

    <main class="container flex-grow-1 py-4">
        <?= /* Singurul loc fara e(): $content e HTML deja randat si escapat */ $content ?>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
