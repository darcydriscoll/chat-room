<?php
  /**
   * Database manipulation functions.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'string_func.php';
  require_once 'session.php';
  require_once 'db.php';

  /**
   * Represents and manages an account in the database.
   */
  class User {

    public function __construct() {
      SessionRegister::start();
    }

    /**
     * @return string Nickname stored in $_SESSION.
     */
    public function get_nickname() {
      return SessionRegister::get('nickname');
    }

    /**
     * Queries the database to check if the given nickname is taken.
     *
     * @param string $nickname The nickname we want to check
     *
     * @return bool true if the nickname is taken, false if not
     */
    private function is_nickname_taken($nickname) {
      return DBRegister::count_same_nicknames($nickname) > 0;
    }

    /**
     * Add the given nickname to the database.
     *
     * @param string $nickname The nickname we want to add.
     *
     * @return bool true if the nickname was successfully added, false if not.
     */
    private function add_nickname($nickname) {
      if (!$this->is_nickname_taken($nickname)) {
        return DBRegister::insert_nickname($nickname);
        // TODO - error handling?
      }
      return false;
    }

    /**
     * Validates a given nickname.
     *
     * A 'valid name':
     *  1) composed of characters on a standard American, British,
     *     or Australian QWERTY keyboard;
     *  2) consists of 3-20 characters.
     *
     * @param string @nickname The nickname we want to validate
     *
     * @return bool true if the nickname is valid, false otherwise
     */
    public function is_nickname_valid($nickname) {
      $nick_len = mb_strlen($nickname, 'UTF-8');
      // if length is valid
      if ($nick_len >= 3 && $nick_len <= 20) {
        // if characters are valid
        for ($i = 0; $i < $nick_len; $i++) {
          // TODO - possibly slow - n^2 (b/c php doesn't understand UTF-8 implictly, can't just access characters in UTF-8 string in constant time)
          $nick_i = mb_substr($nickname, $i, 1, 'UTF-8');
          $char_ord = mb_ord($nick_i, 'UTF-8');
          if (($nick_i !== '£') && ($char_ord < 33 || $char_ord > 126)) {
            return false;
          }
        }
        // we've passed the loop, so the nick is valid
        return true;
      }
      return false;
    }

    public function is_signed_in() {
      return !is_null(SessionRegister::get('nickname'));
    }

    /**
     * Sign in to the chatroom with a nickname.
     *
     * @param string $nickname The nickname we try to sign in with.
     *
     * @return bool true if we successfully sign in, false if not
     */
    public function sign_in($nickname) {
      // have we already signed in?
      if ($this->is_signed_in()) {
        return true;
      }
      $nickname = StringFunc::sanitise_string($nickname);
      // does nickname meet reqs?
      if (!$this->is_nickname_valid($nickname)) {
        return false;
      }
      // try adding nickname
      if (!$this->add_nickname($nickname)) {
        return false;
      }
      // add nickname
      SessionRegister::set('nickname', $nickname);
      return true;
    }
  }

 ?>
