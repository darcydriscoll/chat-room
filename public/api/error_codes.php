<?php
  /**
   * Representation and handling of error codes.
   *
   * @author Darcy Driscoll <darcy.driscoll@outlook.com
   */

  /**
   * Stores error codes and provides an interface for retrieval.
   */
  class ErrorCodes {
    private const CODES_FILEPATH = './../configs/error_codes.json';
    private $codes;

    public function __construct() {
      $this->codes = $this->get_codes();
    }

    /**
     * Get the error codes from the defined file.
     *
     * @return Array The error codes as an associative error with the format:
     *    e.g. ['SERVER_ERROR' : 0, ...]
     */
    private function get_codes() {
      // read file in as json
      $file_str = file_get_contents(ErrorCodes::CODES_FILEPATH);
      if ($file_str === false) {
        error_log(sprintf('Filename of the path \'%s\' cannot be found. Verify
                          that the file exists and that its length > 0.',
                          ErrorCodes::CODES_FILEPATH));
        throw new Exception();
      }
      $json_obj = json_decode($file_str, false);
      if ($json_obj === null) {
        error_log(json_last_error_msg());
        throw new Exception();
      }
      // process
      $codes = $json_obj->error_codes;
      $codes = array_flip($codes); // into e.g. {'SERVER_ERROR' : 0, ...}
      return $codes;
    }

    /**
     * See if the provided code exists. Provide the integer representation of
     * the code if it does, else, throw an exception.
     *
     * @param string $code The string we think corresponds to a code.
     *
     * @return int If $code is an error code, the integer representation of it.
     * @throws Exception if $code is not an error code.
     */
    public function get($code) {
      if (array_key_exists($code, $this->codes)) {
        return $this->codes[$code];
      } else {
        throw new Exception();
      }
    }
  }

 ?>
