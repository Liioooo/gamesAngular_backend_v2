<?php

namespace app\api_actions;


abstract class Action
{
    abstract static function doAction($params, $userID): object;
    abstract static function needsToken(): bool;
}