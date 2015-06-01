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
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingInt;


class ZoneSettingIntTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider zoneIntValidValuesProvider
   */
  public function testParseValidValues($testResult){
    new ZoneSettingInt($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneIntInvalidValuesProvider
   */
  public function testParseInvalidValues($testResult){
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    new ZoneSettingInt($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneIntInvalidValuesProvider
   */
  public function testSetterInvalidValues($testResult) {
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    $zone_setting = new ZoneSettingInt(55, $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setValue($testResult['value']);
  }

  /**
   * @dataProvider zoneIntValidValuesProvider
   */
  public function testSetterValidValues($testResult) {
    $zone_setting = new ZoneSettingInt(55, $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setValue($testResult['value']);
    $this->assertEquals($zone_setting->getValue(), $testResult['value']);
  }


  public function zoneIntInvalidValuesProvider()
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

    $testData = [[$case0], [$case1], [$case2], [$case3], [$case4], [$case5], [$case6], [$case7], [$case8]];

    return $testData;
  }
  public function zoneIntValidValuesProvider()
  {
    $case0 =[
      'value'=> 0,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case1 =[
      'value'=> 1,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case2 =[
      'value'=> '0',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case3 =[
      'value'=> '1',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case4 =[
      'value'=> '55',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $case5 =[
      'value'=> 901,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $testData = [[$case0], [$case1], [$case2], [$case3], [$case4], [$case5]];

    return $testData;
  }



}
