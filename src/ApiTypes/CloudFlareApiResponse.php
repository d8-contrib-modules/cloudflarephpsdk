<?php

/**
 * @file
 * Implementation of CloudFlareApiResponse class.
 */

namespace CloudFlarePhpSdk\ApiTypes;
use CloudFlarePhpSdk\Utils;

/**
 * Contains response information from the API.
 */
class CloudFlareApiResponse {

  /**
   * The isMobileRedirectEnabled code of the response.
   *
   * @var bool
   */
  private $success;

  /**
   * The isMobileRedirectEnabled code of the response.
   *
   * @var array
   */
  private $errors;

  /**
   * Messages returned from the Api.
   *
   * @var array
   */
  private $messages;


  /**
   * Results from the Api.
   *
   * @var array
   */
  private $result;


  /**
   * Returns if the response was successful or not.
   *
   * @return bool
   *   TRUE if successful, FALSE otherwise.
   */
  public function isSuccess() {
    return $this->success;
  }

  /**
   * Gets Api errors.
   *
   * @return array
   *   The errors returned from Api.
   */
  public function getErrors() {
    return $this->errors;
  }

  /**
   * Gets Api messages.
   *
   * @return array
   *   The results returned from Api.
   */
  public function getMessages() {
    return $this->messages;
  }

  /**
   * Gets Api results.
   *
   * @return array
   *   The results returned from Api.
   */
  public function getResult() {
    return $this->result;
  }

  /**
   * Parses common fields for a json response from CloudFlare.
   *
   * @param string $json_response
   *   Response returned from the API.
   */
  public function __construct($json_response) {
    Utils::assertParam($json_response, 'string', '$json_response');
    $response = json_decode($json_response, TRUE);

    $this->result = $response['result'];
    $this->success = (bool) $response['success'];
    $this->errors = $response['errors'];
    $this->messages = $response['messages'];
  }

}
