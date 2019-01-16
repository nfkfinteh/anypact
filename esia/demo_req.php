<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 27.10.2017
 * Time: 0:10
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/PhoneHelper.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Email.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/ArrayHelper.class.php';

function content_mail($array, $url) {
$message 	 = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Вопрос с сайта pioneer-leasing.ru о биржевых облигациях</title>
<style type="text/css">

</style>
</head>
<body>
<div class="rcmBody" style="margin: 0">
    <table cellspacing="0" cellpadding="0" width="600" border="0" align="center" style="border-collapse: collapse; background: #ffffff; margin-top: 20px; margin-bottom: 20px; table-layout: fixed; padding: 0">
        <tbody>
		<tr>
                <td style="width: 400px"><span style="font-family: "Roboto", sans-serif; color: #3b3c3b; font-size: 14px; text-decoration: none; color: #000000">тел. 8 800 200-84-84, <a style="font-family: "Roboto", sans-serif; color: #3b3c3b; font-size: 14px; text-decoration: none" href="http://nfksber.ru" target="_blank" rel="noreferrer">nfksber.ru</a></span></td>
		      <td style="text-align: right; vertical-align: top">
               <a href="http://vk.com/nfksber" target="_blank" rel="noreferrer"><img alt="vk" border="0" width="36" height="32" src="http://nfksber.ru/assets/components/mail-shablon/vk.gif"></a>
                <a href="https://www.facebook.com/nfksber/" target="_blank" rel="noreferrer"><img alt="facebook" border="0" width="36" height="32" src="http://nfksber.ru/assets/components/mail-shablon/fb.gif"></a>
                <a href="http://ok.ru/nfksber" target="_blank" rel="noreferrer"><img alt="одноклассники" border="0" width="36" height="32" src="http://nfksber.ru/assets/components/mail-shablon/ok.gif"></a>
                <a href="https://twitter.com/nfksber" target="_blank" rel="noreferrer"><img alt="Twitter" border="0" width="30" height="28" src="http://nfksber.ru/assets/components/mail-shablon/tw.gif"></a>
            </td>
		</tr>
            <tr>
                <td style="height: 91px; text-align: center; padding-bottom: 0px; background: rgba(19, 96, 36, 0.83)">
                    <a href="http://nfksber.ru/" style="display: block; color: #333333; font-family: Tahoma, sans-serif; font-size: 12px; text-decoration: underline; line-height: 20px; -webkit-text-size-adjust: none" target="_blank" rel="noreferrer"><img src="http://nfksber.ru/assets/components/mail-shablon/nfk-logo.jpg" alt="НФК Сбережения" border="0" width="600" height="91" style="display: block; margin: 0px"></a>
                </td>
            </tr>
       </tbody></table>
       <table cellspacing="0" cellpadding="0" width="600" border="0" align="center" style="border-collapse: collapse; background: #ffffff; margin-top: 20px; margin-bottom: 20px; table-layout: fixed; padding: 0">
	 <tbody><tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #c9c9c9; table-layout: fixed">
                        <tbody>
                        <tr>
				            <td style="padding: 10px 20px; color: #3b3c3b; font-size: 13px; text-decoration: none">
                                <h2>Запрос консультации с сайта nfksber.ru</h2>
                                <p>Запрос был отправлен с формы <b>'.$url.'</b></p>
                                <h2>Контактные данные:</h2>
                                <p>'.$array[2].'</p>
                                <p>'.$array[0].'</p>
                                <p>'.$array[1].'</p>
                                <p>'.$array[3].'</p>
                                <p>'.$array[4].'</p>
                                <p>'.$array[5].'</p>
                            </td>
                        </tr>
                    </tbody></table>
                </td>
            </tr>
            <tr>
                <td style="text-align: center; padding-top: 10px">
                    <span style="width: 600px; margin: 0 auto; display: block; color: #a5a5a5; font-family: Tahoma, sans-serif; font-size: 12px; font-weight: 400; line-height: 20px; -webkit-text-size-adjust: none">
			Акционерное общество <br> «Инвестиционная компания «НФК-Сбережения»<br>428001,ЧУВАШИЯ ЧУВАШСКАЯ РЕСПУБЛИКА - ГОРОД ЧЕБОКСАРЫ,<br>ПРОСПЕКТ МАКСИМА ГОРЬКОГО д. 5 корп. 2</span>
                </td>
            </tr>        
    </tbody></table>
</div> 
</body>
</html>';

return $message;

}


$mail1  = 'test@nfksber.ru';
$sub    = 'Заявка на открытие Демо-счета';
$url    = '';

if (!empty($_POST['form_pagetitle'])){
    $sub = "Запрос с сайта по теме: ".$_POST['form_pagetitle'];
    $url = $_POST['form_pagetitle'];
}


$Model = [
    'form_email'    => 'Электронная почта',
    'form_phone'    => 'Телефон',
    'form_name'     => 'Имя',
    'form_city'     => 'Город',
    'form_text'     => 'Сообщение',
    'form_url'      => 'Страница с которой сделан запрос ',
];

$Msg = [];

foreach ( $Model as $K => $V )
{
    $Msg[] = $V . ': ' . ArrayHelper::Value( $_REQUEST, $K );
}

//$mail_mess = json_encode( $_REQUEST, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
//$mail_mess = implode( '<br>', $Msg );
$mail_mess = content_mail($Msg, $url);
$headers = "From: mail@pioneer-leasing.ru\r\n Content-type: text/html; charset=utf-8\r\n X-Mailer: PHP mail script";


\Strelok\Classes\Helpers\Bitrix\Email\Email::Mail($mail1, $sub, $mail_mess, $headers);

$RES = [
    'RESULT' => true,
];

echo json_encode( $RES );