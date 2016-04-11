<?php

class AskareKontrolleri extends BaseController {

    public static function index() {
        $askareet = Askare::all();
        View::make('askare/index.html', array('askareet' => $askareet));
    }
    
    public static function nayta($id) {
        $askare = Askare::find($id);
        View::make('askare/nayta.html', array('askare' => $askare));
    }

}
