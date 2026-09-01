<?php
declare(strict_types=1);

const FEED_URL = 'https://www.digi24.ro/rss';
const FEED_SOURCE = 'Digi24';

// Stirile sunt parsate si salvate in baza de date
// feed-ul nu ajunge niciodata direct in HTML
function importNews(): void
{
    // Reluam cel mult o data pe ora
    $fresh = fetchOne(
        'SELECT id FROM stiri_externe WHERE sursa = ? AND preluat_la > NOW() - INTERVAL 1 HOUR',
        [FEED_SOURCE]
    );

    if ($fresh !== null) {
        return;
    }

    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $body = @file_get_contents(FEED_URL, false, $context);
    // @: daca feed-ul raspunde cu altceva decat XML, nu scriem avertismente in pagina
    $feed = $body === false ? false : @simplexml_load_string($body);

    if ($feed === false) {
        error_log('Feed indisponibil: ' . FEED_URL);
        return;
    }

    foreach ($feed->channel->item as $item) {
        // Cheia unica (sursa, guid) asigura ca nu duplicam stirile deja salvate
        query(
            'INSERT INTO stiri_externe (sursa, guid, titlu, link, publicat_la)
                  VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE preluat_la = NOW()',
            [
                FEED_SOURCE,
                (string) $item->guid,
                (string) $item->title,
                (string) $item->link,
                date('Y-m-d H:i:s', strtotime((string) $item->pubDate)),
            ]
        );
    }
}

function latestNews(): array
{
    return fetchAll(
        'SELECT titlu, link, publicat_la FROM stiri_externe
          WHERE sursa = ? ORDER BY publicat_la DESC LIMIT 8',
        [FEED_SOURCE]
    );
}
