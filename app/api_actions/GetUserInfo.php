<?php

namespace app\api_actions;
use app\DatabaseConnection;
use stdClass;

class GetUserInfo extends Action {

    static function doAction($params, $userID = null): stdClass {
        $response = new stdClass();
        $db = new DatabaseConnection();

        $userID = $db->getIDbyUsername($params->username);

        if($userID != null) {
            $response->highscores = $db->getHighscoresUser($params->username);
            $response->profilePicture = $db->getProfilePicture($userID);
            $response->description = $db->getUserDescription($userID);
        } else {
            $response->error = 'noSuchUser';
        }

        return $response;
    }

    static function needsToken(): bool {
        return false;
    }
}