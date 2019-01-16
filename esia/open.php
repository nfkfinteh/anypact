<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/PhoneHelper.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Email.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/ArrayHelper.class.php';

EsiaLogger::DumpEnviroment( 'open' );

require_once 'Core/modx.config.php';

$PhoneNumber = PhoneHelper::FormatPhone( $_REQUEST['form_phone'], '+7$1$2$3$4' );

$name_in = $modx->quote($_REQUEST['form_name']);
$email_in = $modx->quote($_REQUEST['form_email']);
$phone_in = $modx->quote( $PhoneNumber );
$town_in = $modx->quote($_REQUEST['form_city']);
$comm_in = $modx->quote($_REQUEST['form_text']);

$_SESSION['in_phone']=$phone_in;
$_SESSION['in_email']=$email_in;


$query="INSERT INTO persons SET name_in=$name_in, phone_in=$phone_in, email_in=$email_in, town_in=$town_in, comm_in=$comm_in";
$results = $modx->query($query); 
$_SESSION['id_person']=$modx->lastInsertId();

session_commit();


$arFrom = array("'");
$arTo = array("");
$name_in = str_replace($arFrom, $arTo, $name_in);
$phone_in = $PhoneNumber;
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



$mess_shablon=file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/components/mail-shablon/nfk_shablon.html");

$text_mess=$name_in.", Ваша заявка на открытие брокерского счета получена.<br/><br/>
Наш менеджер сможет проконсультировать Вас по всем возникающим вопросам  по телефонам 8(8352)45-77-11, 45-77-22,  8 800 200-84-84.";

$search = array ("##title_text##", "##text##");
$replace = array ("Открытие брокерского счета", $text_mess);
$mess = str_replace ($search, $replace, $mess_shablon);


$mail1="test@nfksber.ru";

$SSS = ArrayHelper::Value( $_REQUEST, 's' );

switch ( $SSS )
{
    case 'real':
    {
        $sub = 'Заявка на открытие брокерского счета';

        \Strelok\Classes\Helpers\Bitrix\Email\Email::Mail($email_in, $sub, $mess, $headers);
        \Strelok\Classes\Helpers\Bitrix\Email\Email::Mail($mail1, $sub, $mail_mess, $headers);

        echo "Спасибо. Ваша заявка получена. Мы свяжемся с Вами в ближайшее время!";
    }
    break;

    case 'real_gu':
    {
        $sub = 'Первичная заявка на открытие счета через госуслуги';

        \Strelok\Classes\Helpers\Bitrix\Email\Email::Mail($mail1, $sub, $mail_mess, $headers);

        echo "Спасибо. Ваша заявка получена";
    }
    break;

    case 'real_smev':
    {
        $sub = 'Первичная заявка на открытие счета через СМЭВ';

        \Strelok\Classes\Helpers\Bitrix\Email\Email::Mail($mail1, $sub, $mail_mess, $headers);

        echo "Спасибо. Ваша заявка получена";
    }
    break;
}