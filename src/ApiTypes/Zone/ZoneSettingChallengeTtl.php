<?php

namespace CloudFlarePhpSdk\ApiTypes\Zone;

/**
 * Contains fields for a setting that has an on/off value.
 *
 * Having a specific type for these allows us to manipulate these settings in a
 * similar manner.
 */
class ZoneSettingChallengeTtl extends ZoneSettingSelectBase {

  /**
   * Returns a listing of valid values for ZoneSettingChallengeTtl.
   *
   * @return array
   *   Valid values for ZoneSettingChallengeTtl.
   */
  public function validValues() {
    $value = [
      300,
      900,
      1800,
      2700,
      3600,
      7200,
      10800,
      14400,
      28800,
      57600,
      86400,
      604800,
      2592000,
      31536000,
    ];

    return $value;
  }

}
