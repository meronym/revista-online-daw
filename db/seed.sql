-- Date de test pentru dezvoltare
-- Toti utilizatorii au parola: parola123

SET NAMES utf8mb4;

-- Rulabil de oricate ori: golim in ordinea dependentelor si folosim id-uri
-- explicite, ca legaturile dintre tabele sa nu depinda de AUTO_INCREMENT
DELETE FROM articole_favorite;
DELETE FROM vizite;
DELETE FROM mesaje;
DELETE FROM articole;
DELETE FROM rubrici;
DELETE FROM utilizatori;


INSERT INTO utilizatori (id, nume_utilizator, email, parola, rol) VALUES
(1, 'admin',   'admin@revista.test',   '$2y$10$QwKGnhf8HyP./egDcg82LugG50v.DE1p5SPuY0seUwRjClHgFMAji', 'admin'),
(2, 'ioana',   'ioana@revista.test',   '$2y$10$/MM.0RRAUFY7m/t8i6Fk1uqgxzYep53TFlKsxmcIZ8T7JhLHznfPC', 'autor'),
(3, 'radu',    'radu@revista.test',    '$2y$10$I48j5hVMEWhGoMYA309sjOvuXo8bmTfFSTQcfzz55GyuOzNFrh8b.', 'autor'),
(4, 'cititor', 'cititor@revista.test', '$2y$10$QsemgxXIaZk.I5MNuifH5.NLUNoMRkikZLqV20guZTkjWMBVsUi3G', 'cititor');


INSERT INTO rubrici (id, slug, nume) VALUES
(1, 'actualitate', 'Actualitate'),
(2, 'cultura',     'Cultură'),
(3, 'tehnologie',  'Tehnologie'),
(4, 'sport',       'Sport'),
(5, 'calatorii',   'Călătorii');


