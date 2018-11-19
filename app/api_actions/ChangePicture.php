<?php

namespace app\api_actions;
use stdClass;
use app\DatabaseConnection;

class ChangePicture extends Action {

    static function doAction($params, $userID): stdClass
    {
        $response = new stdClass();
        $db = new DatabaseConnection();

        $newPicture = ChangePicture::base64_to_file($params->file, $userID);
        if($newPicture == 'error') {
            $response->error = 'invalidFile';
        } else {
            $oldPicture = $db->getProfilePicture($userID);
            if($oldPicture != '/profilePictures/default-profile-img.svg' && $oldPicture != $newPicture) {
                unlink('..' . $oldPicture); //TODO: relative Path
            }
            $db->updateProfilePicture($userID, $newPicture);
            $response->error = '';
            $response->picture = $newPicture;
        }

        return $response;
    }

    static function needsToken(): bool
    {
        return true;
    }

    static private function base64_to_file($base64_string, $output_file) {
        $extension = explode('/', mime_content_type($base64_string))[1];
        if(!($extension == 'jpeg' || $extension == 'png' || $extension == 'gif')) {
            return 'error';
        }
        $ifp = fopen( '../profilePictures/' . $output_file . '.' . $extension, 'wb' ); //TODO: relative Path
        $data = explode( ',', $base64_string );
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );
        return '/profilePictures/' . $output_file . '.' . $extension;
    }
}