<?php
  /**
   * Generate and return an arbitrary message.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'user.php';
  require_once 'php_session.php';
  require_once 'mysql_db.php';

  $return = null;
  $user = new User();
  $return = $user->init();
  if ($return->bool) {
    $return = $user->generate_message();
  }
  echo BoolMsg::encode_json($return);
