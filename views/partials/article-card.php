<div class="col">
    <article class="card h-100">
        <div class="card-body">
            <a class="badge text-bg-secondary text-decoration-none"
               href="/rubrica/<?= e($item['slug_rubrica']) ?>"><?= e($item['rubrica']) ?></a>

            <h2 class="card-title h5 mt-2">
                <a class="text-decoration-none link-body-emphasis"
                   href="/articol/<?= e($item['slug']) ?>"><?= e($item['titlu']) ?></a>
            </h2>

            <p class="card-text text-body-secondary"><?= e($item['rezumat']) ?></p>
        </div>

        <div class="card-footer bg-transparent text-body-secondary small">
            <?= e($item['autor']) ?> &middot; <?= e(formatDate($item['publicat_la'])) ?>
        </div>
    </article>
</div>
