<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
// проверяем авторизован ли пользователь
global $USER;

if ($USER->IsAuthorized()){
    session_start();

    //require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';
    //EsiaLogger::DumpEnviroment( 'open_gu' );

    $_SESSION['id_esia']="";
    unset($_SESSION['id_esia']);

    $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia";
    include $urlEsia."/Esia.php";
    include $urlEsia."/EsiaOmniAuth_t.php";

    $keys_dir = $_SERVER['DOCUMENT_ROOT'] . '/esia/sert';    
    $config = array(
    "site" => "https://esia.gosuslugi.ru/", //esia portal
    "redirect_uri" => "http://anypact.nfksber.ru/profile/",  //callback url
    //"redirect_uri" => "http://nfksber.game-server.xyz:789/esia/work1.php",  //callback url
    "pkey_path"  => $keys_dir."/secret_NFKS01211.key",
    "cert_path"  => $keys_dir."/cert_NFKS01211.crt",
    "client_id" => "NFKS01211",
    "scope" => "openid fullname id_doc"
    );
    
    $esia = new EsiaOmniAuth($config);
    $esia->create();
} else {
    echo "редирект на ..";
}
?>