<?php
declare(strict_types=1);

// Orice text care ajunge in pagina trece pe aici
function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate(?string $datetime): string
{
    if ($datetime === null) {
        return '';
    }

    $months = [
        'ianuarie', 'februarie', 'martie', 'aprilie', 'mai', 'iunie',
        'iulie', 'august', 'septembrie', 'octombrie', 'noiembrie', 'decembrie',
    ];
    $time = strtotime($datetime);

    return date('j', $time) . ' ' . $months[(int) date('n', $time) - 1] . ' ' . date('Y', $time);
}


// Textul articolelor e stocat ca text simplu; escapam intai, apoi adaugam markup
function paragraphs(?string $text): string
{
    $blocks = preg_split('/\n\s*\n/', trim($text ?? '')) ?: [];

    return implode("\n", array_map(
        fn (string $block) => '<p>' . nl2br(e($block)) . '</p>',
        array_filter($blocks, fn (string $block) => trim($block) !== '')
    ));
}

// Pagina se randeaza intr-un buffer, apoi layout-ul o primeste in $content
function render(string $page, array $data = []): void
{
    // EXTR_SKIP: datele paginii nu au voie sa suprascrie $page sau $data
    extract($data, EXTR_SKIP);

    ob_start();
    require __DIR__ . '/../views/pages/' . $page . '.php';
    $content = ob_get_clean();

    require __DIR__ . '/../views/layout.php';
}

// Mecanism generic de parcurgere: acelasi apel listeaza articole, mesaje sau
// orice alt set de randuri, schimband doar partial-ul
function renderList(array $items, string $partial): void
{
    foreach ($items as $item) {
        require __DIR__ . '/../views/partials/' . $partial . '.php';
    }
}
