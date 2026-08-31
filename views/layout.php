<!doctype html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Revistă Online') ?></title>
    <meta name="description" content="<?= e($description ?? 'Revistă online cu articole de actualitate, cultură, tehnologie, sport și călătorii.') ?>">
    <link rel="canonical" href="<?= e(siteUrl(currentPath())) ?>">

    <meta property="og:type" content="<?= e($ogType ?? 'website') ?>">
    <meta property="og:title" content="<?= e($title ?? 'Revistă Online') ?>">
    <meta property="og:description" content="<?= e($description ?? 'Revistă online cu articole de actualitate, cultură, tehnologie, sport și călătorii.') ?>">
    <meta property="og:url" content="<?= e(siteUrl(currentPath())) ?>">
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
