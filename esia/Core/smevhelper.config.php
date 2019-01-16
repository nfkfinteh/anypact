<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 22.10.2017
 * Time: 18:27
 */

require_once $this->DIRRECTORY_ESIA . 'SMEVHelper.class.php';
require_once $this->DIRRECTORY_ESIA . 'SMEVCheckStatusMail.class.php';

$SMEVHelperProxyModel = [
    'nfksber7.game-server.xyz' => 'strelok.dlinkddns.com:1188',
];

$Server = ArrayHelper::Value( $_SERVER, 'HTTP_HOST' );

$Proxy = ArrayHelper::Value( $SMEVHelperProxyModel, $Server );

if ( $Proxy !== null )
{
    SMEVHelper::$Proxy = $Proxy;
}

$Req = new SMEVHelperRequest();

$Req->Type = 'VerifyRequest';