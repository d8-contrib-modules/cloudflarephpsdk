<?php

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
    Utils::assertType($actual_value, $expected_type, $message);
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

  /**
   * Parses a date-time from CloudFlare into a unix timestamp.
   *
   * @param string|null $str_date
   *   A string containing a UTC ISO-8601 formated date, including microseconds.
   *   e.g '2014-05-28T18:46:18.764425Z'.
   *
   * @return int
   *   A unix timestamp of the date.
   *
   * @throws \InvalidArgumentException
   *   Exception is thrown when an unexpected type is encountered.
   */
  public static function parseCloudFlareDate($str_date) {
    if (is_null($str_date)) {
      return NULL;
    }

    $timezone = new \DateTimeZone('UTC');
    $date = \DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $str_date, $timezone);
    $is_date_invalid = $date === FALSE;

    if ($is_date_invalid) {
      throw new \InvalidArgumentException("The value: $str_date is an invalid.  Date fields will always be in UTC ISO-8601 format, including microseconds.  e.g '2014-05-28T18:46:18.764425Z'");
    }

    $date_output = $date->getTimestamp();

    return $date_output;
  }

}
