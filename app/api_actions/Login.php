<?php

namespace app\api_actions;
use app\TokenManagement;
use stdClass;
use app\DatabaseConnection;

class Login extends Action {

    public static function doAction($params, $userID = null): stdClass {
        $response = new stdClass();
        $db = new DatabaseConnection();

        switch ($db->checkCredentials($params->username, $params->password)) {
            case "success":
                $response->userID = $db->getIDbyUsername($params->username);
                $response->username = $params->username;
                $response->auth = 'true';
                $response->error = '';
                $response->picturePath = $db->getProfilePicture($response->userID);
                TokenManagement::generateToken($response->userID);
                break;
            case "invalid":
                $response->auth = 'false';
                $response->error = 'invalid';
                break;
            case "noSuchUser":
                $response->auth = 'false';
                $response->error = 'noSuchUser';
                break;
        }
        return $response;
    }

    public static function needsToken(): bool {
        return false;
    }
}