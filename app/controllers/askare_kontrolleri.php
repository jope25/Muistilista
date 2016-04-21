<?php

class AskareKontrolleri extends BaseController {

    public static function index() {
        $askareet = Askare::kaikki();
        View::make('askare/index.html', array('askareet' => $askareet));
    }

    public static function nayta($id) {
        $askare = Askare::etsi($id);
        View::make('askare/nayta.html', array('askare' => $askare));
    }

    public static function luo() {
        View::make('askare/uusi.html');
    }

    public static function lisaa() {
        $params = $_POST;
        $attribuutit =array(
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
        $askare = Askare::etsi($id);
        View::make('askare/muokkaa.html', array('attribuutit' => $askare));
    }

    public static function paivita($id) {
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
        $askare = new Askare(array('id' => $id));
        $askare->poista();
        Redirect::to('/askare', array('viesti' => 'Askare on poistettu onnistuneesti!'));
    }

}
