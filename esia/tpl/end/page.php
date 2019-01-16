<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 8:41
 */

/** @var \EsiaCore $this */

$CurrentDateTime = new \DateTime();

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Email.class.php';

function IsTestEnvironment()
{
	return false;
}

$modx->query("UPDATE persons_esia SET klient_verify=1 WHERE id='$arResult[id_esia]'");


//$text_mess=$arResult['first_name']." ".$arResult['second_name'].", поздравляем! Сбор информации завершён. После проверки информации Вы получите email-уведомление с подтверждением о принятии Ваших документов и инструкции для начала работы.<br/>Наш менеджер может проконсультировать Вас по всем возникающим вопросам по телефону 8 800 200-84-84.";

function xJSON($N, $V)
{
    echo  '<h1>'.$N.'</h1>' . PHP_EOL;

    echo '<pre>' . json_encode($V, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>' . PHP_EOL;
}

$phone = PhoneHelper::FormatPhone( $arResult['mobile'] );
$code = $arResult['SMS_CODE_CONFIRM_2'];
$time = DateHelper::GetDateString( $arResult['date_kod_out1'], 'H:i' );

$text_mess=$arResult['last_name'] . ' ' . $arResult['first_name']." ".$arResult['second_name'].", поздравляем! Сбор информации завершён. <br>Вы подписали комплект документов для открытия счета в АО «НФК-Сбережения» электронной подписью с номера телефона {$arResult['mobile']} и кодом подтверждения {$arResult['SMS_CODE_CONFIRM_2']} в $time UTC+3:00.<br/>
            Получение Вами письма с подтверждением открытия счета по электронной почте с адреса <u>openaccount@nfksber.ru</u>
            будет означать подписание соответствующих документов со стороны АО &#171;НФК-Сбережения&#187;. <br>Ожидайте письма с подтверждением открытия счета и инструкциями для начала работы.
            На процесс обработки документов может уйти до двух рабочих дней.<br/>Наш менеджер может проконсультировать Вас по всем возникающим вопросам по телефону 8 800 200-84-84.";

$headers = "From: NFKSBER.RU <openaccount@nfksber.ru>\n";
$headers .= "X-Mailer: PHP\n";                                 // mailer
$headers .= "X-Priority: 1\n";                                 // Urgent message!
$headers .= "Content-Type: text/html; charset=utf-8\n"; // Mime type

if ( ArrayHelper::Value( $arResult, 'IS_SMEV' ) === 'Y' )
{
    $PreviewKey = 'open_smev/';
    $RES_FIO = $arResult['last_name'] . ' ' . $arResult['first_name']." ".$arResult['second_name'];
    $RES_BD = ArrayHelper::Value( $arResult, 'birth_day' );

    if ( empty($RES_BD) )
    {
        $RES_BD = ArrayHelper::GetValuePath( $this->Preview, 'agree/birth_day' );
    }

    //$RES_METHOD = 'СМЭВ<br>Статус проверки: `' . ArrayHelper::Value( $arResult, 'SMEV_CHECK' ). '`';
    $RES_METHOD = 'СМЭВ';

    $sub = 'Открытие счета через СМЭВ';
}
else
{
    $PreviewKey = 'open/';
    $RES_FIO = $arResult['fio'];
    $RES_BD = $arResult['birth_day_esia'];

    if ( empty($RES_BD) )
    {
        $RES_BD = ArrayHelper::GetValuePath( $this->Preview, 'agree/birth_day' );
    }

    $RES_METHOD = 'ЕСИА';

    $sub = 'Открытие счета через Госуслуги';
}

$PersonID = ArrayHelper::Value( $arResult, 'id_person' );
$PerconCode = $this->Code;

$mail_mess = date('d.m.Y H:i:s')." <br />
--------------------------------------------------------- <br><br>

ID: ".$PersonID."<br>
CODE: ".$PerconCode."<br><br>>

ФИО: ".$RES_FIO."<br>
Телефон: ".$arResult['mobile']."<br>
Электронная почта: ".$arResult['email']."<br><br>
Паспорт: ".$arResult['pass_seria']." ".$arResult['pass_number']." выдан ".$arResult['pass_dv']." ".$arResult['pass_who']."<br>
ИНН: ".$arResult['inn']."<br><br>
Адрес регистрации: ".$arResult['reg_adr']."<br>
Адрес почтовый: ".$arResult['post_adr']."<br><br>
Место рождения: ".$arResult['birth_place']."<br>
Дата рождения: ".$RES_BD."<br>
Гражданство: ".$arResult['citizen']."		
<br><br>Метод подтверждения данных:".$RES_METHOD."
<br><br>Данные входной страницы:
Электронная почта: ".ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_email' )."<br>
Телефон: ".ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_phone' )."<br>
ФИО: ".ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_name' )."<br>
Населенный пункт: ".ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_city' )."<br><br>
Сообщение:  ".ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_text' )."<br>

<br><br>Коды подтверждения<br/>
Согласие: ".ArrayHelper::Value( $arResult, 'SMS_CODE_CONFIRM_1' )." (".ArrayHelper::Value( $arResult, 'date_kod_out' ).")<br/>
Подпись: ".ArrayHelper::Value( $arResult, 'SMS_CODE_CONFIRM_2' )." (".ArrayHelper::Value( $arResult, 'date_kod_out1' ).")<br/>

<br><br>Выбранные секторы обслуживания<br/>";

if ($arResult['market_stock']==1){$mail_mess .="Брокерский комплект (стандарт), ";}
if ($arResult['market_outstock']==1){$mail_mess .="Внебиржевой рынок, ";}
if ($arResult['market_valuta']==1){$mail_mess .="Валютный рынок и рынок драг. металлов, ";}
if ($arResult['market_other']==1){$mail_mess .="Другое ";}
$mail_mess .="<br/>";
if ($arResult['iis']==1){$mail_mess .="Брокерский комплект (ИИС) ";}
if (strlen($arResult['iis_company'])>1){$mail_mess .="ИИС открыт в компании ".$arResult['iis_company'];}
$mail_mess .="<br/>";
if ($arResult['depo']==1){$mail_mess .="Нужно открыть Дополнительный депозитарный договор ";
    if ($arResult['depo_type_ch']==1){$mail_mess .="на владельца (торговый) ";}else{$mail_mess .="на владельца ";}
    if ($arResult['depo_dohod_ch']==1){$mail_mess .="доход переводить на брокерский счет";}else{$mail_mess .="доход переводить на реквизиты:".$arResult['shet'].";".$arResult['k_shet'].";".$arResult['bank'].";".$arResult['bik'];}

}

//xJSON('$mail_mess', $mail_mess);
//xJSON('$arResult', $arResult);
//xJSON('Preview', $this->Preview);
//return;

$mess_shablon=file_get_contents( $_SERVER['DOCUMENT_ROOT'] . "/assets/components/mail-shablon/nfk_shablon.html");


$search = array ("##title_text##", "##text##");
$replace = array ("Открытие счета через госуслуги - этап сбора информации завершен", $text_mess);
$mess = str_replace ($search, $replace, $mess_shablon);


$mail2=$arResult['email'];
//$mail3="ilinskiy@gmail.com";
$mail1="openaccount@nfksber.ru";
//mail($mail1, $sub . ' ' . $arResult['first_name']." ".$arResult['second_name'], $mail_mess, $headers);

$END_MAIL_SEND = ArrayHelper::Value( $arResult, 'END_MAIL_SEND' );

if ( $END_MAIL_SEND !== 'Y' )
{
    $FIO = [
        ArrayHelper::Value( $arResult, 'last_name' ),
        ArrayHelper::Value( $arResult, 'first_name' ),
        ArrayHelper::Value( $arResult, 'second_name' ),
    ];

    $FIOS = implode( ' ', $FIO );

    ob_start(); require 'msg_manager.php'; $msg_mess = ob_get_contents(); ob_end_clean();

    $msg_template = file_get_contents( $_SERVER['DOCUMENT_ROOT'] . "/assets/components/mail-shablon/nfk_shablon.html");
    $msg_search = array ("##title_text##", "##text##");
    $msg_replace = array ($sub . ' ' . $FIOS, $msg_mess);
    $msg = str_replace ($msg_search, $msg_replace, $msg_template);

    //echo $msg;

    \Strelok\Classes\Helpers\Bitrix\Email\Email::Mail( $mail1, $sub . ' ' . $FIOS, $msg );
    //\Strelok\Classes\Helpers\Bitrix\Email\Email::Mail( $mail1, $sub . ' ' . $FIOS, $mail_mess );

//mail($mail3, $sub, $mail_mess, $headers);
//mail($arResult['email'], $sub, $mess, $headers);
//mail($arResult['email'], $sub, $mess, $headers);

    \Strelok\Classes\Helpers\Bitrix\Email\Email::Mail( $arResult['email'], $sub, $mess );
}
else
{
//    $FIO = [
//        ArrayHelper::Value( $arResult, 'last_name' ),
//        ArrayHelper::Value( $arResult, 'first_name' ),
//        ArrayHelper::Value( $arResult, 'second_name' ),
//    ];
//
//    $FIOS = implode( ' ', $FIO );
//
//    ob_start(); require 'msg_manager.php'; $msg_mess = ob_get_contents(); ob_end_clean();
//
//    $msg_template = file_get_contents( $_SERVER['DOCUMENT_ROOT'] . "/assets/components/mail-shablon/nfk_shablon.html");
//    $msg_search = array ("##title_text##", "##text##");
//    $msg_replace = array ($sub . ' ' . $FIOS, $msg_mess);
//    $msg = str_replace ($msg_search, $msg_replace, $msg_template);
//
//    echo $msg;
}

$arResult['END_MAIL_SEND'] = 'Y';

ob_start(); require $this->DIRRECTORY_ESIA_TPL . 'arresult.hidden.php'; $out .= ob_get_contents(); ob_end_clean();

ob_start(); require $this->DIRRECTORY_ESIA_TPL . 'steep.progress.cc.php'; $out .= ob_get_contents(); ob_end_clean();

ob_start(); require_once $this->DIRRECTORY_ESIA_TPL . 'document.sign.success.php'; $out .= ob_get_contents(); ob_end_clean();

require_once $this->DIRRECTORY_ESIA_CORE . 'out.php';