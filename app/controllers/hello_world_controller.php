<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('suunnitelmat/etusivu.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        View::make('helloworld.html');
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
