<?php

namespace app\api_actions;
use stdClass;
use app\DatabaseConnection;

class DeleteProfilePicture extends Action {

    static function doAction($params, $userID = null): stdClass {
        $response = new stdClass();
        $db = new DatabaseConnection();

        $oldPicture = $db->getProfilePicture($userID);
        if($oldPicture != '/profilePictures/default-profile-img.svg') {
            unlink('..' . $oldPicture); //TODO: correct relative Path
            $db->updateProfilePicture($userID, '/profilePictures/default-profile-img.svg');
        }
        $response->picturePath = "/profilePictures/default-profile-img.svg";

        return $response;
    }

    static function needsToken(): bool
    {
        return true;
    }
}