<?php
abstract class Model
{
    public $table = '';
    protected $connection;

    public function __construct(){
        $this->connection = new mysqli('localhost', 'root', '','pilots');
        if (mysqli_connect_errno()) {
            die(mysqli_connect_error());
        }
        $this->connection->query("SET NAMES 'UTF-8'");
    }

    public function Table($table, $id = 'all')
    {
        if ($id == 'all') {
            $sql = "SELECT * FROM $table";
        } else {
            $sql = "SELECT * FROM $table WHERE id = $id";
        }
        $stmt = $this->connection->query($sql);
        $records = $stmt->fetch_all(MYSQLI_ASSOC);
        $this->connection->close();
        $mes = array(
            'status' => "ok",
            'payload' => $records);
        $jsonOutput = json_encode($mes);
        return($jsonOutput);
    }

    public function selectOne($sql, $param, $type) {
        if ($stmt = $this->connection->prepare($sql)){
            $stmt->bind_param($type,$param);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            return $result;
        }
    }

}

