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
