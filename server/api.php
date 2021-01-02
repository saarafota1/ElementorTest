<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST,GET,PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Response.php';
require __DIR__.'/route.php';

$methodsArray = ["post","get","put"];
// IF REQUEST METHOD IS NOT EQUAL TO POST
if(!in_array( strtolower($_SERVER["REQUEST_METHOD"]), $methodsArray)){
    $returnData = Response::msg(0,404,'Page Not Found!');
    Response::send($returnData);
}else{
    $route = explode("api.php", $_SERVER['REQUEST_URI']);
    $routeArray = Route::getRoute($route[1]);
    $controller = isset($routeArray['controller'])?$routeArray['controller']:null;
    $action = isset($routeArray['action'])?$routeArray['action']:null;
    $params = isset($routeArray['params'])?$routeArray['params']:null;

    if($controller !== null && $action !== null){
        try{
            require __DIR__. '/controllers/' . $controller .'Controller' . '.php';
            switch($controller){
                case 'User':
                    $controller = new UserController($params);
                    break;
            }
            $controller->{ $action }();
        }catch(Exception $e){
            $returnData = Response::msg(0,500,$e->getMessage());
            Response::send($returnData);
        }
    }
}
    


?>
