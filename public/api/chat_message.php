<?php

/**
 * Represents a chat message.
 */
class ChatMessage {
  public $id;
  public $timestamp;
  public $nickname;
  public $msg;

  public function __construct($id, $timestamp, $nickname, $msg) {
    $this->id = $id;
    $this->timestamp = $timestamp;
    $this->nickname = $nickname;
    $this->msg = $msg;
  }
}
