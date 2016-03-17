<?php
class Session extends Model
{
    public $table = 'Session';
    protected $connection;

    public function Subscribe ($ses_id, $user_email) {

        $wr_users = $this->selectOne("SELECT COUNT(*) FROM Ses_Users WHERE ses_id = ?", $ses_id, 'i');
        $maxPlaces = $this->selectOne("SELECT maxPlaces FROM Session WHERE id = ?", $ses_id, 'i');


        if ($wr_users < $maxPlaces) {
            //добавить проверку на то, что данный пользователь еще не записан на эту лекцию
            //получать из таблицы Users id пользователя по e-mail и вставлять в таблицу с записями на лекцию его id
            $sql = "INSERT INTO Ses_Users SET user_email = ?, ses_id = ?";
                if ($ins = $this->connection->prepare($sql)){
                    $ins->bind_param('si',$user_email, $ses_id);
                    $ins->execute();
                    $ins->close();
            }
            $this->connection->close();
            $mes = array(
                'status' => "ok",
                'message' => "Спасибо, вы успешно записаны!");
            $jsonOutput = json_encode($mes);
            return($jsonOutput);
        }else {
            $this->connection->close();
            $mes = array(
                'status' => "ok",
                'message' => "Извините, все места заняты");
            $jsonOutput = json_encode($mes);
            return($jsonOutput);
        }

    }

}
