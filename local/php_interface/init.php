<?php define("PREFIX_PATH_404", "/404.php");
include_once 'function.php';

if(file_exists($_SERVER['DOCUMENT_ROOT']."/local/php_interface/constants.php"))
  require_once($_SERVER['DOCUMENT_ROOT']."/local/php_interface/constants.php");

if(file_exists($_SERVER['DOCUMENT_ROOT']."/local/class/CNotification.php"))
  require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CNotification.php");

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("main", "OnAfterEpilog", "Prefix_FunctionName");

//проверка являеться ли пользователь сотрудником компании и сброс по необходимости
$eventManager->addEventHandler("main", "OnAfterEpilog", "checkUserCompany");


function Prefix_FunctionName() {
    global $APPLICATION;

    // Check if we need to show the content of the 404 page
    if (!defined('ERROR_404') || ERROR_404 != 'Y') {
        return;
    }

    // Display the 404 page unless it is already being displayed
    if ($APPLICATION->GetCurPage() != PREFIX_PATH_404) {
        header('X-Accel-Redirect: '.PREFIX_PATH_404);
        exit();
    }
}

function checkUserCompany(){
    global $USER;
    $resUser = CUser::GetByID($USER->GetID());
    if ($obj = $resUser->GetNext()){
        $arUser = $obj;
    }
    if(!empty($arUser['UF_CUR_COMPANY'])){
        if(CModule::IncludeModule('iblock')){
            $resCompany = CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID'=>8,
                    'ID'=>$arUser['UF_CUR_COMPANY'],
                    'ACTIVE'=>'Y',
                    [
                        "LOGIC" => "OR",
                        ["=PROPERTY_STAFF" => $arUser['ID']],
                        ["=PROPERTY_DIRECTOR_ID" => $arUser['ID']],
                    ]
                ],
                false,
                false,
                ['IBLOCK_ID', 'ID', 'PROPERTY_STAFF']
            );
            $cntCompany = $resCompany->SelectedRowsCount();

            #если не сотрудник и не директор сбрасываем поле UF_CUR_COMPANY
            if($cntCompany<=0){
                $user = new CUser;
                $user->Update(
                    $arUser['ID'],
                    ['UF_CUR_COMPANY'=>'']
                );
            }
        }
    }
}

AddEventHandler("main", "OnBuildGlobalMenu", "OnBuildGlobalMenu");
function OnBuildGlobalMenu(&$arGlobalMenu, &$arModuleMenu)
{
	global $USER;
	if(!$USER->IsAdmin())
    return;

    $arItems[] = array(
        'parent_menu' => 'global_menu_anypact',
        'section' => 'gosuslugi',
        'sort' => 1,
        'url' => 'anypact_gosuslugi.php?lang=' . LANGUAGE_ID,
        'text' => 'Госуслуги',
        'title' => 'Настройка госуслуг',
        'icon' => 'gosuslugi_menu_icon',
        'page_icon' => 'gosuslugi_page_icon',
        'items_id' => 'menu_gosuslugi',
        'items' => array()
    );

    $arItems[] = array(
        'parent_menu' => 'global_menu_anypact',
        'section' => 'moderation_company',
        'sort' => 10,
        'url' => 'moderation_company.php?lang=' . LANGUAGE_ID,
        'text' => 'Модерация компаний',
        'title' => 'Модерация компаний',
        'icon' => 'moderation_company_menu_icon',
        'page_icon' => 'moderation_company_page_icon',
        'items_id' => 'menu_moderation_company',
        'items' => array()
    );

    $arItems[] = array(
        'parent_menu' => 'global_menu_anypact',
        'section' => 'moderation_deal',
        'sort' => 20,
        'url' => 'moderation_deal.php?lang=' . LANGUAGE_ID,
        'text' => 'Модерация сделок',
        'title' => 'Модерация сделок',
        'icon' => 'moderation_deal_menu_icon',
        'page_icon' => 'moderation_deal_page_icon',
        'items_id' => 'menu_moderation_deal',
        'items' => array()
    );

    $arItems[] = array(
        'parent_menu' => 'global_menu_anypact',
        'section' => 'agreement_status',
        'sort' => 30,
        'url' => 'agreement_status.php?lang=' . LANGUAGE_ID,
        'text' => 'Статусы договоров',
        'title' => 'Статусы договоров',
        'icon' => 'agreement_status_menu_icon',
        'page_icon' => 'agreement_status_page_icon',
        'items_id' => 'menu_agreement_status',
        'items' => array()
    );

    $arItems[] = array(
        'parent_menu' => 'global_menu_anypact',
        'section' => 'moderation_edit_deal',
        'sort' => 40,
        'url' => 'moderation_edit_deal.php?lang=' . LANGUAGE_ID,
        'text' => 'Изменения сделок',
        'title' => 'Изменения сделок',
        'icon' => 'moderation_edit_deal_menu_icon',
        'page_icon' => 'moderation_edit_deal_page_icon',
        'items_id' => 'menu_moderation_edit_deal',
        'items' => array()
    );

    $arGlobalMenu[] = array(
        'menu_id' => 'anypact',
        'text' => 'AnyPact',
        'title' => 'Настройки сайта AnyPact',
        'sort' => 550,
        'item_id' => 'global_menu_anypact',
        'help_section' => 'anypact',
        'items' => $arItems
    );

}

