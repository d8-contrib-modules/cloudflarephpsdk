<?php

namespace CloudFlarePhpSdk\Exceptions;

/**
 * Defines CloudFlareException.
 *
 * Basic exception thrown from the CloudFlarePhpSdk.
 */
class CloudFlareException extends \Exception {

  /**
   * Constructor for CloudFlareException.
   *
   * @param string $message
   *   The message returned in the HTTP response.
   * @param int $code
   *   The error code.
   * @param \Exception $previous
   *   Previous exceptions thrown higher in the call-stack.
   */
  public function __construct($message, $code, \Exception $previous = NULL) {
    parent::__construct($message, $code, $previous);
    $this->message = $message;
  }

}
