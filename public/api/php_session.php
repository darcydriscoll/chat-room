<?php
  /**
   * Sets up a PHP session proxy, for use in production.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'session.php';

  SessionRegister::init(new PHPSession());

 ?>
