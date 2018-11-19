<?php

namespace app\api_actions;
use app\DatabaseConnection;
use stdClass;

class GetUserDescription extends Action {

    static function doAction($params, $userID = null): stdClass {
        $response = new stdClass();
        $db = new DatabaseConnection();

        $response->description = $db->getUserDescription($userID);

        return $response;
    }

    static function needsToken(): bool
    {
        return true;
    }
}