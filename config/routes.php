<?php

$routes->get('/', function() {
AskareKontrolleri::index();
});

$routes->get('/askare', function() {
AskareKontrolleri::index();
});

$routes->get('/askare/:id', function($id){
    AskareKontrolleri::nayta($id);
});



$routes->get('/hiekkalaatikko', function() {
HelloWorldController::sandbox();
});

$routes->get('/askareet', function() {
HelloWorldController::askare_listaus();
});

$routes->get('/askareet/1', function() {
HelloWorldController::askare();
});

$routes->get('/askareet/1/muokkaus', function() {
HelloWorldController::askare_muokkaus();
});
