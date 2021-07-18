<?php

  class DBRegister {
    private static $db;

    /**
     * Initialise the register.
     */
    public static function init(DB $db) {
      self::$db = $db;
    }

    public static function connect() {
      return self::$db->connect();
    }

    public static function count_same_nicknames($nickname) {
      return self::$db->count_same_nicknames($nickname);
    }

    public static function insert_nickname($nickname) {
      return self::$db->insert_nickname($nickname);
    }
  }

  interface DB {
    public function connect();
    public function count_same_nicknames($nickname);
    public function insert_nickname($nickname);
  }

  /**
   * Manages database connection and queries.
   */
  class MySQLDB implements DB {
    private mysqli $conn;

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
    public function connect() {
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
    public function close() {
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

 ?>
