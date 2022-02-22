<?php

  require_once 'public/api/session.php';

  class MemorySession implements Session {
    private $vars = array();

    public function get($var) {
      return isset($this->vars[$var]) ? $this->vars[$var]: null;
    }

    public function set($var, $value) {
      $this->vars[$var] = $value;
    }

    public function start() {
      return;
    }

    public function write_close() {
      return;
    }
  }

  SessionRegister::init(new MemorySession());

 ?>
