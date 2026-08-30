-- Structura bazei de date pentru revista online
-- Tabelele se sterg si se recreeaza la fiecare rulare, in ordinea dependentelor

SET NAMES utf8mb4;

DROP TABLE IF EXISTS stiri_externe;
DROP TABLE IF EXISTS vizite;
DROP TABLE IF EXISTS mesaje;
DROP TABLE IF EXISTS articole_favorite;
DROP TABLE IF EXISTS articole;
DROP TABLE IF EXISTS rubrici;
DROP TABLE IF EXISTS utilizatori;


-- Cele trei roluri: administratorul gestioneaza tot, autorul doar articolele
-- lui, cititorul isi salveaza favorite
CREATE TABLE utilizatori (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nume_utilizator VARCHAR(50)  NOT NULL,
    email           VARCHAR(100) NOT NULL,
    parola          VARCHAR(255) NOT NULL,
    rol             ENUM('admin','autor','cititor') NOT NULL DEFAULT 'cititor',
    creat_la        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_utilizatori_email (email),
    UNIQUE KEY uq_utilizatori_nume (nume_utilizator)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE rubrici (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL,
    nume VARCHAR(50) NOT NULL,
    UNIQUE KEY uq_rubrici_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- stare separa ciorna de articolul publicat: autorul scrie, administratorul publica
CREATE TABLE articole (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    id_utilizator INT NOT NULL,
    id_rubrica    INT NOT NULL,
    slug          VARCHAR(255) NOT NULL,
    titlu         VARCHAR(255) NOT NULL,
    rezumat       VARCHAR(500) DEFAULT NULL,
    continut      TEXT NOT NULL,
    imagine       VARCHAR(255) DEFAULT NULL,
    url_video     VARCHAR(255) DEFAULT NULL,
    stare         ENUM('ciorna','publicat') NOT NULL DEFAULT 'ciorna',
    creat_la      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modificat_la  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    publicat_la   DATETIME DEFAULT NULL,
    UNIQUE KEY uq_articole_slug (slug),
    KEY idx_articole_utilizator (id_utilizator),
    KEY idx_articole_rubrica (id_rubrica),
    -- Acopera listarea publica: filtrare pe stare, ordonare dupa data publicarii
    KEY idx_articole_listare (stare, publicat_la),
    CONSTRAINT fk_articole_utilizator
        FOREIGN KEY (id_utilizator) REFERENCES utilizatori(id) ON DELETE CASCADE,
    -- RESTRICT, nu CASCADE: stergerea unei rubrici nu are voie sa ia articolele cu ea
    CONSTRAINT fk_articole_rubrica
        FOREIGN KEY (id_rubrica) REFERENCES rubrici(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Cheia unica compusa impiedica adaugarea aceluiasi articol de doua ori la favorite
CREATE TABLE articole_favorite (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    id_utilizator INT NOT NULL,
    id_articol    INT NOT NULL,
    creat_la      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_favorite (id_utilizator, id_articol),
    CONSTRAINT fk_favorite_utilizator
        FOREIGN KEY (id_utilizator) REFERENCES utilizatori(id) ON DELETE CASCADE,
    CONSTRAINT fk_favorite_articol
        FOREIGN KEY (id_articol) REFERENCES articole(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE mesaje (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nume     VARCHAR(100) NOT NULL,
    email    VARCHAR(100) NOT NULL,
    telefon  VARCHAR(30) DEFAULT NULL,
    continut TEXT NOT NULL,
    citit    TINYINT(1) NOT NULL DEFAULT 0,
    creat_la DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_mesaje_creat (creat_la)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Pastram hash-ul IP-ului, nu IP-ul: numaram vizitatori unici fara date personale
CREATE TABLE vizite (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    cale       VARCHAR(255) NOT NULL,
    id_articol INT DEFAULT NULL,
    hash_ip    CHAR(64) NOT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    creat_la   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_vizite_creat (creat_la),
    KEY idx_vizite_articol (id_articol),
    KEY idx_vizite_unic (hash_ip, creat_la),
    -- SET NULL, nu CASCADE: vizita a avut loc, chiar daca articolul a fost sters
    CONSTRAINT fk_vizite_articol
        FOREIGN KEY (id_articol) REFERENCES articole(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Continut preluat si parsat dintr-un flux extern; guid pentru deduplicare
CREATE TABLE stiri_externe (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    sursa       VARCHAR(50)  NOT NULL,
    guid        VARCHAR(255) NOT NULL,
    titlu       VARCHAR(255) NOT NULL,
    link        VARCHAR(500) NOT NULL,
    publicat_la DATETIME DEFAULT NULL,
    preluat_la  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_stiri (sursa, guid),
    KEY idx_stiri_publicat (publicat_la)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
