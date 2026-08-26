<nav aria-label="Navigare secundară">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Acasă</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= e($section['nume']) ?></li>
    </ol>
</nav>

<h1 class="h3 mb-4"><?= e($section['nume']) ?></h1>

<?php if ($articles === []): ?>
    <p class="text-body-secondary">Nu există încă articole publicate în această rubrică.</p>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php renderList($articles, 'article-card'); ?>
    </div>
<?php endif; ?>
