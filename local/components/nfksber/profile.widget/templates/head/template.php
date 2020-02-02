<div class="widget_user_profile">
    <div class="login-information">
        <div class="login-information-container">
            <?if(!empty($arResult['ACTIVE_COMPANY'])):?>
                <? if(empty($arResult['ACTIVE_COMPANY']['PREVIEW_PICTURE'])):?>
                    <div class="widget_user_profile_avatar container_prof">
                        <span><?=$arResult['ACTIVE_COMPANY']['NAME'][0]?></span>
                        <?if($arResult['UNREAD_MESSAGE']>0):?>
                            <div class="count_unread"><?=$arResult['UNREAD_MESSAGE']?></div>
                        <?endif?>
                    </div>
                <?else:?>
                    <div class="login-information-photo container_prof">
                        <img src="<?=CFile::GetPath($arResult['ACTIVE_COMPANY']['PREVIEW_PICTURE'])?>">
                        <?if($arResult['UNREAD_MESSAGE']>0):?>
                            <div class="count_unread"><?=$arResult['UNREAD_MESSAGE']?></div>
                        <?endif?>
                    </div>
                <?endif?>
            <?else:?>
                <? if(empty($arResult["PERSONAL_PHOTO"])):?>
                    <div class="widget_user_profile_avatar container_prof">
                        <span><?=$arResult["IN_NAME"]?></span>
                        <?if($arResult['UNREAD_MESSAGE']>0):?>
                            <div class="count_unread"><?=$arResult['UNREAD_MESSAGE']?></div>
                        <?endif?>
                    </div>
                <?else:?>
                    <div class="login-information-photo container_prof">
                        <img src="<?=$arResult["PERSONAL_PHOTO"]?>">
                        <?if($arResult['UNREAD_MESSAGE']>0):?>
                            <div class="count_unread"><?=$arResult['UNREAD_MESSAGE']?></div>
                        <?endif?>
                    </div>
                <?endif?>
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
                        <li><a href="/my_pacts/edit_my_pact/?ACTION=ADD">+ Создать новое предложение</a></li>
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

