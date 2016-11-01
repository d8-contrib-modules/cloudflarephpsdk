<?php

namespace src\Unit\ApiEndpoints;

use CloudFlarePhpSdk\ApiEndpoints\ZoneApi;
use CloudFlarePhpSdk\ApiTypes\Zone\Zone;
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettings;
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingSecurityLevel;
use CloudFlarePhpSdk\ApiTypes\Zone\ZoneSettingSsl;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Response;

class ZoneSettingsApiTest extends \PHPUnit_Framework_TestCase {
   /* function testZoneListing(){
      $api_key = 'c6c5a7008d5d84d51325a27bca9e261378b60';
      $email = 'adam@weingarten.me';

      $api = new ZoneApi($api_key, $email);
      $api->listZones();
    }*/

  /*function testZoneSettings(){
    $api_key = 'c6c5a7008d5d84d51325a27bca9e261378b60';
    $email = 'adam@weingarten.me';

    $api = new ZoneApi($api_key, $email);
    $zone = $api->listZones()[0];
   // $zoneSettings = $api->getZoneSettings($zone->getId());
   // $api = new ZoneApi($api_key, $email);
  }*/

 /* function testUpdateZone(){
    $api_key = '';
    $email = '';

    // Create a mock and queue two responses.
    $mock = new MockHandler([
      new Response(404, ['X-Foo' => 'Bar']),
      new Response(202, ['Content-Length' => 0])
    ]);

    $api = new ZoneApi($api_key, $email);
    $api -> setMockApi();
    $api->updateZone()
  }*/

  /**
   * @dataProvider zoneSettingsResponse
   */
  function testGetZoneSettings($zoneSettingsInfoResponse) {
    $arr_json = json_decode($zoneSettingsInfoResponse, TRUE);

    $mock = new MockHandler([
      new Response(200, [], $zoneSettingsInfoResponse),
    ]);

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $zone_settings = $api->getZoneSettings("test");


    $parsed_value = $zone_settings->getSettingById(ZoneSettings::SETTING_ADVANCED_DDOS)->getValue();
    $raw_value = $arr_json['result'][0]['value'];

    if ($parsed_value) {
      $this->assertEquals($raw_value, 'on');
    }

    else {
      $this->assertEquals($raw_value, 'off');
    }


//    //getSettingById
    //getSettings
    /*updateZone
    purgeAllFiles
    purgeIndividualFiles*/
  }

  /**
 * @dataProvider getPurgeIndividualFilesResponse
 */
  function testPurgeIndividualFiles($response){
    $mock = new MockHandler([
      new Response(200, [], $response),
    ]);

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $api->purgeIndividualFiles("blah",['/alpha', 'beta']);
  }

  /**
   * @dataProvider getPurgeIndividualFilesResponse
   */
  function testPurgeCacheTags($response){
    $mock = new MockHandler([
      new Response(200, [], $response),
    ]);

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $api->purgeTags("blah",['/alpha', 'beta']);
  }

  /**
   * @dataProvider getPurgeIndividualFilesResponse
   */
  function testPurgeFiles($response){
    $mock = new MockHandler([
      new Response(200, [], $response),
    ]);

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $api->purgeAllFiles("zone_id");
  }

  /**
   * @dataProvider editZoneResponse
   */
  function testUpdateZone($editResponse, $zoneSettingResponse){
    $mock = new MockHandler([
      new Response(200, [], $zoneSettingResponse),
      new Response(200, [], $editResponse),
    ]);

   // $zone_settings = new ZoneSettings('myid',)

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $zone_settings = $api->getZoneSettings("test");
    $ddos_protection = $zone_settings->getSettingById(ZoneSettings::SETTING_ADVANCED_DDOS);
    $ddos_protection->setValue(FALSE);
    $minify =  $zone_settings->getSettingById(ZoneSettings::SETTING_MINIFY);
    $minify->setValue(TRUE, TRUE, TRUE);
    $mobile_redirect =  $zone_settings->getSettingById(ZoneSettings::SETTING_MOBILE_REDIRECT);
    $mobile_redirect->setMobileSubdomain("mobile");
    $security_level =  $zone_settings->getSettingById(ZoneSettings::SETTING_SECURITY_LEVEL);
    $security_level->setValue(ZoneSettingSecurityLevel::SECURITY_HIGH);
    $ssl = $zone_settings->getSettingById(ZoneSettings::SETTING_SSL);
    $ssl->setValue(ZoneSettingSsl::SSL_STRICT);



    $api->updateZone($zone_settings);

  }



  /**
   * @dataProvider httpMockErrorResponses
   */
  function testInvalidHttpResponses($code, $response_header){
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareHttpException');

    $mock = new MockHandler([
      new Response($code, $response_header),
    ]);

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $api->listZones();
  }

