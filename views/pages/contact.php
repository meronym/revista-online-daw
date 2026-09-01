<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h1 class="h3 mb-4">Contact</h1>

        <?php if ($sent): ?>
            <div class="alert alert-success">Mesajul a fost trimis. Îți răspundem cât putem de repede.</div>
        <?php endif; ?>

        <?php if ($errors !== []): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="bg-body p-4 rounded shadow-sm" method="post" action="/contact">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

            <div class="mb-3">
                <label class="form-label" for="nume">Nume</label>
                <input class="form-control" id="nume" name="nume" maxlength="100"
                       value="<?= e($values['nume']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" maxlength="100"
                       value="<?= e($values['email']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="telefon">Telefon</label>
                <input class="form-control" id="telefon" name="telefon" maxlength="30"
                       value="<?= e($values['telefon']) ?>">
                <div class="form-text">Opțional.</div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="continut">Mesaj</label>
                <textarea class="form-control" id="continut" name="continut" rows="6"><?= e($values['continut']) ?></textarea>
            </div>

            <div class="g-recaptcha mb-3" data-sitekey="<?= e(recaptchaSiteKey()) ?>"></div>

            <button class="btn btn-primary" type="submit">Trimite mesajul</button>
        </form>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
