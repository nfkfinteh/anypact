<div class="widget_user_profile">
    <? // если есть фото 
    if($arResult["PERSONAL_PHOTO"] != ""){
    ?>
    <div class="login-information">
        <div class="login-information-container">
            <div class="login-information-photo">
                <img src="<?=$arResult["PERSONAL_PHOTO"]?>">
                <!-- <a href="#" class="login-information-message-counter">4</a> -->
            </div>
            <div class="login-information-text widget_user_profile_name">
                <a href="#"><?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?></a>
                <span class="widget_user_profile_url_profile">Профиль</span>
                <div class="widget_user_profile_select">
                    <ul>
                        <li><a href="/profile/">Редактировать профиль</a></li>
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
    <?}else {?>
    <? // если нет фото ?>
    <div class="widget_user_profile_avatar">
        <span><?=$arResult["IN_NAME"]?></span>
    </div>
    <div class="widget_user_profile_name">
        <span class="widget_user_profile_fio"><?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?></span>
        <span class="widget_user_profile_url_profile">Профиль</span>
        <div class="widget_user_profile_select">
            <ul>
                <li><a href="/profile/">Редактировать профиль</a></li>
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
    <?}?>
</div>

