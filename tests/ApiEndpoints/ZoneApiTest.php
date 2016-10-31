<?php

namespace src\Unit\ApiEndpoints;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use CloudFlarePhpSdk\ApiTypes;
use CloudFlarePhpSdk\ApiEndpoints\ZoneApi;
use CloudFlarePhpSdk\Exceptions\CloudFlareApiException;

class ZoneApiTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider getZoneDataListing
   */
  public function testZoneSettingBoolConstructor($zoneDataResponse){
    $arr_json = json_decode($zoneDataResponse,true);

    $mock = new MockHandler([
      new Response(200, [], $zoneDataResponse),
    ]);

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $zones = $api->listZones();

    for($i = 0;  $i<count($zones); $i++) {
      /** @var \CloudFlarePhpSdk\ApiTypes\Zone\Zone $zone */
      $zone = $zones[$i];
      $zone_json = $arr_json['result'][$i];
      $this->checkZoneEquality($zone, $zone_json);

    }
  }


  /**
   * @dataProvider getFailedRequestCodes
   */
  public function testFailedRequestCodes($code){
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareHttpException');
    $mock = new MockHandler([
      new Response($code,[], "This could be a problem."),
    ]);

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $zones = $api->listZones();
  }

  /**
   * @dataProvider getFailedApiResponses
   */
  public function testFailedApiResponses($code) {
    $this->setExpectedException('CloudFlarePhpSdk\Exceptions\CloudFlareApiException');
    $mock = new MockHandler([
      new Response(200,[], $code),
    ]);

    $api = new ZoneApi("68ow48650j63zfzx1w9jd29cr367u0ezb6a4g", "email", $mock);
    $zones = $api->listZones();
  }

  /**
   * Checks that the Parsed zone matches raw zone data.
   *
   * @param \CloudFlarePhpSdk\ApiTypes\Zone\Zone $zone
   *   Parsed zone object.
   * @param array $zone_json
   *   Unparsed zone data.
   */
  public function checkZoneEquality($zone, $zone_json) {
    $this->assertEquals($zone->getZoneId(),$zone_json['id']);
    $this->assertEquals($zone->getName(),$zone_json['name']);
    $this->assertEquals($zone->getStatus(),$zone_json['status']);
    $this->assertEquals($zone->isZonePaused(),$zone_json['paused']);
    $this->assertEquals($zone->getType(),$zone_json['type']);
    $this->assertEquals($zone->isDevelopmentModeEnabled(),$zone_json['development_mode']);
    $this->assertEquals($zone->getNameServers(),$zone_json['name_servers']);
    $this->assertEquals($zone->getOriginalNameServers(),$zone_json['original_name_servers']);
    $this->assertEquals($zone->getOriginalDnshost(),$zone_json['original_dnshost']);
    $this->assertEquals($zone->getOriginalRegistrar(), $zone_json['original_registrar']);
    $this->assertEquals($zone->getModifiedOn(),$zone_json['modified_on']);
    $this->assertEquals($zone->getCreatedOn(),$zone_json['created_on']);
    // $this->assertEquals($zone->getMeta(),$zone_json['meta']);
    $this->assertEquals($zone->getOwner(), $zone_json['owner']);
    $this->assertEquals($zone->getPermissions(), $zone_json['permissions']);
    $this->assertEquals($zone->getPlan(), $zone_json['plan']);
  }

  public function getFailedRequestCodes(){
    return [
      ['304'],
      ['400'],
      ['401'],
      ['403'],
      ['405'],
      ['415'],
      ['429'],
    ];
  }

  public function getZoneDataListing(){
     $json ='{
        "success": true,
        "errors": [],
        "messages": [],
        "result": [
        {
        "id": "9a7806061c88ada191ed06f989cc3dac",
        "name": "example.com",
        "development_mode": 7200,
        "original_name_servers": [
        "ns1.originaldnshost.com",
        "ns2.originaldnshost.com"
        ],
        "original_registrar": "GoDaddy",
        "original_dnshost": "NameCheap",
        "created_on": "2014-01-01T05:20:00.12345Z",
        "modified_on": "2014-01-01T05:20:00.12345Z",
        "name_servers": [
        "tony.ns.cloudflare.com",
        "woz.ns.cloudflare.com"
        ],
        "owner": {
        "id": "9a7806061c88ada191ed06f989cc3dac",
        "email": "user@example.com",
        "owner_type": "user"
        },
        "permissions": [
        "#zone:read",
        "#zone:edit"
        ],
        "plan": {
        "id": "9a7806061c88ada191ed06f989cc3dac",
        "name": "Pro Plan",
        "price": 20,
        "currency": "USD",
        "frequency": "monthly",
        "is_subscribed": true,
        "can_subscribe": true
        },
        "status": "active",
        "paused": false,
        "type": "full"
        }
        ],
        "result_info": {
        "page": 1,
        "per_page": 20,
        "count": 1,
        "total_count": 5,
        "total_pages": 1
        }
        }';
     return [[$json]];
 }

  public function getZoneDetails(){
    $json = '{
"success": true,
"errors": [],
"messages": [],
"result": {
"id": "9a7806061c88ada191ed06f989cc3dac",
"name": "example.com",
"development_mode": 7200,
"original_name_servers": [
"ns1.originaldnshost.com",
"ns2.originaldnshost.com"
],
"original_registrar": "GoDaddy",
"original_dnshost": "NameCheap",
"created_on": "2014-01-01T05:20:00.12345Z",
"modified_on": "2014-01-01T05:20:00.12345Z",
"name_servers": [
"tony.ns.cloudflare.com",
"woz.ns.cloudflare.com"
],
"owner": {
"id": "9a7806061c88ada191ed06f989cc3dac",
"email": "user@example.com",
"owner_type": "user"
},
"permissions": [
"#zone:read",
"#zone:edit"
],
"plan": {
"id": "9a7806061c88ada191ed06f989cc3dac",
"name": "Pro Plan",
"price": 20,
"currency": "USD",
"frequency": "monthly",
"is_subscribed": true,
"can_subscribe": true
},
"status": "active",
"paused": false,
"type": "full"
}
}';
    return [[$json]];
  }


  public function getFailedApiResponses() {
    $case1 = '{
  "success": false,
  "errors": [],
  "messages": [],
  }';

    $case2 = '{
  "success": false,
  "errors": ["blah"],
  "messages": [],
  }';

    $case3 = '{
  ';


    return [[$case1], [$case2], [$case3]];
  }

}


