<?php

/**
 * @file
 * Implementation of ZoneSettingBool class.
 */

namespace CloudFlarePhpSdk\ApiTypes\Zone;
use \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException;


/**
 * Contains fields for a setting that has an on/off value.
 *
 * Having a specific type for these allows us to manipulate these settings in a
 * similar manner.
 */
class ZoneSettingSecurityLevel extends ZoneSettingBase {

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
   * The response value.
   *
   * @var string
   */
  private $value;

  /**
   * Default constructor for ZoneSettingsSecurityLevel.
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
    $this->setInternalValue($value);
  }

  /**
   * Gets security level.
   *
   * @return string
   *   The security Level.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Sets the zone security level and marks as edited.
   *
   * @param string $value
   *   Zone security level from SECURITY_LEVELS.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Throws exception when the user tries to change a value that is not
   *   modifiable for them.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *    Exception thrown when a value not in the SECURITY_LEVELS
   *    passed to the function.
   */
  public function setValue($value) {
    $this->assertEditable();
    $this->setInternalValue($value);
    $this->markForEdit();
  }

  /**
   * Sets the zone security level.
   *
   * @param string $value
   *   Zone security level from SECURITY_LEVELS.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Throws exception when the user tries to change a value that is not
   *   modifiable for them.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *    Exception thrown when a value not in the SECURITY_LEVELS
   *    passed to the function.
   */
  public function setInternalValue($value) {
    $this->assertValidValue($value);
    $this->value = $value;
  }

  /**
   * Asserts that the value is valid.
   *
   * @param string $value
   *   The value to check.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   When the value is invalid an exception is thrown.
   */
  public function assertValidValue($value) {
    $is_null = is_null($value);
    $is_bool = $value === TRUE || $value === FALSE;
    $is_valid_level = in_array($value, $this->getSecurityLevels());

    if ($is_null || $is_bool || !$is_valid_level) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $this->value);
    }
  }

}
