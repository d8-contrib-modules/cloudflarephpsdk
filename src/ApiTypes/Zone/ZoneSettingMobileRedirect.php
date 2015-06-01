<?php

/**
 * @file
 * Implementation of ZoneSettingMobileRedirect class.
 */

namespace CloudFlarePhpSdk\ApiTypes\Zone;
use CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException;
use CloudFlarePhpSdk\Utils;

/**
 * Contains fields for ZoneSettingMobileRedirect.
 */
class ZoneSettingMobileRedirect extends ZoneSettingBase {
  /* @var bool $isMobileRedirectEnabled */
  private $isMobileRedirectEnabled;

  /* @var string $string */
  private $mobileSubdomain;

  /* @var bool $isStripUriEnabled */
  private $isStripUriEnabled;

  /**
   * Default constructor for ZoneSettingMobileRedirect.
   *
   * @param bool $status
   *   TRUE if enabled.  FALSE if disabled.
   * @param string $mobile_subdomain
   *    Subdomain prefix you wish to redirect visitors on mobile devices to
   *   (subdomain must already exist).
   * @param bool $strip_uri
   *   Whether to drop the current page path and redirect to the mobile
   *   subdomain URL root or to keep the path and redirect to the same page on
   *   the mobile subdomain.
   * @param bool $setting_id
   *   The name of the setting.
   * @param bool $editable
   *   TRUE if editable.  FALSE if setting is read-only.
   * @param int $modified_on
   *   The last time that the setting was modified on the server.
   */
  public function __construct($status, $mobile_subdomain, $strip_uri, $setting_id, $editable, $modified_on) {
    parent::__construct($setting_id, $editable, $modified_on);
    $this->setIsMobileRedirectEnabled($status);
    $this->setMobileSubdomain($mobile_subdomain);
    $this->setIsStripUriEnabled($strip_uri);
  }

  /**
   * Checks if mobile redirects are enabled.
   *
   * @return bool
   *   TRUE if mobile redirects are enabled. FALSE otherwise.
   */
  public function isIsMobileRedirectEnabled() {
    return $this->isMobileRedirectEnabled;
  }

  /**
   * Subdomain prefix you wish to redirect visitors on mobile devices to.
   *
   * @return NULL|string
   *   The current subdomain.
   */
  public function getMobileSubdomain() {
    return $this->mobileSubdomain;
  }

  /**
   * Checks if Uri stripping enabled.
   *
   * @return bool
   *   TRUE if Uri stripping enabled.  FALSE otherwise.
   */
  public function isIsStripUriEnabled() {
    return $this->isStripUriEnabled;
  }

  /**
   * Sets if mobile redirects are enabled.
   *
   * @param bool $mobile_redirect_enabled
   *   TRUE for enabling mobile redirects.  FALSE otherwise.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   Exception thrown if an invalid value is passed into the setter.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Exception thrown if the setting is read-only.
   */
  public function setIsMobileRedirectEnabled($mobile_redirect_enabled) {
    $this->assertEditable();
    $this->_setIsMobileRedirectEnabled($mobile_redirect_enabled);
    $this->markForEdit();
  }

  private function _setIsMobileRedirectEnabled($mobile_redirect_enabled) {
    $this->assertValidMobileRedirectEnabledValue($mobile_redirect_enabled);
    $this->isMobileRedirectEnabled = $mobile_redirect_enabled;
  }

  public function assertValidMobileRedirectEnabledValue($mobile_redirect_enabled){
    $is_status_a_bool = !is_null(filter_var($mobile_redirect_enabled, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));

    if (!$is_status_a_bool) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $mobile_redirect_enabled);
    }
  }


  /**
   * Sets the Subdomain prefix you wish to redirect visitors on mobile devices.
   *
   * @param null|string $mobile_subdomain
   *   The subdomain to set.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   Exception thrown if an invalid value is passed into the setter.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Exception thrown if the setting is read-only.
   */
  public function setMobileSubdomain($mobile_subdomain) {
    $this->assertEditable();
    $this->_setMobileSubdomain($mobile_subdomain);
    $this->markForEdit();
  }

  private function _setMobileSubdomain($mobile_subdomain) {
    $this->assertValidMobileSubdomain($mobile_subdomain);
    $this->mobileSubdomain = $mobile_subdomain;
  }

  public function assertValidMobileSubdomain($mobile_subdomain){
    if(is_null($mobile_subdomain)){
      return TRUE;
    }

    $is_mobile_subdomain_string = gettype($mobile_subdomain) == 'string';

    if (!$is_mobile_subdomain_string) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $mobile_subdomain);
    }
  }


  /**
   * Enables URI stripping.
   *
   * @param bool $is_strip_uri_enabled
   *   TRUE if enabled.  False otherwise.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   Exception thrown if an invalid value is passed into the setter.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Exception thrown if the setting is read-only.
   */
  public function setIsStripUriEnabled($is_strip_uri_enabled) {
    $this->assertEditable();
    $this->_setIsStripUriEnabled($is_strip_uri_enabled);
    $this->markForEdit();
  }

  public function _setIsStripUriEnabled($is_strip_uri_enabled) {
    $this->assertValidStripUriValue($is_strip_uri_enabled);
    $this->isStripUriEnabled = $is_strip_uri_enabled;
  }

  public function assertValidStripUriValue($strip_uri) {
    $is_strip_uri_a_bool = !is_null(filter_var($strip_uri, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));

    if (!$is_strip_uri_a_bool) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $strip_uri);
    }
  }

}