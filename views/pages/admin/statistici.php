<h1 class="h3 mb-4">Statistici</h1>

<div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
    <div class="col">
        <div class="card h-100 text-center">
            <div class="card-body">
                <p class="display-5 mb-1"><?= (int) $stats['accesari'] ?></p>
                <p class="text-body-secondary mb-0">Accesări de pagini</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 text-center">
            <div class="card-body">
                <p class="display-5 mb-1"><?= (int) $stats['vizitatori'] ?></p>
                <p class="text-body-secondary mb-0">Vizitatori unici</p>
            </div>
        </div>
    </div>
</div>

<h2 class="h5 mb-3">Cele mai citite articole</h2>

<?php if ($top === []): ?>
    <p class="text-body-secondary">Nu s-a citit încă niciun articol.</p>
<?php else: ?>
    <div class="table-responsive bg-body rounded shadow-sm">
        <table class="table align-middle mb-0">
            <tbody>
                <?php foreach ($top as $item): ?>
                    <tr>
                        <td><a href="/articol/<?= e($item['slug']) ?>"><?= e($item['titlu']) ?></a></td>
                        <td class="text-end text-body-secondary"><?= (int) $item['accesari'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
