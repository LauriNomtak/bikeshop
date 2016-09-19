<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

$app = new Silex\Application();


$app->POST('//bikes', function(Application $app, Request $request) {
            
            
            return new Response('How about implementing addBike as a POST method ?');
            });


$app->DELETE('//bikes/{id}', function(Application $app, Request $request, $id) {
            
            
            return new Response('How about implementing deleteBike as a DELETE method ?');
            });


$app->GET('//bikes/{id}', function(Application $app, Request $request, $id) {
            
            
            return new Response('How about implementing findBikeById as a GET method ?');
            });


$app->GET('//bikes', function(Application $app, Request $request) {
            
            
            return new Response('How about implementing findBikes as a GET method ?');
            });


$app->PUT('//bikes/{id}', function(Application $app, Request $request, $id) {
            
            
            return new Response('How about implementing updateBike as a PUT method ?');
            });


$app->run();
