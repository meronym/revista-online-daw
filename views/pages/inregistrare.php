<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <h1 class="h3 mb-4">Cont nou</h1>

        <?php if ($errors !== []): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="bg-body p-4 rounded shadow-sm" method="post" action="/inregistrare">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            <div class="mb-3">
                <label class="form-label" for="nume_utilizator">Nume de utilizator</label>
                <input class="form-control" id="nume_utilizator" name="nume_utilizator"
                       maxlength="50" value="<?= e($values['nume_utilizator']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email"
                       maxlength="100" value="<?= e($values['email']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="parola">Parolă</label>
                <input class="form-control" type="password" id="parola" name="parola">
                <div class="form-text">Cel puțin 8 caractere.</div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="confirmare">Confirmă parola</label>
                <input class="form-control" type="password" id="confirmare" name="confirmare">
            </div>

            <div class="g-recaptcha mb-3" data-sitekey="<?= e(recaptchaSiteKey()) ?>"></div>

            <button class="btn btn-primary w-100" type="submit">Creează contul</button>
        </form>

        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <p class="text-body-secondary small mt-3 mb-0">
            Ai deja cont? <a href="/autentificare">Autentifică-te</a>.
        </p>
    </div>
</div>
