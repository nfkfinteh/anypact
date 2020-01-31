<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
// проверяем авторизован ли пользователь
global $USER;
echo $USER->GetParam("NAME");
// урл по которому пришел пользователь, получаем через get  и дешифруем
print_r($_GET);
$ReturnURL = base64_decode($_GET['returnurl']);

// если пользователь пришел с редактирования контракта нужно ID записи добавить в GET
if(!empty($_GET['ID_SENDITEM'])){
    $URL_REF = $ReturnURL.'&ID_SENDITEM='.$_GET['ID_SENDITEM'];    
}else {
    $URL_REF = $ReturnURL;
}

echo "<br> пришли с этого адреса ".$URL_REF;
//$URL_REF = 'https://anypact.ru/my_pacts/';

//if ($USER->IsAuthorized()){
    session_start();

    //require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';
    //EsiaLogger::DumpEnviroment( 'open_gu' );
   /* 
    $_SESSION['id_esia']="";
    unset($_SESSION['id_esia']);

    $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia";
    include $urlEsia."/Esia.php";
    include $urlEsia."/EsiaOmniAuth_t.php";

    $keys_dir = $_SERVER['DOCUMENT_ROOT'] . '/esia/sert';    
    $config = array(
        "site"          => "https://esia.gosuslugi.ru/", //esia portal
        "redirect_uri"  => $URL_REF,  //callback url
        "pkey_path"     => $keys_dir."/secret_NFKS01211.key",
        "cert_path"     => $keys_dir."/cert_NFKS01211.crt",
        "client_id"     => "NFKS01211",
        "scope"         => "openid fullname id_doc"
    );
    
    $esia = new EsiaOmniAuth($config);
    $esia->create(); 
//} else {
    echo "редирект на ..";
//}
*/

?>