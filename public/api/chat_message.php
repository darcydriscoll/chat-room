<?php

/**
 * Represents a chat message.
 */
class ChatMessage {
  public $timestamp;
  public $nickname;
  public $msg;

  public function __construct($timestamp, $nickname, $msg) {
    $this->timestamp = $timestamp;
    $this->nickname = $nickname;
    $this->msg = $msg;
  }
}
