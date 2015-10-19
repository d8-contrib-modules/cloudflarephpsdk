<?php

/**
 * @file
 * Base functionality for sending requests to the CloudFlare API.
 */

namespace CloudFlarePhpSdk\ApiEndpoints;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
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

  // The CloudFlare API sets a maximum of 200 requests in a 24-hour period.
  const API_TAG_PURGE_DAILY_RATE_LIMIT = 200;

  // Max Number of
  const MAX_PURGES_PER_REQUEST = 30;

  // Time in seconds.
  const HTTP_CONNECTION_TIMEOUT = 1.5;

  // Time in seconds.
  const HTTP_TIMEOUT = 3;

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
  public function __construct($apikey, $email, $mock_handler = NULL) {
    $this->apikey = $apikey;
    $this->email = $email;
    $headers = ['X-Auth-Key' => $apikey, 'X-Auth-Email' => $email, 'Content-Type' => 'application/json'];
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
      throw new CloudFlareTimeoutException($http_response_code, $http_response_message, $re->getPrevious());
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
