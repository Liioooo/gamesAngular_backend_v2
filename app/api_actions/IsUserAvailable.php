<?php

namespace app\api_actions;
use app\DatabaseConnection;
use stdClass;

class IsUserAvailable extends Action {

    static function doAction($params, $userID = null): stdClass {
        $response = new stdClass();
        $db = new DatabaseConnection();

        if($db->getIDbyUsername($params->username) == null) {
            $response->available = '1';
        } else {
            $response->available = '0';
        }
        return $response;
    }

    static function needsToken(): bool
    {
        return false;
    }
}