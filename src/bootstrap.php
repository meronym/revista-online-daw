<?php
declare(strict_types=1);

require __DIR__ . '/db.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/articles.php';

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
