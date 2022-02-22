<?php
  /**
   * Sets up a MySQL database proxy, for use in production.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'db.php';

  DBRegister::init(new MySQLDB());

 ?>
