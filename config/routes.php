<?php

$routes->get('/', function() {
    AskareKontrolleri::index();
});

$routes->get('/askare', function() {
    AskareKontrolleri::index();
});

$routes->post('/askare', function() {
    AskareKontrolleri::lisaa();
});

$routes->get('/askare/uusi', function() {
    AskareKontrolleri::luo();
});

$routes->get('/askare/:id', function($id) {
    AskareKontrolleri::nayta($id);
});

$routes->get('/askare/:id/muokkaa', function($id){
  AskareKontrolleri::muokkaa($id);
});

$routes->post('/askare/:id/muokkaa', function($id){
  AskareKontrolleri::paivita($id);
});

$routes->post('/askare/:id/poista', function($id){
  AskareKontrolleri::poista($id);
});

$routes->get('/kirjautuminen', function(){
  KayttajaKontrolleri::sisaankirjautuminen();
});
$routes->post('/kirjautuminen', function(){
  KayttajaKontrolleri::kasittele_kirjautuminen();
});

$routes->post('/uloskirjautuminen', function(){
    KayttajaKontrolleri::uloskirjautuminen();
});
