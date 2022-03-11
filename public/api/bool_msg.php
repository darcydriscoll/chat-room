<?php

/**
 * Represents a boolean value associated with a payload.
 */
class BoolMsg {
  public $bool;
  public $msg;

  public function __construct($bool, $msg) {
    $this->bool = $bool;
    $this->msg = $msg;
  }

  /**
   * Encode the given BoolMsg in JSON format.
   *
   * @param BoolMsg $bool_msg The BoolMsg we want to encode.
   * @param callable $encode_msg The function with which to encode the msg if
   *  necessary.
   *
   * @return string|false depending on whether the encoding succeeds.
   */
  public static function encode_json($bool_msg, callable $encode_msg = null) {
    $msg = null;
    if ($encode_msg === null) {
      $msg = $bool_msg->msg;
    } else {
      $msg = $encode_msg($bool_msg->msg);
    }
    $arr = array('bool' => $bool_msg->bool, 'msg' => $msg);
    return json_encode($arr);
  }
}
