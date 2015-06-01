<?php

/**
 * @file
 * Implementation of ZoneSettingBool class.
 */

namespace CloudFlarePhpSdk\ApiTypes\Zone;
use CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException;


/**
 * Contains fields for a setting that has an on/off value.
 *
 * Having a specific type for these allows us to manipulate these settings in a
 * similar manner.
 */
class ZoneSettingBool extends ZoneSettingBase {

  /**
   * The response value.
   *
   * @var bool
   */
  protected $value;


  /**
   * Gets the response value.
   *
   * @return bool
   *   The query response.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Sets the value of the setting and marks the setting as edited.
   *
   * @param bool $value
   *   The boolean to set the current setting to.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   Exception thrown when the value passed in is not a bool.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Exception thrown if a non-bool is passed.
   */
  public function setValue($value) {
    $this->assertEditable($value);
    $this->_setValue($value);
    $this->markForEdit();
  }

  public function _setValue($value) {
    $this->assertValidValue($value);
    $this->value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
  }

  public function assertValidValue($value) {
    $result = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

    if (is_null($result)) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $this->value);
    }
  }

  /**
   * Default constructor for ZoneSettingsBool.
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
    $this->_setValue($value);
  }

}
