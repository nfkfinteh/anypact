<?php define("PREFIX_PATH_404", "/404.php");
include_once 'function.php';

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
        'sort' => 10,
        'url' => 'moderation_deal.php?lang=' . LANGUAGE_ID,
        'text' => 'Модерация сделок',
        'title' => 'Модерация сделок',
        'icon' => 'moderation_deal_menu_icon',
        'page_icon' => 'moderation_deal_page_icon',
        'items_id' => 'menu_moderation_deal',
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
    } else {
        //echo 'Message sent!';
    }
	$mail->clearAddresses();
	$mail->ClearCustomHeaders();

}

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "SendEmailForDealModeration");
function SendEmailForDealModeration(&$arFields){
    if($arFields['IBLOCK_ID'] == 8 || $arFields['IBLOCK_ID'] == 3){
        $check = false;
        if($arFields["ACTIVE"] == "Y"){
            if($arFields['IBLOCK_ID'] == 3){
                if($arFields['PROPERTY_VALUES']['MODERATION'] == 7){
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
        if($check){
            $rsEl = CIBlockElement::GetList(Array(), array("ID" => $arFields['ID']), false, false, array("ID", "CREATED_BY"));
            if($el = $rsEl->GetNext())
            {
                $rsUser = CUser::GetList($order = array('LAST_NAME' => 'asc', 'NAME'=> 'asc'), $tmp = "asc", array("ID" => $el['CREATED_BY']), [ 'FIELDS' => ['ID', 'EMAIL']]);
                if($user = $rsUser->getNext())
                {
                    CEvent::Send("MODERATION_COMPLETE", "s1", array("EMAIL" => $user['EMAIL'], "TYPE" => $type, "LINK" => $link, "THEME" => $theme));
                }
            }
        }
    }
}