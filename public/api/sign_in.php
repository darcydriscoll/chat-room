<?php
  /**
   * Try signing the given nickname in $_POST into the chatroom.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'user.php';
  require_once 'php_session.php';
  require_once 'mysql_db.php';

  // sign in
  if (!empty($_POST['nickname'])) {
    $user = new User();
    $sign_in = $user->sign_in($_POST['nickname']);
    if ($sign_in->bool) {
      echo 'true: ' . $user->get_nickname();
    } else {
      echo 'false: ' . $sign_in->msg;
    }
  } else {
    echo 'false';
  }

?>
