<?php

class AskareKontrolleri extends BaseController {

    public static function index() {
        self::check_logged_in();

        $kayttaja_id = self::get_user_logged_in()->id;

        $askareet = Askare::kaikki($kayttaja_id);
        View::make('askare/index.html', array('askareet' => $askareet));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $askare = Askare::etsi($id);

        if ($askare->kayttaja == $kayttaja_id) {
            View::make('askare/nayta.html', array('askare' => $askare));
        } else {
            Redirect::to('/askare', array('virhe' => 'Askare ei kuulu muistilistaasi!'));
        }
    }

    public static function luo() {
        self::check_logged_in();
        View::make('askare/uusi.html');
    }

    public static function lisaa() {
        self::check_logged_in();
        $params = $_POST;
        $attribuutit = array(
            'kayttaja' => self::get_user_logged_in()->id,
            'nimi' => $params['nimi'],
            'lisatieto' => $params['lisatieto'],
        );
        $askare = new Askare($attribuutit);
        $virheet = $askare->virheet();

        if (count($virheet) == 0) {
            $askare->tallenna();
            Redirect::to('/askare/' . $askare->id, array('viesti' => 'Askare on lisätty '
                . 'muistilistaan!'));
        } else {
            View::make('askare/uusi.html', array('virheet' => $virheet,
                'attribuutit' => $attribuutit));
        }
    }

    public static function muokkaa($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $askare = Askare::etsi($id);

        if ($askare->kayttaja == $kayttaja_id) {
            View::make('askare/muokkaa.html', array('attribuutit' => $askare));
        } else {
            Redirect::to('/askare', array('virhe' => 'Askare ei kuulu muistilistaasi!'));
        }
    }

    public static function paivita($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $params = $_POST;
        $attribuutit = array(
            'id' => $id,
            'nimi' => $params['nimi'],
            'valmis' => 'FALSE',
            'lisatieto' => $params['lisatieto']
        );
        if (isset($_POST['tehty'])) {
            $attribuutit['valmis'] = 'TRUE';
        }
        $askare = new Askare($attribuutit);
        $virheet = $askare->virheet();
        if (!$askare->on_kirjautuneen_kayttajan($kayttaja_id)) {
            Redirect::to('/askare', array('virhe' => 'Askare ei kuulu muistilistaasi!'));
        }
        if (count($virheet) > 0) {
            View::make('askare/muokkaa.html', array('virheet' => $virheet,
                'attribuutit' => $attribuutit));
        } else {
            $askare->paivita();
            Redirect::to('/askare/' . $askare->id, array('viesti' =>
                'Askaretta on muokattu onnistuneesti!'));
        }
    }

    public static function poista($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $askare = new Askare(array('id' => $id));

        if ($askare->on_kirjautuneen_kayttajan($kayttaja_id)) {
            $askare->poista();
            Redirect::to('/askare', array('viesti' => 'Askare on poistettu onnistuneesti!'));
        } else {
            Redirect::to('/askare', array('virhe' => 'Askare ei kuulu muistilistaasi!'));
        }
    }

}
