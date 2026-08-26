<?php
declare(strict_types=1);

// Interogarile publice: join-urile nu intra in helperele generice, care lucreaza
// pe un singur tabel

const ARTICLE_JOINS = '
      FROM articole a
      JOIN rubrici r     ON r.id = a.id_rubrica
      JOIN utilizatori u ON u.id = a.id_utilizator';

function allSections(): array
{
    return findAll('rubrici', 'nume');
}

function sectionBySlug(string $slug): ?array
{
    return fetchOne('SELECT * FROM rubrici WHERE slug = ?', [$slug]);
}

function publishedArticles(?int $sectionId = null): array
{
    $sql = "SELECT a.slug, a.titlu, a.rezumat, a.publicat_la,
                   r.slug AS slug_rubrica, r.nume AS rubrica,
                   u.nume_utilizator AS autor" . ARTICLE_JOINS . "
             WHERE a.stare = 'publicat'";
    $params = [];

    if ($sectionId !== null) {
        $sql .= ' AND a.id_rubrica = ?';
        $params[] = $sectionId;
    }

    return fetchAll($sql . ' ORDER BY a.publicat_la DESC', $params);
}

// Ciornele raman invizibile public, indiferent daca cineva ghiceste slug-ul
function publishedArticleBySlug(string $slug): ?array
{
    return fetchOne(
        "SELECT a.*, r.slug AS slug_rubrica, r.nume AS rubrica,
                u.nume_utilizator AS autor" . ARTICLE_JOINS . "
          WHERE a.slug = ? AND a.stare = 'publicat'",
        [$slug]
    );
}
