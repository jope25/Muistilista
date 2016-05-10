<?php

class AskareKontrolleri extends BaseController {

    public static function index() {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $askareet = Askare::kaikki($kayttaja_id);
        
        foreach ($askareet as $askare) {
            $askare->paivan_indeksi_viikonpaivaksi();
        }
        View::make('askare/index.html', array('askareet' => $askareet));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $kayttaja_id = self::get_user_logged_in()->id;
        $askare = Askare::etsi($id);
        $askare->paivan_indeksi_viikonpaivaksi();
        
        if ($askare->kayttaja == $kayttaja_id) {
            View::make('askare/nayta.html', array('askare' => $askare));
        } else {
            Redirect::to('/askare', array('virhe' => 'Askare ei kuulu muistilistaasi!'));
        }
    }

    public static function luo() {
        self::check_logged_in();
        
        $kayttaja_id = self::get_user_logged_in()->id;
        $asteet = Tarkeysaste::kaikki($kayttaja_id);
        $luokat = Luokka::kaikki($kayttaja_id);
        
        View::make('askare/uusi.html', array('asteet' => $asteet, 'luokat' => $luokat));
    }

    public static function lisaa() {
        self::check_logged_in();
        $params = $_POST;
        $attribuutit = array(
            'kayttaja' => self::get_user_logged_in()->id,
            'nimi' => $params['nimi'],
            'paivan_indeksi' => $params['paivan_indeksi'],
            'lisatieto' => $params['lisatieto'],
            'luokat' => array()
        );
        if ($params['ta'] > 0) {
            $apu = array('ta' => $params['ta']);
            $attribuutit = array_merge($apu, $attribuutit);
        }
        if (isset($params['luokat'])) {
            $luokat = $params['luokat'];
            foreach ($luokat as $luokka) {
                $attribuutit['luokat'][] = $luokka;
            }
        }
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
        $asteet = Tarkeysaste::kaikki($kayttaja_id);
        $luokat = Luokka::kaikki($kayttaja_id);
        
        if ($askare->kayttaja == $kayttaja_id) {
            if ($askare->valmis) {
                View::make('askare/muokkaa.html', array('attribuutit' => $askare,
                    'valmis' => 'valmis', 'asteet' => $asteet, 'luokat' => $luokat));
            } else {
                View::make('askare/muokkaa.html', array('attribuutit' => $askare, 'asteet' =>
                    $asteet, 'luokat' => $luokat));
            }
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
            'paivan_indeksi' => $params['paivan_indeksi'],
            'lisatieto' => $params['lisatieto'],
            'luokat' => array()
        );
        if (isset($params['tehty'])) {
            $attribuutit['valmis'] = 'TRUE';
        }
        if ($params['ta'] > 0) {
            $apu = array('ta' => $params['ta']);
            $attribuutit = array_merge($apu, $attribuutit);
        }
        if (isset($params['luokat'])) {
            $luokat = $params['luokat'];
            foreach ($luokat as $luokka) {
                $attribuutit['luokat'][] = $luokka;
            }
        }
        $askare = new Askare($attribuutit);
        $virheet = $askare->virheet();
        if (!$askare->on_kirjautuneen_kayttajan($kayttaja_id)) {
            Redirect::to('/askare', array('virhe' => 'Askare ei kuulu muistilistaasi!'));
        }
        if (count($virheet) > 0) {
            if ($attribuutit['valmis'] == 'TRUE') {
                View::make('askare/muokkaa.html', array('virheet' => $virheet,
                    'attribuutit' => $attribuutit, 'valmis' => 'valmis'));
            } else {
                View::make('askare/muokkaa.html', array('virheet' => $virheet,
                    'attribuutit' => $attribuutit));
            }
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
