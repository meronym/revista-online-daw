<?php
declare(strict_types=1);

require __DIR__ . '/../src/db.php';
require __DIR__ . '/../src/helpers.php';

$rubrici = findAll('rubrici', 'nume');

// Join-urile raman SQL scris de mana; helperele generice acopera un singur tabel
$articole = fetchAll(
    "SELECT a.slug, a.titlu, a.rezumat, a.publicat_la,
            r.slug AS slug_rubrica, r.nume AS rubrica,
            u.nume_utilizator AS autor
       FROM articole a
       JOIN rubrici r     ON r.id = a.id_rubrica
       JOIN utilizatori u ON u.id = a.id_utilizator
      WHERE a.stare = 'publicat'
      ORDER BY a.publicat_la DESC"
);
?>
<!doctype html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Revistă Online</title>
</head>
<body>
    <header>
        <h1>Revistă Online</h1>
        <nav>
            <?php foreach ($rubrici as $rubrica): ?>
                <a href="/rubrica/<?= e($rubrica['slug']) ?>"><?= e($rubrica['nume']) ?></a>
            <?php endforeach; ?>
        </nav>
    </header>

    <main>
        <?php foreach ($articole as $articol): ?>
            <article>
                <h2>
                    <a href="/articol/<?= e($articol['slug']) ?>"><?= e($articol['titlu']) ?></a>
                </h2>
                <p>
                    <a href="/rubrica/<?= e($articol['slug_rubrica']) ?>"><?= e($articol['rubrica']) ?></a>
                    &middot; <?= e($articol['autor']) ?>
                    &middot; <?= e(formatDate($articol['publicat_la'])) ?>
                </p>
                <p><?= e($articol['rezumat']) ?></p>
            </article>
        <?php endforeach; ?>
    </main>
</body>
</html>
