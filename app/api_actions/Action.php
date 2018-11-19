<?php

namespace app\api_actions;
use stdClass;

abstract class Action
{
    abstract static function doAction($params, $userID): stdClass;
    abstract static function needsToken(): bool;
}