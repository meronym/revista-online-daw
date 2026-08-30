<?php
declare(strict_types=1);

// Pastram hash-ul IP-ului, nu IP-ul: numaram vizitatori unici fara sa retinem
// date personale
function recordVisit(string $path, ?int $articleId = null): void
{
    insert('vizite', [
        'cale'       => substr($path, 0, 255),
        'id_articol' => $articleId,
        'hash_ip'    => hash('sha256', getenv('ANALYTICS_SALT') . ($_SERVER['REMOTE_ADDR'] ?? '')),
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);
}

function visitStats(): array
{
    return fetchOne('SELECT COUNT(*) AS accesari, COUNT(DISTINCT hash_ip) AS vizitatori FROM vizite');
}

function topArticles(int $limit = 5): array
{
    return fetchAll(
        "SELECT a.slug, a.titlu, COUNT(*) AS accesari
           FROM vizite v
           JOIN articole a ON a.id = v.id_articol
          GROUP BY a.id
          ORDER BY accesari DESC
          LIMIT " . (int) $limit
    );
}
