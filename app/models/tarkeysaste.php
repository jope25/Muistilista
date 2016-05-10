<?php

class Tarkeysaste extends BaseModel {

    public $id, $kayttaja, $nimi, $tarkeys, $lisatieto;

    public function _construct($attribuutit) {
        parent::_construct($attribuutit);
    }

    public static function kaikki($kayttaja_id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Tarkeysaste '
                . 'WHERE kayttaja = :kayttajaId ORDER BY tarkeys DESC');
        $kysely->execute(array('kayttajaId' => $kayttaja_id));
        $rivit = $kysely->fetchAll();
        $asteet = array();

        foreach ($rivit as $rivi) {
            $asteet[] = new Tarkeysaste(array(
                'id' => $rivi['id'],
                'kayttaja' => $rivi['kayttaja'],
                'nimi' => $rivi['nimi'],
                'tarkeys' => $rivi['tarkeys'],
                'lisatieto' => $rivi['lisatieto']
            ));
        }

        return $asteet;
    }

    public static function etsi($id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Tarkeysaste WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $ta = new Tarkeysaste(array(
                'id' => $rivi['id'],
                'kayttaja' => $rivi['kayttaja'],
                'nimi' => $rivi['nimi'],
                'tarkeys' => $rivi['tarkeys'],
                'lisatieto' => $rivi['lisatieto']
            ));

            return $ta;
        }

        return null;
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Tarkeysaste (kayttaja, nimi, '
                . 'tarkeys, lisatieto) VALUES (:kayttaja, :nimi, :tarkeys, :lisatieto) '
                . 'RETURNING id');
        $kysely->execute(array('kayttaja' => $this->kayttaja, 'nimi' => $this->nimi,
            'tarkeys' => $this->tarkeys, 'lisatieto' => $this->lisatieto));
        $rivi = $kysely->fetch();
        $this->id = $rivi['id'];
    }

    public function paivita() {
        $kysely = DB::connection()->prepare('UPDATE Tarkeysaste SET nimi = :nimi, '
                . 'tarkeys = :tarkeys, lisatieto = :lisatieto '
                . 'WHERE id = :id');
        $kysely->execute(array('nimi' => $this->nimi, 'tarkeys' => $this->tarkeys,
            'lisatieto' => $this->lisatieto, 'id' => $this->id));
    }

    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Tarkeysaste WHERE id = :id');
        $kysely->execute(array('id' => $this->id));
    }

    // Validoidaan nimi ja lisatieto ja palautetaan mahdolliset virheet.
    public function virheet() {
        $nimen_validointi = $this->validoi_pituus($this->nimi, 25);
        $lisatiedon_validointi = $this->validoi_lisatieto($this->lisatieto);
        $virheet = array_merge($nimen_validointi, $lisatiedon_validointi);
        if ($this->tarkeys == '' || $this->tarkeys == null) {
            $virheet[] = 'Tärkeysasteella tulee olla tärkeys!';
        }
        return $virheet;
    }

    // Tarkistetaan, onko ta kirjautuneen käyttäjän.
    public function on_kirjautuneen_kayttajan($kayttaja_id) {
        $ta = $this->etsi($this->id);
        return $ta->kayttaja == $kayttaja_id;
    }
    
    // Tarkistetaan, onko tärkeysaste asetettu johonkin askareeseen.
    public function voiko_poistaa() {
        $kysely = DB::connection()->prepare('SELECT * FROM Askare WHERE ta = :id LIMIT 1');
        $kysely->execute(array('id' => $this->id));
        $rivi = $kysely->fetch();
        if ($rivi) {
            return false;
        }
        return true;
    }
}
