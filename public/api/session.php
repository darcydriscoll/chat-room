<?php
  /**
   * Classes representing session handling.
   *
   * Registers + proxies used here to allow for mocking during unit testing.
   * Register + proxies pattern adapted from:
   * https://www.sitepoint.com/community/t/phpunit-testing-cookies-and-sessions/36557/2
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  /**
   * A register (?) that interfaces with a given session proxy.
   *
   * Uses method signatures from the Session interface. We don't implement the
   * Session interface because we want our methods here to be static.
   */
  class SessionRegister {
    private static $session;

    /**
     * Initialise the register.
     *
     * @param Session $s The session proxy with which we want to initialise
     *                   the register.
     */
    public static function init(Session $s) {
      self::$session = $s;
    }

    public static function get($var) {
      return self::$session->get($var);
    }

    public static function set($var, $value) {
      return self::$session->set($var, $value);
    }

    public static function start() {
      return self::$session->start();
    }

    public static function write_close() {
      return self::$session->write_close();
    }
  }

  interface Session {
    /**
     * Get the value of a var from the session variables.
     *
     * @param string|int $var Var name.
     *
     * @return mixed Var value
     */
    public function get($var);

    /**
     * Set $var in session variables to $value.
     *
     * @param string|int $var Var name.
     * @param mixed $value Var value.
     */
    public function set($var, $value);

    /**
     * Start the session.
     */
    public function start();

    /**
     * Close the session and save changes.
     */
    public function write_close();
  }

  class PHPSession implements Session {
    public function get($var) {
      return isset($_SESSION[$var]) ? $_SESSION[$var] : null;
    }

    public function set($var, $value) {
      $_SESSION[$var] = $value;
    }

    /**
     * Starts the session, then either increases the session lifetime or
     * destroys it depending on how long it has been inactive for.
     */
    private function manage_session_lifetime() {
      session_start();
      $current = time();
      // have we started this session before?
      if (isset($_SESSION['start_timestamp'])) {
        // session's been inactive for more than 24 hours
        if ($_SESSION['start_timestamp'] - $current > 86400) {
          // start a new session
          setcookie("PHP_SESSID", session_id(), strtotime("-24 hours"), "/");
          session_unset();
          session_destroy();
          session_start();
        } else {
          // update session and cookie
          $_SESSION['start_timestamp'] = $current; // gc won't touch b/c we've modified $_SESSION
          setcookie("PHPSESSID", session_id(), strtotime('+72 hours'), "/");
        }
      // we're starting the session for the first time
      } else {
        $_SESSION['start_timestamp'] = $current;
      }
    }

    public function start() {
      $this->manage_session_lifetime();
    }

    public function write_close() {
      session_write_close();
    }
  }

 ?>
