<?php
/**
 * Created by PhpStorm.
 * User: Lauri
 * Date: 17.10.2016
 * Time: 10:43
 */

namespace Api\Controllers;

use Api;

class Stores extends Controller
{

    function findBikeInStores($bikeId)
    {
        try {
            $this->checkIfExists($bikeId, 'bikes');

            $sql = "SELECT s.name, sb.stock FROM store_bike sb
            JOIN stores s ON (sb.store_id = s.id)
            JOIN bikes b ON (sb.bike_id = b.id)
            WHERE sb.bike_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $bikeId);
            $stmt->execute();

            $stocks = [];

            while ($row = $stmt->fetch()) {
                $stock = new Api\Models\Stock();
                $stock->store = $row['name'];
                $stock->stock = $row['stock'];
                array_push($stocks, $stock);
                }

            return $this->jsonResponse(200, $stocks);

        } catch (\Exception $e) {
            return $this->sendError("704", null);
        }

    }

}