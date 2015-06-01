<?php

/**
 * @file
 * Base functionality for sending requests to the CloudFlare API.
 */

namespace CloudFlarePhpSdk\ApiEndpoints;

use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;

use CloudFlarePhpSdk\ApiTypes\CloudFlareApiResponse;
use CloudFlarePhpSdk\Exceptions\CloudFlareHttpException;
use CloudFlarePhpSdk\Exceptions\CloudFlareApiException;

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
   * @var Response;
   */
  protected $lastHttpResponse;
  protected $lastApiResponse;
  // Contact "source" property values.
  const REQUEST_TYPE_GET = 'GET';
  const REQUEST_TYPE_POST = 'POST';
  const REQUEST_TYPE_PUT = 'PUT';
  const REQUEST_TYPE_PATCH = 'PATCH';
  const REQUEST_TYPE_DELETE = 'DELETE';
  const API_ENDPOINT_BASE = 'https://api.cloudflare.com/client/v4/';

  // The CloudFlare API sets a maximum of 1,200 requests in a 5-minute period.
  const API_RATE_LIMIT = 1200;

  /**
   * Constructor for the Cloudflare SDK object.
   *
   * Parameters include minimum required credentials for all requests.
   *
   * @param string $apikey
   *   API key generated on the "My Account" page.
   * @param string $email
   *   Email address associated with your CloudFlare account.
   */
  public function __construct($apikey, $email) {
    $this->apikey = $apikey;
    $this->email = $email;
    $this->client = new Client([
      'base_url' => self::API_ENDPOINT_BASE,
      'defaults' => [
        'headers' => ['X-Auth-Key' => $apikey, 'X-Auth-Email' => $email, 'Content-Type' => 'application/json'],
        'verify' => FALSE
      ]
    ]);
  }

  /**
   * Sets a mock API response.
   *
   * This is only intended to be used for automated unit testing.
   *
   * @param \GuzzleHttp\Mock $mock
   *   Mock Response Object.
   */
  public function setMockApi(Mock $mock) {
    $this->client->getEmitter()->attach($mock);

    /*$this->client = new Client([
      'handler' => $mock,
      'base_url' => self::API_ENDPOINT_BASE,
      'headers' => ['X-Auth-Key' => "test", 'X-Auth-Email' => '1@2.com', 'Content-Type' => 'application/json'],
      'verify' => FALSE
    ]);*/
  }


  /**
   * Accepts a HTTP response code and returns isMobileRedirectEnabled.
   *
   * @param string $response_code
   *   The HTTP response code returned from the cloudflare API.
   *
   * @return string
   *   String associated with the HTTP code.
   */
  public function responseCodeToStatusString($response_code) {
    switch ($response_code) {
      case '200':
        return 'OK';

      case '304':
        return 'Not Modified';

      case '400':
        return 'Bad Request';

      case '401':
        return 'Unauthorized';

      case '403':
        return 'Forbidden';

      case '429':
        return 'Too many requests';

      case '405':
        return 'Method Not Allowed';

      case '415':
        return 'Unsupported Media Type';
    }
    return 'Unknown Response Code';
  }


  /**
   * Accepts a HTTP response code and returns the description string for it.
   *
   * @param string $response_code
   *   The HTTP response code returned from the cloudflare API.
   *
   * @return string
   *   Description associated with the HTTP code.
   */
  public function responseCodeToDescription($response_code) {
    switch ($response_code) {
      case '200':
        return 'request successful';

      case '304':
        return '';

      case '400':
        return 'request was invalid';

      case '401':
        return 'user does not have permission';

      case '403':
        return 'request not authenticated';

      case '429':
        return 'client is rate limited';

      case '405':
        return 'incorrect HTTP method provided';

      case '415':
        return 'response is not valid JSON';
    }
    return 'Unknown Response Code';
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
   * @param array|null $request_params
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
  protected function makeRequest($request_type, $api_end_point, $request_params = NULL) {
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
          // json,data
          break;
      }
    }
    catch (ServerException $se) {
      $http_response_code = $se->getCode();
      $http_response_message = $se->getMessage();
      throw new CloudFlareHttpException($http_response_code, $http_response_message, $se->getPrevious());
    }

    catch (RequestException $re) {
      $http_response_code = $re->getCode();
      $http_response_message = $re->getMessage();
      throw new CloudFlareHttpException($http_response_code, $http_response_message, $re->getPrevious());
    }

    $http_response_code = $this->lastHttpResponse->getStatusCode();
    $is_status_code_good = $http_response_code == '200' || $http_response_code == '301';

    // HTTP level error.
    if (!$is_status_code_good) {
      $http_response_message = $this->lastHttpResponse->getReasonPhrase();
      throw new CloudFlareHttpException($http_response_code, $http_response_message, NULL);
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

}
