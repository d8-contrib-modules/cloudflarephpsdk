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
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingBool;


class ZoneSettingBoolTest extends \PHPUnit_Framework_TestCase  {

  /**
   * @dataProvider zoneBoolSettingProvider
   */
  public function testZoneSettingBoolConstructor($testResult) {
    $zone_setting_bool = new ZoneSettingBool($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $has_expected_base_class = is_a($zone_setting_bool, 'CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingBase');
    $this->assertFalse($zone_setting_bool->isModifiedLocally());

    $this->assertTrue($has_expected_base_class);
    $this->assertEquals($testResult['value'], $zone_setting_bool->getValue());
    $this->assertEquals($testResult['editable'], $zone_setting_bool->isEditable());
    $this->assertEquals($zone_setting_bool->getZoneSettingName(), $testResult['id']);

  //  $timestamp = strtotime("U",$zone_setting_bool->getTimeModifiedOnServer());
//    $formatted_date =  date('U',$timestamp);
//    $formatted_date = strpos($testResult['result']['modified_on'], $formatted_date) >=0;
//    $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s+', '2013-02-13T08:35:34.195Z');
  //  $this->assertEquals($timestamp,$testResult['result']['modified_on'] );
  }

  /**
   * @dataProvider zoneBoolSettingProvider
   */
  public function testZoneSettingGetterSetter($testResult) {
    $zone_setting_bool = new ZoneSettingBool($testResult['value'], $testResult['id'], $testResult['editable'], $testResult['modified_on']);
    $this->assertFalse($zone_setting_bool->isModifiedLocally());
    $new_value = !($zone_setting_bool->getValue());

    if ($zone_setting_bool->isEditable()) {
      $zone_setting_bool->setValue($new_value);
      $this->assertEquals($new_value, $zone_setting_bool->getValue());
      $this->assertTrue($zone_setting_bool->isModifiedLocally());
    }

    if (!$zone_setting_bool->isEditable()) {
      $threw_exception = FALSE;
      try{
        $zone_setting_bool->setValue($new_value);
      }

      catch(CloudFlareNotModifiableException $e){
        $threw_exception = TRUE;
      }

      $this->assertTrue($threw_exception);
    }
  }

  /**
   * @dataProvider zoneBoolInvalidValuesProvider
   */
  public function testParseInvalidValues($testResult){
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    $zone_setting_bool = new ZoneSettingBool($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $this->assertFalse($zone_setting_bool->isModifiedLocally());
  }



  /**
   * @dataProvider zoneBoolValidValuesProvider
   */
  public function testParseValidValues($testResult){
    $zone_setting_bool = new ZoneSettingBool($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $this->assertFalse($zone_setting_bool->isModifiedLocally());
  }

  /**
   * @dataProvider zoneBoolReadOnlyProvider
   */
  public function testParseReadOnlyValues($testResult){
    $zone_setting_bool = new ZoneSettingBool($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $this->assertFalse($zone_setting_bool->isEditable());
    $this->assertFalse($zone_setting_bool->isModifiedLocally());

  }

  /**
   * @dataProvider zoneBoolReadOnlyProvider
   */
  public function testParseWriteToReadOnlyValues($testResult){
    $zone_setting_bool = new ZoneSettingBool($testResult['value'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareNotModifiableException');
    $zone_setting_bool->setValue(TRUE);
  }

  public function zoneBoolInvalidValuesProvider()
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
      'value' =>55,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $testData = [[$case0], [$case1], [$case2], [$case3], [$case4], [$case5]];

    return $testData;
  }

  public function zoneBoolValidValuesProvider()
  {
    $test1 =[
      'value'=> 1,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test2 =[
      'value'=> 0,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test3 =[
      'value'=> '0',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test4 =[
      'value'=> '1',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test5 =[
      'value'=> 'TRUE',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test6 =[
      'value'=> 'FALSE',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test6 =[
      'value'=> TRUE,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test7 =[
      'value'=> FALSE,
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test8 =[
      'value'=> 'off',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test9 =[
      'value'=> 'on',
      'id' => 'advanced_ddos',
      'editable' => TRUE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $testData = [[$test1], [$test2], [$test3], [$test4], [$test5], [$test6], [$test7], [$test8], [$test9]];

    return $testData;
  }

  public function zoneBoolReadOnlyProvider()
  {
    $test1 =[
      'value'=> TRUE,
      'id' => 'advanced_ddos',
      'editable' => FALSE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $test2 =[
      'value'=> FALSE,
      'id' => 'advanced_ddos',
      'editable' => FALSE,
      'modified_on' => '2014-05-28T18:46:18.764425Z',
    ];

    $testData = [[$test1], [$test2]];

    return $testData;
  }



  public function zoneBoolSettingProvider()
  {
    $testResponse1['value'] = FALSE;
    $testResponse1['id'] = 'advanced_ddos';
    $testResponse1['editable'] = TRUE;
    $testResponse1['modified_on'] = '2014-05-28T18:46:18.764425Z';

    $testResponse2['value'] = TRUE;
    $testResponse2['id'] = 'minify';
    $testResponse2['editable'] = TRUE;
    $testResponse2['modified_on'] = '2014-05-28T18:46:18.764425Z';

    $testResponse3['value'] = TRUE;
    $testResponse3['id'] = 'mock_3';
    $testResponse3['editable'] = FALSE;
    $testResponse3['modified_on'] = '2014-05-28T18:46:18.764425Z';

    $testData = [[$testResponse1], [$testResponse2], [$testResponse3]];

    return $testData;
  }
}
