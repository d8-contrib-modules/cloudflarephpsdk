<?php

namespace CloudFlarePhpSdk\Exceptions;

/**
 * Defines CloudFlareInvalidSettingValueException.
 *
 * This exception is thrown when a user attempts to edit a value which they
 * do not have the ability to modify.
 */
class CloudFlareInvalidSettingValueException extends CloudFlareException {
  /**
   * Name of the field that was attempted to be changed.
   *
   * @var string
   */
  private $fieldName;

  /**
   * The invalid value that was attempted to be set.
   *
   * @var string
   */
  private $invalidValue;

  /**
   * Constructor for CloudFlareInvalidSettingValueException.
   *
   * @param string $field_name
   *   The name of the field that was attempted to be set.
   * @param int $value
   *   The value that was attempted to be set.
   * @param \Exception $previous
   *   If this error was triggered by a previous exception passed that stack
   *   information up.
   */
  public function __construct($field_name, $value, \Exception $previous = NULL) {
    $value_as_string = '';

    try {
      $value_as_string = (string) $value;
    }

    catch (\Exception $e) {
      $value_as_string = gettype($value) . 'type ';
    }

    $message = 'CloudFlarePhpSdk: The ' . $value_as_string . ' is not valid for ' . $field_name;

    parent::__construct($message, 0, $previous);
    $this->fieldName = $field_name;
    $this->invalidValue = $value;
  }

}
