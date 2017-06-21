#!/usr/local/bin/php

<?php

require('../vendor/autoload.php');

const BASE_URL = 'http://d38mthnobw7tda.cloudfront.net/';
const KEYPAIR_ID = 'APKAJCYSIPAFENARGBBQ';
date_default_timezone_set('asia/tokyo');

function make_policy($resource_url, $expire) {
    $time = time() + $expire;
    $policy = <<<POLICY
{
   "Statement": [
      {
         "Resource":"$resource_url",
         "Condition":{
            "DateLessThan":{"AWS:EpochTime":$time}
         }
      }
   ]
}

POLICY;

    return $policy;
}

$cf = new Aws\CloudFront\CloudFrontClient([
    'region'  => 'us-west-2',
    'version' => '2014-11-06'
]);
$signed_url = $cf->getSignedUrl([
    'url' => BASE_URL . $argv[1],
    'policy' => make_policy(BASE_URL . $argv[1], 30),
    'private_key' => '../keypair/private.pem',
    'key_pair_id' => KEYPAIR_ID,
]);

echo "Signed URL: \n" . $signed_url . "\n";
