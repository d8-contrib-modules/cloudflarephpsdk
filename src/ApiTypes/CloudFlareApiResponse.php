<?php

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
   * The current request page returned from the Api.
   *
   * @var int
   */
  private $page;

  /**
   * The number of results returned per page.
   *
   * @var int
   */
  private $resultsPerPage;

  /**
   * The number of page.
   *
   * @var int
   */
  private $numPages;

  /**
   * The number of individual records returned.
   *
   * @var int
   */
  private $totalCount;

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
   * The current request page returned from the Api.
   *
   * @return int
   *   Page number.
   */
  public function getPage() {
    return $this->page;
  }

  /**
   * The number of results returned per page.
   *
   * @return int
   *   Results per page.
   */
  public function getResultsPerPage() {
    return $this->resultsPerPage;
  }

  /**
   * The number of pages of results returned by the API.
   *
   * @return int
   *   Number of pages.
   */
  public function getNumPages() {
    return $this->numPages;
  }

  /**
   * The total number of records that could be returned across pages by the API.
   *
   * @return int
   *   The number of records.
   */
  public function getNumRecords() {
    return $this->totalCount;
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

    $has_pagination_data = isset($response['result_info']['total_count']);
    if ($has_pagination_data) {
      $this->totalCount = $response['result_info']['total_count'];
      $this->page = $response['result_info']['page'];
      $this->resultsPerPage = $response['result_info']['per_page'];
      $this->numPages = $response['result_info']['total_pages'];
    }
  }

}
