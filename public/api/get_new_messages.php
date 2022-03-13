<?php

/**
 * Try getting new messages from the database.
 *
 * @author Darcy Driscoll <darcy.driscoll@outlook.com>
 */

require_once 'user.php';
require_once 'php_session.php';
require_once 'mysql_db.php';
require_once 'error_codes.php';

$e_codes = new ErrorCodes();

$user = new User();
// initialisation failed?
$user_init = $user->init();
if (!$user_init->bool) {
  echo BoolMsg::encode_json($user_init);
  return;
}
// try and get new message
$new_message = $user->get_new_message();
echo BoolMsg::encode_json($new_message);
