<?php

class AskareKontrolleri extends BaseController {

    public static function index() {
        self::check_logged_in();
        $askareet = Askare::kaikki($_SESSION['kayttaja']);
        View::make('askare/index.html', array('askareet' => $askareet));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $askare = Askare::etsi($id);
        View::make('askare/nayta.html', array('askare' => $askare));
    }

    public static function luo() {
        self::check_logged_in();
        View::make('askare/uusi.html');
    }

    public static function lisaa() {
        self::check_logged_in();
        $params = $_POST;
        $attribuutit =array(
            'kayttaja' => $_SESSION['kayttaja'],
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
        $askare = Askare::etsi($id);
        View::make('askare/muokkaa.html', array('attribuutit' => $askare));
    }

    public static function paivita($id) {
        self::check_logged_in();
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
        $askare = new Askare(array('id' => $id));
        $askare->poista();
        Redirect::to('/askare', array('viesti' => 'Askare on poistettu onnistuneesti!'));
    }

}
