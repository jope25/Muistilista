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
        $askare = new Askare(array(
            'nimi' => $params['nimi'],
            'lisatieto' => $params['lisatieto'],
        ));
        $askare->tallenna();
        Redirect::to('/askare/' . $askare->id, array('viesti' => 'Askare on lisätty '
            . 'muistilistaan!'));
    }

}
