<?php
/**
 * Created by PhpStorm.
 * User: Lauri
 * Date: 19.09.2016
 * Time: 14:19
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require 'Bike.php';

function findBikes() {

    $conn = createConnection();
    $sql = "SELECT * FROM bikes";
    $stmt = $conn->query($sql);

    $bikes = [];

    while ($row = $stmt->fetch()) {

        $bike = new Bike();
        $bike->id = $row['id'];
        $bike->name = $row['name'];
        $bike->color = $row['color'];

        array_push($bikes, $bike);
    }
    return json_response(200,$bikes);
}

function addBike($request) {

    $data = json_decode($request->getContent(), true);
    $request->request->replace(is_array($data) ? $data : array());

    $conn = createConnection();

    $sql = "INSERT INTO bikes(name, color) VALUES (?, ?) RETURNING *";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $request->request->get('name'));
    $stmt->bindValue(2, $request->request->get('color'));
    $stmt->execute();

    while ($row = $stmt->fetch()) {

        $bike = new Bike();
        $bike->id = $row['id'];
        $bike->name = $row['name'];
        $bike->color = $row['color'];
    }

    return json_response(200, $bike);

}
