<?php

namespace app\api_actions;
use stdClass;
use app\DatabaseConnection;

class GetHighscoresGame extends Action {

    static function doAction($params, $userID = null): object {
        $response = new stdClass();
        $db = new DatabaseConnection();

        $response->scores = $db->getHighscoresGame($params->gameID);

        return $response;
    }

    static function needsToken(): bool {
        return false;
    }
}