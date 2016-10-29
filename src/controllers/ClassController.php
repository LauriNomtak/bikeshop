<?php
/**
 * Created by PhpStorm.
 * User: Lauri
 * Date: 11.10.2016
 * Time: 16:53
 */

namespace Api\Controllers;

use Api;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;


class ClassController implements ControllerProviderInterface
{
    public function connect(Application $app) {

        $factory=$app['controllers_factory'];
        $factory->get('/bikes', $this->controller('Bikes/findBikes'));
        $factory->post('/bikes', $this->controller('Bikes/addBike'));
        $factory->get('/bikes/{bikeId}', $this->controller('Bikes/findBikeById'));
        $factory->put('/bikes/{bikeId}', $this->controller('Bikes/updateBike'));
        $factory->delete('/bikes/{bikeId}', $this->controller('Bikes/deleteBike'));
        $factory->get('/stores/{bikeId}', $this->controller('Stores/findBikeInStores'));
        $factory->post('/order', $this->controller('Orders/order'));
        return $factory;
    }

    function controller($shortName)
    {
        list($shortClass, $shortMethod) = explode('/', $shortName, 2);

        return sprintf('Api\Controllers\%s::%s', ucfirst($shortClass), $shortMethod);
    }
}