<li class="list-group-item d-flex justify-content-between gap-3">
    <a href="<?= e($item['link']) ?>" target="_blank" rel="noopener"><?= e($item['titlu']) ?></a>
    <span class="text-body-secondary small text-nowrap"><?= e(formatDate($item['publicat_la'])) ?></span>
</li>
