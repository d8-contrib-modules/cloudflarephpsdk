<?php

namespace CloudFlarePhpSdk\ApiEndpoints;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;

use CloudFlarePhpSdk\ApiTypes\CloudFlareApiResponse;
use CloudFlarePhpSdk\Exceptions\CloudFlareHttpException;
use CloudFlarePhpSdk\Exceptions\CloudFlareApiException;
use CloudFlarePhpSdk\Exceptions\CloudFlareTimeoutException;
use CloudFlarePhpSdk\Exceptions\CloudFlareInvalidCredentialException;

/**
 * Base functionality for interacting with CloudFlare's API.
 */
abstract class CloudFlareAPI {

  /**
   * HTTP client used for interfacing with the API.
   *
   * @var \GuzzleHttp\Client
   */
  private $client;

  /**
   * Last raw response returned from the API.  Intended for debugging only.
   *
   * @var \Psr\Http\Message\ResponseInterface
   */
  protected $lastHttpResponse;

  /**
   * Last raw response returned from the API.  Intended for debugging only.
   *
   * @var \CloudFlarePhpSdk\ApiTypes\CloudFlareApiResponse
   */
  protected $lastApiResponse;
  // Contact "source" property values.
  const REQUEST_TYPE_GET = 'GET';
  const REQUEST_TYPE_POST = 'POST';
  const REQUEST_TYPE_PUT = 'PUT';
  const REQUEST_TYPE_PATCH = 'PATCH';
  const REQUEST_TYPE_DELETE = 'DELETE';
  const REQUEST_ALL_PAGES = -1;
  const API_ENDPOINT_BASE = 'https://api.cloudflare.com/client/v4/';

  // The length of the Api key.
  // The Api will throw a non-descriptive http code: 400 exception if the key
  // length is greater than 37. If the key is invalid but the expected length
  // the Api will return a more informative http code of 403.
  const API_KEY_LENGTH = 37;

  // The CloudFlare API sets a maximum of 1,200 requests in a 5-minute period.
  const API_RATE_LIMIT = 1200;

  // The CloudFlare API sets a maximum of 2000 requests in a 24-hour period.
  const API_TAG_PURGE_DAILY_RATE_LIMIT = 2000;

  // Max Number of.
  const MAX_TAG_PURGES_PER_REQUEST = 30;

  // Time in seconds.
  const HTTP_CONNECTION_TIMEOUT = 1.5;

  // Time in seconds.
  const HTTP_TIMEOUT = 3;

  // MAX Number of results returned by the API in one request.
  const MAX_ITEMS_PER_PAGE = 50;

  // Pagination Direction ascending.
  const PAGINATION_DIRECTION_ASC = 'ASC';

  // Pagination Direction descending.
  const PAGINATION_DIRECTION_DESC = 'DESC';

  /**
   * Constructor for the Cloudflare SDK object.
   *
   * Parameters include minimum required credentials for all requests.
   *
   * @param string $apikey
   *   API key generated on the "My Account" page.
   * @param string $email
   *   Email address associated with your CloudFlare account.
   * @param \GuzzleHttp\Handler\MockHandler $mock_handler
   *   Allow mocking of the Api.
   */
  public function __construct($apikey, $email, MockHandler $mock_handler = NULL) {
    $this->apikey = $apikey;
    $this->email = $email;
    $headers = [
      'X-Auth-Key' => $apikey,
      'X-Auth-Email' => $email,
      'Content-Type' => 'application/json',
    ];

    $client_params = [
      'base_uri' => self::API_ENDPOINT_BASE,
      'headers' => $headers,
      'timeout'         => self::HTTP_TIMEOUT,
      'connect_timeout' => self::HTTP_CONNECTION_TIMEOUT,
    ];

    if ($mock_handler != NULL) {
      $client_params['handler'] = $mock_handler;
    }

    $this->client = new Client($client_params);
  }

