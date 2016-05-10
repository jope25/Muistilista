<?php

class Kayttaja extends BaseModel {

    public $id, $nimi, $salasana, $tarkistus;

    public function __construct($attribuutit) {
        parent::__construct($attribuutit);
    }

    public static function etsi($id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'id' => $rivi['id'],
                'nimi' => $rivi['nimi'],
                'salasana' => $rivi['salasana']
            ));

            return $kayttaja;
        }

        return null;
    }

    public static function todenna($nimi, $salasana) {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = '
                . ':nimi AND salasana = :salasana LIMIT 1');
        $kysely->execute(array('nimi' => $nimi, 'salasana' => $salasana));
        $rivi = $kysely->fetch();
        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'id' => $rivi['id'],
                'nimi' => $rivi['nimi'],
                'salasana' => $rivi['salasana']
            ));
            return $kayttaja;
        } else {
            return null;
        }
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, salasana) '
                . 'VALUES (:nimi, :salasana) RETURNING id');
        $kysely->execute(array('nimi' => $this->nimi, 'salasana' => $this->salasana));
        $rivi = $kysely->fetch();
        $this->id = $rivi['id'];
    }
    
    // Validoidaan nimi ja salasana ja palautettaan mahdolliset virheet.
    public function virheet() {
        $virheet = array_merge($this->validoi_kayttajanimi(), $this->validoi_salasana());
        return $virheet;
    }

    // Validoidaan käyttäjänimen pituus ja saatavuus.
    private function validoi_kayttajanimi() {
        $nimen_pituus = $this->validoi_pituus($this->nimi, 25);
        $nimi_kaytossa = $this->kayttajanimi_kaytossa();
        $virheet = array_merge($nimen_pituus, $nimi_kaytossa);
        return $virheet;
    }

    // Tarkistetaan, ettei käyttäjänimi ole käytössä.
    private function kayttajanimi_kaytossa() {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :nimi LIMIT 1');
        $kysely->execute(array('nimi' => $this->nimi));
        $rivi = $kysely->fetch();
        $virhe = array();
        if ($rivi) {
            $virhe[] = "Nimi on jo käytössä!";
        }
        return $virhe;
    }
    
    // Validoidaan salasanan pituus ja samuus tarkistussalasanaan.
    private function validoi_salasana() {
        $salasanan_pituus = $this->validoi_pituus($this->salasana, 50);
        $samat_salasanat = $this->salasanat_tasmaavat();
        $virheet = array_merge($salasanan_pituus, $samat_salasanat);
        return $virheet;
    }
    
    // Tarkistetaan, että salasanat täsmäävät.
    private function salasanat_tasmaavat() {
        $virhe = array();
        if ($this->salasana !== $this->tarkistus) {
            $virhe[] = "Salasanat eivät täsmää!";
        }
        return $virhe;
    }
}
