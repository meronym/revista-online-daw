<h1 class="h3 mb-4"><?= e($title) ?></h1>

<?php if ($errors !== []): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="bg-body p-4 rounded shadow-sm" method="post"
      action="/admin/articole/<?= $id === null ? 'nou' : (int) $id ?>">

    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

    <div class="mb-3">
        <label class="form-label" for="titlu">Titlu</label>
        <input class="form-control" id="titlu" name="titlu" maxlength="255"
               value="<?= e($values['titlu']) ?>">
    </div>

    <div class="row">
        <div class="col-md-8 mb-3">
            <label class="form-label" for="id_rubrica">Rubrică</label>
            <select class="form-select" id="id_rubrica" name="id_rubrica">
                <option value="">Alege rubrica</option>
                <?php foreach ($sections as $section): ?>
                    <?php $ales = (int) $values['id_rubrica'] === (int) $section['id']; ?>
                    <option value="<?= (int) $section['id'] ?>" <?= $ales ? 'selected' : '' ?>><?= e($section['nume']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label" for="stare">Stare</label>
            <?php if ($canPublish): ?>
                <select class="form-select" id="stare" name="stare">
                    <option value="ciorna" <?= $values['stare'] === 'ciorna' ? 'selected' : '' ?>>Ciornă</option>
                    <option value="publicat" <?= $values['stare'] === 'publicat' ? 'selected' : '' ?>>Publicat</option>
                </select>
                <div class="form-text">Publicarea îl face vizibil pe site.</div>
            <?php else: ?>
                <input class="form-control" id="stare" value="<?= $values['stare'] === 'publicat' ? 'Publicat' : 'Ciornă' ?>" disabled>
                <div class="form-text">Publicarea o face administratorul.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="rezumat">Rezumat</label>
        <textarea class="form-control" id="rezumat" name="rezumat" rows="2"
                  maxlength="500"><?= e($values['rezumat']) ?></textarea>
    </div>

    <div class="mb-4">
        <label class="form-label" for="continut">Conținut</label>
        <textarea class="form-control" id="continut" name="continut" rows="12"><?= e($values['continut']) ?></textarea>
        <div class="form-text">Paragrafele se separă cu un rând gol.</div>
    </div>

    <button class="btn btn-primary" type="submit">Salvează</button>
    <a class="btn btn-link" href="/admin/articole">Renunță</a>
</form>
