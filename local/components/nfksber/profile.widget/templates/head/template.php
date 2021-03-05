<?
/*Ссылки на ресурсы*/
$MessURL = '/list_message/';
$profileURL = '/profile/';
if(!empty($arResult['ACTIVE_COMPANY']['ID'])){
    $profileURL = '/profile_user/?ID='.$arResult['ACTIVE_COMPANY']['ID'].'&type=company';
}else{
    $profileURL = '/profile_user/?ID='.$arResult["ID"];
}
?>
<div class="widget_user_profile">
    <div class="login-information">
        <div class="login-information-container">
            <?if(!empty($arResult['ACTIVE_COMPANY'])):?>
                <? if(empty($arResult['ACTIVE_COMPANY']['PREVIEW_PICTURE'])):?>
                    <div class="widget_user_profile_avatar container_prof">
                        <span><?=$arResult['ACTIVE_COMPANY']['NAME'][0]?></span>
                        <div class="count_unread <?if($arResult['UNREAD_MESSAGE']>0):?>active<?endif?>"><?=$arResult['UNREAD_MESSAGE']?></div>
                    </div>
                <?else:?>
                    <div class="login-information-photo container_prof">
                        <img src="<?=CFile::GetPath($arResult['ACTIVE_COMPANY']['PREVIEW_PICTURE'])?>">
                        <div class="count_unread <?if($arResult['UNREAD_MESSAGE']>0):?>active<?endif?>"><?=$arResult['UNREAD_MESSAGE']?></div>
                    </div>
                <?endif?>
            <?else:?>

                <a href="<?=$profileURL?>" class="link-messageList">
                    <? if(empty($arResult["PERSONAL_PHOTO"])):?>
                        <?/*?><div class="widget_user_profile_avatar container_prof">
                            <span><?=$arResult["IN_NAME"]?></span><?*/?>
                        <div class="login-information-photo container_prof">
                            <img src="<?= SITE_TEMPLATE_PATH ?>/image/people-search-no-phpto.png">
                    <?else:?>
                        <div class="login-information-photo container_prof">
                            <img src="<?=$arResult["PERSONAL_PHOTO"]?>">
                    <?endif?>
                            <div class="count_unread <?if($arResult['UNREAD_MESSAGE']>0):?>active<?endif?>"><?=$arResult['UNREAD_MESSAGE']?></div>
                        </div>
                </a>
                <div class="user-menu-overflow">
                    <div class="user-menu-block" onclick="event.stopPropagation()">
                        <div class="user-info-block">
                            <a class="user-info" href="<?=$profileURL?>">
                            <? if(empty($arResult["PERSONAL_PHOTO"])):?>
                                <div class="widget_user_profile_avatar container_prof">
                                    <span><?=$arResult["IN_NAME"]?></span>
                            <?else:?>
                                <div class="login-information-photo container_prof">
                                    <img src="<?=$arResult["PERSONAL_PHOTO"]?>">
                            <?endif?>
                                    <div class="count_unread <?if($arResult['UNREAD_MESSAGE']>0):?>active<?endif?>"><?=$arResult['UNREAD_MESSAGE']?></div>
                                </div>
                                <div class="user-fio">
                                    <?if(!empty($arResult['ACTIVE_COMPANY'])):?><?=$arResult['ACTIVE_COMPANY']['NAME']?>,   <?endif?> <?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?>
                                </div>
                            </a>
                            <?$APPLICATION->IncludeComponent("nfksber:moneta.balance","",Array());?>
                        </div>
                        <div class="user-menu">
                            <ul>
                                <li><a href="<?=$profileURL?>">Мой профиль</a></li>
                                <li><a href="/profile/">Редактировать профиль</a></li>
                                <?if($arResult['UF_ESIA_AUT']==1):?>
                                    <li><a href="/profile/select_company/">Переключить профиль</a></li>
                                <?endif?>
                            </ul>
                            <a href="<?echo $APPLICATION->GetCurPageParam("logout=yes",
                            array(
                                "login",
                                "logout",
                                "register",
                                "forgot_password",
                                "change_password"));?>" class="exit-profile">Выйти</a>
                        </div>
                    </div>
                </div>
            <?endif?>

            <div class="login-information-text widget_user_profile_name">
                <!-- <a href="#" class="widget_user_profile_name__title">
                    <?if(!empty($arResult['ACTIVE_COMPANY'])):?><?=$arResult['ACTIVE_COMPANY']['NAME']?>,   <?endif?> <?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?>
                </a> -->
                <span class="widget_user_profile_name__title">
                    <span>
                        <?if(!empty($arResult['ACTIVE_COMPANY'])):?><?=$arResult['ACTIVE_COMPANY']['NAME']?>,   <?endif?> <?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?>
                    </span>
                    <span class="dropdown-arrow-profile"></span>
                </span>
                <?/*<span class="widget_user_profile_url_profile">Профиль</span>*/?>

                <div class="widget_user_profile_select" style="display: none;">
                    <?/*?><span class="triangle">▲</span><?*/?>
                    <ul>
                        <li><a href="<?=$profileURL?>">Мой профиль</a></li>
                        <li><a href="/profile/">Редактировать профиль</a></li>
                        <?if($arResult['UF_ESIA_AUT']==1):?>
                            <li><a href="/profile/select_company/">Переключить профиль</a></li>
                        <?endif?>
                        <!-- <li data-href="/my_pacts/"><a href="/my_pacts/">Мои сделки</a></li>
                        <li data-href="/friends/"><a href="/friends/">Мои друзья</a></li>
                        <li data-href="/list_message/"><a href="/list_message/">Сообщения</a></li> -->
                    </ul>
                    <a href="<?echo $APPLICATION->GetCurPageParam("logout=yes",
                        array(
                            "login",
                            "logout",
                            "register",
                            "forgot_password",
                            "change_password"));?>" class="exit-profile">Выйти</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script>
    // $('.widget_user_profile_name a').on('click', function() {
    //     window.location = $( this ).attr('href');
    // });
    //клик по профилю в шапке
    // $('#widget_user_profile_name__title').on('click', function(){
    //     let select = $('#widget_user_profile_select');
    //     let visual = $(select).css('display');
    //     if(visual == 'none'){
    //         $(select).css('display', 'block');
    //     }else {
    //         $(select).css('display', 'none');
    //     }
    // });
    // $('#widget_user_profile_name__title').click(function () {
    //     $('#widget_user_profile_select').fadeIn(50);

    // });

    // $(document).mouseup(function (e) {
    //     var popup = $('#widget_user_profile_name__title');
    //     if (e.target!=popup[0]&&popup.has(e.target).length === 0){
    //         $('#widget_user_profile_select').css('display', 'none');
    //     }
    // });
</script> -->
