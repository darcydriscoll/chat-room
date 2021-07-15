<?php
  /**
   * Database manipulation functions.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'string_func.php';

  /**
   * Manages database connection and queries.
   */
  class DB {
    public mysqli $conn;

    public function __construct() {
      $this->connect();
    }

    public function __destruct() {
      $this->close();
    }

    /**
     * Connect to the database.
     *
     * @return mysqli
     */
    private function connect() {
      if (!isset($this->conn)) {
        require_once 'login.php';
        $this->conn = new mysqli($hn, $un, $pw, $db_name);
        if ($this->conn->connect_error) {
          die('Connection error: ' . $this->conn->connect_error); // TODO
        }
      }
      return $this->conn;
    }

    /**
     * Close the connection with the database.
     */
    private function close() {
      return $this->conn->close();
    }

    /**
     * Prepare a query with bound variables and execute it.
     *
     * As specified in mysqli::prepare, use the '?' character to designate
     * spots where variables should be bound.
     *
     * @param string $query MySQL query statement
     * @param string $types Characters specifying types for the corresponding
     *                      bind variables. See mysqli_stmt::bind_param
     * @param mixed[] $vars Variables to bind to the query
     *
     * @return mysqli_stmt Query statement, prepared and executed
     */
    private function prep_exec($query, $types, $vars) {
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param($types, ...$vars);
      $stmt->execute();
      return $stmt;
    }

    /**
     * Return the number of nicknames equal to $nickname.
     *
     * @param string $nickname The nickname we want to check against.
     *
     * @return int The number of equal nicknames.
     */
    public function count_same_nicknames($nickname) {
      $result = $this->db->prep_exec(
        'SELECT COUNT(nickname) FROM accounts WHERE nickname = ?',
        's', [$nickname])->get_result();
      $count = $result->fetch_row()[0];
      $result->close();
      return $count;
    }

    /**
     * Try inserting the given nickname into the database.
     *
     * @param string $nickname The nickname we want to insert.
     *
     * @return bool Whether the insertion succeeded or not.
     */
    public function insert_nickname($nickname) {
      $this->db->prep_exec(
        'INSERT INTO accounts (nickname) VALUES (?)',
        's', [$nickname]);
      return true; // TODO - error handling
    }
  }

  /**
   * Represents and manages an account in the database.
   */
  class User {
    public DB $db;

    public function __construct($db) {
      $this->db = $db;
      // set session length
      $session_length = 60*60*24; // 24 hours
      ini_set('session.gc_maxlifetime', $session_length);
      session_start();
    }

    /**
     * @return string Nickname stored in $_SESSION.
     */
    public function get_nickname() {
      return $_SESSION['nickname'];
    }

    /**
     * Queries the database to check if the given nickname is taken.
     *
     * @param string $nickname The nickname we want to check
     *
     * @return bool true if the nickname is taken, false if not
     */
    private function is_nickname_taken($nickname) {
      return $this->db->count_same_nicknames($nickname) > 0;
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
        return $this->db->insert_nickname($nickname);
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
    private function is_nickname_valid($nickname) {
      $nick_len = mb_strlen($nickname);
      // if length is valid
      if ($nick_len >= 3 && nick_len <= 20) {
        // if characters are valid
        for (int i = 0; i < $nick_len; i++) {
          $char_ord = mb_ord($nickname[i], 'UTF-8');
          if (($char_ord < 33 || $char_ord > 126) && $char_ord != 163) {
            return false;
          }
        }
        // we've passed the loop, so the nick is valid
        return true;
      }
      return false;
    }

    private function is_signed_in() {
      return isset($_SESSION['nickname']);
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
      $nickname = sanitiseString($nickname);
      // does nickname meet reqs?
      if (!$this->is_nickname_valid($nickname)) {
        return false;
      }
      // try adding nickname
      if (!$this->add_nickname($nickname)) {
        $this->db->close();
        return false;
      }
      // start session, add nickname
      $_SESSION['nickname'] = $nickname;
      return true;
    }
  }

 ?>
