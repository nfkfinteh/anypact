<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
// проверяем авторизован ли пользователь
global $USER;

if ($USER->IsAuthorized()){
    
    $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia";
    include $urlEsia."/Esia.php";
    include $urlEsia."/EsiaOmniAuth_t.php";
    
    $keys_dir = $_SERVER['DOCUMENT_ROOT'] . '/esia/sert';
   
    $config = array(
    "site" => "https://esia.gosuslugi.ru/", //esia portal
    "redirect_uri" => "http://anypact.nfksber.ru/profile/",  //callback url
    "pkey_path"  => $keys_dir."/secret_NFKS01211.key",
    "cert_path"  => $keys_dir."/cert_NFKS01211.crt",
    "client_id" => "NFKS01211",
    "scope" => "openid fullname id_doc"
    );
    print_r($_GET);
    
    $esia = new EsiaOmniAuth($config);
    print_r($esia);
    echo "<br>========================";
    $info   = array();
    $token  = $esia->get_token($_GET['code']);
    $info   = $esia->get_info($token);
    print_r($info);
    echo "<br>========================";   
}
?>
	<div class="container">
		<?$APPLICATION->IncludeComponent("bitrix:main.profile","anypact",Array(
        "USER_PROPERTY_NAME" => "",
        "SET_TITLE" => "Y", 
        "AJAX_MODE" => "N", 
        "USER_PROPERTY" => Array("UF_PASSPORT"), 
        "SEND_INFO" => "Y", 
        "CHECK_RIGHTS" => "Y",  
        "AJAX_OPTION_JUMP" => "N", 
        "AJAX_OPTION_STYLE" => "Y", 
        "AJAX_OPTION_HISTORY" => "N",
        "ESIA_RESPONSE" => $info
    )
);?> 
	</div>
<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>