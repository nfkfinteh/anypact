<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 09.10.2017
 * Time: 23:39
 */

session_start();

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

$arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';

/** @var SMEVHelperRequest $Req */
require_once $this->DIRRECTORY_ESIA_CORE . 'smevhelper.config.php';

$Time = time();
$TimeLimit = 10*60;
$TimeLast = (int)$_SESSION['smev_first_check'];
$TimeDiff = $Time - $TimeLast;

$UserData = $_SESSION['smev_first_user'];

if ( $TimeDiff > $TimeLimit )
{
    $RES = [
        'RESULT' => true,
        'STATUS_HAVE' => true,
        'STATUS_VALID' => true,
    ];

    $arResult['SMEV_CHECK'] = 'TIMEOUT';

    $this->Merge( $this->Detail, 'RESULT', $arResult );

    $this->Save();

    SMEVCheckStatusMail::Send( $UserData, SMEVCheckStatusMail::STATUS_TIMEOUT );

    echo json_encode($RES);

    exit;
}

$CheckID = ArrayHelper::Value( $_REQUEST, 'ID' );

$Req->Request = [
    'requestId' => $CheckID,
];

$ReqRes = SMEVHelper::MakeRequest( $Req );

$Code = ArrayHelper::GetValuePath( $ReqRes, 'Response/code' );

//Допустимые значения:
// VALID – данные корректны;
// PROCESSING – в процессе обработки;
// INVALID – данные некорректны;
// INVALID_CFM_CODE – неверный код подтверждения;
// INVALID_REQUEST_ID – Неверный код запроса.

if ( $Code === 'VALID' )
{
    $RES = [
        'RESULT' => true,
        'STATUS_HAVE' => true,
        'STATUS_VALID' => true,
    ];

    $arResult['SMEV_CHECK'] = 'VERIFIED_SLOW';

    $this->Merge( $this->Detail, 'RESULT', $arResult );

    $this->Save();

    SMEVCheckStatusMail::Send( $UserData, SMEVCheckStatusMail::STATUS_VERIFIED );
}
elseif (  $Code === 'PROCESSING' )
{
    $RES = [
        'RESULT' => true,
        'CHECK_TIMER' => true,
        'CHECK_TIMER_INTERVAL' => 5000,
        'CHECK_ID' => $CheckID,
    ];
}
else
{
    $RES = [
        'RESULT' => false,
    ];

    $arResult['SMEV_CHECK'] = 'UNVERIFID';

    $this->Merge( $this->Detail, 'RESULT', $arResult );

    $this->Save();

    SMEVCheckStatusMail::Send( $UserData, SMEVCheckStatusMail::STATUS_UNVERIFIED );
}

$NextTime = time();

$RES['TIME'] = $NextTime - $TimeLast;

$RES['SMEVHelper'] = SMEVHelper::$Last;

echo json_encode($RES);

exit;