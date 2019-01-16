<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 2:01
 */

/** @var \EsiaCore $this */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Email.class.php';

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

$arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

$ID_ESIA = ArrayHelper::Value( $arResult, 'id_esia' );

require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';

$name_in = $modx->quote($_REQUEST['form_name']);
$email_in = $modx->quote($_REQUEST['form_email']);
$phone_in = $modx->quote($_REQUEST['form_phone']);
$town_in = $modx->quote($_REQUEST['form_city']);
$comm_in = $modx->quote($_REQUEST['form_text']);

$arResult['in_phone']=$phone_in;
$arResult['in_email']=$email_in;


$query="INSERT INTO persons SET name_in=$name_in, phone_in=$phone_in, email_in=$email_in, town_in=$town_in, comm_in=$comm_in";
$results = $modx->query($query);
$arResult['id_person']=$modx->lastInsertId();


$arFrom = array("'");
$arTo = array("");
$name_in = str_replace($arFrom, $arTo, $name_in);
$phone_in = str_replace($arFrom, $arTo, $phone_in);
$email_in = str_replace($arFrom, $arTo, $email_in);
$town_in = str_replace($arFrom, $arTo, $town_in);
$comm_in = str_replace($arFrom, $arTo, $comm_in);

$headers = "From: NFKSBER.RU <test@nfksber.ru>\n";
$headers .= "X-Mailer: PHP\n";                                 // mailer
$headers .= "X-Priority: 1\n";                                 // Urgent message!
$headers .= "Content-Type: text/html; charset=utf-8\n"; // Mime type
$mail_mess = date('d.m.Y H:i:s')." <br />
--------------------------------------------------------- <br>
ФИО: ".$name_in."<br>
Телефон: ".$phone_in."<br>
Электронная почта: ".$email_in."<br>
Город: ".$town_in."<br>
Сообщение: ".$comm_in."<br>";



$mess_shablon=file_get_contents( $_SERVER['DOCUMENT_ROOT'] . "/assets/components/mail-shablon/nfk_shablon.html");

$text_mess=$name_in.", Ваша заявка на открытие брокерского счета получена.<br/><br/>
Наш менеджер сможет проконсультировать Вас по всем возникающим вопросам  по телефонам 8(8352)45-77-11, 45-77-22,  8 800 200-84-84.";

$search = array ("##title_text##", "##text##");
$replace = array ("Открытие брокерского счета", $text_mess);
$mess = str_replace ($search, $replace, $mess_shablon);


//$mail1="ilinskiy@gmail.com";
$mail1="test@nfksber.ru";
//$mail2="ilinskiy@gmail.com";

if (isset($_REQUEST['s']) and $_REQUEST['s']=='real')
{
    //$sub = 'Заявка на открытие брокерского счета';
    //\Strelok\Classes\Helpers\Bitrix\Email\Email::Mail($email_in, $sub, $mess, $headers);
    //\Strelok\Classes\Helpers\Bitrix\Email\Email::Mail($mail1, $sub, $mail_mess, $headers);
//mail($mail2, $sub, $mail_mess, $headers);
    //echo "Спасибо. Ваша заявка получена";
}
else
    {
    //$sub = 'Первичная заявка на открытие счета через госуслуги';
//mail($email_in, $sub, $mess, $headers);
    //\Strelok\Classes\Helpers\Bitrix\Email\Email::Mail($mail1, $sub, $mail_mess, $headers);
//mail($mail2, $sub, $mail_mess, $headers);
   // echo "Спасибо. Ваша заявка получена";
}

$this->Merge( $this->Detail, 'RESULT', $arResult );

$this->Save();

if ( !empty( $ID_ESIA ) )
{
    $url = $this->URL('esia');
    header("Location: $url");

    exit;
}

require_once $this->DIRRECTORY_ESIA_CORE . 'esia.config.php';

$esia->create();