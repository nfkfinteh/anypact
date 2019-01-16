<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 4:04
 */

/** @var \EsiaCore $this */

/** @var array $info */






############## проверяем на полнуту данных

//if ($arResult['PASS_VRF']!='VERIFIED' or strlen($arResult['first_name'])==0 or strlen($arResult['second_name'])==0 or strlen($arResult['last_name'])==0 or $arResult['PASS_ID']=='000000')
//{
//    $modx->query("UPDATE persons_esia SET klient_verify=0 WHERE id='$arResult[id_esia]'");
//
//    $Location = $this->URL( 'no_full_data' );
//
//    header("Location: " . $Location);
//    exit();
//}


$out = "";

$info["user_info"]["trusted"]=1;

### если учетная запись подтверждена
///$info["user_info"]["trusted"]=0;
if ($info["user_info"]["trusted"]==1)
{

    ob_start(); require_once $this->DIRRECTORY_ESIA_TPL . 'esia' . DIRECTORY_SEPARATOR . 'form.php'; $out .= ob_get_contents(); ob_end_clean();

### если учетная запись НЕ подтверждена
}
else
{
    $modx->query("UPDATE persons_esia SET klient_verify=0 WHERE id='$arResult[id_esia]'");
    $out .= "<p><b>К сожалению, Ваша учетная запись на сайте Госуслуг не подтверждена.</b><br/>В ближайшее время с Вами свяжется менеджер для продолжения процедуры открытия счета</p>";

    $sub = 'Открытие счета через госуслуги. Учетная запись неподтверждена';
    $headers = "From: NFKSBER.RU <openaccount@nfksber.ru>\n";
    $headers .= "X-Mailer: PHP\n";                                 // mailer
    $headers .= "X-Priority: 1\n";                                 // Urgent message!
    $headers .= "Content-Type: text/html; charset=utf-8\n"; // Mime type
    $mail_mess = date('d.m.Y H:i:s')." <br />
--------------------------------------------------------- <br>

У пользователя неподтвержденная учетная запись! <br>

ФИО: ".$arResult['fio']."<br>
Телефон: ".$arResult['mobile']."<br>
Электронная почта: ".$arResult['email']."<br><br>
Паспорт: ".$arResult['pass_seria']." ".$arResult['pass_number']." выдан ".$arResult['pass_dv']." ".$arResult['pass_who']."<br><br>
Адрес регистрации: ".$arResult['reg_adr']."<br><br>
Место рождения: ".$arResult['birth_place']."<br>
Дата рождения: ".$arResult['birth_day']."<br>
Гражданство: ".$arResult['citizen']."		
";

    $mail1="openaccount@nfksber.ru";
    mail($mail1, $sub, $mail_mess, $headers);


}

require_once $this->DIRRECTORY_ESIA_CORE . 'out.php';