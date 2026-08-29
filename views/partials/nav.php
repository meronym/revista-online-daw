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

            <?php $user = currentUser(); ?>
            <ul class="navbar-nav ms-auto">
                <?php if ($user === null): ?>
                    <li class="nav-item"><a class="nav-link" href="/inregistrare">Cont nou</a></li>
                    <li class="nav-item"><a class="nav-link" href="/autentificare">Autentificare</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/favorite">Favorite</a></li>
                    <?php if ($user['rol'] !== 'cititor'): ?>
                        <li class="nav-item"><a class="nav-link" href="/admin/articole">Administrare</a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <form method="post" action="/deconectare">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <button class="btn btn-link nav-link" type="submit">
                                Ieși (<?= e($user['nume_utilizator']) ?>)
                            </button>
                        </form>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
