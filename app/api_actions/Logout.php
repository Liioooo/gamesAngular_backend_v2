<?php

namespace app\api_actions;
use stdClass;

class Logout extends Action {

    static function doAction($params, $userID = null): object {
        $response = new stdClass();

        unset($_COOKIE['jwt-token']);
        setcookie('jwt-token',null,time()-1);
        $response->auth = 'false';
        return $response;

    }

    static function needsToken(): bool
    {
        return false;
    }
}