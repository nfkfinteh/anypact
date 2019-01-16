<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 30.04.2017
 * Time: 21:58
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/ArrayHelper.class.php';

$INN = ArrayHelper::Value( $_REQUEST, 'inn' );

$INN = trim($INN);

if ( $INN == '' )
{
    echo 'true';

    exit();
}

if ( strlen( $INN ) == 12 )
{
    echo 'true';

    exit();
}

echo 'false';