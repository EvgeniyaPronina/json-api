<?php
class Message {

    public $content;

    public function __construct($content) {
        $this->content = $content;
    }

    public function jsonMessage() {

        return json_encode($this->content);
    }
}