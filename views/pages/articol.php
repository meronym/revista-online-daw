<nav aria-label="Navigare secundară">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Acasă</a></li>
        <li class="breadcrumb-item">
            <a href="/rubrica/<?= e($article['slug_rubrica']) ?>"><?= e($article['rubrica']) ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= e($article['titlu']) ?></li>
    </ol>
</nav>

<article class="bg-body p-4 rounded shadow-sm">
    <h1 class="h2"><?= e($article['titlu']) ?></h1>

    <p class="text-body-secondary">
        <?= e($article['autor']) ?> &middot; <?= e(formatDate($article['publicat_la'])) ?>
    </p>

    <?php if ($article['rezumat'] !== null): ?>
        <p class="lead"><?= e($article['rezumat']) ?></p>
    <?php endif; ?>

    <hr>

    <?= paragraphs($article['continut']) ?>
</article>