/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/Exception.php'))
    require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/Exception.php';
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/PHPMailer.php'))
    require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/PHPMailer.php';
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/SMTP.php'))
    require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/SMTP.php';

function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters)
{
    define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/custom_mail.log");
    $mail = new PHPMailer();
    $mail->isSMTP();
    //$mail->SMTPDebug = 2;
    $mail->Host = 'post.nfksber.ru';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'info@anypact.ru';
    $mail->Password = 'PKmR5g3k42';
    $mail->setFrom('info@anypact.ru', 'AnyPact');
    $mail->addAddress($to);
    $mail->CharSet  = 'UTF-8';
    $mail->Subject = $subject;

    $message_new = explode("Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit", $message)[1];

    list($message_alt, $message_html) = explode("Content-Type: text/html; charset=UTF-8
Content-Transfer-Encoding: 8bit", $message_new);

    if(empty($message_html)){
        $message_html = $message;
    }else{
        $message_html = substr($message_html, 0, -26);
        $message_alt = substr($message_alt, 0, -24);
    }

    $mail->msgHTML($message_html);
    $mail->AltBody = $message_alt;

    $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
    );

    if (!$mail->send()) {
        AddMessage2Log($mail->ErrorInfo, 'ErrorInfo');
        return false;
        exit;
    } else {
        //echo 'Message sent!';
        return true;
    }
	$mail->clearAddresses();
	$mail->ClearCustomHeaders();

} */

