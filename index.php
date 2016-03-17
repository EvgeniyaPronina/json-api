<?php
include_once 'init.php';
$route = explode('/', $_SERVER['REQUEST_URI']);
$table = filter_var($_POST['table'], FILTER_SANITIZE_STRING);
$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);//id поля таблицы
$sessionId = filter_var($_POST['sessionId'], FILTER_SANITIZE_NUMBER_INT);//id сессии, на которую нужно записаться
$userEmail = filter_var($_POST['userEmail'], FILTER_SANITIZE_EMAIL);
$newsTitle = filter_var($_POST['newsTitle'], FILTER_SANITIZE_STRING);
$newsMessage = filter_var($_POST['newsMessage'], FILTER_SANITIZE_STRING);

switch ($route[4]) {
    case 'Table':
        $current_table = $table;
        $obj = new $current_table;
        $answer = $obj ->Table($current_table);
        break;
    case 'SessionSubscribe':
        $obj = new Session;
        $answer = $obj ->Subscribe($sessionId, $userEmail);
        break;
    case 'PostNews':
        $obj = new News;
        $answer = $obj ->Post($userEmail, $newsTitle, $newsMessage);
        break;
    default:
        $mes = array(
            'status' => "error",
            'message' => "Ошибка в запросе");
        $jsonOutput = json_encode($mes);
        return($jsonOutput);
        break;
}
