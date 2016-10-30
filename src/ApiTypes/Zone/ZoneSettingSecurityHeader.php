<?php

namespace CloudFlarePhpSdk\ApiTypes\Zone;

use CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException;

/**
 * Contains fields for ZoneSettingSecurityHeader.
 */
class ZoneSettingSecurityHeader extends ZoneSettingBase {

  /**
   * The Zone Security header value.
   *
   * @var array
   */
  private $value;

  /**
   * Default constructor for ZoneSettingSecurityHeader.
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
    $this->value = $value;
  }

  /**
   * Gets security level.
   *
   * @return array
   *   The security Level.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Sets the value for zone security header settings.
   *
   * @param bool $enabled
   *   Turns the ZoneSettingSecurityHeader on or off.
   * @param int $max_age
   *   The max age of the security header.
   * @param bool $include_subdomains
   *   If TRUE applies strict transport setting to subdomains.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Throws exception when the user tries to change a value that is not
   *   modifiable for them.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   Exception is thrown if one of the values does match the method's
   *   signature.
   */
  public function setValue($enabled, $max_age, $include_subdomains) {
    if (!is_bool($enabled)) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $enabled);
    }

    if (!is_int($max_age)) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $max_age);
    }

    if (!is_bool($include_subdomains)) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $include_subdomains);
    }

    $this->markForEdit();

    $this->value = [
      'strict_transport_security' =>
      [
        'enabled' => $enabled,
        'max_age' => $max_age,
        'include_subdomains' => $include_subdomains,
      ],
    ];
  }

}
