<?php

namespace CloudFlarePhpSdk\ApiTypes\Zone;

/**
 * Contains fields for a setting that has an on/off value.
 *
 * Having a specific type for these allows us to manipulate these settings in a
 * similar manner.
 */
class ZoneSettingSecurityLevel extends ZoneSettingSelectBase {

  // Zone security levels.
  const SECURITY_OFF = 'essentially_off';
  const SECURITY_LOW = 'low';
  const SECURITY_MEDIUM = 'medium';
  const SECURITY_HIGH = 'high';
  const SECURITY_UNDERATTACK = 'under_attack';

  /**
   * Returns a listing of the strings denoting Ssl level.  Poor-man's enum.
   *
   * @return array
   *   List of Ssl string levels.
   */
  public static function getSecurityLevels() {
    return [
      self::SECURITY_OFF,
      self::SECURITY_LOW,
      self::SECURITY_MEDIUM,
      self::SECURITY_HIGH,
      self::SECURITY_UNDERATTACK,
    ];
  }

  /**
   * Returns a listing of valid values for ZoneSettingSecurityLevel.
   *
   * @return array
   *   Valid values for ZoneSettingSecurityLevel.
   */
  public function validValues() {
    return self::getSecurityLevels();
  }

}
