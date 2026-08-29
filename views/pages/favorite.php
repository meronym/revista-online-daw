<h1 class="h3 mb-4">Articolele mele favorite</h1>

<?php if ($articles === []): ?>
    <p class="text-body-secondary">
        Nu ai salvat încă niciun articol. Deschide un articol și apasă
        „Adaugă la favorite".
    </p>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php renderList($articles, 'article-card'); ?>
    </div>
<?php endif; ?>
