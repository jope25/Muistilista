<?php

class Luokka extends BaseModel {

    public $id, $kayttaja, $nimi, $lisatieto;

    public function _construct($attribuutit) {
        parent::_construct($attribuutit);
    }

    public static function kaikki($kayttaja_id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Luokka '
                . 'WHERE kayttaja = :kayttajaId ORDER BY nimi ASC');
        $kysely->execute(array('kayttajaId' => $kayttaja_id));
        $rivit = $kysely->fetchAll();
        $luokat = array();

        foreach ($rivit as $rivi) {
            $luokat[] = new Luokka(array(
                'id' => $rivi['id'],
                'kayttaja' => $rivi['kayttaja'],
                'nimi' => $rivi['nimi'],
                'lisatieto' => $rivi['lisatieto']
            ));
        }

        return $luokat;
    }

    public static function etsi($id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Luokka WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $luokka = new Luokka(array(
                'id' => $rivi['id'],
                'kayttaja' => $rivi['kayttaja'],
                'nimi' => $rivi['nimi'],
                'lisatieto' => $rivi['lisatieto']
            ));

            return $luokka;
        }

        return null;
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Luokka (kayttaja, nimi, '
                . ' lisatieto) VALUES (:kayttaja, :nimi, :lisatieto) RETURNING id');
        $kysely->execute(array('kayttaja' => $this->kayttaja, 'nimi' => $this->nimi,
            'lisatieto' => $this->lisatieto));
        $rivi = $kysely->fetch();
        $this->id = $rivi['id'];
    }

    public function paivita() {
        $kysely = DB::connection()->prepare('UPDATE Luokka SET nimi = :nimi,'
                . ' lisatieto = :lisatieto WHERE id = :id');
        $kysely->execute(array('nimi' => $this->nimi,
            'lisatieto' => $this->lisatieto, 'id' => $this->id));
    }

    public function poista() {
        $eka_kysely = DB::connection()->prepare('DELETE FROM Askareluokka WHERE luokka = :id');
        $eka_kysely->execute(array('id' => $this->id));

        $toka_kysely = DB::connection()->prepare('DELETE FROM Luokka WHERE id = :id');
        $toka_kysely->execute(array('id' => $this->id));
    }

    public function virheet() {
        $nimen_validointi = $this->validoi_pituus($this->nimi, 25);
        $lisatiedon_validointi = $this->validoi_lisatieto($this->lisatieto);
        $virheet = array_merge($nimen_validointi, $lisatiedon_validointi);
        return $virheet;
    }

    public function on_kirjautuneen_kayttajan($kayttaja_id) {
        $luokka = $this->etsi($this->id);
        return $luokka->kayttaja == $kayttaja_id;
    }

}
