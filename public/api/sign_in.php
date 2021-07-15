<?php
  /**
   * Try signing the given nickname in $_POST into the chatroom.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'database_func.php';

  // sign in
  if (!empty($_POST['nickname'])) {
    $db = new DB;
    $user = new User($db);
    if ($user->sign_in($_POST['nickname'])) {
      echo 'true';
    } else {
      echo 'false';
    }
  } else {
    echo 'false';
  }

?>
