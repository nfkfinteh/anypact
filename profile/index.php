<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || Мой профиль");
// проверяем авторизован ли пользователь
global $USER;

if ($USER->IsAuthorized() || ($_GET["change_password"] == "yes" && !empty($_GET["USER_CHECKWORD"]) && !empty($_GET["USER_LOGIN"]))){

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
        if( isset( $info['user_docs']['elements'] ) > 0 && $info['user_info']['trusted']){

            $info_form = $info;
            $number_pass = $info['user_docs']['elements'][0]['series']." ".$info['user_docs']['elements'][0]['number'] ;
            $pass_by = "Выдан: ".$info['user_docs']['elements'][0]['issuedBy'];

            // проверяем ЕСИА ид на наличие в базе, что бы не было регистраций на нескольких пользователей
            $filterESIAID = array(
                "UF_ESIA_ID" => $info['user_id']
            );
            $GET_USER_ESIA_ID = CUser::GetList(($by="ID"), ($order="DESC"), $filterESIAID);
            $Users_have_esia_id = $GET_USER_ESIA_ID->Fetch();
            if(empty($Users_have_esia_id["ID"])){            
                // если все ок то меняем реквизиты пользователю
                $fields = Array(
                    "UF_ESIA_AUT" => 1,
                    "UF_ETAG_ESIA" => $info['user_info']['eTag'],
                    "UF_SPASSPORT" => (int) $info['user_docs']['elements'][0]['series'],
                    "UF_NPASSPORT" => (int) $info['user_docs']['elements'][0]['number'],
                    "UF_DATA_PASSPORT" => $info['user_docs']['elements'][0]['issueDate'],
                    "UF_KEM_VPASSPORT" => $pass_by,
                    "LAST_NAME" => mb_convert_case($info['user_info']['lastName'], MB_CASE_TITLE), // Фамилия
                    "NAME" => mb_convert_case($info['user_info']['firstName'], MB_CASE_TITLE), // Имя
                    "SECOND_NAME" => mb_convert_case($info['user_info']['middleName'], MB_CASE_TITLE), // Отчество
                    "UF_PASSPORT" => $number_pass,
                    "UF_ESIA_ID" => $info['user_id']
                );        
                $USER->Update($USER->GetID(), $fields);
                $arGroups[] = 6; // ID группы которые авторизовались через ЕСИА
                CUser::SetUserGroup($USER->GetID(), $arGroups);
                header("Refresh: 0");
            }else {            
                $fields = Array(
                    "UF_ESIA_ERROR" => "Пользователь использующий этот аккаунт госуслуг уже зареистрирован."
                );        
                $USER->Update($USER->GetID(), $fields);                   
            }
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
<?
}else{
?>
    <h2>Стать участником</h2>
    <div class="short-divider"></div>
    <div class="row">
        <div class="col-md-6 order-2 order-md-1">
            Регистрация, авторизация и заключение договоров на площадке AnyPact проходят в режиме онлайн.
            Для заключения сделок вам понадобится подтвержденная учетная запись на портале Госуслуг.
            Подтвердить учетную запись портала Госуслуг можно в любом Многофункциональном центре Вашего города.
            <button class="btn btn-nfk send-btn new-reg-button" id="open_reg_form">Зарегистрироваться</button>
        </div>
        <div class="col-md-6 order-2 order-md-1">
            <?/*<div  <?if(!$USER->IsAuthorized()):?>id="open_reg_form"<?endif?>>
                <img src="<?=SITE_TEMPLATE_PATH?>/image/img_reg_us.png" alt="Подпись" style="max-width: 100%">
            </div>*/?>
            <div class="new-auth">
                <div class="new-auth-block">
                    <form name="system_auth_form" method="post" target="_top" action="">
                        <!--<input type="hidden" name="AUTH_FORM" value="Y" />
                        <input type="hidden" name="TYPE" value="AUTH" /> -->
                        <div class="user_credentials"><img src="<?=SITE_TEMPLATE_PATH?>/image/icons8_user_credentials_100px.png"></div>
                        <h2>Авторизация</h2>
                        <!--Логин-->
                        <p>Логин</p>
                        <input type="text" name="USER_LOGIN_ERROR" class="regpopup_content_form_input" data-mess="" value="" id="user_aut_login_main" placeholder="" />
                        <!--Пароль-->
                        <p>Пароль</p>
                        <input type="password" name="USER_PASSWORD" class="regpopup_content_form_input"  autocomplete="off" id="user_aut_pass_main" placeholder=""/>    
                        <div id="message_error_aut_main"></div>
                        <a href="javascript:undefined" class="regpopup_content_form_submit" id="submit_button_aut_user_main"><?=GetMessage("AUTH_LOGIN_BUTTON")?></a>
                        <p class="text-center forgetpass-text">Забыли пароль? <a href="#" id="open_fgpw_form">Восстановить</a></p>
                    </form>
                </div>
                <div class="lock-img">
                    <img src="<?=SITE_TEMPLATE_PATH?>/image/icons8_password_check_127px_1.png">
                </div>
            </div>
        </div>
    </div>
<?
}
?>
</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
