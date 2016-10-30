<?php

namespace CloudFlarePhpSdk\Exceptions;

/**
 * Defines CloudFlareApiException.
 *
 * The purpose of this class is to translate exceptions from API and Guzzle
 * (the tool being used for web-services) to the application layer so that they
 * can be handled accordingly.
 */
class CloudFlareApiException extends CloudFlareException {
  /**
   * API level error code.
   *
   * NOTE: This is NOT the HTTP response code.
   *
   * @var null|string
   */
  private $apiResponseCode;

  /**
   * HTTP response code.
   *
   * @var null|string
   */
  private $httpResponseCode;

  /**
   * Constructor for CloudFlareApiException.
   *
   * @param string $http_response_code
   *   The HTTP response code returned from the request.  We expect this to be
   *   200 or 301.  Other error codes should be handled by
   *   CloudFlareHttpException.
   * @param int $api_response_code
   *   The response code returned from the CloudFlare API.
   * @param string $message
   *   The user readable description of what went wrong.
   * @param \Exception $previous
   *   If there were previous errors pass them along.
   */
  public function __construct($http_response_code, $api_response_code, $message, \Exception $previous = NULL) {
    parent::__construct($message, $api_response_code, $previous);
    $this->httpResponseCode = $http_response_code;
    $this->apiResponseCode = $api_response_code;
    $this->message = $message;
  }

}