  /**
   * Sends a request to the API.
   *
   * @param string $request_type
   *   The type of HTTP request being made.
   *   Expected to be one of: REQUEST_TYPE_GET, REQUEST_TYPE_POST
   *   REQUEST_TYPE_PATCH, REQUEST_TYPE_PUT or REQUEST_TYPE_DELETE.
   * @param string $api_end_point
   *   The relative url for the endpoint.  All endpoints are assumed to be
   *   relative to 'https://api.cloudflare.com/client/v4/'.
   * @param array $request_params
   *   (Optional) Associative array of parameters to be passed with the HTTP
   *   request.
   *
   * @return \CloudFlarePhpSdk\ApiTypes\CloudFlareApiResponse
   *   The response from the Api
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareApiException
   *    Exception at the application level.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareHttpException
   *     Exception at the Http level.
   */
  protected function makeRequest($request_type, $api_end_point, $request_params = []) {
    // Default the number of pages returned by the API to MAX.
    if (!isset($request_params['per_page'])) {
      $request_params['per_page'] = self::MAX_ITEMS_PER_PAGE;
    }
    // This check seems superfluous.  However, the Api only returns a http 400
    // code. This proactive check gives us more information.
    $is_api_key_valid = strlen($this->apikey) == CloudFlareAPI::API_KEY_LENGTH;
    $is_api_key_alpha_numeric = ctype_alnum($this->apikey);
    $is_api_key_lower_case = !(preg_match('/[A-Z]/', $this->apikey));

    if (!$is_api_key_valid) {
      throw new CloudFlareInvalidCredentialException("Invalid Api Key: Key should be 37 chars long.", 403);
    }

    if (!$is_api_key_alpha_numeric) {
      throw new CloudFlareInvalidCredentialException('Invalid Api Key: Key can only contain alphanumeric characters.', 403);
    }

    if (!$is_api_key_lower_case) {
      throw new CloudFlareInvalidCredentialException('Invalid Api Key: Key can only contain lowercase or numerical characters.', 403);
    }

    try {
      switch ($request_type) {
        case self::REQUEST_TYPE_GET:
          $this->lastHttpResponse = $this->client->get($api_end_point, ['query' => $request_params]);
          break;

        case self::REQUEST_TYPE_POST:
          $this->lastHttpResponse = $this->client->post($api_end_point, ['data' => $request_params]);
          break;

        case self::REQUEST_TYPE_PATCH:
          $this->lastHttpResponse = $this->client->patch($api_end_point, ['json' => $request_params]);
          break;

        case self::REQUEST_TYPE_PUT:
          $this->lastHttpResponse = $this->client->put($api_end_point, ['json' => $request_params]);
          break;

        case self::REQUEST_TYPE_DELETE:
          $this->lastHttpResponse = $this->client->delete($api_end_point, ['json' => $request_params]);
          break;
      }
    }
    catch (ServerException $se) {
      $http_response_code = $se->getCode();
      $http_response_message = $se->getMessage();
      throw new CloudFlareHttpException($http_response_message, $http_response_code, $se->getPrevious());
    }

    catch (RequestException $re) {
      $http_response_code = $re->getCode();
      $http_response_message = $re->getMessage();
      if ($http_response_code == 403) {
        throw new CloudFlareInvalidCredentialException("Unfortunately your credentials failed to authenticate against the CloudFlare API.  Please enter valid credentials.", 403);
      }
      else {
        throw new CloudFlareTimeoutException($http_response_message, $http_response_code, $re->getPrevious());
      }
    }

    $http_response_code = $this->lastHttpResponse->getStatusCode();
    $is_status_code_good = $http_response_code == '200' || $http_response_code == '301';

    // HTTP level error.
    if (!$is_status_code_good) {
      $http_response_message = $this->lastHttpResponse->getReasonPhrase();
      throw new CloudFlareHttpException($http_response_message, $http_response_code, NULL);
    }

    // Note this behavior was introduced in Guzzle 6.
    $response_body = (string) $this->lastHttpResponse->getBody();
    $this->lastApiResponse = new CloudFlareApiResponse($response_body);
    $json_decode_failure = is_null($this->lastApiResponse);
    if ($json_decode_failure) {
      throw new CloudFlareApiException($http_response_code, NULL, "Unable to decode response payload.", NULL);
    }

    $is_request_successful = $this->lastApiResponse->isSuccess();

    // See https://api.cloudflare.com/#responses
    $has_errors_from_api = count($this->lastApiResponse->getErrors()) > 0;

    // Application level error.
    if (!$is_request_successful || $has_errors_from_api) {
      $http_response_message = $this->lastHttpResponse->getReasonPhrase();
      throw new CloudFlareApiException($http_response_code, NULL, $http_response_message, NULL);
    }
    return $this->lastApiResponse;
  }

  /**
   * Request multiple pages of information from the API.
   *
   * @param string $request_type
   *   The type of HTTP request being made.
   *   Expected to be one of: REQUEST_TYPE_GET, REQUEST_TYPE_POST
   *   REQUEST_TYPE_PATCH, REQUEST_TYPE_PUT or REQUEST_TYPE_DELETE.
   * @param string $api_end_point
   *   The relative url for the endpoint.  All endpoints are assumed to be
   *   relative to 'https://api.cloudflare.com/client/v4/'.
   * @param array|null $request_params
   *   (Optional) Associative array of parameters to be passed with the HTTP
   *   request.
   * @param int $page
   *   (Optional) The current page of the request.
   *
   * @return \CloudFlarePhpSdk\ApiTypes\CloudFlareApiResponse
   *   The response from the Api
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareApiException
   *    Exception at the application level.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareHttpException
   *     Exception at the Http level.
   */
  protected function makeListingRequest($request_type, $api_end_point, $request_params = NULL, $page = self::REQUEST_ALL_PAGES) {
    $get_all_pages = $page == self::REQUEST_ALL_PAGES;
    $responses = [];

    if (!$get_all_pages) {
      $request_params['page'] = $page;
    }

    /* @var \CloudFlarePhpSdk\ApiTypes\CloudFlareApiResponse $initial_request */
    $initial_request = $this->makeRequest($request_type, $api_end_point, $request_params);
    $responses[] = $initial_request;
    $num_pages = $initial_request->getNumPages();

    if ($get_all_pages) {
      for ($i = 1; $i < $num_pages; $i++) {
        $request_params['page'] = $i + 1;
        $current_request = $this->makeRequest($request_type, $api_end_point, $request_params);
        $responses[] = $current_request;
      }
    }
    return $responses;
  }

  /**
   * Gets number of pages of records/results for the last API request.
   *
   * @return int
   *   Number of pages.
   */
  public function getTotalNumberOfPagesForLastRequest() {
    $has_pagination_data = is_set($this->lastApiResponse->getNumPages());
    return $has_pagination_data ? $this->lastApiResponse->getNumPages() : 1;
  }

}
