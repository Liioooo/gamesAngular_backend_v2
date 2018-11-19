<?php

namespace app\api_actions;
use app\DatabaseConnection;
use stdClass;

class SaveHighscore extends Action {

    static function doAction($params, $userID = null): stdClass {
        $response = new stdClass();
        $db = new DatabaseConnection();

        $highscore = $db->saveHighscore($params->gameID, $userID, $params->score);
        $response->userHighscore = $highscore;
        $response->allHighscore = $db->getHighscore($params->gameID);

        return $response;
    }

    static function needsToken(): bool
    {
        return true;
    }
}