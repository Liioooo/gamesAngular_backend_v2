<?php

namespace app\api_actions;


use app\API;

abstract class Action
{
    abstract static function doAction($params): object;
    abstract static function needsToken(): bool;
}