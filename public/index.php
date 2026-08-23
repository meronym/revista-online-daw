<?php
// Verificare temporara a stack-ului: PHP porneste si ajunge la MySQL

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME')
);

try {
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $stare = 'MySQL ' . $pdo->query('SELECT VERSION()')->fetchColumn();
} catch (PDOException $e) {
    // Mesajul include numele bazei si al utilizatorului: il trimitem in log pentru a preveni un leak
    error_log('Conexiune esuata: ' . $e->getMessage());
    $stare = 'conexiune esuata';
}
?>
<!doctype html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Revistă Online</title>
</head>
<body>
    <h1>Revistă Online</h1>
    <p>PHP <?= PHP_VERSION ?> &mdash; <?= htmlspecialchars($stare, ENT_QUOTES, 'UTF-8') ?></p>
</body>
</html>
