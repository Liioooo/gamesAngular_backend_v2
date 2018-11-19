<?php

namespace app\api_actions;

 use stdClass;
 use app\DatabaseConnection;

 class ChangeUsername extends Action {

     static function doAction($params, $userID): stdClass {
         $response = new stdClass();
         $db = new DatabaseConnection();

         if($db->updateUsername($userID, $params->newUsername) == 'success') {
             $response->username = $params->newUsername;
             $response->error = '';
         } else {
             $response->error = 'alreadyExists';
         }

         return $response;
     }

     static function needsToken(): bool {
         return true;
     }
 }