<?php
if (!defined ("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER;
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>" class="<?$APPLICATION->ShowProperty('HtmlClass');?>">
<head>
    <?$APPLICATION->ShowProperty('AfterHeadOpen');?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><? $APPLICATION->ShowTitle(); ?></title>
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
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.datetimepicker.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/croppie.css');
    $APPLICATION->SetAdditionalCSS('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/emoji.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/module/lightGallery-master/dist/css/lightgallery.min.css');
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WWQFXKG');</script>
    <!-- End Google Tag Manager -->
    <?
    $APPLICATION->AddHeadString('<script src="https://yastatic.net/share2/share.js" async="async"></script>',true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/jquery/jquery-3.3.1.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/selectize/selectize.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/bootstrap.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.sliderPro.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/owl.carousel.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/cropper/cropper.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/jquery.hotkeys/script.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/jquery.maskedinput/jquery.inputmask.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/jquery.validation/jquery.validate.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.datetimepicker.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/jquery.ui/jquery-ui.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/new_popup.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/croppie.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/config.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/util.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.emojiarea.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/emoji-picker.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/lightGallery-master/dist/js/lightgallery.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/lightGallery-master/modules/lg-zoom.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/lightGallery-master/modules/lg-fullscreen.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/module/lightGallery-master/modules/lg-thumbnail.min.js');

    CJSCore::Init(array('popup', 'date'));

    $APPLICATION->ShowHead();
    ?>
    <link href="<?=SITE_TEMPLATE_PATH?>/css/jquery.datetimepicker.css" type="text/css"  rel="stylesheet" />
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(64629523, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
    });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/64629523" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

</head>
<?
    $page = explode('/', $_SERVER['REQUEST_URI']);
    $class_container = '';
    if(!empty($page[1]) && $page[1] == 'pacts' ){ $class_container = 'bg-russia';}
    if(!empty($page[2]) && $page[2] == 'view_pact' ){ $class_container = '';}
?>
<body class="<?=$class_container?>">
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<?$getGeo = $APPLICATION->IncludeComponent("nfksber:location","",Array(
        'CACHE_TYPE'=>'Y',
        'ACTION_VARIABLE'=>'action',
));?>
<? if (!$USER->IsAuthorized()){?>
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
                                    "SHOW_FIELDS" => Array("LOGIN", "EMAIL", "PASSWORD", "CONFIRM_PASSWORD", "PERSONAL_PHONE"),
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
                            "new_anypact_auth_form",
                            Array(
                                "REGISTER_URL" => "register.php",
                                "FORGOT_PASSWORD_URL" => "",
                                "PROFILE_URL" => "profile.php",
                                "SHOW_ERRORS" => "Y",
                                "STORE_PASSWORD" => "Y"
                                )
                            );?>
                        </div>
                        <div class="regpopup_content" id="regpopup_forgotpassword" style="display:none;">
                            <h2>Восстановление пароля</h2>
                            <?$APPLICATION->IncludeComponent("bitrix:system.auth.forgotpasswd",
                            "anypact",
                            Array(
                                "REGISTER_URL" => "",
                                "FORGOT_PASSWORD_URL" => "",
                                "PROFILE_URL" => "/profile/",
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
<?}else if($USER->IsAdmin() || $USER->GetID() == 58){
    $APPLICATION->IncludeComponent("nfksber:moneta.registration",
    "",
    Array(
        "ACTION_VARIABLE" => "action"
    ));
}?>
<!--/Окно регистрации-->
<div class="container">
        <!--Шапка-->
        <header class="header" id="header" style="width: 100%;">
            <div class="header_item tablet_ver_logo">
                <a href="/" class="logo"><img src="<?=SITE_TEMPLATE_PATH?>/image/logo_ap.svg" alt=""
                        style="width: 166px;"></a>
                <?if(!empty($getGeo['cityName'])):?>
                <span class="location"><?=$getGeo['cityName']?></span>
                <?else:?>
                <span class="location">Выберите город</span>
                <?endif?>
                <a href="/AnyPact инструкция.pdf" class="manual" target="_blank"
                    onclick="ym(64629523,'reachGoal','manual');">Инструкция</a>
            </div>
            <? if ($USER->IsAuthorized()){
                $res = CUser::GetList($by="personal_country", $order="desc", [ 'ID' => $USER->GetID() ], [ 'SELECT' => ['UF_ESIA_AUT'], 'FIELDS' => ['ID'] ]);
                if ( $u = $res -> getNext() )
                    $userEsiaAut = $u['UF_ESIA_AUT'];
                ?>
            <div class="header_item tablet_ver_profile">
                <div class="tablet_ver_tel">
                    <a href="tel:88002008484" class="phone">8 (800) 200-84-84</a>
                </div>
                <?if ( $userEsiaAut != 1 ) {?>
                    <div class="profile_item">
                        <div class="create-pact-btn">
                            <a href="/first-help/"></a>
                            <div>Помощь в создании объявления</div>
                        </div>
                    </div>
                    <div class="profile_item">
                        <?
                                $APPLICATION->IncludeComponent("nfksber:profile.widget",
                                "head",
                                Array(
                                        'IS_PAGE_MESSAGE' => $APPLICATION->GetCurPage() == '/list_message/view_message/' ? 'Y' : 'N'
                                    )
                                );
                            ?>
                </div>
                <?} else {?>
                    <?if($USER->IsAdmin() || $USER->GetID() == 58){?>
                        <?$APPLICATION->IncludeComponent("nfksber:moneta.balance","",Array());?>
                    <?}?>
                    <div class="profile_item">
                        <!--Кнопка создать новое предложение-->
                        <div class="create-pact-btn">
                            <a href="/my_pacts/edit_my_pact/?ACTION=ADD"></a>
                            <div>Создать предложение</div>
                        </div>
                        <!------------>
                    </div>
                    <div class="profile_item">
                        <?
                                $APPLICATION->IncludeComponent("nfksber:profile.widget",
                                "head",
                                Array(
                                        'IS_PAGE_MESSAGE' => $APPLICATION->GetCurPage() == '/list_message/view_message/' ? 'Y' : 'N'
                                    )
                                );
                            ?>
                    </div>
                <? } ?>
            </div>
            <?} else {?>
            <div class="header_item tablet_ver_tel_login tablet_ver_tel_login_custom">
                <div class="create-pact-btn">
                    <a href="/first-help/"></a>
                    <div>Помощь в создании объявления</div>
                </div>

                <a href="tel:88002008484" class="phone">8 (800) 200-84-84</a>

                <?if(!empty($getGeo['cityName'])):?>
                <span class="location"><?=$getGeo['cityName']?></span>
                <?else:?>
                <span class="location">Выберите город</span>
                <?endif?>
                <button class="btn btn-nfk btn-login" id="reg_button"
                    onclick="ym(64629523,'reachGoal','reg_btn');">Регистрация / Вход</button>

            </div>
            <?}?>
        </header>
        <!--Меню навигации-->
        <nav class="navbar navbar-expand-md" style="width: 100%;">
            <div class="navbar-brand-block">
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
            </div>
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
                        // авторизованный пользователь
                        $arUrlMenu = array(
                            '/pacts/'           => 'Все предложения',
                            '/search_people/'   => 'Поиск контрагентов',
                            '/my_pacts/'        => 'Мои сделки',
                            '/friends/'         => 'Мои друзья',
                            '/list_message/'    => 'Сообщения',
                            '/service/'         => 'О сервисе',
                            '/help/'            => 'Контакты',
                            // '/promotion/'       => 'Промоакция'
                        );
                    }else {
                        // неавторизованный пользователь
                        $arUrlMenu = array(
                            '/pacts/'           => 'Все предложения',
                            '/search_people/'   => 'Поиск контрагентов',
                            '/service/'         => 'О сервисе',
                            '/help/'            => 'Контакты',
                            // '/promotion/'       => 'Промоакция',
                            '#'                 => 'Регистрация/вход'
                        );
                    }
                    $APPLICATION->IncludeComponent("nfksber:navmenu.head",
                    "",
                        Array(
                            "ArURL_MENU"         => $arUrlMenu,
                            )
                    );
                ?>
                <?if ($USER->IsAuthorized()){?>
                    <?$APPLICATION->IncludeComponent("nfksber:messenger_hl.unread.wiget", "", array('ACTION_VARIABLE' => 'action'));?>
                    <?$APPLICATION->IncludeComponent("nfksber:friends.incoming.wiget", "", array('ACTION_VARIABLE' => 'action'));?>
                <?}?>
            </div>
        </nav>
        <!--//Меню навигации-->
        <!--//Шапка-->
