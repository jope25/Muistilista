-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kayttaja (nimi, salasana) VALUES ('testikayttaja', 'Testi123');
INSERT INTO Luokka (kayttaja, nimi) VALUES (1, 'Koulu');
INSERT INTO Luokka (kayttaja, nimi) VALUES (1, 'Koti');
INSERT INTO Tarkeysaste (kayttaja, nimi, tarkeys) VALUES (1, 'Keski', 3);
INSERT INTO Tarkeysaste (kayttaja, nimi, tarkeys) VALUES (1, 'Tärkein', 5);
INSERT INTO Tarkeysaste (kayttaja, nimi, tarkeys) VALUES (1, 'Jos löytyy aikaa', 1);
INSERT INTO Askare (kayttaja, ta, nimi, paivan_indeksi) VALUES (1, 1, 'Imuroi', 1);
INSERT INTO Askare (kayttaja, ta, nimi, paivan_indeksi) VALUES (1, 2, 'Läksyt', 2);
INSERT INTO Askare (kayttaja, ta, nimi, paivan_indeksi) VALUES (1, 3, 'Pese ikkunat', 3);
INSERT INTO Askareluokka VALUES (1, 2);
INSERT INTO Askareluokka VALUES (2, 1);
INSERT INTO Askareluokka VALUES (3, 2);