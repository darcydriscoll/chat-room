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

    public function start() {
      session_start();
    }

    public function write_close() {
      session_write_close();
    }
  }

 ?>
