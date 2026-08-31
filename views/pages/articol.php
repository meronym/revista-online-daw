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

    <div class="d-flex justify-content-between align-items-center">
        <p class="text-body-secondary mb-0">
            <?= e($article['autor']) ?> &middot; <?= e(formatDate($article['publicat_la'])) ?>
        </p>

        <div class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-secondary" href="/articol/<?= e($article['slug']) ?>/pdf">
                Versiune PDF
            </a>

            <?php if (currentUser() !== null): ?>
            <form method="post" action="/articol/<?= e($article['slug']) ?>/favorit">
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                <button class="btn btn-sm <?= $isFavourite ? 'btn-primary' : 'btn-outline-primary' ?>" type="submit">
                    <?= $isFavourite ? 'Salvat la favorite' : 'Adaugă la favorite' ?>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($article['rezumat'] !== null): ?>
        <p class="lead"><?= e($article['rezumat']) ?></p>
    <?php endif; ?>

    <hr>

    <?php if ($videoId = youtubeId($article['url_video'])): ?>
        <div class="ratio ratio-16x9 mb-4">
            <iframe src="https://www.youtube.com/embed/<?= e($videoId) ?>"
                    title="Material video" allowfullscreen loading="lazy"></iframe>
        </div>
    <?php endif; ?>

    <?= paragraphs($article['continut']) ?>
</article>
