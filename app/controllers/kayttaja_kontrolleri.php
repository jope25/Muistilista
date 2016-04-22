<?php

class KayttajaKontrolleri extends BaseController {

    public static function kirjautuminen() {
        View::make('kayttaja/kirjautuminen.html');
    }

    public static function kasittele_kirjautuminen() {
        $params = $_POST;

        $kayttaja = Kayttaja::todenna($params['nimi'], $params['salasana']);

        if (!$kayttaja) {
            View::make('kayttaja/kirjautuminen.html', array('virhe' => 
                'Väärä käyttäjätunnus tai salasana!', 'nimi' => $params['nimi']));
        } else {
            $_SESSION['kayttaja'] = $kayttaja->id;

            Redirect::to('/', array('viesti' => 'Tervetuloa takaisin ' . $kayttaja->nimi . '!'));
        }
    }

}
