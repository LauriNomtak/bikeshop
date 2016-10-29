<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

use Api\Controllers\Security;
use Api\Controllers\Controller;

$app = new Silex\Application();

$app['debug'] = true;



/*$app->before(function(Request $request, Application $app) {
    try {
        $app['session'] = new \Symfony\Component\HttpFoundation\Session\Session();
        $security = new Security();
        $security->authenticate($app, $request);

    } catch (\Exception $e) {
        error_log($e->getMessage());
        $controller = new Controller();
        return new Response($controller->sendError("700"));
        $app['session']->invalidate();
        die;
    }
});*/

$app->mount('/', new Api\Controllers\ClassController());

/*$app->POST('/bikes', function(Application $app, Request $request) {
    return new Response(addBike($request));
});


$app->DELETE('/bikes/{bikeId}', function(Application $app, Request $request, $bikeId) {

    $bikesController = new ApiControllers\Bikes();

    return new Response($bikesController->deleteBike());
});


$app->GET('/bikes/{bikeId}', function(Application $app, Request $request, $bikeId) {

    $bikesController = new Api\Controllers\Bikes();

    return new Response($bikesController->findBikeById($bikeId));

});


$app->GET('/bikes', function(Application $app, Request $request) {

    $bikesController = new Api\Controllers\Bikes();

    return new Response($bikesController->findBikes());

});


$app->PUT('/bikes/{bikeId}', function(Application $app, Request $request, $bikeId) {

    return new Response('How about implementing updateBike as a PUT method ?');

});

$app->GET('/stores/{bikeId}', function(Application $app, Request $request, $bikeId) {

    return new Response(findBikesStores($bikeId));

});*/

$app->run();
