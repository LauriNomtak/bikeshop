<?php
/**
 * Created by PhpStorm.
 * User: Lauri
 * Date: 17.10.2016
 * Time: 13:42
 */

namespace Api\controllers;

use Api\Controllers\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;;


class Security extends Controller
{
    public function authenticate(Application $app, Request $request)
    {
        $sql = "SELECT name, password FROM clients";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $key = $request->headers->get('KEY');

        while ($row = $stmt->fetch()) {
            $flag = 'false';
            $data = [];
            $data[0] = $row['name'];
            $data[1] = $row['password'];

            $compareKey = hash("sha256", implode($data));

            if ($key === $compareKey) {
                $flag = 'true';
                $app['session']->set('client', $data[0]);
                break;
            }
        }
        if ($flag === "false") {
            throw new \Exception("Authentication failed");
        }
    }


}