<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 8:33
 */

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

/** @var \EsiaCore $this */

$arResult = &$this->Detail['RESULT'];

if (!isset($arResult['sms_kod_right_esia1']))
{
    $perm=rand(100000, 999999);
    if (substr($arResult['mobile'], 0, 1)=='7'){$pref='7'; $mobile_send="+".$arResult['mobile']; }else{$mobile_send = substr($arResult['mobile'], 1); $mobile="+7".$mobile_send;}

    $arResult['sms_kod1']=$perm;
    $sms_text="Kod dlya podtverzhdenia ".$perm.". Konfidencialno.";
    #echo $mobile;
    if (isset($arResult['id_person']))
    {
        echo send("gate.prostor-sms.ru", 80, "t89278485872", "897054", $mobile_send, $sms_text, "nfksber", "nfksber.ru");
        //$url="http://api.prostor-sms.ru/messages/v2/send/?login=t89278485872&password=897054&sender=nfksber&phone=".$mobile_send."&text=".$sms_text;
        //get_sms($url);
		require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';
        $arResult['date_kod_in1']=date('Y-m-d H:i:s');
        $sql="INSERT INTO konklude SET id_client='".intval($arResult['id_person'])."', phone=".$modx->quote($mobile_send).", date_in=".$modx->quote($arResult['date_kod_in1']).", code_in=".$modx->quote($arResult['sms_kod1']).", comment='Подписание'";

        $modx->query($sql);
        $arResult['id_konklude1']=$modx->lastInsertId();

    }
}

function send($host, $port, $login, $password, $phone, $text, $sender = false, $wapurl = false )
{
    $ReqData = [
        'host' => $host,
        'port' => $port,
        'login' => $login,
        'pass' => $password,
        'phone' => $phone,
        'text' => $text,
        'sender' => ( $sender ) ? 'Y' : 'N',
        'wapurl' => ( $wapurl ) ? 'Y' : 'N',
    ];

    Logger::AddText( $ReqData, 'SMS/RAW' );

    $fp = fsockopen($host, $port, $errno, $errstr);
    if (!$fp)
    {
        Logger::AddText( '!$fp', 'SMS/RAW' );

        return "errno: $errno \nerrstr: $errstr\n";
    }

    fwrite($fp, "GET /send/" .
        "?phone=" . rawurlencode($phone) .
        "&text=" . rawurlencode($text) .
        ($sender ? "&sender=" . rawurlencode($sender) : "") .
        ($wapurl ? "&wapurl=" . rawurlencode($wapurl) : "") .
        " HTTP/1.0\n");
    fwrite($fp, "Host: " . $host . "\r\n");
    if ($login != "")
    {
        fwrite($fp, "Authorization: Basic " .
            base64_encode($login. ":" . $password) . "\n");
    }
    fwrite($fp, "\n");
    $response = "";

    while(!feof($fp))
    {
        $response .= fread($fp, 1);
    }

    fclose($fp);

    Logger::AddText( $response, 'SMS/RAW' );

    list($other, $responseBody) = explode("\r\n\r\n", $response, 2);

    return $responseBody;
}