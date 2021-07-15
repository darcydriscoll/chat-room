<?php
  /**
   * String manipulation functions.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com>
   */

  /**
   * Sanitise a given string for safe use.
   *
   * @param string $str The string we want to sanitise.
   *
   * @return string The sanitised string.
   */
  function sanitiseString($str) {
    // if quotes have been escaped, remove slashes
    // TODO
    // if (get_magic_quotes_gpc) {
    //   $str = stripslashes($str);
    // }
    // strip HTML???
    $str = strip_tags($str);
    // remove HTML from string
    $str = htmlentities($str);
    return $str;
  }

 ?>
