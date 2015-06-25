<?php

/**
 * @file
 * Implementation of ZoneSettingSsl class.
 */

namespace CloudFlarePhpSdk\ApiTypes\Zone;
use CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException;

/**
 * Contains fields for a setting that has an on/off value.
 *
 * Having a specific type for these allows us to manipulate these settings in a
 * similar manner.
 */
class ZoneSettingSsl extends ZoneSettingBase {
  const SSL_OFF = 'off';
  const SSL_FLEXIBLE = 'flexible';
  const SSL_FULL = 'full';
  const SSL_STRICT = 'strict';

  /**
   * The response value.
   *
   * @var string
   */
  private $value;



  public static function getSslLevels(){
    return [
      self::SSL_OFF,
      self::SSL_FLEXIBLE,
      self::SSL_FULL,
      self::SSL_STRICT
    ];
  }

  /**
   * Gets the response value.
   *
   * @return int
   *   The query response.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Sets the zone Ssl level and marks the setting as editied.
   *
   * @param string $value
   *   Zone security level from SSL_LEVELS.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Throws exception when the user tries to change a value that is not
   *   modifiable for them.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *    Exception thrown when a value not in the getSslLevels
   *    passed to the function.
   */
  public function setValue($value) {
    $this->assertEditable();
    $this->_setValue($value);
    $this->markForEdit();
  }

  /**
   * Sets the zone Ssl level.
   *
   * @param string $value
   *   Zone security level from getSslLevels.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *    Exception thrown when a value not in the getSslLevels
   *    passed to the function.
   */
  private function _setValue($value) {
    $this->assertValidValue($value);
    $this->value = $value;
  }

  public function assertValidValue($value) {
    $is_null = is_null($value);
    $is_bool = $value === TRUE || $value === FALSE;
    $is_ssl_level = in_array($value, $this->getSslLevels());

    if ($is_null || $is_bool || !$is_ssl_level) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $this->value);
    }
  }


  /**
   * Default constructor for ZoneSettingsSsl.
   *
   * @param string $value
   *   The settings boolean value.
   * @param bool $setting_id
   *   The name of the setting.
   * @param bool $editable
   *   TRUE if editable.  FALSE if setting is read-only.
   * @param int $modified_on
   *   The last time that the setting was modified on the server.
   */
  public function __construct($value, $setting_id, $editable, $modified_on) {
    parent::__construct($setting_id, $editable, $modified_on);
    $this->_setValue($value);
  }

}
