<?php
declare(strict_types=1);

// Helperele generice accepta nume de tabel doar din lista de mai jos
const TABLES = [
    'utilizatori',
    'rubrici',
    'articole',
    'articole_favorite',
    'mesaje',
    'vizite',
    'stiri_externe',
];

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            getenv('DB_HOST'),
            getenv('DB_PORT'),
            getenv('DB_NAME')
        );

        $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Fara emulare: interogarea si datele ajung separat la MySQL
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    return $pdo;
}


// Toate query-urile trec pe aici, deci toate sunt prepared
function query(string $sql, array $params = []): PDOStatement
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt;
}

function fetchAll(string $sql, array $params = []): array
{
    return query($sql, $params)->fetchAll();
}

function fetchOne(string $sql, array $params = []): ?array
{
    return query($sql, $params)->fetch() ?: null;
}


function checkTable(string $table): string
{
    if (!in_array($table, TABLES, true)) {
        throw new InvalidArgumentException("Tabel necunoscut: $table");
    }

    return $table;
}


function find(string $table, int $id): ?array
{
    return fetchOne(sprintf('SELECT * FROM %s WHERE id = ?', checkTable($table)), [$id]);
}

function findAll(string $table, string $orderBy = 'id'): array
{
    return fetchAll(sprintf('SELECT * FROM %s ORDER BY %s', checkTable($table), $orderBy));
}

function insert(string $table, array $data): int
{
    $columns = array_keys($data);

    query(sprintf(
        'INSERT INTO %s (%s) VALUES (%s)',
        checkTable($table),
        implode(', ', $columns),
        implode(', ', array_map(fn ($c) => ":$c", $columns))
    ), $data);

    return (int) db()->lastInsertId();
}

function update(string $table, int $id, array $data): int
{
    $sets = array_map(fn ($c) => "$c = :$c", array_keys($data));

    return query(sprintf(
        'UPDATE %s SET %s WHERE id = :id',
        checkTable($table),
        implode(', ', $sets)
    ), $data + ['id' => $id])->rowCount();
}

function delete(string $table, int $id): int
{
    return query(sprintf('DELETE FROM %s WHERE id = ?', checkTable($table)), [$id])->rowCount();
}
