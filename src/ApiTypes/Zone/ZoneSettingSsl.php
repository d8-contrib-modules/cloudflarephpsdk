<?php

namespace CloudFlarePhpSdk\ApiTypes\Zone;

/**
 * Contains fields for a setting that has an on/off value.
 *
 * Having a specific type for these allows us to manipulate these settings in a
 * similar manner.
 */
class ZoneSettingSsl extends ZoneSettingSelectBase {
  const SSL_OFF = 'off';
  const SSL_FLEXIBLE = 'flexible';
  const SSL_FULL = 'full';
  const SSL_STRICT = 'strict';

  /**
   * Returns a listing of the strings denoting Ssl level.
   *
   * @return array
   *   List of Ssl string levels.
   */
  public static function getSslLevels() {
    return [
      self::SSL_OFF,
      self::SSL_FLEXIBLE,
      self::SSL_FULL,
      self::SSL_STRICT,
    ];
  }

  /**
   * Returns a listing of valid values for ZoneSettingSsl.
   *
   * @return array
   *   Valid values for ZoneSettingSsl.
   */
  public function validValues() {
    return self::getSslLevels();
  }

}
