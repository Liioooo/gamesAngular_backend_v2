<?php

namespace app\api_actions;
use stdClass;
use app\DatabaseConnection;

class DeleteUserAccount extends Action {

    static function doAction($params, $userID = null): stdClass {
        $response = new stdClass();
        $db = new DatabaseConnection();

        $username = $db->getUsernameByID($userID);

        if($db->checkCredentials($username, $params->password) == 'success') {
            $oldPicture = $db->getProfilePicture($userID);
            if($oldPicture != '/profilePictures/default-profile-img.svg') {
                unlink('..' . $oldPicture);
            }
            $db->deleteUser($userID);
            unset($_COOKIE['jwt-token']);
            setcookie('jwt-token',null,time()-1);
            $response->auth = 'false';
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