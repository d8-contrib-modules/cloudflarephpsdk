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
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingSecurityLevel;


class ZoneSettingSecurityLevelTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider zoneSettingSecurityLevelValidValuesProvider
   */
  public function testParseValidValues($testResult){
    new ZoneSettingSecurityLevel($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneSettingSecurityLevelInvalidValuesProvider
   */
  public function testParseInvalidValues($testResult){
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    new ZoneSettingSecurityLevel($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneSettingSecurityLevelInvalidValuesProvider
   */
  public function testSetterInvalidValues($testResult) {
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    $zone_setting = new ZoneSettingSecurityLevel(ZoneSettingSecurityLevel::SECURITY_UNDERATTACK, $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setValue($testResult['value']);
  }

  /**
   * @dataProvider zoneSettingSecurityLevelValidValuesProvider
   */
  public function testSetterValidValues($testResult) {
    $zone_setting = new ZoneSettingSecurityLevel(ZoneSettingSecurityLevel::SECURITY_UNDERATTACK, $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setValue($testResult['value']);
    $this->assertEquals($zone_setting->getValue(), $testResult['value']);
  }

  public function zoneSettingSecurityLevelInvalidValuesProvider()
  {
    $case0 = [
      'value' =>'Blah',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case1 = [
      'value' => ['blah' => 'blah'],
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case2 = [
      'value' =>[],
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case3 = [
      'value' =>[0],
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case4 = [
      'value' =>[0,1],
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case5 = [
      'value' =>TRUE,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case6 = [
      'value' =>FALSE,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case7 = [
      'value' =>'TRUE',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case8 = [
      'value' =>'FALSE',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $testData = [[$case0], [$case1], [$case2], [$case3], [$case4],  [$case6], [$case7], [$case8]];

    return $testData;
  }
  public function zoneSettingSecurityLevelValidValuesProvider()
  {
    $case0 =[
      'value'=> ZoneSettingSecurityLevel::SECURITY_HIGH,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case1 =[
      'value'=> ZoneSettingSecurityLevel::SECURITY_LOW,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case2 =[
      'value'=> ZoneSettingSecurityLevel::SECURITY_MEDIUM,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case3 =[
      'value'=> ZoneSettingSecurityLevel::SECURITY_OFF,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case4 =[
      'value'=> ZoneSettingSecurityLevel::SECURITY_UNDERATTACK,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];


    $testData = [[$case0], [$case1], [$case2], [$case3],[$case4],];

    return $testData;
  }
}
