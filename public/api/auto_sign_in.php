<?php
  /**
   * Return whether the user on the current session is already signed in.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'user.php';
  require_once 'php_session.php';
  require_once 'mysql_db.php';

  $user = new User();
  $return = $user->init();
  if ($return->bool) {
    $return = $user->is_signed_in_msg();
  }
  echo BoolMsg::encode_json($return);

?>
