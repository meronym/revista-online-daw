<?php
declare(strict_types=1);

require __DIR__ . '/db.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/articles.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/analytics.php';
require __DIR__ . '/contact.php';
require __DIR__ . '/news.php';
require __DIR__ . '/reports.php';
require __DIR__ . '/admin.php';

session_start();

// Afisarea erorilor e oprita in php.ini, ca sa nu afisam informatii sensibile in pagina
// in mediul local de dezvoltare o activam explicit
if (getenv('APP_ENV') === 'local') {
    ini_set('display_errors', '1');
}

// Pagina trece printr-un buffer pentru ca handler-ul de mai jos sa o poata curata la nevoie
// altfel dupa primul byte trimis continutul nu se mai poate schimba
ob_start();

// Mesajul unui PDOException contine DSN-ul, cu host, baza si utilizator
// fara handler ar ajunge direct in pagina
set_exception_handler(function (Throwable $e): void {
    error_log((string) $e);

    // Curatam pagina pe jumatate randata, altfel eroarea apare in mijlocul ei
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    http_response_code(500);
    require __DIR__ . '/../views/500.php';
});
