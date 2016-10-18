<?php

namespace CloudFlarePhpSdk\ApiTypes\Zone;

use CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException;

/**
 * Contains fields for a setting that only allows a specified number of values.
 */
abstract class ZoneSettingSelectBase extends ZoneSettingBase {

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
    $this->value = $value;
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
    if (!in_array($value, $this->validValues())) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $value);
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

  /**
   * Returns a listing of valid values for select values.
   *
   * @return array
   *   Valid values for the select.
   */
  public abstract function validValues();

}
