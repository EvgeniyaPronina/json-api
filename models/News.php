<?php
class News extends Model
{
    public $table = 'News';

    public function Post($userEmail, $newsTitle, $newsMessage) {
        $user_id = $this->selectOne("SELECT id FROM Users WHERE  user_email = ?", $userEmail, 's');
        if (!$this->selectOne("SELECT id FROM News WHERE  ParticipantId = ?", $user_id, 'i')) {
            $sql = "INSERT INTO News SET ParticipantId = ?, NewsTitle = ?, NewsMessage = ?, LikesCounter = 0";
            if ($ins = $this->connection->prepare($sql)){
                $ins->bind_param('iss',$user_id, $newsTitle, $newsMessage);
                $ins->execute();
                $ins->close();
            }

            $this->connection->close();
            $mes = array(
                'status' => "ok",
                'message' => "Спасибо, ваша новость сохранена!");
            $jsonOutput = json_encode($mes);
            return($jsonOutput);
        }
        //можно добавить else с сообщением, что "вы можете добавить не более одной новости"
    }

}
