<?php

namespace app\api_actions;
use stdClass;
use app\DatabaseConnection;

class GetHighscore extends Action {

    static function doAction($params, $userID = null): object {
        $response = new stdClass();
        $db = new DatabaseConnection();

        $highscore = $db->getHighscore($params->gameID);
        if($highscore == null) {
            $response->allHighscore = '0';
        } else {
            $response->allHighscore = $highscore;
        }

        return $response;
    }

    static function needsToken(): bool
    {
        return false;
    }
}