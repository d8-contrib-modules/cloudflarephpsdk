<?php

/**
 * @file
 * Functions for interacting with Zones via the CloudFlare API.
 */

namespace CloudFlarePhpSdk\ApiEndpoints;
use CloudFlarePhpSdk\ApiTypes\Zone\Zone;
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettings;


/**
 * Provides functionality for reading and manipulating CloudFlare zones via API.
 */
class ZoneApi extends CloudFlareAPI {

  /**
   * Constructor for new instance of ZoneApi.
   *
   * @param string $apikey
   *   Cloud flare API key.
   * @param NULL|string $email
   *   Email address of API user.
   */
  public function __construct($apikey, $email = NULL, $mock = NULL) {
    parent::__construct($apikey, $email, $mock);
  }

  /**
   * Retrieves a listing of Zones from CloudFlare.
   *
   * @return array
   *   A array of CloudFlareZones objects from the current CloudFlare account.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareApiException
   *   Throws an exception if there is an application level error returned from
   *   the API.
   */
  public function listZones() {
    $request_path = 'zones';
    $result = $this->makeRequest(self::REQUEST_TYPE_GET, $request_path);
    $parsed_zones = [];

    foreach ($result->getResult() as $zone) {
      $parsed_zones[] = new Zone($zone);
    }
    return $parsed_zones;
  }

  /**
   * Gets zone information and settings from CloudFlare API.
   *
   * @param string $zone_id
   *   The zone to look up.
   *
   * @return \CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettings
   *   Zone object with settings and info from CloudFlare API.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareApiException
   *   Throws an exception if there is an application level error returned from
   *   the API.
   */
  public function getZoneSettings($zone_id) {
    $request_path = strtr('zones/:zone_identifier/settings', [':zone_identifier' => $zone_id]);
    $result = $this->makeRequest(self::REQUEST_TYPE_GET, $request_path);
    return new ZoneSettings($zone_id, $result);

  }

  /**
   * Updates multiple zone settings at CloudFlare.
   *
   * @param \CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettings $zone
   *   Accepts a Zone object with the changes set.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareApiException
   *   Throws an exception if there is an application level error returned from
   *   the API.
   */
  public function updateZone(ZoneSettings $zone) {
    $request_path = strtr('zones/:zone_identifier/settings', [':zone_identifier' => $zone->getId()]);
    $changed_settings = $zone->getChangedResults();
    $this->makeRequest(self::REQUEST_TYPE_PATCH, $request_path, $changed_settings);
  }

  /**
   * Purges all files and paths from a zone.
   *
   * @param string $zone_id
   *   The zoneId for the zone to access.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareApiException
   *   Throws an exception if there is an application level error returned from
   *   the API.
   */
  public function purgeAllFiles($zone_id) {
    $request_path = strtr('zones/:identifier/purge_cache', [':identifier' => $zone_id]);
    $this->makeRequest(self::REQUEST_TYPE_DELETE, $request_path, ['purge_everything' => TRUE]);
  }

  /**
   * Purges multiple files from CloudFlare.
   *
   * @param string $zone_id
   *   The zoneId for the zone to access.
   * @param array $files
   *   The list of files/paths to purge.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareApiException
   *   Throws an exception if there is an application level error returned from
   *   the API.
   */
  public function purgeIndividualFiles($zone_id, array $files) {
    $request_path = strtr('zones/:identifier/purge_cache', [':identifier' => $zone_id]);
    $this->makeRequest(self::REQUEST_TYPE_DELETE, $request_path, ['files' => $files]);
  }

  /**
   * Purges tags from CloudFlare.
   *
   * @param string $zone_id
   *   The zoneId for the zone to access.
   * @param array $tags
   *   The list of tags to purge.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareApiException
   *   Throws an exception if there is an application level error returned from
   *   the API.
   */
  public function purgeTags($zone_id, array $tags) {
    $request_path = strtr('zones/:identifier/purge_cache', [':identifier' => $zone_id]);
    $this->makeRequest(self::REQUEST_TYPE_DELETE, $request_path, ['tags' => $tags]);
  }

}
