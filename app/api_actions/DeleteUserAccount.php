<?php

namespace app\api_actions;
use stdClass;
use app\DatabaseConnection;

class DeleteUserAccount extends Action {

    static function doAction($params, $userID = null): object {
        $response = new stdClass();
        $db = new DatabaseConnection();

        if($db->checkCredentials($params->username, $params->password)) {
            $oldPicture = $db->getProfilePicture($userID);
            if($oldPicture != '/profilePictures/default-profile-img.svg') {
                unlink('..' . $oldPicture); //TODO: correct relative Path
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