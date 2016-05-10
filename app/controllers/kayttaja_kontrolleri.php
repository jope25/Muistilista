<?php

class KayttajaKontrolleri extends BaseController {

    public static function sisaankirjautuminen() {
        if (self::get_user_logged_in()) {
            Redirect::to('/askare');
        } else {
            View::make('kayttaja/kirjautuminen.html');
        }
    }

    public static function kasittele_kirjautuminen() {
        $params = $_POST;

        $kayttaja = Kayttaja::todenna($params['nimi'], $params['salasana']);

        if (!$kayttaja) {
            View::make('kayttaja/kirjautuminen.html', array('virhe' =>
                'Väärä käyttäjätunnus tai salasana!', 'nimi' => $params['nimi']));
        } else {
            $_SESSION['kayttaja'] = $kayttaja->id;

            Redirect::to('/askare', array('viesti' => 'Tervetuloa takaisin ' . $kayttaja->nimi . '!'));
        }
    }

    public static function uloskirjautuminen() {
        $_SESSION['kayttaja'] = null;
        Redirect::to('/kirjautuminen', array('viesti' => 'Olet kirjautunut ulos!'));
    }

    public static function rekisteroityminen() {
        View::make('kayttaja/rekisteroityminen.html');
    }

    public static function kasittele_rekisteroityminen() {
        $params = $_POST;
        $attribuutit = array(
            'nimi' => $params['nimi'],
            'salasana' => $params['salasana'],
            'tarkistus' => $params['tarkistus'],
        );
        $kayttaja = new Kayttaja($attribuutit);
        $virheet = $kayttaja->virheet();

        if (count($virheet) == 0) {
            $kayttaja->tallenna();
            Redirect::to('/kirjautuminen', array('viesti' => 'Sinut on rekisteröity! '
                . 'Voit kirjautua sisään uusilla tunnuksillasi!'));
        } else {
            View::make('kayttaja/rekisteroityminen.html', array('virheet' => $virheet,
                'attribuutit' => $attribuutit));
        }
    }

}
