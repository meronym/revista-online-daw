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


// Lista din administrare arata si ciornele, deci nu filtreaza dupa stare
function allArticles(): array
{
    return fetchAll(
        "SELECT a.id, a.titlu, a.stare, a.creat_la, a.publicat_la,
                r.nume AS rubrica, u.nume_utilizator AS autor" . ARTICLE_JOINS . "
          ORDER BY a.creat_la DESC"
    );
}

// Validarea completa se face pe server: campurile din formular pot fi oricum
function validateArticle(array $input): array
{
    $errors = [];

    if ($input['titlu'] === '') {
        $errors['titlu'] = 'Titlul este obligatoriu.';
    } elseif (mb_strlen($input['titlu']) > 255) {
        $errors['titlu'] = 'Titlul poate avea cel mult 255 de caractere.';
    }

    if ($input['continut'] === '') {
        $errors['continut'] = 'Conținutul este obligatoriu.';
    }

    if (find('rubrici', $input['id_rubrica']) === null) {
        $errors['id_rubrica'] = 'Alege o rubrică.';
    }

    if (!in_array($input['stare'], ['ciorna', 'publicat'], true)) {
        $errors['stare'] = 'Starea trebuie să fie ciornă sau publicat.';
    }

    return $errors;
}
