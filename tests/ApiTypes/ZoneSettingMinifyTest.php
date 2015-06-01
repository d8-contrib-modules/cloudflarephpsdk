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
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingMinify;


class ZoneSettingMinifyTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider zoneMinifyValidValuesProvider
   */
  public function testParseValidValues($testResult){
    new ZoneSettingMinify($testResult['css'],$testResult['html'],$testResult['js'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneMinifyInvalidValuesProvider
   */
  public function testParseInvalidValues($testResult){
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    new ZoneSettingMinify($testResult['css'],$testResult['html'],$testResult['js'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
  }

  /**
   * @dataProvider zoneMinifyInvalidValuesProvider
   */
  public function testSetterInvalidValues($testResult) {
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareInvalidSettingValueException');
    $zone_setting = new ZoneSettingMinify('on','off','on', $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setValue($testResult['css'],$testResult['html'],$testResult['js']);
  }

  /**
   * @dataProvider zoneMinifyValidValuesProvider
   */
  public function testSetterValidValues($testResult) {
    $zone_setting = new ZoneSettingMinify($testResult['css'],$testResult['html'],$testResult['js'], $testResult['id'],$testResult['editable'], $testResult['modified_on']);
    $zone_setting->setValue($testResult['css'],$testResult['html'],$testResult['js']);
    $this->assertEquals($zone_setting->isCssMinifyEnabled(), $testResult['css']);
    $this->assertEquals($zone_setting->isJsMinifyEnabled(), $testResult['js']);
    $this->assertEquals($zone_setting->isHtmlMinifyEnabled(), $testResult['html']);
  }


  public function zoneMinifyInvalidValuesProvider()
  {
    $case0 = [
      'css' =>'blah',
      'js' =>'blah',
      'html' =>'blah',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case1 = [
      'css' =>'on',
      'js' =>'on',
      'html' =>'blah',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case2 = [
      'css' =>'on',
      'js' =>'on',
      'html' =>'blah',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case3 = [
      'css' =>'on',
      'js' =>'blah',
      'html' =>'off',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case4 = [
      'css' =>'blah',
      'js' =>'off',
      'html' =>'off',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];


    $testData = [[$case0], [$case1], [$case2], [$case3], [$case4]];

    return $testData;
  }


  public function zoneMinifyValidValuesProvider()
  {
    $case0 = [
      'css' =>'on',
      'js' =>'on',
      'html' =>'on',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case1 = [
      'css' =>'off',
      'js' =>'on',
      'html' =>'on',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case2 = [
      'css' =>'on',
      'js' =>'off',
      'html' =>'on',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case3 = [
      'css' =>'on',
      'js' =>'on',
      'html' =>'off',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case4 = [
      'css' =>'on',
      'js' =>'off',
      'html' =>'off',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case5 = [
      'css' =>'off',
      'js' =>'on',
      'html' =>'off',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $case6 = [
      'css' =>'off',
      'js' =>'off',
      'html' =>'on',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];


    $case7 = [
      'css' =>'off',
      'js' =>'off',
      'html' =>'off',
      'id' => 'minify',
      'editable' => TRUE,
      'modified_on'=> '2014-05-28T18:46:18.764425Z'
    ];

    $testData = [[$case0], [$case1], [$case2], [$case3], [$case4], [$case5], [$case6], [$case7]];

    return $testData;
  }



}
