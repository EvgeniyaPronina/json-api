<?php
Class Model {

    private $connection;
    private static $instance;
    public $affectedRows;

    private function __clone() {}
    private function __wakeup() {}

    private function __construct() {
        $this->connection = new mysqli('localhost', 'root', '','pilots');
        if (mysqli_connect_errno()) {
            die(mysqli_connect_error());
        }
        $this->connection->query("SET NAMES 'UTF-8'");
    }

    public static function getInstance() {
        if (empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function someParamSelect($sql, $types, $param1, $param2 = '', $param3 = '', $param4 = '', $param5 = '') {
        if ($stmt = $this->connection->prepare($sql)){
            $marker = $stmt->param_count;
            switch ($marker) {
                case '1':
                    $stmt->bind_param($types, $param1);
                    break;
                case '2':
                    $stmt->bind_param($types, $param1, $param2);
                    break;
                case '3':
                    $stmt->bind_param($types, $param1, $param2, $param3);
                    break;
                case '4':
                    $stmt->bind_param($types, $param1, $param2, $param3, $param4);
                    break;
                case '5':
                    $stmt->bind_param($types, $param1, $param2, $param3, $param4, $param5);
                    break;
                default: //todo записать сообщение об ошибке в журнал;
                    break;
            }
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            return $result;
        }
    }

    public function someParamQuery($sql, $types, $param1, $param2 = '', $param3 = '', $param4 = '', $param5 = '') {//можно вызывать для INSERT, UPDATE и других команд, когда результат действия не надо выводить куда-либо
        if ($stmt = $this->connection->prepare($sql)){
            $marker = $stmt->param_count;
            switch ($marker) {
                case '1':
                    $stmt->bind_param($types, $param1);
                    break;
                case '2':
                    $stmt->bind_param($types, $param1, $param2);
                    break;
                case '3':
                    $stmt->bind_param($types, $param1, $param2, $param3);
                    break;
                case '4':
                    $stmt->bind_param($types, $param1, $param2, $param3, $param4);
                    break;
                case '5':
                    $stmt->bind_param($types, $param1, $param2, $param3, $param4, $param5);
                    break;
                default: //todo записать сообщение об ошибке в журнал;
                    break;
            }
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            return $affectedRows;
        }
    }

    public function paramSelect($sql, $id = 'all') {
        $stmt = $this->connection->query($sql);
        $records = $stmt->fetch_all(MYSQLI_ASSOC);
        return $records;
    }

    public function error() {
        return $this->connection->errno;
    }

   public function CheckInsertUser($userEmail) {
        $sqlUserId = "SELECT id FROM Users WHERE  userEmail = ?";
        $userId = $this->someParamSelect($sqlUserId, 's', $userEmail);
        //если пользователя не было в системе, вставить e-mail в БД, чтобы у него был ID
        if (empty($userId)) {
            $sql = "INSERT INTO Users SET userEmail = ?";
            $this->someParamQuery($sql, 's', $userEmail);
            $userId = $this->someParamSelect($sqlUserId, 's', $userEmail);
        }
        return $userId;
    }

}

