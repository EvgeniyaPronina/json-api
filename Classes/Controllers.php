<?php
class Controllers {

    public function Table() {

        $allowedTables = array('News', 'Session');
        $table = filter_var($_POST['table'], FILTER_SANITIZE_STRING);
        if (in_array($table, $allowedTables)){

            $connect = Model::getInstance();

            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            if (!empty($id)) {
                $sql = "SELECT * FROM $table WHERE id = $id";
                $records = $connect->paramSelect($sql, $id);
            } else {
                $sql = "SELECT * FROM $table";
                $records = $connect->paramSelect($sql);
            }
            $content = [
                'status' => "ok",
                'payload' => $records];
            return($content);
        } else {
            $content = [
                'status' => "error",
                'message' => "Нет доступа к этой таблице"];
            return($content);
        }
    }

    public function SessionSubscribe () {

        $sessionId = filter_var($_POST['sessionId'], FILTER_SANITIZE_NUMBER_INT);
        $userEmail = filter_var($_POST['userEmail'], FILTER_SANITIZE_EMAIL);

        if ($sessionId && $userEmail) {
            $connect = Model::getInstance();

            $wrUsers = $connect->someParamSelect("SELECT COUNT(*) FROM SesUsers WHERE sesId = ?", 'i', $sessionId);
            //проверить, что такая лекция существует в БД
            if ($maxPlaces = $connect->someParamSelect("SELECT maxPlaces FROM `Session` WHERE id = ?", 'i', $sessionId)) {

                if ($wrUsers < $maxPlaces) {
                    $userId = $connect->CheckInsertUser($userEmail);
                    $sql = "SELECT id FROM SesUsers WHERE userId = ? and sesId = ?";
                    if (!$connect-> someParamSelect($sql, 'ii', $userId, $sessionId)) {
                        $sql = "INSERT INTO SesUsers SET userId = ? and sesId = ?";
                        $connect->someParamQuery($sql, 'si', $userId, $sessionId);
                        $content = [
                            'status' => "ok",
                            'message' => "Спасибо, вы успешно записаны!"];
                        return($content);
                    } else {
                        $content = [
                            'status' => "error",
                            'message' => "Вы уже записаны на эту лекцию"];
                        return($content);
                    }

                } else {
                    $content = [
                        'status' => "ok",
                        'message' => "Извините, все места заняты"];
                    return($content);
                }

            } else {
                $content = [
                    'status' => "error",
                    'message' => "Извините, такой лекции нет в расписании"];
                return($content);
            }
        } else {
            //todo записать сообщение об ошибке в журнал;
        }

    }

    public function PostNews() {

        $userEmail = filter_var($_POST['userEmail'], FILTER_SANITIZE_EMAIL);
        $newsTitle = filter_var($_POST['newsTitle'], FILTER_SANITIZE_STRING);
        $newsMessage = filter_var($_POST['newsMessage'], FILTER_SANITIZE_STRING);

        $connect = Model::getInstance();

        $userId = $connect->CheckInsertUser($userEmail);

        $sql = "SELECT id FROM News WHERE  ParticipantId = ?";
        if (!$userId or !$connect->someParamSelect($sql, 'i', $userId)) {
            $sql = "INSERT INTO News SET ParticipantId = ?, NewsTitle = ?, NewsMessage = ?, LikesCounter = 0";
            if ($connect-> someParamQuery($sql, 'iss', $userId, $newsTitle, $newsMessage)) {
                $content = [
                    'status' => "ok",
                    'message' => "Спасибо, ваша новость сохранена!"];
                return ($content);
            } else {
                //todo записать сообщение об ошибке в журнал;
            }
        } else {
            $content = [
                'status' => "error",
                'message' => "Вы можете вставить не более одной новости"];
            return($content);
        }
    }

}
