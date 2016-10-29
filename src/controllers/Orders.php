<?php
/**
 * Created by PhpStorm.
 * User: Lauri
 * Date: 17.10.2016
 * Time: 10:43
 */

namespace Api\Controllers;

use Api;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Orders extends Controller
{
    function order(Application $app, Request $request) {

        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());

        try {
            $errors = [];
            $bikeId = $request->request->get('bikeId');
            $storeId = $request->request->get('storeId');
            $amount = $request->request->get('amount');

            if (!$bikeId) {
                array_push($errors, "bikeId");
            }
            if (!$storeId) {
                array_push($errors, "storeId");
            }
            if (!$amount) {
                array_push($errors, "amount");
            }

            if ($errors != null) {
                throw new \Exception("Missing parameters: ".implode(", ", $errors));
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return $this->sendError("703", $errors);
        }

        $client = $app['session']->get('client');

        try {

            $this->checkIfExists($bikeId, 'bikes');
            $this->checkIfExists($storeId, 'stores');

            $sql = "WITH insert1 As (
		                INSERT INTO orders(client_id)
			            VALUES (?)
		                RETURNING id
                    )
                    INSERT INTO order_bike(bike_id, amount, store_id, order_id)
                    VALUES (?, ?, ?, (SELECT id FROM insert1))";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $client);
            $stmt->bindValue(2, $bikeId);
            $stmt->bindValue(3, $amount);
            $stmt->bindValue(4, $storeId);
            $stmt->execute();

            return $this->jsonResponse(200, "Order successful");

        } catch (\Exception $e) {
            if (strpos($e, 'bikes')) {
                error_log($e->getMessage());
                return $this->sendError("701", null);
            } elseif (strpos($e, 'stores')) {
                error_log($e->getMessage());
                return $this->sendError("702", null);
            }
        }
    }
}