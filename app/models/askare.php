<?php

class Askare extends BaseModel {

    public $id, $kayttaja, $ta, $nimi, $valmis, $lisatty, $lisatieto;

    public function _construct($attribuutit) {
        parent::_construct($attribuutit);
    }

    public static function all() {
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

    public static function find($id) {
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

}
