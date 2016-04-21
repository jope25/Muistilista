<?php

class Askare extends BaseModel {

    public $id, $kayttaja, $ta, $nimi, $valmis, $lisatty, $lisatieto;

    public function _construct($attribuutit) {
        parent::_construct($attribuutit);
// Kint antaa jostain syystä virheilmoituksen "Invalid argument supplied for foreach()" 
// BaseModelin rivistä 23
//
//        $this->validators = array('validoi_pituus($this->nimi, 25)', 'validoi_tarkeysaste()',
//            'validoi_luokat()', 'validoi_lisatieto($this->lisatieto)');
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT * FROM Askare');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $askareet = array();

        foreach ($rivit as $rivi) {
            $askareet[] = new Askare(array(
                'id' => $rivi['id'],
                'kayttaja' => $rivi['kayttaja'],
                'ta' => $rivi['ta'],
                'nimi' => $rivi['nimi'],
                'valmis' => $rivi['valmis'],
                'lisatty' => $rivi['lisatty'],
                'lisatieto' => $rivi['lisatieto']
            ));
        }

        return $askareet;
    }

    public static function etsi($id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Askare WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $askare = new Askare(array(
                'id' => $rivi['id'],
                'kayttaja' => $rivi['kayttaja'],
                'ta' => $rivi['ta'],
                'nimi' => $rivi['nimi'],
                'valmis' => $rivi['valmis'],
                'lisatty' => $rivi['lisatty'],
                'lisatieto' => $rivi['lisatieto']
            ));

            return $askare;
        }

        return null;
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Askare (nimi, lisatieto, lisatty) '
                . 'VALUES (:nimi, :lisatieto, NOW()) '
                . 'RETURNING id');
        $kysely->execute(array('nimi' => $this->nimi, 'lisatieto' => $this->lisatieto));
        $rivi = $kysely->fetch();
        $this->id = $rivi['id'];
    }

    public function paivita() {
        $kysely = DB::connection()->prepare('UPDATE Askare SET nimi = :nimi, valmis = :valmis, '
                . 'lisatieto = :lisatieto '
                . 'WHERE id = :id');
        $kysely->execute(array('nimi' => $this->nimi, 'valmis' => $this->valmis, 
            'lisatieto' => $this->lisatieto, 'id' => $this->id));
    }

    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Askare WHERE Askare.id = :id');
        $kysely->execute(array('id' => $this->id));
    }

    public function virheet() {
        $nimen_validointi = $this->validoi_pituus($this->nimi, 25);
        $tan_validointi = $this->validoi_tarkeysaste();
        $luokkien_validointi = $this->validoi_luokat(); 
        $lisatiedon_validointi = $this->validoi_lisatieto($this->lisatieto);
        
        $eka = array_merge($tan_validointi, $nimen_validointi);
        $toka = array_merge($luokkien_validointi, $lisatiedon_validointi);
        $virheet = array_merge($eka, $toka);
        return $virheet;
    }

}
