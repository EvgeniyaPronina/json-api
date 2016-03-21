<?php
spl_autoload_register(function($className) {
    require_once 'Classes/'.$className.'.php';
});

$route = explode('/', $_SERVER['REQUEST_URI']);
$controllerName = $route[4];

if (method_exists('Controllers', $controllerName)) {
    $answear = (new Controllers())->$controllerName();
} else {
    $answear = [
        'status' => "error",
        'message' => "Ошибка в запросе"];
}
$mes = new Message($answear);
return $mes->jsonMessage();