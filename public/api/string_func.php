<?php
  /**
   * String manipulation functions.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  /**
   * Holds all string manipulation functions.
   */
  class StringFunc {
    /**
     * Sanitise a given string for safe use.
     *
     * @param string $str The string we want to sanitise.
     *
     * @return string The sanitised string.
     */
    public static function sanitise_string($str) {
      $html = filter_var($str, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $slashes = filter_var($html, FILTER_SANITIZE_ADD_SLASHES);
      return $slashes;
    }
  }

 ?>