function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters)
{

    if (!class_exists('PHPMailer'))
    {
        require_once ($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/libraries/PHPMailer/PHPMailerAutoload.php');
    }

    $arErrorSendEmail = array();

    if (stristr($additional_headers, "Content-Type: text/html") || stristr($message, "Content-Type: text/html"))
    {
        $isHTML=true;
    } else
    {
        $isHTML=false;
    }

    $filesIDs=array();
    $filesSRCs=array();
    if (stristr($message,'------------'))
    {
        $messageArr=explode('------------',$message);
        $messageArr2=explode('Content-Transfer-Encoding: 8bit',$messageArr[1]);
        $messageAlt=trim($messageArr2[1]);
        $messageArr2=explode('Content-Transfer-Encoding: 8bit',$messageArr[2]);
        $messageHTML=trim($messageArr2[1]);

        for ($i=2;$i<count($messageArr);$i++)
        {
            $curPart=$messageArr[$i];
            if (stristr($curPart,'Content-ID: <'))
            {
                $curPartArr=explode('Content-ID: <',$curPart);
                $curPartArr=explode('>',$curPartArr[1]);
                $filesIDs[]=$curPartArr[0];
            }
        }
    }
    if (stristr($message,'---------'))
    {
        $messageArr=explode('---------',$message);
        $messageArr2=explode('Content-Transfer-Encoding: 8bit',$messageArr[1]);
        $messageAlt=trim($messageArr2[1]);
        $messageArr2=explode('Content-Transfer-Encoding: 8bit',$messageArr[2]);
        $messageHTML=trim($messageArr2[1]);

        for ($i=2;$i<count($messageArr);$i++)
        {
            $curPart=$messageArr[$i];
            if (stristr($curPart,'Content-ID: <'))
            {
                $curPartArr=explode('Content-ID: <',$curPart);
                $curPartArr=explode('>',$curPartArr[1]);
                $filesIDs[]=$curPartArr[0];
            }
        }
    }

    if(empty($messageHTML))
        $messageHTML = $message;
    if(empty($messageAlt))
        $messageAlt = $messageHTML;

    foreach ($filesIDs as $filesID)
    {
        $src=CFile::GetFileArray($filesID);
        $filesSRCs[]=$src["SRC"];
    }

    $to = str_replace(' ','',$to);
    $to = strtolower($to);

    $fromName="AnyPact";

    $host="post.nfksber.ru";
    $port=587;
    $user="info@anypact.ru";
    $from="info@anypact.ru";
    $pass="PKmR5g3k43";

    preg_match('/Reply-To: (.+)\n/i', $additional_headers, $matches);
    list(, $ReplyTo) = $matches;
    $ReplyTo = str_replace(' ','',$ReplyTo);
    $ReplyTo = strtolower($ReplyTo);
    $ReplyTo=explode(",",$ReplyTo);

    preg_match('/BCC: (.+)\n/i', $additional_headers, $matches);
    list(, $bcc) = $matches;

    $bcc=explode(",",$bcc);
    $bcc2=COption::GetOptionString("main", "all_bcc");
    $bcc2=explode(",",$bcc2);
    $bcc=array_merge($bcc,$bcc2);
    $bcc2=array();
    $address = explode(',', $to);
    foreach ($bcc as $email)
    {
        $mail=trim($email);
        if (!in_array($mail,$address))
        {
            $bcc2[]=$mail;
        }
    }
    $bcc=$bcc2;
    $bcc=array_unique($bcc);

    if ($isHTML)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();

        $address = explode(',', $to);
        foreach ($address as $addr)
        {
            $mail->addAddress(trim($addr));
        }

        foreach ($ReplyTo as $email)
        {
            if (trim($email)!="")
            {
                $mail->AddReplyTo($email);
            }
        }

        foreach ($bcc as $email)
        {
            if (trim($email)!="")
            {
                $mail->addBCC(trim($email));
            }
        }

        $mail->CharSet = 'UTF-8';
        $mail->Host = $host; // SMTP server example
        $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
        $mail->SMTPAuth = true; // enable SMTP authentication
        //$mail->SMTPSecure = 'tls';
        $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Port = $port; // set the SMTP port for server
        $mail->Username = $user; // SMTP account username example
        $mail->Password = $pass; // SMTP account password example
        $mail->From = $from;
        $mail->FromName = $fromName;

        $mail->IsHTML(true);
        $mail->Subject = $subject;

        $bndr = substr(substr($messageHTML, 0, 25), -23); // А ВОТ ТУТ МУТИМ СВОЮ МАГИЮ!
        $mail->ContentType = 'multipart/mixed; boundary="' . $bndr . '"';
        $mail->Body = $messageHTML;
        $mail->AltBody = strip_tags(str_replace("<br />","\n",$messageAlt));

        foreach ($filesSRCs as $file)
        {
            $mail->AddAttachment($_SERVER["DOCUMENT_ROOT"].$file);
        }

        if (!$mail->send())
        {
            // file_put_contents($_SERVER["DOCUMENT_ROOT"]."/_asd.log","\nError: ".$mail->ErrorInfo."\n",FILE_APPEND);
            $message = $mail->ErrorInfo;
            // logEmailSendIblock($to, $subject, $message, $additional_headers, $additional_parameters);
            return false;
            exit;
        }
        // logEmailSendIblock($to, $subject, $message, $additional_headers, $additional_parameters);
        return true;
    } else
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();

        $address = explode(',', $to);
        foreach ($address as $addr)
        {
            $mail->addAddress(trim($addr));
        }

        foreach ($ReplyTo as $email)
        {
            if (trim($email)!="")
            {
                $mail->AddReplyTo(trim($email));
            }
        }

        foreach ($bcc as $email)
        {
            if (trim($email)!="")
            {
                $mail->addBCC(trim($email));
            }
        }

        $mail->CharSet = 'UTF-8';
        $mail->Host = $host; // SMTP server example
        $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
        //$mail->SMTPSecure = 'tls';
        $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->Port = $port; // set the SMTP port for the GMAIL server
        $mail->Username = $user; // SMTP account username example
        $mail->Password = $pass; // SMTP account password example
        $mail->From = $from;
        $mail->FromName = $fromName;

        $mail->Subject = $subject;
        $mail->Body = $messageHTML;

        foreach ($filesSRCs as $file)
        {
            $mail->AddAttachment($_SERVER["DOCUMENT_ROOT"].$file);
        }

        if (!$mail->send())
        {
            // file_put_contents($_SERVER["DOCUMENT_ROOT"]."/_asd.log","\nError: ".$mail->ErrorInfo."\n",FILE_APPEND);
            $message = $mail->ErrorInfo;
            // logEmailSendIblock($to, $subject, $message, $additional_headers, $additional_parameters);
            return false;
            exit;
        }
        // logEmailSendIblock($to, $subject, $message, $additional_headers, $additional_parameters);
        return true;
    }
    // logEmailSendIblock($to, $subject, $message, $additional_headers, $additional_parameters);
    return false;
}

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "SendEmailForCompanyAndDealModeration");
function SendEmailForCompanyAndDealModeration(&$arFields){
    if($arFields['IBLOCK_ID'] == 8 || $arFields['IBLOCK_ID'] == 3){
        $check = false;
        if($arFields["ACTIVE"] == "Y" || $arFields['IBLOCK_ID'] == 3){
            $res = CIBlockElement::GetList(Array(), array("ID" => $arFields['ID']), false, false, array("ID", "IBLOCK_ID", "ACTIVE", "PROPERTY_MODERATION"));
            if($El = $res->GetNext())
            {
                if($El['ACTIVE'] == "N" || $arFields['IBLOCK_ID'] == 3){
                    if($arFields['IBLOCK_ID'] == 3){
                        if($arFields['PROPERTY_VALUES'][62][0]['VALUE'] == 7 && $El['PROPERTY_MODERATION_VALUE'] != 'Y'){
                            $check = true;
                            $type = "ваше предложение успешно размещено";
                            $theme = "Ваше предложение опубликовано";
                            $link = "https://anypact.ru/pacts/view_pact/?ELEMENT_ID=".$arFields['ID'];
                        }
                    }else{
                        $check = true;
                        $type = "ваша компания/ИП успешно размещена";
                        $theme = "Ваша компания/ИП прошла проверку";
                        $link = "https://anypact.ru/profile_user/?ID=".$arFields['ID']."&type=company";
                    }
                }
            }
        }
        if($check){
            $rsEl = CIBlockElement::GetList(Array(), array("ID" => $arFields['ID']), false, false, array("ID", "CREATED_BY"));
            if($el = $rsEl->GetNext())
            {
                $rsUser = CUser::GetList($order = array('LAST_NAME' => 'asc', 'NAME'=> 'asc'), $tmp = "asc", array("ID" => $el['CREATED_BY']), [ 'FIELDS' => ['ID', 'EMAIL']]);
                if($user = $rsUser->getNext())
                {
                    $CNotification = new CNotification();
                    if($type == "ваше предложение успешно размещено"){
                        $not_text = "Ваше [URL=$link]предложение[/URL] опубликовано";
                    }else{
                        $not_text = "Ваша [URL=$link]компания/ИП[/URL] прошла проверку";
                    }
                    $CNotification -> Add(array("USER_ID" => $user['ID'], "TEXT" => $not_text, "IS_SYSTEM" => "Y"));
                    CEvent::Send("MODERATION_COMPLETE", "s1", array("EMAIL" => $user['EMAIL'], "TYPE" => $type, "LINK" => $link, "THEME" => $theme));
                }
            }
        }
    }
    if($arFields['IBLOCK_ID'] == 8){
        if($arFields["ACTIVE"] == "Y"){
            $rsEl = CIBlockElement::GetList(Array(), array("ID" => $arFields['ID']), false, false, array("ID", "IBLOCK_ID", "CREATED_BY", "ACTIVE"));
            if($El = $rsEl->GetNext())
            {
                if($El['ACTIVE'] == "N"){
                    $rsUser = CUser::GetList($order = array('LAST_NAME' => 'asc', 'NAME'=> 'asc'), $tmp = "asc", array("ID" => $El['CREATED_BY']), [ 'FIELDS' => ['ID', 'EMAIL']]);
                    if($user = $rsUser->getNext())
                    {
                        $CNotification = new CNotification();
                        $CNotification -> Add(array("USER_ID" => $user['ID'], "TEXT" => "Ваша [URL=https://anypact.ru/profile_user/?ID=".$arFields['ID']."&type=company]компания/ИП[/URL] прошла проверку", "IS_SYSTEM" => "Y"));
                        CEvent::Send("MODERATION_COMPLETE", "s1", array("EMAIL" => $user['EMAIL'], "TYPE" => "ваша компания/ИП успешно размещена", "LINK" => "https://anypact.ru/profile_user/?ID=".$arFields['ID']."&type=company", "THEME" => "Ваша компания/ИП прошла проверку"));
                    }
                }
            }
        }
    }
}

