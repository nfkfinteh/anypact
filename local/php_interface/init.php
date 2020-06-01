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

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\SMTP;
// if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/Exception.php'))
//     require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/Exception.php';
// if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/PHPMailer.php'))
//     require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/PHPMailer.php';
// if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/SMTP.php'))
//     require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/PHPMailer/SMTP.php';

function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters)
{
    smtp_mail('post.nfksber.ru', 587, 'info@anypact.ru', 'PKmR5g3k42', 'info@anypact.ru', 'AnyPact', $to, $subject, $message, 'ok');
//     define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/custom_mail.log");
//     // Создаем письмо
//     $mail = new PHPMailer();
// 	$mail->SMTPDebug = true;
// 	$mail->isSMTP();
// 	$mail->CharSet  = 'UTF-8';
// 	$mail->setLanguage('ru');
//     $mail->Host   = 'post.nfksber.ru';  // Адрес SMTP сервера
//     $mail->SMTPAuth   = true;          // Enable SMTP authentication
//     $mail->Username   = 'info@anypact.ru';       // ваше имя пользователя (без домена и @)
//     $mail->Password   = 'PKmR5g3k42';    // ваш пароль
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // шифрование ssl
//     $mail->Port   = 587;
//     $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
//     $mail->Debugoutput = function($str, $level) {
//         AddMessage2Log("$level: $str", "SMTP Error");
//     };

//     $message_new = explode("Content-Type: text/plain; charset=UTF-8
// Content-Transfer-Encoding: 8bit", $message)[1];

//     list($message_alt, $message_html) = explode("Content-Type: text/html; charset=UTF-8
// Content-Transfer-Encoding: 8bit", $message_new);

//     if(empty($message_html)){
//         $message_html = $message;
//     }else{
//         $message_html = substr($message_html, 0, -26);
//         $message_alt = substr($message_alt, 0, -24);
//     }

//     $mail->From = 'info@anypact.ru';
// 	$mail->FromName = 'AnyPact';
// 	$mail->isHTML(true);
// 	$mail->Subject = $subject;
//     $mail->Body = $message_html;
//     $mail->AltBody = $message_alt;
//     $mail->addCustomHeader($additional_headers);
// 	$mail->addAddress($to);
// 	if(!$mail->send()) {
//         AddMessage2Log($mail->ErrorInfo, "ErrorInfo");
//     }
// 	$mail->clearAddresses();
// 	$mail->ClearCustomHeaders();

}
/**
* smtp_mail() - Отправка электронной почты с авторизацией через SMTP сервер
* v1.0.0
*
* smtp_read(); smtp_write() - вторичные
* Подключаемая пользовательская функция для отправки сообщений по электронной почте 
* с использованием аутентификации пользователя на почтовом сервере SMTP.
* Рекомендуется использовать в том случае, если стандартная функция mail()
* на хостинге работает неправильно или с ошибками. Данная функция корректно 
* работает на PHP 4 и выше с установленным модулем расширения php_sockets
* 
*
* http://koks-host.ru
* Оригинальная кодировка UTF-8
*/

smtp_mail('post.nfksber.ru', 587, 'info@anypact.ru', 'PKmR5g3k42', 'info@anypact.ru', 'AnyPact', 'test.mail.bitrix13@yandex.ru', 'Тема', 'Тест', 'ЗБС');

function smtp_mail ($smtp,			// SMTP-сервер
          $port,			// порт SMTP-сервера
          $login,			// имя пользователя для доступа к почтовому ящику
          $password, 		// пароль для доступа к почтовому ящику
          $from,			// адрес электронной почты отправителя
          $from_name,		// имя отправителя
          $to, 			// адрес электронной почты получателя
          $subject, 		// тема сообщения
          $message,		// текст сообщения
          $res)			// сообщение, выводимое при успешной отправке
{	

//    header('Content-Type: text/plain;');	// необязательный параметр, особенно если включаем через include()
//    error_reporting(E_ALL ^ E_WARNING);	// необязательный параметр, включает отображение всех ошибок и предупреждений
//    ob_implicit_flush();					// необязательный параметр, включает неявную очистку

//    блок для других кодировок, отличных от UTF-8
//    $message = iconv("UTF-8","KOI8-R",$message); // конвертируем в koi8-r
//    $message = "Content-Type: text/plain; charset=\"koi8-r\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".$message; // конвертируем в koi8-r
//    $subject=base64_encode(iconv("UTF-8","KOI8-R",$subject)); // конвертируем в koi8-r
//    $subject=base64_encode($subject); // конвертируем в koi8-r

  $from_name = base64_encode($from_name);
  $subject = base64_encode($subject);
  $message = base64_encode($message);
    $message = "Content-Type: text/plain; charset=\"utf-8\"\r\nContent-Transfer-Encoding: base64\r\nUser-Agent: Koks Host Mail Robot\r\nMIME-Version: 1.0\r\n\r\n".$message;
    $subject="=?utf-8?B?{$subject}?=";
    $from_name="=?utf-8?B?{$from_name}?=";

    try {
        
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            throw new Exception('socket_create() failed: '.socket_strerror(socket_last_error())."\n");
        }

        $result = socket_connect($socket, $smtp, $port);
        if ($result === false) {
            throw new Exception('socket_connect() failed: '.socket_strerror(socket_last_error())."\n");
        } 

        smtp_read($socket);
        
        smtp_write($socket, 'EHLO '.$login);
        smtp_read($socket); 
        smtp_write($socket, 'AUTH LOGIN');
        smtp_read($socket);        
        smtp_write($socket, base64_encode($login));
        smtp_read($socket);
        smtp_write($socket, base64_encode($password));
        smtp_read($socket); 
        smtp_write($socket, 'MAIL FROM:<'.$from.'>');
        smtp_read($socket); 
        smtp_write($socket, 'RCPT TO:<'.$to.'>');
        smtp_read($socket); 
        smtp_write($socket, 'DATA');
        smtp_read($socket); 
        $message = "FROM:".$from_name."<".$from.">\r\n".$message; 
        $message = "To: $to\r\n".$message; 
        $message = "Subject: $subject\r\n".$message;

  date_default_timezone_set('UTC');
  $utc = date('r');

        $message = "Date: $utc\r\n".$message;
        smtp_write($socket, $message."\r\n.");
        smtp_read($socket); 
        smtp_write($socket, 'QUIT');
        smtp_read($socket); 
        return $res;
        
    } catch (Exception $e) {
        echo "\nError: ".$e->getMessage();
    }

   
    if (isset($socket)) {
        socket_close($socket);
    }
}

function smtp_read($socket) {
  $read = socket_read($socket, 1024);
        if ($read{0} != '2' && $read{0} != '3') {
            if (!empty($read)) {
                throw new Exception('SMTP failed: '.$read."\n");
            } else {
                throw new Exception('Unknown error'."\n");
            }
        }
}
    
function smtp_write($socket, $msg) {
  $msg = $msg."\r\n";
  socket_write($socket, $msg, strlen($msg));
}
