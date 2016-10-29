<?php
/**
 * Created by PhpStorm.
 * User: Lauri
 * Date: 19.09.2016
 * Time: 14:19
 */
namespace Api\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Api;

class Bikes extends Controller
{
    function findBikes()
    {

        $sql = "SELECT * FROM bikes";
        $stmt = $this->db->query($sql);

        $bikes = [];

        while ($row = $stmt->fetch()) {

            $bike = new Api\Models\Bike();
            $bike->id = $row['id'];
            $bike->name = $row['name'];
            $bike->color = $row['color'];

            array_push($bikes, $bike);
        }
        return $this->jsonResponse( 200, $bikes);
    }

    function addBike(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());

            $processedRequest = $this->verifyRequest($request, ['name', 'color']);

            $sql = "INSERT INTO bikes(name, color) VALUES (?, ?) RETURNING *";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $processedRequest['name']);
            $stmt->bindValue(2, $processedRequest['color']);
            $stmt->execute();

            $row = $stmt->fetch();

            $bike = new Api\Models\Bike();
            $bike->id = $row['id'];
            $bike->name = $row['name'];
            $bike->color = $row['color'];

            return $this->jsonResponse(200, $bike);

        } catch(\Exception $e) {
            return $this->sendError("703", explode(",",$e->getMessage()));
        }
    }

    function findBikeById($bikeId)
    {
        try {
            $this->checkIfExists($bikeId, 'bikes');

            $sql = "SELECT * FROM bikes WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $bikeId);
            $stmt->execute();

            $row = $stmt->fetch();

            $bike = new Api\Models\Bike();
            $bike->id = $row['id'];
            $bike->name = $row['name'];
            $bike->color = $row['color'];

            return $this->jsonResponse(200, $bike);
        } catch (\Exception $e) {
            return $this->sendError("701", null);
        }
    }

    function deleteBike($bikeId)
    {
        try {
            $this->checkIfExists($bikeId, 'bikes');

            $sql = "DELETE FROM bikes WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $bikeId);
            $stmt->execute();

            return $this->jsonResponse(200, 'Deleted');
        } catch (\Exception $e) {
            return $this->sendError("701", null);
        }
    }

    function updateBike($bikeId, Request $request) {

        try {
            $this->checkIfExists($bikeId, 'bikes');

            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());

            $processedRequest = $this->verifyRequest($request, ['name', 'color']);

            $sql = "UPDATE bikes SET name=?, color=? WHERE id=? RETURNING *";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $processedRequest['name']);
            $stmt->bindValue(2, $processedRequest['color']);
            $stmt->bindValue(3, $bikeId);
            $stmt->execute();

            $row = $stmt->fetch();

            $bike = new Api\Models\Bike();
            $bike->id = $row['id'];
            $bike->name = $row['name'];
            $bike->color = $row['color'];

            return $this->jsonResponse('200', $bike);

        } catch (\Exception $e) {
            if ($e->getCode() == "703") {
                return $this->sendError("703", explode(",", $e->getMessage()));
            } else {
                return $this->sendError("701" , null);
            }
        }
    }
}