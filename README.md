# Revistă Online

Proiect pentru cursul **Dezvoltarea Aplicațiilor Web** (DAW), FMI UniBuc.

O revistă online: autorii scriu articole, administratorul le publică și le
gestionează, cititorii înregistrați își salvează articolele favorite, iar
administratorul urmărește statisticile — cu formular de contact, export PDF și
CSV, conținut preluat din surse externe și statistici de trafic.

## Tehnologii

- PHP 8.3 și MySQL 8, fără framework și fără CMS
- Docker Compose pentru mediul de dezvoltare
- Caddy ca server web, cu TLS automat în producție
- FPDF și PHPMailer incluse direct în proiect, fără Composer

## Mediu de dezvoltare

```
make setup   # creeaza .env din .env.example
make up      # porneste stack-ul (Caddy + PHP-FPM + MySQL + phpMyAdmin)
make down    # opreste stack-ul
make logs    # urmareste log-urile
make sh      # shell in containerul PHP
make mysql   # client MySQL

make db-schema   # reincarca structura bazei de date
```

| Serviciu | Adresă |
|---|---|
| Aplicație | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |
| MySQL | localhost:3306 |

Completează parolele în `.env` înainte de primul `make up`.

## Stadiu

Schelet în lucru.
