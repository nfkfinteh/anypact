<h1>ЕСИА</h1>

<?php
echo "1/ =================================================";
$out="";
$burl="http://dev.nfksber.ru/esia/work1.php";
error_reporting(0);
ini_set('display_errors', 0);

ob_start();
error_reporting(0);
include "Esia.php";
include "EsiaOmniAuth_t.php";
$keys_dir = $_SERVER['DOCUMENT_ROOT'] . '/esia/sert';

$config = array(
"site" => "https://esia.gosuslugi.ru/", //esia portal
"redirect_uri" => "http://anypact.nfksber.ru/esia/",  //callback url
"pkey_path"  => $keys_dir."/secret_NFKS01211.key",
"cert_path"  => $keys_dir."/cert_NFKS01211.crt",
"client_id" => "NFKS01211",
"scope" => "openid fullname id_doc"
 );

$esia = new EsiaOmniAuth($config);
echo "<br>2/ =================================================";
if (!isset($_GET['action'])){
    echo "<br>3/ =================================================";
    print_r($_GET);
    print_r($_SESSION);
    
        echo "4/ =================================================";
        $token = $esia->get_token($_GET['code']);
        $info = $esia->get_info($token);
        print_r($info);
        \Logger::AddText( $info, 'ESIA/Info' );

            echo var_dump($info);

    ### проверка подтверждена или нет запись 	
            echo  "фамилия".$info["user_info"]["firstName"];
            $_SESSION['second_name']=$info["user_info"]["middleName"];
            $_SESSION['last_name']=$info["user_info"]["lastName"];
            $inn=$info["user_info"]["inn"];
            $snils=$info["user_info"]["snils"];
            $_SESSION['birth_day_esia']=$info["user_info"]["birthDate"];
            $birth_day=ret_date($info["user_info"]["birthDate"]);
            $_SESSION['birth_place']=$info["user_info"]["birthPlace"];
            $_SESSION['citizen']=$info["user_info"]["citizenship"];
    
}
?>