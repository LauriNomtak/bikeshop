<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

require 'db.php';
require 'bikesController.php';
require 'json_response.php';

$app = new Silex\Application();

$app['debug'] = true;



$app->POST('/bikes', function(Application $app, Request $request) {


            return new Response(addBike($request));
            });


$app->DELETE('/bikes/{id}', function(Application $app, Request $request, $id) {
            
            
            return new Response('How about implementing deleteBike as a DELETE method ?');
            });


$app->GET('/bikes/{id}', function(Application $app, Request $request, $id) {


            return new Response('How about implementing findBikeById as a GET method ?');
            });


$app->GET('/bikes', function(Application $app, Request $request) {

    return new Response(findBikes());

});


$app->PUT('/bikes/{id}', function(Application $app, Request $request, $id) {
            
            
            return new Response('How about implementing updateBike as a PUT method ?');
            });


$app->run();
