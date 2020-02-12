<?php
if (!defined ("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
} 
global $USER;
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>" class="<?$APPLICATION->ShowProperty('HtmlClass');?>">
<head>
    <?$APPLICATION->ShowProperty('AfterHeadOpen');?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><? $APPLICATION->ShowTitle(); ?></title>
    <?/*<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>*/?>
    
    <!-- Google Fonts -->
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-open-sans" data-protected="true" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&subset=cyrillic">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-roboto" data-protected="true" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&subset=cyrillic,cyrillic-ext,latin-ext">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-roboto-slab" data-protected="true" href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700&subset=cyrillic,cyrillic-ext,latin-ext">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-ek-mukta" data-protected="true" href="https://fonts.googleapis.com/css?family=Ek+Mukta:400,600,700">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-montserrat" data-protected="true" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700,900&subset=cyrillic">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-alegreya-sans" data-protected="true" href="https://fonts.googleapis.com/css?family=Alegreya+Sans:400,700,900&subset=cyrillic-ext,latin-ext">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-cormorant-infant" data-protected="true" href="https://fonts.googleapis.com/css?family=Cormorant+Infant:400,400i,600,600i,700,700i&subset=cyrillic-ext,latin-ext">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-pt-sans-caption" data-protected="true" href="https://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700&subset=cyrillic-ext,latin-ext">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-pt-sans-narrow" data-protected="true" href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700|PT+Sans:400,700&subset=cyrillic-ext,latin-ext">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-pt-sans" data-protected="true" href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=cyrillic-ext,latin-ext">
    <link rel="preload" as="style" onload="this.removeAttribute('onload');this.rel='stylesheet'" data-font="g-font-lobster" data-protected="true" href="https://fonts.googleapis.com/css?family=Lobster&subset=cyrillic-ext,latin-ext">    
    <noscript>
        <link data-font="g-font-open-sans" data-protected="true" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&subset=cyrillic" rel="stylesheet">
    </noscript>

    <?    
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/bootstrap.css'); 
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/template_style.css'); 
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/slider-pro.min.css'); 
    $APPLICATION->SetAdditionalCSS('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/owl.carousel.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/owl.theme.default.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/module/cropper/cropper.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/module/selectize/selectize.css');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/jquery/jquery.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/selectize/selectize.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/bootstrap.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.sliderPro.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/owl.carousel.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/cropper/cropper.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/jquery.maskedinput/jquery.inputmask.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/jquery.validation/jquery.validate.min.js');

    CJSCore::Init(array('popup', 'date'));

    $APPLICATION->ShowHead();
    //$APPLICATION->ShowProperty('MetaOG');
    ?>
</head>
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<?$getGeo = $APPLICATION->IncludeComponent("nfksber:location","",Array(
        'CACHE_TYPE'=>'Y'
));?>
<!--Окно регистрации-->
<noindex>
    <div id="regpopup_bg">
        <div class="container">
        <div class="row align-items-center justify-content-center">            
            <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                <div class="regpopup_win">
                        <div id="regpopup_close">Х</div>
                        <!--Регистрационная форма-->
                        <div class="regpopup_content" id="regpopup_registration" style="display:none;">
                            <h2>Регистрация</h2>
                            <?  
                                ShowMessage($arParams["~AUTH_RESULT"]); 

                                $APPLICATION->IncludeComponent( 
                                "bitrix:main.register", 
                                "anypact", 
                                Array( 
                                    "USER_PROPERTY_NAME" => "", 
                                    "SEF_MODE" => "N", 
                                    "SHOW_FIELDS" => Array(), 
                                    "REQUIRED_FIELDS" => Array(), 
                                    "AUTH" => "Y", 
                                    "USE_BACKURL" => "N", 
                                    "SUCCESS_PAGE" => "/informaciya_o_registracii",//$APPLICATION->GetCurPageParam('',array('backurl')), 
                                    "SET_TITLE" => "N", 
                                    "USER_PROPERTY" => Array()
                                ) 
                                ); 
                            ?>
                        </div>
                        <!--форма авторизации-->
                        <div class="regpopup_autorisation" id="regpopup_autarisation">
                            <h2>Авторизация</h2>
                            <?$APPLICATION->IncludeComponent("bitrix:system.auth.form",
                            "anypact_popup",
                            Array(
                                "REGISTER_URL" => "register.php",
                                "FORGOT_PASSWORD_URL" => "",
                                "PROFILE_URL" => "profile.php",
                                "SHOW_ERRORS" => "Y",
                                "STORE_PASSWORD" => "Y"
                                )
                            );?>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</noindex>
<!--/Окно регистрации-->
    <?
        $page = explode('/', $_SERVER['REQUEST_URI']);
        $class_container = '';
        if(!empty($page[1]) && $page[1] == 'pacts' ){ $class_container = 'bg-russia';}
        if(!empty($page[2]) && $page[2] == 'view_pact' ){ $class_container = '';}
    ?>
<div class="container <?=$class_container?>">
        <!--Шапка-->
        <header class="header" style="width: 100%;">
            <div class="row">
                <div class="col-md-6">
                    <a href="/" class="logo"><img src="<?=SITE_TEMPLATE_PATH?>/image/logo_ap.svg" alt="" style="width: 166px;"></a>
                    <span class="phone">8(800) 200-84-84</span>
                </div>
                <? if ($USER->IsAuthorized()){ ?>
                    <div class="col-md-2">
                        <?if(!empty($getGeo['cityName'])):?>
                            <span class="location"><?=$getGeo['cityName']?></span>
                        <?else:?>
                            <span class="location">Выберите город</span>
                        <?endif?>

                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-4">
                                <!--Кнопка создать новое предложение-->
                                <div class="create-pact-btn">
                                    <a href="/my_pacts/edit_my_pact/?ACTION=ADD"></a>
                                    <div>Создать предложение</div>
                                </div>
                                <!------------>                   
                            </div>
                            <div class="col-md-8">
                                <?                                
                                    $APPLICATION->IncludeComponent("nfksber:profile.widget",
                                    "head",
                                    Array(
                                            'IS_PAGE_MESSAGE' => $APPLICATION->GetCurPage() == '/list_message/view_message/' ? 'Y' : 'N'
                                        )
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                    <?}else {?>
                        <div class="col-md-6">
                            <?if(!empty($getGeo['cityName'])):?>
                                <span class="location"><?=$getGeo['cityName']?></span>
                            <?else:?>
                                <span class="location">Выберите город</span>
                            <?endif?>
                            <button class="btn btn-nfk btn-login" id="reg_button">Регистрация / Вход</button>
                        </div>
                    <?}?>                    
            </div>
        </header>
        <!--Меню навигации-->
        <nav class="navbar navbar-expand-md" style="width: 100%;">
            <?
                $Section = $_GET['SECTION_ID'];
                $APPLICATION->IncludeComponent("nfksber:stepback", 
                "", 
                    Array(
                        "IBLOCK_ID" => "3",
                        "SECTION_ID" => $Section,   
                        )
                );
            ?>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
            <a href="/" class="logo"><img src="/local/templates/anypact/image/logo_ap.svg" alt="" style="width: 150px;"></a>
            <div>
            <? if ($USER->IsAuthorized()){ ?>
                <?
                $APPLICATION->IncludeComponent("nfksber:profile.widget",
                    "head",
                    Array(
                        'IS_PAGE_MESSAGE' => $APPLICATION->GetCurPage() == '/list_message/view_message/' ? 'Y' : 'N'
                    )
                );}
                ?>
            </div>
            <div class="collapse navbar-collapse " id="navbarSupportedContent" style="padding-right: 0;">
                <? // навигационное меню для разных типов пользователей
                    if ($USER->IsAuthorized()){
                        $arUrlMenu = array(
                            '/' => 'Главная',
                            '/my_pacts/' => 'Мои сделки',                               
                            '/pacts/' => 'Все предложения',
                            '/search_people/' => 'Поиск людей',
                            '/list_message/' => 'Сообщения',
                            '/service/' => 'О сервисе',
                            '/help/' => 'Контакты'                            
                        );
                    }else {
                        $arUrlMenu = array(
                            '/' => 'Главная',                               
                            '/service/' => 'О сервисе',
                            '/help/' => 'Поддержка',
                            '#' => 'Регистрация/вход'
                        );
                    }
                    $APPLICATION->IncludeComponent("nfksber:navmenu.head", 
                    "", 
                        Array(
                            "ArURL_MENU"         => $arUrlMenu,
                            )
                    );
                ?>
            </div>
        </nav>
        <!--//Меню навигации-->
        <!--//Шапка-->
