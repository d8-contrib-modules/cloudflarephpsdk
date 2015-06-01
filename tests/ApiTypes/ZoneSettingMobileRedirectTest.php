<?php
/**
 * Created by PhpStorm.
 * User: adam.weingarten
 * Date: 5/28/15
 * Time: 10:59 PM
 */

namespace src\Unit\ApiTypes;

use CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException;
use CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException;
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingMobileRedirect;


class ZoneSettingMobileRedirectTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider zoneMobileRedirectValidValuesProvider
   */
  public function testParseValidValues($testResult){
    new ZoneSettingMobileRedirect($testResult['status'],$testResult['strip_uri'],$testResult['mobile_subdomain'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneMobileRedirectInvalidValuesProvider
   */
  public function testParseInvalidValues($testResult){
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    new ZoneSettingMobileRedirect($testResult['status'],$testResult['strip_uri'],$testResult['mobile_subdomain'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneMobileRedirectInvalidValuesProvider
   */
  public function testSetterInvalidValues($testResult) {
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    $zone_setting = new ZoneSettingMobileRedirect('on','off','on', $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setIsStripUriEnabled($testResult['strip_uri']);
    $zone_setting->setMobileSubdomain($testResult['mobile_subdomain']);
    $zone_setting->setIsMobileRedirectEnabled($testResult['status']);
  }

  /**
   * @dataProvider zoneMobileRedirectValidValuesProvider
   */
  public function testSetterValidValues($testResult) {
    $zone_setting = new ZoneSettingMobileRedirect($testResult['status'],$testResult['strip_uri'],$testResult['mobile_subdomain'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);

    $zone_setting->setIsStripUriEnabled($testResult['strip_uri']);
    $zone_setting->setMobileSubdomain($testResult['mobile_subdomain']);
    $zone_setting->setIsMobileRedirectEnabled($testResult['status']);

    $this->assertEquals($zone_setting->isIsMobileRedirectEnabled(), $testResult['status']);
    $this->assertEquals($zone_setting->getMobileSubdomain(), $testResult['mobile_subdomain']);
    $this->assertEquals($zone_setting->isIsStripUriEnabled(), $testResult['strip_uri']);
  }


  public function zoneMobileRedirectInvalidValuesProvider()
  {
    $case0 = [
      'status' =>'blah',
      'mobile_subdomain' =>'blah',
      'strip_uri' =>'blah',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case1 = [
      'status' =>'off',
      'mobile_subdomain' =>'blah',
      'strip_uri' =>'blah',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case2 = [
      'status' =>'blah',
      'mobile_subdomain' =>'off',
      'strip_uri' =>'off',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case3 = [
      'status' =>'blah',
      'mobile_subdomain' => FALSE,
      'strip_uri' =>'off',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case4 = [
      'status' =>'blah',
      'mobile_subdomain' => [1,2],
      'strip_uri' =>'off',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];


    $testData = [[$case0], [$case1], [$case2], [$case3], [$case4]];

    return $testData;
  }


  public function zoneMobileRedirectValidValuesProvider()
  {
    $case0 = [
      'status' =>'on',
      'mobile_subdomain' =>'on',
      'strip_uri' =>'on',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case1 = [
      'status' =>'off',
      'mobile_subdomain' =>'on',
      'strip_uri' =>'on',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case2 = [
      'status' =>'on',
      'mobile_subdomain' =>'off',
      'strip_uri' =>'on',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case3 = [
      'status' =>'on',
      'mobile_subdomain' =>'on',
      'strip_uri' =>'off',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case4 = [
      'status' =>'on',
      'mobile_subdomain' =>'off',
      'strip_uri' =>'off',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case5 = [
      'status' =>'off',
      'mobile_subdomain' =>'on',
      'strip_uri' =>'off',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case6 = [
      'status' =>'off',
      'mobile_subdomain' =>'off',
      'strip_uri' =>'on',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];


    $case7 = [
      'status' =>'off',
      'mobile_subdomain' =>'off',
      'strip_uri' =>'off',
      'id' => 'mobile_redirect',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $testData = [[$case0], [$case1], [$case2], [$case3], [$case4], [$case5], [$case6], [$case7]];

    return $testData;
  }
  
}
