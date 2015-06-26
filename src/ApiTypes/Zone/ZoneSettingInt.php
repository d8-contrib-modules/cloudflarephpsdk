<?php

/**
 * @file
 * Implementation of ZoneSettingBool class.
 */

namespace CloudFlarePhpSdk\ApiTypes\Zone;
use CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException;

/**
 * Contains fields for a setting that has an integer value.
 */
class ZoneSettingInt extends ZoneSettingBase {

  /**
   * The response value.
   *
   * @var int
   */
  private $value;


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
   * Sets the setting value and marks the setting as having been edited.
   *
   * @param int $value
   *   The value to update the setting to.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   Exception thrown when the value passed in is not an int.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Throws exception when the user tries to change a value that is not
   *   modifiable for them.
   */
  public function setValue($value) {
    $this->assertEditable($value);
    $this->setInternalValue($value);
    $this->markForEdit();
  }

  /**
   * Sets the setting value.
   *
   * @param int $value
   *   The value to update the setting to.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   Exception thrown when the value passed in is not an int.
   */
  private function setInternalValue($value) {
    $this->assertValidValue($value);
    $this->value = filter_var($value, FILTER_VALIDATE_INT);
  }

  /**
   * Asserts that the value is valid.
   *
   * @param bool $value
   *   The int to type check.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   When the value is invalid an exception is thrown.
   */
  public function assertValidValue($value) {
    $is_null = is_null($value);
    $is_bool = $value === TRUE || $value === FALSE;
    $is_int = filter_var($value, FILTER_VALIDATE_INT) !== FALSE;

    if ($is_null || $is_bool || !$is_int) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $this->value);
    }
  }


  /**
   * Default constructor for ZoneSettingsInt.
   *
   * @param bool $value
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

}
