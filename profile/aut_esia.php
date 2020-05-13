<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $USER;
// проверяем авторизован ли пользователь
echo $USER->GetParam("NAME");
// урл по которому пришел пользователь, получаем через get  и дешифруем

print_r($_GET);
$domane = 'https://anypact.ru';
$ReturnURL = base64_decode($_GET['returnurl']);
$ReturnURL = $domane.$ReturnURL;
echo $ReturnURL;

// если пользователь пришел с редактирования контракта нужно ID записи добавить в GET
if(!empty($_GET['ID_SENDITEM'])){
    $URL_REF = $ReturnURL.'?ID_SENDITEM='.$_GET['ID_SENDITEM'];    
}else {
    $URL_REF = $ReturnURL;
}

echo "<br> пришли с этого адреса ".$URL_REF;
//$URL_REF = 'https://anypact.ru/my_pacts/';

$UserTest = 1;
//if ($USER->IsAuthorized()){
if ( $UserTest == 1 ) {
    session_start();

    //require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';
    //EsiaLogger::DumpEnviroment( 'open_gu' );
   
    $_SESSION['id_esia']="";
    unset($_SESSION['id_esia']);

    $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia";
    include $urlEsia."/Esia.php";
    include $urlEsia."/EsiaOmniAuth.php";

    $keys_dir = $_SERVER['DOCUMENT_ROOT'] . '/esia/sert';    
    $config = array(
        "site" => "https://esia.gosuslugi.ru/", //esia portal
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        "redirect_uri" => "http://anypact.nfksber.ru/profile/",  //callback url
=======
        "redirect_uri" => "https://anypact.ru/profile/",  //callback url
>>>>>>> b198de1e188ed3e6c903dcf530de588b352009f8
=======
        "redirect_uri" => "http://anypact.ru/profile/",  //callback url
>>>>>>> be49c0fe4b0954317354b74510fb2459109f317a
=======
        "redirect_uri" => "https://anypact.ru/profile/",  //callback url
>>>>>>> e7ad65d63696f4d0d43f0a8268a7fea697a564ff
        "pkey_path"  => $keys_dir."/secret.key",
        "cert_path"  => $keys_dir."/cert.crt",        
        "client_id" => "04VS01",    
        "scope" => "openid fullname id_doc"
    );
    
    $esia = new EsiaOmniAuth($config);
    $esia->create(); 
} else {
    echo "Извините вы не авторизованы.";
}
