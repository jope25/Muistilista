-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Kayttaja(
    id SERIAL PRIMARY KEY,
    nimi varchar(25) NOT NULL,
    salasana varchar(50) NOT NULL
);

CREATE TABLE Tarkeysaste(
    id SERIAL PRIMARY KEY,
    kayttaja integer REFERENCES Kayttaja(id),
    nimi varchar(25) NOT NULL,
    tarkeys integer NOT NULL,
    lisatieto varchar(500),
    CONSTRAINT tarkista_tarkeys CHECK (tarkeys > 0 AND tarkeys < 6)
);

CREATE TABLE Luokka(
    id SERIAL PRIMARY KEY,
    kayttaja integer REFERENCES Kayttaja(id),
    nimi varchar(25) NOT NULL,
    lisatieto varchar(500)
);

CREATE TABLE Askare(
    id SERIAL PRIMARY KEY,
    kayttaja integer REFERENCES Kayttaja(id),
    ta integer REFERENCES Tarkeysaste(id),
    nimi varchar(25) NOT NULL,
    valmis boolean DEFAULT FALSE,
    paivan_indeksi integer,
    lisatieto varchar(500)
);

CREATE TABLE Askareluokka(
    askare integer REFERENCES Askare(id),
    luokka integer REFERENCES Luokka(id)
);