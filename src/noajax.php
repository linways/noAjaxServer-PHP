<?php
require_once __DIR__ . '/../vendor/autoload.php';

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


class MethodNotFoundException extends Exception{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
class ClassNotFoundException extends Exception{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

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