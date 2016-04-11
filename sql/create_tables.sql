-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Kayttaja(
    id SERIAL PRIMARY KEY,
    nimi varchar(25) NOT NULL,
    salasana varchar(50) NOT NULL
);

CREATE TABLE Tarkeysaste(
    id SERIAL PRIMARY KEY,
    kayttaja integer REFERENCES Kayttaja(id) NOT NULL,
    nimi varchar(25) NOT NULL,
    tarkeys integer NOT NULL,
    lisatieto text,
    CONSTRAINT tarkista_tarkeys CHECK (tarkeys > 0 AND tarkeys < 6)
);

CREATE TABLE Luokka(
    id SERIAL PRIMARY KEY,
    kayttaja integer REFERENCES Kayttaja(id) NOT NULL,
    nimi varchar(25) NOT NULL,
    lisatieto text
);

CREATE TABLE Askare(
    id SERIAL PRIMARY KEY,
    kayttaja integer REFERENCES Kayttaja(id) NOT NULL,
    ta integer REFERENCES Tarkeysaste(id),
    nimi varchar(25) NOT NULL,
    valmis boolean DEFAULT FALSE,
    lisatty date,
    lisatieto text
);

CREATE TABLE Askareluokka(
    askare integer REFERENCES Askare(id),
    luokka integer REFERENCES Luokka(id)
);