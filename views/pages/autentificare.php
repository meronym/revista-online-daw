<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <h1 class="h3 mb-4">Autentificare</h1>

        <?php if ($error !== null): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form class="bg-body p-4 rounded shadow-sm" method="post" action="/autentificare">
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="<?= e($email) ?>">
            </div>

            <div class="mb-4">
                <label class="form-label" for="parola">Parolă</label>
                <input class="form-control" type="password" id="parola" name="parola">
            </div>

            <button class="btn btn-primary w-100" type="submit">Intră în cont</button>
        </form>

        <div class="alert alert-secondary mt-4 small mb-0">
            <strong>Conturi de test:</strong>
            admin@revista.test (administrator), ioana@revista.test (autor),
            cititor@revista.test (cititor) &mdash; parola <code>parola123</code>
        </div>
    </div>
</div>