AddEventHandler("iblock", "OnIBlockElementSetPropertyValuesEx", "SendEmailForDealModeration");
function SendEmailForDealModeration($ELEMENT_ID, $IBLOCK_ID, $PROPERTY_VALUES, $propertyList, $arDBProps){
    if($IBLOCK_ID == 3){
        if($PROPERTY_VALUES["MODERATION"] == "7"){
            $res = CIBlockElement::GetList(Array(), array("ID" => $ELEMENT_ID), false, false, array("ID", "IBLOCK_ID", "CREATED_BY", "PROPERTY_MODERATION"));
            if($El = $res->GetNext())
            {
                if($El['PROPERTY_MODERATION_VALUE'] != 'Y'){
                    $rsUser = CUser::GetList($order = array('LAST_NAME' => 'asc', 'NAME'=> 'asc'), $tmp = "asc", array("ID" => $El['CREATED_BY']), [ 'FIELDS' => ['ID', 'EMAIL']]);
                    if($user = $rsUser->getNext())
                    {
                        $CNotification = new CNotification();
                        $CNotification -> Add(array("USER_ID" => $user['ID'], "TEXT" => "Ваше [URL=https://anypact.ru/pacts/view_pact/?ELEMENT_ID=".$El['ID']."]предложение[/URL] опубликовано", "IS_SYSTEM" => "Y"));
                        CEvent::Send("MODERATION_COMPLETE", "s1", array("EMAIL" => $user['EMAIL'], "TYPE" => "ваше предложение успешно размещено", "LINK" => "https://anypact.ru/pacts/view_pact/?ELEMENT_ID=".$El['ID'], "THEME" => "Ваше предложение опубликовано"));
                    }
                }
            }
        }
    }
}

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");
function OnBeforeUserRegisterHandler(&$arFields)
{
    global $APPLICATION;
    if(!check_bitrix_sessid() || (!empty($_REQUEST['fax'])) || (!isset($_REQUEST['fax']))){
        $APPLICATION->ThrowException('Ошибка регистрации.');
        return false;
    }
    if(!empty($arFields['PERSONAL_PHONE'])){
        if($_SESSION['PHONE_NUMBER_CHECK'] == "Y" && !empty($_SESSION['PHONE_NUMBER']) && $_SESSION['PHONE_NUMBER'] == str_replace(array(" ", "-", "(", ")"), "", $arFields['PERSONAL_PHONE'])){
            $_SESSION['PHONE_NUMBER_CHECK'] = "";
            $_SESSION['PHONE_NUMBER'] = "";
            $user = new CUser;
            $filter = Array("PERSONAL_PHONE" => $arFields["PERSONAL_PHONE"]);
            $rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter);
            while($arUser = $rsUser->Fetch()){
                $user->Update($arUser['ID'], array("PERSONAL_PHONE" => false));
            }
        }else{
            $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("PERSONAL_PHONE" => $arFields['PERSONAL_PHONE']), array('FIELDS' => array("ID")));
            if($array = $res -> fetch()){
                return false;
            }
        }
    }
}
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");
function OnAfterUserRegisterHandler(&$arFields)
{
    CBitrixComponent::clearComponentCache('nfksber:user.list');
}

