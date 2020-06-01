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

    $arGlobalMenu[] = array(
        'menu_id' => 'anypact',
        'text' => 'AnyPact',
        'title' => 'Настройки сайта AnyPact',
        'sort' => 550,
        'item_id' => 'global_menu_anypact',
        'help_section' => 'anypact',
        'items' => $arItems
    );

    // echo "<pre>";
    // var_dump($arModuleMenu);
    // echo "</pre>";
    
	// $aMenu = array(
    //     "parent_menu" => "global_menu_content",
    //     "section" => "clouds",
    //     "sort" => 150,
    //     "text" => GetMessage("CLO_STORAGE_MENU"),
    //     "title" => GetMessage("CLO_STORAGE_TITLE"),
    //     "url" => "clouds_index.php?lang=".LANGUAGE_ID,
    //     "icon" => "clouds_menu_icon",
    //     "page_icon" => "clouds_page_icon",
    //     "items_id" => "menu_clouds",
    //     "more_url" => array(
	// 	    "clouds_index.php",
	//     ),
	//     "items" => array()
	// );
	
	// $aMenu["items"][] = array(
	// 	"text" => $arBucket["BUCKET"],
	// 	"url" => "clouds_file_list.php?lang=".LANGUAGE_ID."&bucket=".$arBucket["ID"]."&path=/",
	// 	"more_url" => array(
	// 	    "clouds_file_list.php?bucket=".$arBucket["ID"],
	// 	),
	// 	"title" => "",
	// 	"page_icon" => "clouds_page_icon",
	// 	"items_id" => "menu_clouds_bucket_".$arBucket["ID"],
	// 	"module_id" => "clouds",
	// 	"items" => array()
	// );
	
	// if(!empty($aMenu["items"]))
	// $aModuleMenu[] = $aMenu;
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
    // Создаем письмо
    $mail = new PHPMailer();
	$mail->SMTPDebug = true;
	$mail->isSMTP();
	$mail->CharSet  = 'UTF-8';
	$mail->setLanguage('ru');
    $mail->Host   = 'post.nfksber.ru';  // Адрес SMTP сервера
    $mail->SMTPAuth   = true;          // Enable SMTP authentication
    $mail->Username   = 'info@anypact.ru';       // ваше имя пользователя (без домена и @)
    $mail->Password   = 'PKmR5g3k42';    // ваш пароль
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // шифрование ssl
    $mail->Port   = 587;
    $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
    $mail->Debugoutput = function($str, $level) {
        AddMessage2Log("$level: $str", "SMTP Error");
    };

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

    $mail->From = 'info@anypact.ru';
	$mail->FromName = 'AnyPact';
	$mail->isHTML(true);
	$mail->Subject = $subject;
    $mail->Body = $message_html;
    $mail->AltBody = $message_alt;
    $mail->addCustomHeader($additional_headers);
	$mail->addAddress($to);
	if(!$mail->send()) {
        AddMessage2Log($mail->ErrorInfo, "ErrorInfo");
    }
	$mail->clearAddresses();
	$mail->ClearCustomHeaders();

}