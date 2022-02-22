<?php

  require_once 'public/api/db.php';

  class MemoryDB implements DB {
    public $returns = array();

    public function connect() {
      return isset($returns['connect']) ? $returns['connect'] : null;
    }

    public function count_same_nicknames($nickname) {
      return isset($returns['count_same_nicknames']) ? $returns['count_same_nicknames'] : null;
    }

    public function insert_nickname($nickname) {
      return isset($returns['insert_nickname']) ? $returns['insert_nickname'] : null;
    }
  }

  DBRegister::init(new MemoryDB());

 ?>
