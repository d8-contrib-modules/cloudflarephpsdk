## Introduction
Provides a PHP client to interact with Cloudflare API see: https://api.cloudflare.com/
The SDK is designed to allow developers to work with CloudFlare without having 
to know all the low-level details of interacting with the API.

The API has been designed with ease of use in mind. 


Note: CloudFlare is a trademark of CloudFlare Inc.  This project is maintained independently of CloudFlare inc.


[![Automated Build](https://travis-ci.org/d8-contrib-modules/cloudflarephpsdk.svg?branch=master)](https://travis-ci.org/d8-contrib-modules/cloudflarephpsdk) [![Latest Stable Version](https://poser.pugx.org/d8-contrib-modules/cloudflarephpsdk/v/stable)](https://packagist.org/packages/d8-contrib-modules/cloudflarephpsdk) [![Total Downloads](https://poser.pugx.org/d8-contrib-modules/cloudflarephpsdk/downloads)](https://packagist.org/packages/d8-contrib-modules/cloudflarephpsdk) [![Latest Unstable Version](https://poser.pugx.org/d8-contrib-modules/cloudflarephpsdk/v/unstable)](https://packagist.org/packages/d8-contrib-modules/cloudflarephpsdk) [![License](https://poser.pugx.org/d8-contrib-modules/cloudflarephpsdk/license)](https://packagist.org/packages/d8-contrib-modules/cloudflarephpsdk)
 

## Usage

See below for some common uses of the API:
```
$api_key = 'your_cloudflare_api_key';
$user_email = 'your_cloudflare_email'
$api = new \Drupal\cloudflare\ZoneApi($api_key, $user_email );
$zoneId = $api->listZones()[0]->getZoneId();
$zone = $api->loadZone($zoneId);
$zone_settings = $zone-> getSettings();

$result = $api->purgeIndividualFiles($zoneId, array('path1'));
$result = $api->setSecurityLevel($zoneId, 'low');
```


## Structure

### ApiTypes
Parses incoming data from the API into typed data structures.  Creating typed
classes for the incoming data makes working with the API a lot simpler for Devs.
It takes away the guess work for what's in an array.

### ApiEndPoints
Provides facilities to interact with the remote api. Each API endpoint extends 
CloudFlareAPI. A new endpoint based off CloudFlareAPI gets a lot of the 
structural work necessary to make requests.

### Exceptions
The SDK relies on an exception model for error handling.  When an unexpected 
result occurs an exception is thrown.  When developing with the SDK you will
need to provide try-catch blocks to handle at the applicaiton level. Different 
exceptions are thrown based on the area of the SDK where the exception occurs.

### CloudFlareAPI.php
Provides facility for making webservice calls to cloudflare.  It provides a
wrapper around guzzle so that people using this module do not need to concern
themselves with the low-level implementation details of guzzle.


## Contribution Guidelines for Developers

### User proper namespacing
All code in this SDK is name-spaced inside CloudFlarePhpSdk using PSR-4 
autoloading. 

### Coding Standards
Contributed code must pass code sniffer.

### Type Hinting
Typehint all variables and parameters.  It makes life a LOT simpler for 
developers working with IDEs.

### Unit Testing
The SDK has a goal of 100% PHPUnit test coverage.  It normally hovers around 80%
coverage. When submitting code please ensure that the change is either covered 
by existing tests OR provide new test!  

### Travis CI
The repo is configured to work with Travis CI.  All pull requests are 
automatically enqueued for automated testing.  PRs must pass automated testing
before being considered for integration.  

