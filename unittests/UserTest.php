<?php

  require_once 'public/api/user.php';
  require_once 'memory_session.php';
  require_once 'memory_db.php';
  use PHPUnit\Framework\TestCase;

  final class UserTest extends TestCase {

    public function test_is_nickname_valid() {
      // setup - TODO abstract away
      $user = new User();
      // tests
      // empty string
      $this->assertSame(false, $user->is_nickname_valid(""));
      // too long, valid characters
      $this->assertSame(false, $user->is_nickname_valid("abcdEFgH11730%#^!@WEGWEFWFY$#GFWEF"));
      // too short, valid characters
      $this->assertSame(false, $user->is_nickname_valid("f%"));
      // valid, almost too short
      $this->assertSame(true, $user->is_nickname_valid("ABC"));
      // valid, almost too long
      $this->assertSame(true, $user->is_nickname_valid("aBhG$%&@(sABNMl:\"L\")"));
      // valid, middle length
      $this->assertSame(true, $user->is_nickname_valid("aHETU&36%^"));
      // valid, middle length, boundary characters
      $this->assertSame(true, $user->is_nickname_valid("!~%7#@+?~!~!A!~!"));
      // too long, invalid characters
      $this->assertSame(false, $user->is_nickname_valid("ÄÄÄÄÄ     §§§§§§§§§§§§§§§§§§§§§§§"));
      // too short, invalid characters
      $this->assertSame(false, $user->is_nickname_valid("Ä "));
      // too long, special invalid characters
      $this->assertSame(false, $user->is_nickname_valid("aBhG$%&@\n\n\n\n\n\n\n\n\n\n:\"L\")"));
      // valid length, invalid characters
      $this->assertSame(false, $user->is_nickname_valid("abcÄ Ägh"));
      // valid length, pound symbol
      $this->assertSame(true, $user->is_nickname_valid("abc£"));
    }
  }

 ?>
