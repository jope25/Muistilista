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

$routes->get('/rekisteroityminen', function(){
  KayttajaKontrolleri::rekisteroityminen();
});

$routes->post('/rekisteroityminen', function(){
  KayttajaKontrolleri::kasittele_rekisteroityminen();
});

$routes->post('/uloskirjautuminen', function(){
    KayttajaKontrolleri::uloskirjautuminen();
});

$routes->get('/ta', function() {
    TaKontrolleri::index();
});

$routes->post('/ta', function() {
    TaKontrolleri::lisaa();
});

$routes->get('/ta/uusi', function() {
    TaKontrolleri::luo();
});

$routes->get('/ta/:id', function($id) {
    TaKontrolleri::nayta($id);
});

$routes->get('/ta/:id/muokkaa', function($id){
  TaKontrolleri::muokkaa($id);
});

$routes->post('/ta/:id/muokkaa', function($id){
  TaKontrolleri::paivita($id);
});

$routes->post('/ta/:id/poista', function($id){
  TaKontrolleri::poista($id);
});
