<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('suunnitelmat/etusivu.html');
    }

    public static function sandbox() {
        $askare = Askare::find(1);
        $askareet = Askare::all();
        Kint::dump($askare);
        Kint::dump($askareet);
    }

    public static function askare_muokkaus() {
        View::make('suunnitelmat/askare_muokkaus.html');
    }

    public static function askare_listaus() {
        View::make('suunnitelmat/askare_listaus.html');
    }

    public static function askare() {
        View::make('suunnitelmat/askare.html');
    }

}
