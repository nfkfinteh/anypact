<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 22.10.2017
 * Time: 18:11
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/ArrayHelper.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/SMEVHelper.class.php';

$Req = new SMEVHelperRequest();

$Req->Type = 'VerifyRequest';

$Req->Request = [
    'passportSeries' => '7311',
    'passportNumber' => '843052',
    'firstname' => 'Яков',
    'lastname' => 'Кравцов',
    'middlename' => 'Сергеевич',
    'snils' => '190-741-521 66',
    'inn' => '',
];

SMEVHelper::$Proxy = 'strelok.dlinkddns.com:1188';

$ReqRes = SMEVHelper::MakeRequest( $Req );

var_dump( $ReqRes );