AddEventHandler('main', 'OnEpilog', 'onEpilog');
function onEpilog() {
    global $APPLICATION;
    //301 редирект со старых новостей
    $curPage = $GLOBALS['APPLICATION']->GetCurPage();
    $curDir = $APPLICATION->GetCurDir();
    if (stripos($curPage, '/pacts/') !== false || $curPage == '/pacts/view_pact/') {
        CModule::IncludeModule("iblock");
        if (!empty($_GET["SECTION_ID"]) && stripos($curPage, '/pacts/') !== false) {
            $arFilter = Array("IBLOCK_ID"=>3, "ID" => $_GET["SECTION_ID"]);
            $arSelect = Array("ID", "SECTION_PAGE_URL");
            $arNavParams = Array("nPageSize" => 1);
            $rsSections = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, $arNavParams);
            if ($arSect = $rsSections->GetNext())  {
                if ($curDir != $arSect["~SECTION_PAGE_URL"]) {
                    localredirect($arSect["~SECTION_PAGE_URL"], false, '301 Moved permanently');
                }
            }

        } elseif (!empty($_GET["ELEMENT_ID"]) && $curPage == '/pacts/view_pact/') {
            $arFilter = Array("IBLOCK_ID"=>3, "ID" => $_GET["ELEMENT_ID"]);
            $rsEl = CIBlockElement::GetList(
                array(),
                $arFilter,
                false,
                false,
                Array("ID", "DETAIL_PAGE_URL")
            );
            if ($obEl = $rsEl->GetNextElement()) {
                $arEl = $obEl->GetFields();
                if ($curDir != $arEl["~DETAIL_PAGE_URL"]) {
                    localredirect($arEl["~DETAIL_PAGE_URL"], false, '301 Moved permanently');
                }
            }
        }
    }
}


