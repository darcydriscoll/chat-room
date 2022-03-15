<?php
  /**
   * Classes representing the database.
   *
   * We use registers and proxies here to allow for mocking during unit
   * testing.
   * Register + proxies pattern adapted from:
   * https://www.sitepoint.com/community/t/phpunit-testing-cookies-and-sessions/36557/2
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  require_once 'bool_msg.php';
  require_once 'chat_message.php';
  require_once 'string_func.php';

  /**
   * Register (?) that interfaces with a given database proxy.
   *
   * Uses method signatures from the DB interface. We don't implement the DB
   * interface because we want our methods here to be static.
   *
   */
  class DBRegister {
    private static DB $db;

    /**
     * Initialise the register.
     *
     * @param DB $db The database proxy with which we want to initialise the
     *               register
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

    public static function add_new_message($nickname) {
      return self::$db->add_new_message($nickname);
    }

    public static function get_new_message($last_id) {
      return self::$db->get_new_message($last_id);
    }
  }

  /**
   * The interface that all database proxies implement.
   */
  interface DB {

    /**
     * Connect to the database proxy.
     *
     * TODO - Should this be a private method? Doesn't make much sense for
     * MemoryDB.
     *
     * @return mixed
     */
    public function connect();

    /**
     * Counts how many nicknames in the DB proxy are equal to $nickname.
     *
     * @param string $nickname The nickname we want to check.
     *
     * @return int The number of nicknames in the DB proxy that are
     *             equal to $nickname.
     */
    public function count_same_nicknames($nickname);

    /**
     * Try inserting $nickname into the DB proxy.
     *
     * @param string $nickname The nickname we want to enter.
     *
     * @return bool True on success, false on failure.
     */
    public function insert_nickname($nickname);
  }

  /**
   * Manages database connection and queries.
   */
  class MySQLDB implements DB {
    private mysqli $conn;

    public function __construct() { }

    public function __destruct() {
      $this->close();
    }

    public function connect() {
      if (!isset($this->conn)) {
        require_once 'login.php';
        $this->conn = new mysqli($hn, $un, $pw, $db_name);
        if ($this->conn->connect_error) {
          error_log(sprintf('MySQLi connection failed: %s\n', $this->conn->connect_error));
          throw new Exception(code: $this->conn->errno);
        }
      }
      return $this->conn;
    }

    /**
     * Close the connection with the database.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function close() {
      return $this->conn->close(); // TODO -- propagate failure to user?
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
    private function prep_exec($query, $types = null, $vars = null) {
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
        error_log(sprintf("Statement preparation failed: %s\n", $this->conn->error));
        throw new Exception(code: $this->conn->errno);
      }
      if (!is_null($types) && !$stmt->bind_param($types, ...$vars)) {
        error_log(sprintf("Parameter binding failed: %s\n", $this->conn->error));
        throw new Exception(code: $this->conn->errno);
      }
      if (!$stmt->execute()) {
        error_log(sprintf("Statement execution failed: (%d) %s\n", $this->conn->errno, $this->conn->error));
        throw new Exception(code: $this->conn->errno);
      }
      return $stmt;
    }

    public function count_same_nicknames($nickname) {
      $stmt = $this->prep_exec(
        'SELECT COUNT(nickname) FROM accounts WHERE nickname = ?',
        's', [$nickname]);
      $result = $stmt->get_result();
      $fetch_row = $result->fetch_row();
      if (!$fetch_row) {
        error_log(sprintf('Failed to fetch rows: %s\n', $this->conn->error));
        throw new Exception(code: $this->conn->errno);
      }
      $count = $fetch_row[0];
      if (!$stmt->close()) {
        error_log(sprintf('Failed to close connection: %s\n', $this->conn->error));
        throw new Exception(code: $this->conn->errno);
      }
      return $count;
    }

    public function insert_nickname($nickname) {
      $this->prep_exec(
        'INSERT INTO accounts (nickname) VALUES (?)',
        's', [$nickname]);
      return true;
    }

    /**
     * Inserts a new message into the messages buffer (deleting all others).
     *
     * @param string $nickname The nickname associated with the message.
     *
     * @return ChatMessage if the insertion succeeds.
     * @throws Exception if the insertion fails.
     */
    public function add_new_message($nickname) {
      // delete all messages
      $this->prep_exec('TRUNCATE TABLE messages');
      // message parameters
      date_default_timezone_set('UTC');
      $timestamp = time();
      $date = date('Y-m-d H:i:s', $timestamp);
      $message = StringFunc::sanitise_string(
        trim(file_get_contents('https://loripsum.net/api/1/medium/plaintext'))
      );
      // try adding a message
      $id = rand(0, 65500);
      while (true) {
        try {
          // add message
          $this->prep_exec(
            'INSERT INTO messages (id, timestamp, nickname, message)
             VALUES (?, ?, ?, ?)',
            'isss', [$id, $date, $nickname, $message]
          );
        } catch (Exception $e) {
          if ($e->getCode() === 1062) {
            // duplicate ID - try again
            $id = rand(0, 65535);
            continue;
          } else {
            throw $e;
          }
        }
        // no errors - pass through
        break;
      }
      return new ChatMessage($id, $timestamp, $nickname, $message);
    }

    /**
     * Try to retrieve a new message from the messages table.
     *
     * @param int $last_id The id of the last retrieved message.
     *
     * @return BoolMsg<null>|BoolMsg<ChatMessage> depending on whether there is
     *  a new message to retrieve.
     */
    public function get_new_message($last_id) {
      // get all messages that don't have $last_id as id
      $stmt = $this->prep_exec(
        'SELECT * FROM messages
         WHERE id != ?',
        'i', [$last_id]
      );
      $result = $stmt->get_result();
      $fetch_row = $result->fetch_row();
      // no new message
      if (!$fetch_row) {
        return new BoolMsg(false, null);
      // new message
      } else {
        $datetime = new DateTime($fetch_row[1], new DateTimeZone('UTC'));
        $message = new ChatMessage($fetch_row[0], $datetime->getTimestamp(),
          $fetch_row[2], $fetch_row[3]);
        return new BoolMsg(true, $message);
      }
    }
  }

 ?>
