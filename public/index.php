<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

// Caddy trimite aici tot ce nu e fisier real, deci ruta se citeste din cale
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$segments = array_values(array_filter(explode('/', $path), fn (string $s) => $s !== ''));

$route = $segments[0] ?? '';
$param = $segments[1] ?? null;

// Ruta inexistenta si resursa negasita ajung in aceeasi pagina
$notFound = static function (): void {
    http_response_code(404);
    render('404', ['title' => 'Pagina nu a fost găsită']);
};

switch ($route) {
    case '':
        render('acasa', [
            'title'    => 'Revistă Online',
            'articles' => publishedArticles(),
        ]);
        break;

    case 'rubrica':
        $section = $param === null ? null : sectionBySlug($param);

        if ($section === null) {
            $notFound();
            break;
        }

        render('rubrica', [
            'title'    => $section['nume'] . ' — Revistă Online',
            'section'  => $section,
            'articles' => publishedArticles((int) $section['id']),
        ]);
        break;

    case 'articol':
        $article = $param === null ? null : publishedArticleBySlug($param);

        if ($article === null) {
            $notFound();
            break;
        }

        render('articol', [
            'title'   => $article['titlu'] . ' — Revistă Online',
            'article' => $article,
        ]);
        break;

    default:
        $notFound();
}
