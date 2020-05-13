<?php 
session_start();

$_SESSION['id_esia']="";
 
unset($_SESSION['id_esia']);

$urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia_test";

include $urlEsia."/Esia.php";
include $urlEsia."/EsiaOmniAuth.php";
 
echo $urlEsia."/EsiaOmniAuth.php";

$keys_dir = $urlEsia.'/sert';  
 
$config = array(
    "site" => "https://esia.gosuslugi.ru/", //esia portal
    "redirect_uri" => "http://anypact.ru/profile/",  //callback url
    "pkey_path"  => $keys_dir."/secret.key",
    "cert_path"  => $keys_dir."/cert.crt",    
    "client_id" => "04VS01",    
    "scope" => "openid fullname id_doc"
);

$esia = new EsiaOmniAuth($config);
$esia->create();