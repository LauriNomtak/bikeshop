<?php
/**
 * Created by PhpStorm.
 * User: Lauri
 * Date: 19.09.2016
 * Time: 14:20
 */
namespace Api\Controllers;

use Doctrine\DBAL\DriverManager;
use Api;

class Controller
{
    public $db = null;

    function __construct()
    {

        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'dbname' => 'bikeshop',
            'user' => 'postgres',
            'password' => 'postgres',
            'host' => 'localhost',
            'port' => '5432',
            'driver' => 'pdo_pgsql',
        );

        $this->db = DriverManager::getConnection($connectionParams, $config);
    }



    function jsonResponse($code = 200, $message = null)
    {
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($code);
        // set the header to make sure cache is forced
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // treat this as json
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            204 => '204 Deleted',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        );
        // ok, validation error, or failure
        header('Status: '.$status[$code]);
        // return the encoded json
        return json_encode((
        $message
        ));
    }

    function checkIfExists($id, $database)
    {
        $sql = "SELECT id FROM $database WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if ($stmt->fetch() == null) {
            if($database == "bikes") {
                throw new \Exception("Id not found in ".$database, 701);
            } elseif ($database == "stores") {
                throw new \Exception("Id not found in ".$database, 702);
            }
        }
    }

    function sendError($code, $params) {
        $error = new Api\Models\BikeError();
        $error->code = $code;

        switch ($code) {
            case "700":
                $message = "Authentication failed";
                break;
            case "701":
                $message = "Bike not found";
                break;
            case "702":
                $message = "Store not found";
                break;
            case "703":
                $message = "Body missing parameters: ".implode(", ", $params);
                break;
            case "704":
                $message = "Bike not in stores";
                break;
        }

        $error->message = $message;
        return $this->jsonResponse(400, $error);
    }

    function verifyRequest($request, $reqParams) {

        $errors = [];
        $values = [];

        foreach ($reqParams as $param) {
            if (!($request->request->get($param))) {
                array_push($errors, $param);
            } else {
                $values[$param] = $request->request->get($param);
            }
        }

        if ($errors != null) {
            throw new \Exception(implode(",", $errors), 703);
        } else {
            return $values;
        }

    }
}

