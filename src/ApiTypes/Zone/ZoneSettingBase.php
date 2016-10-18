<?php

namespace CloudFlarePhpSdk\ApiTypes\Zone;

use CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException;

/**
 * Contains common fields for a response from the zone endpoint.
 */
abstract class ZoneSettingBase {
  /**
   * Name of the zone setting that was queried.
   *
   * @var string
   */
  protected $id;

  /**
   * Is the field is editable given the current API credentials used.
   *
   * @var bool
   */
  protected $editable;

  /**
   * The last time the value was changed at CloudFlare.
   *
   * @var int
   */
  protected $modifiedOn;

  /**
   * Indicator if the value was changed locally.
   *
   * @var bool
   */
  protected $modifiedLocally;


  /**
   * Default constructor for ZoneSetting.
   *
   * @param array $response
   *   Array containing the Json response from the API.
   */

  /**
   * Default constructor for ZoneSetting.
   *
   * @param string $setting_id
   *   Name of the zone setting.
   * @param bool $editable
   *   TRUE if editable.  FALSE otherwise.
   * @param int $modified_on
   *   The time modified on the server.
   */
  public function __construct($setting_id, $editable, $modified_on) {
    $this->id = (string) $setting_id;
    $this->editable = (bool) $editable;
    $this->modifiedOn = $modified_on;
    $this->modifiedLocally = FALSE;
  }

  /**
   * Gets the name of the zone setting that was queried.
   *
   * @return string
   *   The zoneId of the zone setting.
   */
  public function getZoneSettingName() {
    return $this->id;
  }

  /**
   * Checks if the field is editable given the current API credentials used.
   *
   * @return bool
   *   TRUE if editable. False otherwise.
   */
  public function isEditable() {
    return $this->editable;
  }

  /**
   * Gets the last time the value was changed at CloudFlare.
   *
   * @return int
   *   The time modified.
   */
  public function getTimeModifiedOnServer() {
    return $this->modifiedOn;
  }

  /**
   * Checks if the setting has been changed locally.
   *
   * @return bool
   *   TRUE if there are local changes.  FALSE if the value is unchanged.
   */
  public function isModifiedLocally() {
    return $this->modifiedLocally;
  }

  /**
   * Marks a setting as having been edited locally.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   If the setting cannot be edited an exception is thrown.
   */
  protected function markForEdit() {
    $this->assertEditable();
    $this->modifiedLocally = TRUE;
  }

  /**
   * Marks a setting as having been edited locally.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   If the setting cannot be edited an exception is thrown.
   */
  public function assertEditable() {
    if (!$this->editable) {
      throw new CloudFlareNotModifiableException($this->id);
    }
  }

}
