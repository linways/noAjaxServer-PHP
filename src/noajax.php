<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../vendor/autoload.php';

use noAjax\Exception\ClassNotFoundException;
use noAjax\Exception\MethodNotFoundException;

$params = json_decode($_POST['params'],true);

list($className, $methodName) = explode('::', $params['methodName']);
$response = new stdClass();

try{
    if(!class_exists($className))
        throw new ClassNotFoundException("class '$className' not found");
    if(!method_exists($className, $methodName))
        throw new MethodNotFoundException("class '$className' does not have a method '$methodNamess'"); 
    $result = call_user_func_array($params['methodName'], $params['args']);

    $response->status = 'success';    
    if(isset($result)){
        $response->code = 200; // HTTP status code for OK
        $response->result = $result;    
    }
    else{
        $response->code = 204; // The server successfully processed the request and is not returning any content.
        $response->result = true;
    }
}catch(Exception $e){
    $response->status = 'failed';
    $exception = new stdClass();
    $exception->name = get_class($e);
    $exception->code = $e->getCode();
    $exception->message = $e->getMessage();
    $exception->file = $e->getFile();
    $exception->line = $e->getLine();
    $exception->trace = $e->getTraceAsString();
    $response->exception = $exception;
}
echo json_encode($response);    