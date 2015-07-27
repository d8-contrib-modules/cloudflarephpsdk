<?php
/**
 * @file
 * Contains CloudFlareApiException.
 */

namespace CloudFlarePhpSdk\Exceptions;

/**
 * Defines CloudFlareApiException.
 *
 * The purpose of this class is to translate exceptions from API and Guzzle
 * (the tool being used for web-services) to the application layer so that they
 * can be handled accordingly.
 */
class CloudFlareHttpException extends CloudFlareException {
  /**
   * HTTP response code.
   *
   * @var null|string
   */
  private $httpResponseCode;

  /**
   * Constructor for CloudFlareHTTPException.
   *
   * @param string $http_response_code
   *   The HTTP response code returned by the API server.
   * @param string $message
   *   The message returned in the HTTP response.
   * @param \Exception $previous
   *   Previous exceptions thrown higher in the call-stack.
   */
  public function __construct($http_response_code, $message, \Exception $previous = NULL) {
    parent::__construct($message, $http_response_code, $previous);
    $this->httpResponseCode = $http_response_code;
    $this->message = $message;
  }

}
