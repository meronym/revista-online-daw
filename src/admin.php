<?php
declare(strict_types=1);

function adminList(): void
{
    render('admin/articole', [
        'title'    => 'Administrare articole',
        'articles' => allArticles(),
    ]);
}

function adminForm(?int $id, ?array $values = null, array $errors = []): void
{
    $article = $id === null ? null : find('articole', $id) ?? notFound();

    render('admin/articol', [
        'title'    => $id === null ? 'Articol nou' : 'Editare articol',
        'id'       => $id,
        'values'   => $values ?? $article ?? ['titlu' => '', 'rezumat' => '', 'continut' => '', 'id_rubrica' => 0, 'stare' => 'ciorna'],
        'errors'   => $errors,
        'sections' => allSections(),
    ]);
}

function adminSave(?int $id): never
{
    // Lista fixa de campuri: orice altceva din $_POST nu ajunge in baza de date
    $input = [
        'titlu'      => trim($_POST['titlu'] ?? ''),
        'rezumat'    => trim($_POST['rezumat'] ?? ''),
        'continut'   => trim($_POST['continut'] ?? ''),
        'id_rubrica' => (int) ($_POST['id_rubrica'] ?? 0),
        'stare'      => $_POST['stare'] ?? 'ciorna',
    ];

    $errors = validateArticle($input);
    $slug = slugify($input['titlu']);

    if (!isset($errors['titlu']) && fetchOne('SELECT id FROM articole WHERE slug = ? AND id <> ?', [$slug, $id ?? 0])) {
        $errors['titlu'] = 'Există deja un articol cu acest titlu.';
    }

    if ($errors !== []) {
        // Formularul revine cu valorile trimise
        adminForm($id, $input, $errors);
        exit;
    }

    $data = $input + ['slug' => $slug];

    if ($id === null) {
        // Pana la autentificare, articolele noi apartin contului de administrator
        $data['id_utilizator'] = 1;
        $data['publicat_la'] = $input['stare'] === 'publicat' ? date('Y-m-d H:i:s') : null;
        insert('articole', $data);
    } else {
        $article = find('articole', $id) ?? notFound();

        // Data publicarii se scrie o singura data la prima publicare
        if ($input['stare'] === 'publicat' && $article['publicat_la'] === null) {
            $data['publicat_la'] = date('Y-m-d H:i:s');
        }

        update('articole', $id, $data);
    }

    redirect('/admin/articole');
}

function adminDelete(int $id): never
{
    delete('articole', $id);

    redirect('/admin/articole');
}