-- Datele sunt relative la NOW(), ca revista sa arate populata oricand e reincarcata
INSERT INTO articole (id, id_utilizator, id_rubrica, slug, titlu, rezumat, continut, url_video, stare, creat_la, publicat_la) VALUES
(1, 2, 3, 'cum-schimba-inteligenta-artificiala-redactiile-mici',
 'Cum schimbă inteligența artificială redacțiile mici',
 'Instrumentele automate au ajuns și în redacțiile cu trei oameni. Ce se câștigă și ce se pierde.',
 'Într-o redacție de cartier, o zi de lucru înseamnă adesea un singur om care scrie, corectează și publică. Instrumentele automate de transcriere și de corectură au scurtat vizibil drumul de la interviu la text publicat.

Partea mai puțin discutată este verificarea. Un rezumat generat automat sună convingător chiar și atunci când greșește, iar redacțiile mici nu au un al doilea om care să citească înainte de publicare. Soluția pe care au găsit-o câteva publicații locale este simplă: automatizează tot ce ține de formă, dar nimic din ce ține de fapte.',
 NULL, 'publicat', DATE_SUB(NOW(), INTERVAL 9 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),

(2, 3, 2, 'un-festival-de-film-care-umple-salile-din-cluj',
 'Un festival de film care umple sălile din Cluj',
 'Zece zile, patru săli și o competiție în care jumătate dintre regizori sunt la primul lungmetraj.',
 'Programul de anul acesta a mizat pe debuturi, iar pariul a ieșit: proiecțiile de seară s-au vândut integral în primele două zile. Organizatorii spun că publicul tânăr a fost majoritar, ceea ce nu se întâmpla acum cinci ani.

Dincolo de competiție, secțiunea de documentar a atras atenția prin trei filme despre orașe în schimbare. Toate trei au fost filmate pe parcursul a mai mult de doi ani, iar diferența de ritm față de restul programului s-a simțit imediat în sală.',
 'https://www.youtube.com/watch?v=YE7VzlLtp-4',
 'publicat', DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),

(3, 2, 5, 'trei-zile-pe-transalpina-in-afara-sezonului',
 'Trei zile pe Transalpina, în afara sezonului',
 'La sfârșitul lui septembrie drumul e gol, pensiunile sunt ieftine, iar vremea se schimbă de două ori pe zi.',
 'Am plecat într-o joi dimineață, cu ideea că vom prinde ultimele zile bune. Pe primii kilometri am întâlnit trei mașini. La 2.000 de metri era ceață deasă și șapte grade, iar peste o oră, soare.

Cazările din afara sezonului costă aproape jumătate, dar multe se închid la începutul lui octombrie. Merită sunat înainte, pentru că informațiile de pe internet rămân neactualizate până în primăvară.',
 NULL, 'publicat', DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),

(4, 3, 1, 'bucurestiul-isi-numara-din-nou-copacii',
 'Bucureștiul își numără din nou copacii',
 'Un inventar început acum patru ani a ajuns la jumătate. Diferențele față de estimările vechi sunt mari.',
 'Primele sectoare inventariate au arătat că numărul real de arbori de aliniament este cu aproape un sfert mai mic decât cifra folosită în rapoartele anterioare. Explicația ține mai puțin de tăieri și mai mult de faptul că vechile evidențe nu fuseseră niciodată verificate pe teren.

Inventarul se face cu GPS și fotografie pentru fiecare exemplar, iar datele ajung într-un registru public. Termenul anunțat pentru finalizare este anul viitor.',
 NULL, 'publicat', DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),

(5, 3, 4, 'handbalul-feminin-intre-doua-generatii',
 'Handbalul feminin, între două generații',
 'Trei jucătoare de bază s-au retras într-un singur sezon. Ce urmează pentru echipa națională.',
 'Retragerile anunțate la finalul sezonului trecut lasă un gol pe care selecționerul îl recunoaște deschis. Media de vârstă a lotului scade cu patru ani, iar meciurile de pregătire din toamnă sunt, practic, singurul test înainte de calificări.

Vestea bună vine din campionatul intern, unde trei cluburi au început să dea constant minute jucătoarelor sub 21 de ani. Este exact ce lipsea acum un deceniu, când generația care tocmai a plecat a intrat în lot fără rodaj.',
 NULL, 'publicat', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),

(6, 2, 3, 'bateriile-care-se-incarca-in-cinci-minute',
 'Bateriile care se încarcă în cinci minute',
 'Anunțurile se țin lanț, dar drumul de la laborator la mașina din curte trece prin infrastructură.',
 'Celulele demonstrate în laborator ating deja pragul de cinci minute pentru optzeci la sută din capacitate. Problema nu mai este chimia, ci puterea pe care o stație trebuie să o livreze într-un interval atât de scurt.

Pentru un parc de zece stații, vârful de consum depășește ce poate susține rețeaua din multe orașe fără investiții separate. De aceea operatorii testează acum stații cu baterie proprie, care se încarcă lent și livrează rapid.',
 NULL, 'publicat', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),

(7, 1, 2, 'biblioteci-de-cartier-redeschise-dupa-zece-ani',
 'Biblioteci de cartier, redeschise după zece ani',
 'Patru filiale închise în 2015 s-au redeschis cu program prelungit și săli de lucru.',
 'Redeschiderea a venit după o consultare publică în care cererea cea mai frecventă nu a fost pentru cărți, ci pentru spațiu de lucru cu internet. Filialele au fost reamenajate în consecință: mai puține rafturi la parter, mai multe mese.

În prima lună, numărul de fișe noi de împrumut a depășit total anului trecut pentru toate filialele la un loc. Programul se închide acum la ora 20, iar sâmbăta sălile sunt pline de dimineață.',
 NULL, 'publicat', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),

(8, 2, 1, 'ce-se-intampla-cu-piata-chiriilor',
 'Ce se întâmplă cu piața chiriilor',
 'Primele date de toamnă arată o creștere mai mică decât în anii trecuți.',
 'Materialul este în lucru: mai avem de verificat cifrele din două orașe universitare și de obținut o reacție de la asociațiile de proprietari.',
 NULL, 'ciorna', DATE_SUB(NOW(), INTERVAL 2 DAY), NULL),

(9, 3, 5, 'delta-in-afara-traseelor-turistice',
 'Delta, în afara traseelor turistice',
 'Cinci zile cu barca, plecând din Sfântu Gheorghe.',
 'Ciorna: notițele de teren sunt gata, fotografiile urmează să fie selectate.',
 NULL, 'ciorna', DATE_SUB(NOW(), INTERVAL 1 DAY), NULL);


INSERT INTO articole_favorite (id_utilizator, id_articol) VALUES
(4, 3),
(4, 6),
(2, 2);


INSERT INTO mesaje (nume, email, telefon, continut, citit, creat_la) VALUES
('Andrei Marinescu', 'andrei.marinescu@example.com', '0722123456',
 'Bună ziua, aș vrea să propun un articol despre pistele de biciclete din Sectorul 3. Am fotografii proprii.', 1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
('Elena Dobre', 'elena.dobre@example.com', NULL,
 'Ați greșit anul în articolul despre bibliotecile de cartier: filialele s-au închis în 2015, nu în 2016.', 0, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('Mihai Pop', 'mihai.pop@example.com', '0745998877',
 'Există posibilitatea unui abonament tipărit sau apăreți doar online?', 0, DATE_SUB(NOW(), INTERVAL 6 HOUR));


-- Cateva vizite ca statisticile sa aiba ce afisa inainte de primul trafic real
INSERT INTO vizite (cale, id_articol, hash_ip, user_agent, creat_la) VALUES
('/',                                                 NULL, SHA2('seed-1', 256), 'Mozilla/5.0 (X11; Linux x86_64)',  DATE_SUB(NOW(), INTERVAL 5 DAY)),
('/articol/trei-zile-pe-transalpina-in-afara-sezonului',  3, SHA2('seed-1', 256), 'Mozilla/5.0 (X11; Linux x86_64)',  DATE_SUB(NOW(), INTERVAL 5 DAY)),
('/',                                                 NULL, SHA2('seed-2', 256), 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0)', DATE_SUB(NOW(), INTERVAL 4 DAY)),
('/rubrica/tehnologie',                               NULL, SHA2('seed-2', 256), 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0)', DATE_SUB(NOW(), INTERVAL 4 DAY)),
('/articol/cum-schimba-inteligenta-artificiala-redactiile-mici', 1, SHA2('seed-3', 256), 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15)', DATE_SUB(NOW(), INTERVAL 3 DAY)),
('/articol/bateriile-care-se-incarca-in-cinci-minute',    6, SHA2('seed-3', 256), 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15)', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('/',                                                 NULL, SHA2('seed-4', 256), 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('/articol/un-festival-de-film-care-umple-salile-din-cluj', 2, SHA2('seed-4', 256), 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('/contact',                                          NULL, SHA2('seed-5', 256), 'Mozilla/5.0 (Android 14; Mobile)', DATE_SUB(NOW(), INTERVAL 8 HOUR)),
('/articol/biblioteci-de-cartier-redeschise-dupa-zece-ani', 7, SHA2('seed-5', 256), 'Mozilla/5.0 (Android 14; Mobile)', DATE_SUB(NOW(), INTERVAL 7 HOUR));

-- stiri_externe ramane goala: se umple la prima preluare a fluxului
