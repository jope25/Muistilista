<?php

class Kayttaja extends BaseModel {

    public $id, $nimi, $salasana;

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

}
