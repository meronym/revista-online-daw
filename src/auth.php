<?php
declare(strict_types=1);

function currentUser(): ?array
{
    static $user = null;

    if ($user === null && isset($_SESSION['id_utilizator'])) {
        $user = find('utilizatori', (int) $_SESSION['id_utilizator']);
    }

    return $user;
}

function login(string $email, string $password): bool
{
    $user = fetchOne('SELECT * FROM utilizatori WHERE email = ?', [$email]);

    if ($user === null || !password_verify($password, $user['parola'])) {
        return false;
    }

    // Id de sesiune nou la fiecare autentificare
    session_regenerate_id(true);
    $_SESSION['id_utilizator'] = (int) $user['id'];

    return true;
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}

// Verificare la inceputul actiunilor protejate
function requireRole(string ...$roles): void
{
    $user = currentUser();

    if ($user === null) {
        redirect('/autentificare');
    }

    if (!in_array($user['rol'], $roles, true)) {
        http_response_code(403);
        render('403', ['title' => 'Acces interzis']);
        exit;
    }
}

// Un autor isi acceseaza doar propriile articole
function requireOwnerOrAdmin(array $article): void
{
    $user = currentUser();

    if ($user['rol'] !== 'admin' && (int) $article['id_utilizator'] !== (int) $user['id']) {
        http_response_code(403);
        render('403', ['title' => 'Acces interzis']);
        exit;
    }
}

function isAdmin(): bool
{
    return currentUser()['rol'] === 'admin';
}
