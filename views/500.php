<!doctype html>
<!-- Pagina de eroare nu foloseste layout-ul: daca baza de date e cazuta, meniul
     ar arunca a doua oara, chiar din handler -->
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eroare &middot; Revistă Online</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body class="bg-body-tertiary">
    <main class="container py-5">
        <h1 class="h3">A apărut o eroare</h1>
        <p class="text-body-secondary">
            Pagina nu a putut fi afișată. Încearcă din nou peste câteva momente.
        </p>
        <a class="btn btn-primary" href="/">Înapoi la pagina principală</a>

        <?php if (isset($e) && filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOL)): ?>
            <!-- Detaliile depind de display_errors, care in productie e oprit -->
            <div class="alert alert-danger mt-4">
                <p class="fw-semibold mb-1"><?= e($e::class) ?></p>
                <p class="mb-2"><?= e($e->getMessage()) ?></p>
                <p class="small text-body-secondary mb-2">
                    <?= e($e->getFile()) ?>:<?= (int) $e->getLine() ?>
                </p>
                <pre class="small mb-0 overflow-auto"><?= e($e->getTraceAsString()) ?></pre>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
