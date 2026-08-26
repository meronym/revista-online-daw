<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Revistă Online</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#meniu" aria-controls="meniu"
                aria-expanded="false" aria-label="Deschide meniul">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="meniu">
            <ul class="navbar-nav">
                <?php foreach (allSections() as $section): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/rubrica/<?= e($section['slug']) ?>">
                            <?= e($section['nume']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
