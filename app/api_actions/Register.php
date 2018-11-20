<?php

namespace app\api_actions;
use app\TokenManagement;
use stdClass;
use app\DatabaseConnection;

class Register extends Action {

    static function doAction($params, $userID = null): stdClass {
        $response = new stdClass();
        $db = new DatabaseConnection();

        switch ($db->createUser($params->username, $params->password)) {
            case "success":
                $userID = $db->getIDbyUsername($params->username);
                $response->username = $params->username;
                $response->auth = 'true';
                $response->error = '';
                $response->picturePath = $db->getProfilePicture($userID);
                TokenManagement::generateToken($userID);
                break;
            case "error":
                $response->auth = 'false';
                $response->error = 'alreadyExists';
                break;
        }
        return $response;
    }

    static function needsToken(): bool
    {
        return false;
    }
}