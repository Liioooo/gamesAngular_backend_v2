<?php
/**
 * Created by PhpStorm.
 * User: LeoP
 * Date: 19.11.2018
 * Time: 10:19
 */

namespace app\api_actions;
use app\DatabaseConnection;
use stdClass;

class ChangePassword extends Action {

    static function doAction($params, $userID): stdClass
    {
        $response = new stdClass();
        $db = new DatabaseConnection();

        if($db->updatePassword($userID, $params->newPassword, $params->oldPassword) == 'success') {
            $response->error = '';
        } else {
            $response->error = 'invalid';
        }

        return $response;
    }

    static function needsToken(): bool
    {
        return true;
    }
}