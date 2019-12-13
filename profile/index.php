<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
// проверяем авторизован ли пользователь
global $USER;

if ($USER->IsAuthorized()){

    $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia";
    include $urlEsia."/Esia.php";
    include $urlEsia."/EsiaOmniAuth_t.php";
    include $urlEsia."/config_esia.php";

    $config_esia = new ConfigESIA();

    $esia = new EsiaOmniAuth($config_esia->config);
    $info   = array();
    $token  = $esia->get_token($_GET['code']);
    $info   = $esia->get_info($token);
    $info_form = array();
    // авторизуем пользователя    

    if(count($info['user_docs']['elements']) > 0 && $info['user_info']['trusted']){

        $info_form = $info;
        $number_pass = $info['user_docs']['elements'][0]['series']." ".$info['user_docs']['elements'][0]['number'] ;
        $pass_by = "Выдан: ".$info['user_docs']['elements'][0]['issuedBy'];

        $fields = Array(
            "UF_ESIA_AUT" => 1,
            "UF_ETAG_ESIA" => $info['user_info']['eTag'],
            "UF_SPASSPORT" => (int) $info['user_docs']['elements'][0]['series'],
            "UF_NPASSPORT" => (int) $info['user_docs']['elements'][0]['number'],
            "UF_DATA_PASSPORT" => $info['user_docs']['elements'][0]['issueDate'],
            "UF_KEM_VPASSPORT" => $pass_by,
            "LAST_NAME" => $info['user_info']['lastName'], // Фамилия
            "NAME" => $info['user_info']['firstName'], // Имя
            "SECOND_NAME" => $info['user_info']['middleName'], // Отчество
            "UF_PASSPORT" => $number_pass,
            "UF_ESIA_ID" => $info['user_id']
        );
        //echo "<pre>";
        //print_r($info);
        //echo "</pre>";
        $USER->Update($USER->GetID(), $fields);
        header("Refresh: 0");
    }

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
                "ESIA_RESPONSE" => $info_form
            )
        );?>
    </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>