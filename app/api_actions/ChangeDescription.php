<?php
/**
 * Created by PhpStorm.
 * User: LeoP
 * Date: 19.11.2018
 * Time: 10:22
 */

namespace app\api_actions;
use stdClass;
use app\DatabaseConnection;

class ChangeDescription extends Action {

    static function doAction($params, $userID): stdClass
    {
        $response = new stdClass();
        $db = new DatabaseConnection();

        if($db->updateUserDescription($userID, $params->newDescription) == 'success') {
            $response->error = '';
        } else {
            $response->error = 'error';
        }

        return $response;
    }

    static function needsToken(): bool
    {
        return true;
    }
}