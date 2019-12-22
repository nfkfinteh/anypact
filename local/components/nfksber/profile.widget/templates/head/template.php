<div class="widget_user_profile">
    <div class="login-information">
        <div class="login-information-container">
            <? if(empty($arResult["PERSONAL_PHOTO"])):?>
                <div class="widget_user_profile_avatar">
                    <span><?=$arResult["IN_NAME"]?></span>
                </div>
            <?else:?>
                <div class="login-information-photo">
                    <img src="<?=$arResult["PERSONAL_PHOTO"]?>">
                </div>
            <?endif?>

            <div class="login-information-text widget_user_profile_name">
                <a href="#" class="widget_user_profile_name__title">
                    <?if(!empty($arResult['ACTIVE_COMPANY'])):?><?=$arResult['ACTIVE_COMPANY']['NAME']?>,   <?endif?> <?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?>
                </a>
                <?/*<span class="widget_user_profile_url_profile">Профиль</span>*/?>

                <div class="widget_user_profile_select">
                    <ul>
                        <li><a href="/profile/">Редактировать профиль</a></li>
                        <li><a href="/profile/select_company/">Выбрать компанию</a></li>
                        <li><a href="/friends/">Мои друзья</a></li>
                        <li><a href="<?echo $APPLICATION->GetCurPageParam("logout=yes", 
                        array(
                            "login",
                            "logout",
                            "register",
                            "forgot_password",
                            "change_password"));?>">Выйти</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

