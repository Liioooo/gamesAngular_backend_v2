<?php

namespace app;
use ReflectionClass;
use ReflectionException;
use stdClass;

class API {

    public function __construct()
    {
        header("content-type: application/json");
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->throwError(Constants::REQUEST_METHOD_NOT_VALID, 'Method is not POST');
        }
        $rawData = file_get_contents('php://input');
        $this->validateRequest($rawData);
    }

    private function validateRequest($request) {
        if($_SERVER['CONTENT_TYPE'] != 'application/json') {
            $this->throwError(Constants::CONTENT_TYPE_NOT_VALID, 'Content-Type must be application/json');
        }
        $data = json_decode($request);

        if(!isset($data->action) || $data->action == '') {
            $this->throwError(Constants::API_ACTION_REQUIRED, 'API Action required');
        }

        if(!is_object($data->params)) {
            $this->throwError(Constants::API_PARAMS_REQUIRED, 'API Params are required');
        }
        $this->processAPI($data->action, $data->params);
    }

    private function processAPI($action, $params) {
        try {
            $class = new ReflectionClass('app\api_actions\\' . ucfirst($action));
            if($class->getMethod('needsToken')->invoke(null)) {
                if(($userID = TokenManagement::verifyToken($this)) != null) {
                    $this->returnResult($class->getMethod('doAction')->invoke(null, $params, $userID));
                }
            } else {
                $this->returnResult($class->getMethod('doAction')->invoke(null, $params, $this));
            }
        } catch (ReflectionException $e) {
            $this->throwError(Constants::API_DOES_NOT_EXIST, 'API does not exist');
        }
    }

    private function returnResult($result) {
        echo json_encode(['status'=>Constants::SUCCESS_RESPONSE, 'message'=>'no Error', 'result'=>$result]);
        exit();
    }

    public function throwError(int $errorType, string $msg) {
        echo json_encode(['status'=>$errorType, 'message'=>$msg, 'result'=>new stdClass()]);
        exit();
    }
}