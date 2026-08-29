<?php
declare(strict_types=1);

// Un singur token per sesiune, integrat in fiecare formular si verificat la POST in index.php
function csrfToken(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function requireCsrf(): void
{
    if (!hash_equals(csrfToken(), $_POST['csrf'] ?? '')) {
        http_response_code(403);
        render('403', [
            'title'   => 'Cerere respinsă',
            'message' => 'Formularul a expirat sau nu a fost trimis de pe acest site. Reîncarcă pagina și încearcă din nou.',
        ]);
        exit;
    }
}

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

function requireLogin(): array
{
    return currentUser() ?? redirect('/autentificare');
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
        render('403', [
            'title'   => 'Acces interzis',
            'message' => 'Contul tău nu are drepturi pentru această pagină.',
        ]);
        exit;
    }
}

// Un autor isi acceseaza doar propriile articole
function requireOwnerOrAdmin(array $article): void
{
    $user = currentUser();

    if ($user['rol'] !== 'admin' && (int) $article['id_utilizator'] !== (int) $user['id']) {
        http_response_code(403);
        render('403', [
            'title'   => 'Acces interzis',
            'message' => 'Articolul aparține altui autor.',
        ]);
        exit;
    }
}

function isAdmin(): bool
{
    return currentUser()['rol'] === 'admin';
}

function validateRegistration(array $input): array
{
    $errors = [];

    if ($input['nume_utilizator'] === '') {
        $errors['nume_utilizator'] = 'Numele de utilizator este obligatoriu.';
    } elseif (fetchOne('SELECT id FROM utilizatori WHERE nume_utilizator = ?', [$input['nume_utilizator']])) {
        $errors['nume_utilizator'] = 'Numele de utilizator este deja folosit.';
    }

    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Adresa de email nu este validă.';
    } elseif (fetchOne('SELECT id FROM utilizatori WHERE email = ?', [$input['email']])) {
        $errors['email'] = 'Există deja un cont cu acest email.';
    }

    if (mb_strlen($input['parola']) < 8) {
        $errors['parola'] = 'Parola trebuie să aibă cel puțin 8 caractere.';
    } elseif ($input['parola'] !== $input['confirmare']) {
        $errors['confirmare'] = 'Parolele nu coincid.';
    }

    return $errors;
}

function registerReader(array $input): void
{
    $id = insert('utilizatori', [
        'nume_utilizator' => $input['nume_utilizator'],
        'email'           => $input['email'],
        'parola'          => password_hash($input['parola'], PASSWORD_DEFAULT),
        // Rolul e fixat aici, nu vine din formular
        'rol'             => 'cititor',
    ]);

    session_regenerate_id(true);
    $_SESSION['id_utilizator'] = $id;
}
