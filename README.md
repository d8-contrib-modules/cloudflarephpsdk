## Introduction

Provides a PHP client to interact with Cloudflare API see: https://api.cloudflare.com/
The SDK is designed to allow developers to work with CloudFlare without having 
to know all the low-level details of interacting with the API.

The API has been designed with ease of use in mind.  Highly recommend using 
PHP type hints when working with the API.  


Note: CloudFlare is a trademark of CloudFlare Inc.  This project is maintained independently of CloudFlare inc.

[![Latest Stable Version](https://poser.pugx.org/aweingarten/cloudflarephpsdk/v/stable)](https://packagist.org/packages/aweingarten/cloudflarephpsdk) [![Total Downloads](https://poser.pugx.org/aweingarten/cloudflarephpsdk/downloads)](https://packagist.org/packages/aweingarten/cloudflarephpsdk) [![Latest Unstable Version](https://poser.pugx.org/aweingarten/cloudflarephpsdk/v/unstable)](https://packagist.org/packages/aweingarten/cloudflarephpsdk) [![License](https://poser.pugx.org/aweingarten/cloudflarephpsdk/license)](https://packagist.org/packages/aweingarten/cloudflarephpsdk)


## Usage

The current implementation is a POC.   The initial focus is to solidify the PHP CloudFlare API interface.
```
$api_key = 'your_cloudflare_api_key';
$user_email = 'your_cloudflare_email'
$api = new \Drupal\cloudflare\CloudFlareAPI($api_key, $user_email );
$zoneId = $api->listZones()[0]->getZoneId();
$zone = $api->loadZone($zoneId);
$zone_settings = $zone-> getSettings();

$result = $api->purgeIndividualFiles($zoneId, array('path1'));
$result = $api->setSecurityLevel($zoneId, 'low');
```


## Structure

### ApiTypes
Parses incoming data from the API into types data structures.  Creating typed
classes for the incoming data makes working with the API a lot simpler for Devs.
It takes away the guess work for what's in an array.  That also means that a 
Dev using this will not need to re-read the cloudflare API if they don't want
to.  





### ApiEndPoints
Extend CloudFlareAPIBase for specific endpoints.  Provides all the tools you 
need to interface with those endpoints, getters, setters, and constants. 



### CloudFlareAPIBase.php
Provides facility for making webservice calls to cloudflare.  It provides a
wrapper around guzzle so that people using this module do not need to concern
themselves with the low-level implementation details of guzzle.

### CloudFlareApiException
This exception is thrown by the CloudFlarePhpSdk when something unexpected 
happens. Contains application level responses codes and messages that can be
handled at higher levels of the application stack. 

The CloudFlarePhpSdk integration layer has a primary purpose of interfacing
with CloudFlare. In order to focus on this task it knows nothing about drupal. 
It does no exception handling on it's own. It also does not return booleans for
success.  The assumption is that methods are successful.  If they are not an
CloudFlareApiException will be thrown with application level responses codes
and messages that a dev can use.

## Contribution Guidelines for Developers

### Write objects not procedures
The SDK has been written with OO PHP best practices in mind.

### Coding Standards
We contributed code must pass code sniffer.

### User proper namespacing
All code in this SDK is name-spaced inside CloudFlarePhpSdk using PSR-4 
autoloading. In general, the only constructs in this namespace should be 
classes.  There may be exceptions but they should be few and far between.

### Type Hinting
Typehint all variables and parameters.  It makes life a LOT simpler for 
developers working with IDEs.



date-time parsing of timestamps
invalid input exceptions are mediocre for multi settings
alphabatize methods, private fields Comment the code sections

