<?php

namespace CloudFlarePhpSdk\ApiTypes\Zone;

/**
 * Contains fields for a setting that has an on/off value.
 *
 * Having a specific type for these allows us to manipulate these settings in a
 * similar manner.
 */
class ZoneSettingBrowserCacheTtl extends ZoneSettingSelectBase {

  /**
   * Returns a listing of valid values for ZoneSettingBrowserCacheTtl.
   *
   * @return array
   *   Valid values for ZoneSettingBrowserCacheTtl.
   */
  public function validValues() {
    $value = [
      30,
      60,
      300,
      1200,
      1800,
      3600,
      7200,
      10800,
      14400,
      18000,
      28800,
      43200,
      57600,
      72000,
      86400,
      172800,
      259200,
      345600,
      432000,
      691200,
      1382400,
      2073600,
      2678400,
      5356800,
      16070400,
      31536000,
    ];

    return $value;
  }

}
