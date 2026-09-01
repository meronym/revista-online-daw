<h1 class="h3 mb-4">Ultimele articole</h1>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php renderList($articles, 'article-card'); ?>
</div>

<?php if ($news !== []): ?>
    <h2 class="h5 mt-5 mb-3">Știri de pe <?= e(FEED_SOURCE) ?></h2>

    <ul class="list-group">
        <?php renderList($news, 'news-item'); ?>
    </ul>
<?php endif; ?>
