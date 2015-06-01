<?php
/**
 * @file
 * Contains useful helper functions for CloudFlarePhpSdk.
 */

namespace CloudFlarePhpSdk;

/**
 * Class Utils.
 */
class Utils {

  /**
   * Asserts that a function param has an expected type.
   *
   * @param mixed $actual_value
   *   The value to test.
   * @param string $expected_type
   *   The type that $actual_value is expected to have.
   * @param string $param_name
   *   The name of the param in the calling function.
   */
  public static function assertParam($actual_value, $expected_type, $param_name) {
    $calling_function = debug_backtrace()[1]['function'];
    $message = "The function $calling_function only accepts a type of $expected_type for the param $param_name";
    self::assertType($actual_value, $expected_type, $message);
  }

  /**
   * Asserts that a function param has an expected type.
   *
   * @param mixed $actual_value
   *   The value to test.
   * @param string $expected_type
   *   The type that $actual_value is expected to have.
   * @param string $message
   *   The message to throw if the assertion fails.
   *
   * @throws \InvalidArgumentException
   *   Exception is thrown when an unexpected type is encountered.
   */
  public static function assertType($actual_value, $expected_type, $message = '') {
    if (gettype($actual_value) == $expected_type) {
      return;
    }

    if (is_object($expected_type) && is_a($actual_value, $expected_type)) {
      return;
    }

    throw new \InvalidArgumentException($message);
  }

}
