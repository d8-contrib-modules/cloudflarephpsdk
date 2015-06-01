<?php
/**
 * Created by PhpStorm.
 * User: adam.weingarten
 * Date: 5/28/15
 * Time: 10:58 PM
 */

namespace src\Unit;
use CloudFlarePhpSdk\ApiTypes\CloudFlareApiResponse;
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettings;

class ZoneTest extends \PHPUnit_Framework_TestCase {
  /**
   * @dataProvider zoneProvider
   */
 /* public function testZoneConstructor($testResult){
    $zone = new ZoneSettings('blah', new CloudFlareApiResponse($testResult));
    $zone_settings = $zone->getSettings();
    foreach($zone_settings as $id => $value) {
      if(in_array($id, \CloudFlarePhpSdk\ApiTypes\ZoneSettings::BOOLEAN_SETTINGS)){
        $this->assertEquals('CloudFlarePhpSdk\ApiTypes\ZoneSettingBool', get_class($value));
      }
      else{
        $this->assertEquals('CloudFlarePhpSdk\ApiTypes\ZoneSetting', get_class($value));
      }
    }
  }*/

  /**
   * @dataProvider zoneProvider
   */
  /*public function testGetSettingsById($testResult){
    $zone = new ZoneSettings(new CloudFlareApiResponse($testResult));
    $this->assertFalse($zone->getSettingById('advanced_ddos')->getValue());
    $this->assertTrue($zone->getSettingById('always_online')->getValue());
    $this->assertTrue($zone->getSettingById('browser_check')->getValue());
    $this->assertTrue($zone->getSettingById('email_obfuscation')->getValue());
  }*/

  /**
   * @covers \CloudFlarePhpSdk\ApiType\Zone\getChanges
   */
/*  public function testGetChanges(){

  }*/



}
