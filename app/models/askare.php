<?php

class Askare extends BaseModel {

    public $id, $kayttaja, $ta, $nimi, $valmis, $paivan_indeksi, $lisatieto, $viikonpaiva;

    public function _construct($attribuutit) {
        parent::_construct($attribuutit);
// Kint antaa jostain syystä virheilmoituksen "Invalid argument supplied for foreach()" 
// BaseModelin rivistä 23
//
//        $this->validators = array('validoi_pituus($this->nimi, 25)', 'validoi_tarkeysaste()',
//            'validoi_luokat()', 'validoi_lisatieto($this->lisatieto)');
    }

    public static function kaikki($kayttaja_id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Askare WHERE kayttaja = :kayttajaId');
        $kysely->execute(array('kayttajaId' => $kayttaja_id));
        $rivit = $kysely->fetchAll();
        $askareet = array();

        foreach ($rivit as $rivi) {
            $askareet[] = new Askare(array(
                'id' => $rivi['id'],
                'kayttaja' => $rivi['kayttaja'],
                'ta' => $rivi['ta'],
                'nimi' => $rivi['nimi'],
                'valmis' => $rivi['valmis'],
                'paivan_indeksi' => $rivi['paivan_indeksi'],
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
                'paivan_indeksi' => $rivi['paivan_indeksi'],
                'lisatieto' => $rivi['lisatieto']
            ));

            return $askare;
        }

        return null;
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Askare (kayttaja, nimi, '
                . 'paivan_indeksi, lisatieto) VALUES (:kayttaja, :nimi, :paivan_indeksi,'
                . ' :lisatieto) RETURNING id');
        $kysely->execute(array('kayttaja' => $this->kayttaja, 'nimi' => $this->nimi,
            'paivan_indeksi' => $this->paivan_indeksi, 'lisatieto' => $this->lisatieto));
        $rivi = $kysely->fetch();
        $this->id = $rivi['id'];
    }

    public function paivita() {
        $kysely = DB::connection()->prepare('UPDATE Askare SET nimi = :nimi, valmis = :valmis, '
                . 'paivan_indeksi = :paivan_indeksi, lisatieto = :lisatieto '
                . 'WHERE id = :id');
        $kysely->execute(array('nimi' => $this->nimi, 'valmis' => $this->valmis,
            'paivan_indeksi' => $this->paivan_indeksi, 'lisatieto' => $this->lisatieto, 'id' => $this->id));
    }

    public function poista() {
        $eka_kysely = DB::connection()->prepare('DELETE FROM Askareluokka WHERE askare = :id');
        $eka_kysely->execute(array('id' => $this->id));

        $toka_kysely = DB::connection()->prepare('DELETE FROM Askare WHERE id = :id');
        $toka_kysely->execute(array('id' => $this->id));
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

    public function on_kirjautuneen_kayttajan($kayttaja_id) {
        $askare = $this->etsi($this->id);
        return $askare->kayttaja == $kayttaja_id;
    }

    public function paivan_indeksi_viikonpaivaksi() {
        if ($this->paivan_indeksi === 1) {
            $this->viikonpaiva = 'Ma';
        } else if ($this->paivan_indeksi === 2) {
            $this->viikonpaiva = 'Ti';
        } else if ($this->paivan_indeksi === 3) {
            $this->viikonpaiva = 'Ke';
        } else if ($this->paivan_indeksi === 4) {
            $this->viikonpaiva = 'To';
        } else if ($this->paivan_indeksi === 5) {
            $this->viikonpaiva = 'Pe';
        } else if ($this->paivan_indeksi === 6) {
            $this->viikonpaiva = 'La';
        } else {
            $this->viikonpaiva = 'Su';
        }
    }
}