// Монета пополнение счета, обновление баланса
// if($_REQUEST['SuccessfulDebit'] == "Y" && !empty($_REQUEST['MNT_ID']) && !empty($_REQUEST['MNT_OPERATION_ID']) && !empty($_REQUEST['MNT_TRANSACTION_ID'])){
//     require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CMoneta.php");
//     $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("UF_MONETA_ACCOUNT_ID" => $_REQUEST['MNT_ID']), array('FIELDS' => array("ID")));
//     if($arUser = $res->Fetch()){
//         $balance = CMoneta::GetBalance($arUser['UF_MONETA_ACCOUNT_ID']);
//         if($balance['STATUS'] == "SUCCESS" && $arUser['UF_MONETA_BALANCE'] != $balance['DATA']){
//             $CUser = new CUser;
//             $CUser -> Update($arUser['ID'], array("UF_MONETA_BALANCE" => $balance['DATA'], "UF_DATE_MODIFY" => date("d.m.Y H:i:s")));
//         }
//     }
//     CMoneta::updateHLOperation($_REQUEST['MNT_TRANSACTION_ID'], "SUCCESS");
// }
// if($_REQUEST['FailedDebit'] == "Y" && !empty($_REQUEST['MNT_ID']) && !empty($_REQUEST['MNT_OPERATION_ID']) && !empty($_REQUEST['MNT_TRANSACTION_ID'])){
//     require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CMoneta.php");
//     CMoneta::updateHLOperation($_REQUEST['MNT_TRANSACTION_ID'], "ERROR");
// }