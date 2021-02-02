<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ( !empty( $_GET["code"] ) ){
    $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia_test";
    include $urlEsia."/Esia.php";
    include $urlEsia."/EsiaOmniAuth.php";
    include $urlEsia."/config_esia.php";

    $config_esia = new ConfigESIA();

    $esia = new EsiaOmniAuth($config_esia->config);
    $info   = array();
    $token  = $esia->get_token($_GET['code']);
    $info   = $esia->get_info($token);
    $info_form = array();
    // авторизуем пользователя    
    $error_ESIA = "";
    //print_r($info);
    if( isset( $info['user_docs']['elements'] ) > 0 && $info['user_info']['trusted'] && $info['user_docs']['elements'][0]['vrfStu'] == "VERIFIED"){

        $info_form = $info;
        $number_pass = $info['user_docs']['elements'][0]['series']." ".$info['user_docs']['elements'][0]['number'] ;
        $pass_by = "Выдан: ".$info['user_docs']['elements'][0]['issuedBy'];

        // проверяем ЕСИА ид на наличие в базе, что бы не было регистраций на нескольких пользователей
        $filterESIAID = array(
            "UF_ESIA_ID" => $info['user_id']
        );
        $GET_USER_ESIA_ID = CUser::GetList(($by="ID"), ($order="DESC"), $filterESIAID);
        $Users_have_esia_id = $GET_USER_ESIA_ID->Fetch();
        if(!empty($Users_have_esia_id["ID"])){            
            global $USER;
            $USER ->Authorize($Users_have_esia_id["ID"]);
            header("Refresh: 0");
        } else {
            if($_GET['open_auth_popup'] == "Y")
                echo '<script>document.getElementById("regpopup_bg").style.display = "block";document.getElementById("message_error_aut").innerHTML = "&#8226; Аккаунт с указанным пользователем Госуслуг не найден."; if(document.getElementById("message_error_aut_main") !== null) document.getElementById("message_error_aut_main").innerHTML = "&#8226; Аккаунт с указанным пользователем Госуслуг не найден.";</script>';
            else if($_GET['open_fgp_popup'] == "Y")
                echo '<script>document.getElementById("regpopup_bg").style.display = "block";document.getElementById("regpopup_autarisation").style.display = "none"; document.getElementById("regpopup_btn_fgpw").style.display = "block";document.getElementById("message_error_forget_pass").innerHTML = "&#8226; Аккаунт с указанным пользователем Госуслуг не найден.";</script>';
            else
                echo '<script>document.getElementById("message_error_aut").innerHTML = "&#8226; Аккаунт с указанным пользователем Госуслуг не найден."; document.getElementById("message_error_aut_main").innerHTML = "&#8226; Аккаунт с указанным пользователем Госуслуг не найден.";</script>';
        }
    }
}
?>