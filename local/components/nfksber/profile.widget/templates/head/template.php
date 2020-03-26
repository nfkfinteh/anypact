<?
/*Ссылки на ресурсы*/
$MessURL = '/list_message/';
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
                <? if(empty($arResult["PERSONAL_PHOTO"])):?>
                    <a href="<?=$MessURL?>" class="link-messageList">
                        <div class="widget_user_profile_avatar container_prof">
                            <span><?=$arResult["IN_NAME"]?></span>
                            <div class="count_unread <?if($arResult['UNREAD_MESSAGE']>0):?>active<?endif?>"><?=$arResult['UNREAD_MESSAGE']?></div>
                        </div>
                    </a>
                <?else:?>
                    <a href="<?=$MessURL?>" class="link-messageList">
                        <div class="login-information-photo container_prof">
                            <img src="<?=$arResult["PERSONAL_PHOTO"]?>">
                            <div class="count_unread <?if($arResult['UNREAD_MESSAGE']>0):?>active<?endif?>"><?=$arResult['UNREAD_MESSAGE']?></div>
                        </div>
                    </a>
                <?endif?>
            <?endif?>

            <div class="login-information-text widget_user_profile_name">
                <a href="#" class="widget_user_profile_name__title">
                    <?if(!empty($arResult['ACTIVE_COMPANY'])):?><?=$arResult['ACTIVE_COMPANY']['NAME']?>,   <?endif?> <?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?>
                </a>
                <?/*<span class="widget_user_profile_url_profile">Профиль</span>*/?>

                <div class="widget_user_profile_select">
                    <div class="triangle"></div>
                    <ul>
                        <li><a href="/profile/">Редактировать профиль</a></li>
                        <li><a href="/profile/select_company/">Переключить профиль</a></li>
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
<script>
    $('.widget_user_profile_name a').on('click', function() {
        window.location = $( this ).attr('href');
    });
    //клик по профилю в шапке
    $('.widget_user_profile_name').click(function () {
        $('.widget_user_profile_select').fadeIn();
        return false;
    });
    $(document).mouseup(function (e) {
        var popup = $('.widget_user_profile_name');
        if (e.target!=popup[0]&&popup.has(e.target).length === 0){
            $('.widget_user_profile_select').fadeOut();
        }
    });
</script>
