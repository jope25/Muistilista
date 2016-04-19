<?php

class Hiekkalaatikko extends BaseController {

    public static function sandbox() {
        $testi = new Askare(array(
            'nimi' => 'abc',
            'lisatieto' => 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . 'TestiTestiTestiTestiTestiTestiTestiTestiTestiTesti'
                . '1'
        ));
        $virheet = $testi->virheet();

        Kint::dump($virheet);
    }

}
