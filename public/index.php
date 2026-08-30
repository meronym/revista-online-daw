<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

// Caddy trimite aici tot ce nu e fisier real, deci ruta se citeste din cale
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$segments = array_values(array_filter(explode('/', $path), fn (string $s) => $s !== ''));

$route = $segments[0] ?? '';
$param = $segments[1] ?? null;
$post = $_SERVER['REQUEST_METHOD'] === 'POST';

// O singura verificare acopera toate rutele care scriu
if ($post) {
    requireCsrf();
}

switch ($route) {
    case '':
        recordVisit($path);

        render('acasa', [
            'title'    => 'Revistă Online',
            'articles' => publishedArticles(),
        ]);
        break;

    case 'rubrica':
        $section = $param === null ? null : sectionBySlug($param);

        if ($section === null) {
            notFound();
        }

        recordVisit($path);

        render('rubrica', [
            'title'    => $section['nume'] . ' — Revistă Online',
            'section'  => $section,
            'articles' => publishedArticles((int) $section['id']),
        ]);
        break;

    case 'articol':
        $article = $param === null ? null : publishedArticleBySlug($param);

        if ($article === null) {
            notFound();
        }

        if (($segments[2] ?? '') === 'favorit') {
            if (!$post) {
                notFound();
            }

            toggleFavourite((int) requireLogin()['id'], (int) $article['id']);
            redirect('/articol/' . $article['slug']);
        }

        recordVisit($path, (int) $article['id']);

        $user = currentUser();

        render('articol', [
            'title'      => $article['titlu'] . ' — Revistă Online',
            'article'    => $article,
            'isFavourite' => $user !== null && favouriteId((int) $user['id'], (int) $article['id']) !== null,
        ]);
        break;

    case 'autentificare':
        if ($post && login($_POST['email'] ?? '', $_POST['parola'] ?? '')) {
            redirect(currentUser()['rol'] === 'admin' ? '/admin/articole' : '/');
        }

        render('autentificare', [
            'title' => 'Autentificare',
            'email' => $_POST['email'] ?? '',
            'error' => $post ? 'Email sau parolă greșite.' : null,
        ]);
        break;

    case 'inregistrare':
        $input = [
            'nume_utilizator' => trim($_POST['nume_utilizator'] ?? ''),
            'email'           => trim($_POST['email'] ?? ''),
            'parola'          => $_POST['parola'] ?? '',
            'confirmare'      => $_POST['confirmare'] ?? '',
        ];
        $errors = $post ? validateRegistration($input) : [];

        if ($post && $errors === []) {
            registerReader($input);
            redirect('/');
        }

        render('inregistrare', [
            'title'  => 'Cont nou',
            'values' => $input,
            'errors' => $errors,
        ]);
        break;

    case 'favorite':
        render('favorite', [
            'title'    => 'Articolele mele favorite',
            'articles' => favouriteArticles((int) requireLogin()['id']),
        ]);
        break;

    case 'deconectare':
        if ($post) {
            logout();
        }

        redirect('/');

    case 'admin':
        requireRole('admin', 'autor');

        if (($segments[1] ?? '') === 'statistici') {
            requireRole('admin');

            render('admin/statistici', [
                'title' => 'Statistici',
                'stats' => visitStats(),
                'top'   => topArticles(),
            ]);
            break;
        }

        if (($segments[1] ?? '') !== 'articole') {
            notFound();
        }

        $action = $segments[2] ?? '';

        if ($action === '') {
            adminList();
        } elseif ($action === 'nou') {
            $post ? adminSave(null) : adminForm(null);
        } elseif (!ctype_digit($action)) {
            notFound();
        } elseif (($segments[3] ?? '') === '') {
            $post ? adminSave((int) $action) : adminForm((int) $action);
        } elseif ($segments[3] === 'stergere' && $post) {
            adminDelete((int) $action);
        } else {
            notFound();
        }
        break;

    default:
        notFound();
}
