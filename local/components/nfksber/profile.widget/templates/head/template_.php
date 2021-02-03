<div class="col-md-3">
    <? // если есть фото 
    if($arResult["PERSONAL_PHOTO"] != ""){
    ?>
    <div class="login-information">
        <div class="login-information-container">
            <div class="login-information-photo">
                <img src="<?=$arResult["PERSONAL_PHOTO"]?>" alt="Васильев Александр Евгеньевич">
                <!-- <a href="#" class="login-information-message-counter">4</a> -->
            </div>
            <div class="login-information-text" id="widget_user_profile_name">
                <a href="#"><?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?></a>
                <span id="widget_user_profile_url_profile">Профиль</span>
                <div id="widget_user_profile_select">
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
    <div id="widget_user_profile_avatar">
        <span><?=$arResult["IN_NAME"]?></span>
    </div>
    <div id="widget_user_profile_name">
        <span id="widget_user_profile_fio"><?=$arResult["LAST_NAME"]?> <?=$arResult["IN_NAMES"]?></span>
        <span id="widget_user_profile_url_profile">Профиль</span>
        <div id="widget_user_profile_select">
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

