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
class ZoneSettingMinify extends ZoneSettingBase {
  private $css;
  private $html;
  private $js;

  /**
   * Default constructor for ZoneSettingsMinify.
   *
   * @param bool $css_minify
   *   TRUE if minification enabled on css files.  FALSE otherwise.
   * @param bool $html_minify
   *   TRUE if minification enabled on html files.  FALSE otherwise.
   * @param bool $js_minify
   *   TRUE if minification enabled on js files.  FALSE otherwise.
   * @param bool $setting_id
   *   The name of the setting.
   * @param bool $editable
   *   TRUE if editable.  FALSE if setting is read-only.
   * @param int $modified_on
   *   The last time that the setting was modified on the server.
   */
  public function __construct($css_minify, $html_minify, $js_minify, $setting_id, $editable, $modified_on) {
    parent::__construct($setting_id, $editable, $modified_on);
    $this->_setValue($css_minify, $html_minify, $js_minify);
  }

  /**
   * Sets minification settings for the zone.
   *
   * @param bool $css_minify
   *   TRUE if minification enabled on css files.  FALSE otherwise.
   * @param bool $html_minify
   *   TRUE if minification enabled on html files.  FALSE otherwise.
   * @param bool $js_minify
   *   TRUE if minification enabled on js files.  FALSE otherwise.
   *
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException
   *   Throws exception when the user tries to change a value that is not
   *   modifiable for them.
   * @throws \CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException
   *   Exception is thrown if one of the values does match the method's
   *   signature.
   */
  public function setValue($css_minify, $html_minify, $js_minify) {
    $this->assertEditable();
    $this->_setValue($css_minify, $html_minify, $js_minify);
    $this->markForEdit();
  }

  private function _setValue($css_minify, $html_minify, $js_minify) {
    $this->assertValidValue($css_minify, $html_minify, $js_minify);
    $this->css = $css_minify;
    $this->html = $html_minify;
    $this->js = $js_minify;
  }

  public function assertValidValue($css_minify, $html_minify, $js_minify) {
    $valid_css = is_bool(filter_var($css_minify, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    $valid_html = is_bool(filter_var($html_minify, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    $valid_js = is_bool(filter_var($js_minify, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));

    if (!$valid_css) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $css_minify);
    }

    if (!$valid_html) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $html_minify);
    }

    if (!$valid_js) {
      throw new CloudFlareInvalidSettingValueException($this->getZoneSettingName(), $js_minify);
    }
  }


  /**
   * Checks if Css minification is enabled.
   *
   * @return bool
   *   TRUE if minification is enabled for CSS.  FALSE otherwise.
   */
  public function isCssMinifyEnabled() {
    return $this->css;
  }

  /**
   * Checks if Html minification is enabled.
   *
   * @return bool
   *   TRUE if minification is enabled for Html.  FALSE otherwise.
   */
  public function isHtmlMinifyEnabled() {
    return $this->html;
  }

  /**
   * Checks if Js minification is enabled.
   *
   * @return bool
   *   TRUE if minification is enabled for Js.  FALSE otherwise.
   */
  public function isJsMinifyEnabled() {
    return $this->js;
  }

}
