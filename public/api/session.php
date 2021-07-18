<?php
  /*
   * Adapted from
   * https://www.sitepoint.com/community/t/phpunit-testing-cookies-and-sessions/36557/2
   */

  /**
   * A register (?) that interfaces with a given session proxy.
   *
   * The class uses only static variables and methods, because there is only
   * ever one session at a given time. To access, use SessionProxy::X.
   */
  class SessionRegister {
    private static $session;

    /**
     * Initialise the register.
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
    public function get($var);
    public function set($var, $value);
    public function start();
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