  function httpMockErrorResponses() {
    $case0 =[
      'code' => 500,
      'response' => ['X-Foo' => 'Bar'],
    ];
    $case1 =[
      'code' => 400,
      'response' => ['X-Foo' => 'Bar'],
    ];
    $case2 =[
      'code' => 401,
      'response' => ['X-Foo' => 'Bar'],
    ];
    $case3 =[
      'code' => 403,
      'response' => ['X-Foo' => 'Bar'],
    ];
    $case4 =[
      'code' => 404,
      'response' => ['X-Foo' => 'Bar'],
    ];
    $case5 =[
      'code' => 405,
      'response' => ['X-Foo' => 'Bar'],
    ];
    $case6 =[
      'code' => 415,
      'response' => ['X-Foo' => 'Bar'],
    ];
    $case7 =[
      'code' => 429,
      'response' => ['X-Foo' => 'Bar'],
    ];

    return [$case0, $case1, $case2, $case3, $case4, $case5, $case6, $case7];
  }



  public function zoneSettingsInfoResponse($success, $errors, $messages){
    $template = '{
              "success": :success,
              "errors": :errors,
              "messages": :messages,
              "result": [
              {
              "id": "always_online",
              "value": "on",
              "editable": true,
              "modified_on": "2014-01-01T05:20:00.12345Z"
              }
              ],
              "result_info": {
              "page": 1,
              "per_page": 20,
              "count": 1,
              "total_count": 2000,
              "total_pages": 100
              }
              }';

    $response = strtr($template,[':success'=>$success, ':errors' => $errors ,':messages' => $messages ] );
    return $response;
  }
  public function zoneSettingsResponse(){

    $json = '{
            "success": true,
            "errors": [],
            "messages": [],
            "result": [
          {"id":"advanced_ddos","value":"off","modified_on":null,"editable":true},
          {"id":"always_online","value":"on","modified_on":null,"editable":true},
          {"id":"browser_cache_ttl","value":14400,"modified_on":null,"editable":true},
          {"id":"browser_check","value":"on","modified_on":null,"editable":true},
          {"id":"cache_level","value":"simplified","modified_on":"2015-05-11T21:32:41.262960Z","editable":true},
          {"id":"challenge_ttl","value":1800,"modified_on":null,"editable":true},
          {"id":"development_mode","value":"off","modified_on":null,"editable":true},
          {"id":"edge_cache_ttl","value":7200,"modified_on":null,"editable":true},
          {"id":"email_obfuscation","value":"on","modified_on":null,"editable":true},
          {"id":"hotlink_protection","modified_on":null,"value":"off","editable":true},
          {"id":"ip_geolocation","value":"on","modified_on":null,"editable":true},
          {"id":"ipv6","value":"off","modified_on":null,"editable":true},
          {"id":"max_upload","value":100,"modified_on":null,"editable":true},
          {"id":"minify","value":{"css":"off","html":"off","js":"off"},"modified_on":null,"editable":true},
          {"id":"mirage","value":"off","modified_on":null,"editable":false},
          {"id":"mobile_redirect","value":{"enabled":"off","mobile_subdomain":null,"strip_uri":false},"modified_on":null,"editable":true},
          {"id":"polish","value":"off","modified_on":null,"editable":false},
          {"id":"pseudo_ipv4","value":"off","modified_on":null,"editable":true},
          {"id":"rocket_loader","value":"off","modified_on":null,"editable":true},
          {"id":"security_header","modified_on":"2015-05-11T03:04:07.196959Z","value":{"strict_transport_security":{"enabled":true,"max_age":15552000,"include_subdomains":false,"preload":true}},"editable":true},
          {"id":"security_level","value":"low","modified_on":"2015-05-25T17:10:54.994666Z","editable":true},
          {"id":"server_side_exclude","value":"on","modified_on":null,"editable":true},
          {"id":"ssl","value":"strict","modified_on":"2015-05-11T10:41:58.199208Z","certificate_status":"active","editable":true},
          {"id":"tls_client_auth","value":"off","modified_on":"2015-05-11T03:06:32.768900Z","editable":true},
          {"id":"waf","value":"off","modified_on":null,"editable":false}
          ],
            "result_info": {
            "page": 1,
            "per_page": 20,
            "count": 1,
            "total_count": 2000,
            "total_pages": 100
            }
          }';
    return [[$json]];
  }

  public function getPurgeIndividualFilesResponse(){
    $json = '{
      "success": true,
      "errors": [],
      "messages": [],
      "result": {
      "id": "9a7806061c88ada191ed06f989cc3dac"
      }
      }';

    return [[$json]];
  }

  public function editZoneResponse(){
    $json = '{
          "success": true,
          "errors": [],
          "messages": [],
          "result": [
          {
          "id": "always_online",
          "value": "on",
          "editable": true,
          "modified_on": "2014-01-01T05:20:00.12345Z"
          }
          ],
          "result_info": {
          "page": 1,
          "per_page": 20,
          "count": 1,
          "total_count": 2000,
          "total_pages": 100
          }
          }';

    return [[$json, $this->zoneSettingsResponse()[0][0]]];
  }

}


