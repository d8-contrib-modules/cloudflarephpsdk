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
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingSsl;


class ZoneSettingSslTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider zoneSslValidValuesProvider
   */
  public function testParseValidValues($testResult){
    new ZoneSettingSsl($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneSslInvalidValuesProvider
   */
  public function testParseInvalidValues($testResult){
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    new ZoneSettingSsl($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneSslInvalidValuesProvider
   */
  public function testSetterInvalidValues($testResult) {
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    $zone_setting = new ZoneSettingSsl(ZoneSettingSsl::SSL_FULL, $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setValue($testResult['value']);
  }

  /**
   * @dataProvider zoneSslValidValuesProvider
   */
  public function testSetterValidValues($testResult) {
    $zone_setting = new ZoneSettingSsl(ZoneSettingSsl::SSL_FULL, $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setValue($testResult['value']);
    $this->assertEquals($zone_setting->getValue(), $testResult['value']);
  }

  public function zoneSslInvalidValuesProvider()
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

    $testData = [[$case0], [$case1], [$case2],   [$case6], ];

    return $testData;
  }
  public function zoneSslValidValuesProvider()
  {
    $case0 =[
      'value'=> ZoneSettingSsl::SSL_FLEXIBLE,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case1 =[
      'value'=> ZoneSettingSsl::SSL_FULL,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case2 =[
      'value'=> ZoneSettingSsl::SSL_OFF,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case3 =[
      'value'=> ZoneSettingSsl::SSL_STRICT,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];


    $testData = [[$case0], [$case1], [$case2], [$case3],];

    return $testData;
  }
}
