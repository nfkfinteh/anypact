<?php

$APIHost = 'https://nfks.p.rnds.pro/smev3_requests.json';
$APIUser = 'demo';        
$APIPass = 'OMHNLv4!52';
$APIKey = 'a9316be2819731ef7f3a4831b361d1a54177dfef293b4c09ca23368077b1d0eb00e06df7f60c0f53c0f47cb23d4b1a127db163f306fd212aa8afa571e0e41a';

$firstRequestParams = array(
    'passportSeries'    => '9704' ,
    'passportNumber'    => '058308' ,
    'firstname'         => 'Игорь' ,
    'lastname'          => 'Соловьёв' ,
    'middlename'        => 'Владимирович' ,
    'snils'             => '107-022-096 00' ,
    'inn'               => '212200849566' ,
);


$secondRequestParams = array(
    'messageId' => 'bbbdcfcc-7def-11e9-a5e6-0242ac150002'
);

$Timestamp                  = time();
$sing                       = hash('sha256', $Timestamp . $APIKey); 

$Data = [
    'type' => 'VerifyRequest',
    'request' => $firstRequestParams,
    'timestamp' => $Timestamp ,
    'sign' => $sing,
];

$headers        = array(
    'Content-Type: application/json; charset=utf-8'
);

$arrParamsJSON = json_encode($Data);

$CurlRequest    = curl_init($APIHost);        
curl_setopt_array($CurlRequest, array(    
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,    
    CURLOPT_HTTPAUTH => CURLAUTH_ANY,
    CURLOPT_USERPWD => $APIUser.":".$APIPass,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_POSTFIELDS => $arrParamsJSON
));
$response       = curl_exec($CurlRequest);
curl_close($CurlRequest);

print_r($response);

?>