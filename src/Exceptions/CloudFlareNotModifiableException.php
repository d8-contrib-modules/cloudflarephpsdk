<?php

namespace CloudFlarePhpSdk\Exceptions;

/**
 * Defines CloudFlareNotModifiableException.
 *
 * This exception is thrown when a user attempts to edit a value which they
 * do not have the ability to modify.
 */
class CloudFlareNotModifiableException extends CloudFlareException {

  /**
   * Name of the field that was attempted to be changed.
   *
   * @var string
   */
  private $fieldName;

  /**
   * Constructor for CloudFlareNotModifiableException.
   *
   * @param string $field_name
   *   The field that was attempted to be altered.
   * @param \Exception $previous
   *   The previous exceptions in the call stack.
   */
  public function __construct($field_name, \Exception $previous = NULL) {
    $this->fieldName = $field_name;
    $this->message = "CloudFlarePhpSdk: The field $field_name is not editable.";
    parent::__construct($this->message, 0, $previous);
  }

}
