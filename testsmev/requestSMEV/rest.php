<?php

require_once 'class/requestSMEV.php';

$firstRequestParams = array(
    'passportSeries'    => '9704' ,
    'passportNumber'    => '058308' ,
    'firstname'         => 'Игорь' ,
    'lastname'          => 'Соловьёв' ,
    'middlename'        => 'Владимирович' ,
    'snils'             => '107-022-096 00' ,
    'inn'               => '212200849566' ,
);

if(!empty($_POST['arrParams'])){
    $RequestSMEV    = new  requestSMEV();    
    $arrParams = json_decode($_POST['arrParams'], true);
    
    if(!empty($arrParams['ID'])){        
        $RequestParams  = array('messageId' => $arrParams['ID']);        
    }else {        
        $RequestParams  =  $arrParams;        
    }
    
    $ResponseSMEV   = $RequestSMEV->getRequest($RequestParams);
}

print_r($ResponseSMEV);



?>