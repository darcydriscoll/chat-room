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
    if ($user->sign_in($_POST['nickname'])) {
      echo 'true';
    } else {
      echo 'false';
    }
  } else {
    echo 'false';
  }

?>
