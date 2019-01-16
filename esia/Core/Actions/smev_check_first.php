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

function ExtractDigits2Array( $Text, $Count )
{
    $Digits = [
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
    ];

    $Result = [];

    for ($i = 0, $j = strlen($Text); $i < $j; $i++)
    {
        if ( count($Result) > $Count )
        {
            break;
        }

        $Char = $Text[$i];

        if ( in_array( $Char, $Digits ) )
        {
            $Result[] = $Char;
        }
    }

    return $Result;
}

function ExtractDigits2String( $Text, $Count )
{
    $Res = ExtractDigits2Array( $Text, $Count );

    $Result = implode( '', $Res );

    return $Result;
}


$SnilsStr = ArrayHelper::Value( $_REQUEST, 'snils' );

$Snils = ExtractDigits2Array( $SnilsStr, 11 );

$SnilsRes = '';

if ( count( $Snils ) === 11 )
{
    $SnilsRes = $Snils[0] . $Snils[1] . $Snils[2] . '-' . $Snils[3] . $Snils[4] . $Snils[5] . '-' . $Snils[6] . $Snils[7] . $Snils[8] . ' ' . $Snils[9] . $Snils[10];
}

$PassportType = ArrayHelper::Value( $_REQUEST, 'type_doc' );

$PassportSerial = ArrayHelper::Value( $_REQUEST, 'pass_seria' );

switch ( $PassportType )
{
    case 'RF_PASSPORT': $PassportSerialStr = ExtractDigits2String( $PassportSerial, 4 );  break;
    default: $PassportSerialStr = $PassportSerial;
}

$PassportNumber = ArrayHelper::Value( $_REQUEST, 'pass_number' );
$PassportNumberStr = ExtractDigits2String( $PassportNumber, 6 );

$INN = ArrayHelper::Value( $_REQUEST, 'inn' );
$INNStr = ExtractDigits2String( $INN, 12 );

$Req->Request = [
    'passportSeries' => $PassportSerialStr,
    'passportNumber' => $PassportNumberStr,
    'firstname' => trim(ArrayHelper::Value( $_REQUEST, 'first_name' )),
    'lastname' => trim(ArrayHelper::Value( $_REQUEST, 'last_name' )),
    'middlename' => trim(ArrayHelper::Value( $_REQUEST, 'second_name' )),
    'snils' => $SnilsRes,
    'inn' => $INNStr,
];

$FirstTime = time();

$_SESSION['smev_first_check'] = $FirstTime;

$_SESSION['smev_first_user'] = $Req->Request;

session_commit();

$ReqRes = SMEVHelper::MakeRequest( $Req );

if ( $ReqRes->Success === true )
{
    $Code = ArrayHelper::GetValuePath( $ReqRes, 'Response/code' );

    if ( $Code === 'VALID' )
    {
        $RES = [
            'RESULT' => true,
            'STATUS_HAVE' => true,
            'STATUS_VALID' => true,
        ];

        $arResult['SMEV_CHECK'] = 'VERIFIED_FAST';

        $this->Merge( $this->Detail, 'RESULT', $arResult );

        $this->Save();

        SMEVCheckStatusMail::Send( $Req->Request, SMEVCheckStatusMail::STATUS_VERIFIED );
    }
    else
    {
        $RES = [
            'RESULT' => true,
            'CHECK_TIMER' => true,
            'CHECK_TIMER_INTERVAL' => 5000, //ms
            'CHECK_ID' => ArrayHelper::Value( $ReqRes->Response, 'requestId' ),
            'STATUS_HAVE' => false,
        ];
    }
}
else
{
    $RES = [
        'RESULT' => false,
    ];

    $arResult['SMEV_CHECK'] = 'UNVERIFID';

    $this->Merge( $this->Detail, 'RESULT', $arResult );

    $this->Save();

    SMEVCheckStatusMail::Send( $Req->Request, SMEVCheckStatusMail::STATUS_UNVERIFIED );
}

$NextTime = time();

$RES['TIME'] = $NextTime - $FirstTime;

$RES['SMEVHelper'] = SMEVHelper::$Last;

$RES['SMEVHelper.$LastURL'] = SMEVHelper::$LastURL;
$RES['SMEVHelper.$LastDATA'] = SMEVHelper::$LastDATA;
$RES['SMEVHelper.$LastRESPONSE'] = SMEVHelper::$LastRESPONSE;

echo json_encode($RES);

exit;