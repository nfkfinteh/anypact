<div class="text-right btn-view mt-3">
    <span>Вид списка</span>
    <button class="btn btn-tiled active"></button>
    <button class="btn btn-list"></button>
</div>
<div class="row grid-view">
    <?foreach($arResult["USER"] as $user):?>
        <div class="view-item col-lg-3 col-md-4 col-sm-6 col-6 mt-4 pb-3">
            <div class="people-s-photo">
                <a href="/profile_user/?ID=<?=$user['ID']?>">
                    <div class="people-s-photo-img">
                        <? if(!empty($user['PERSONAL_PHOTO'])){ ?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                            <img class="people-s-user-photo" src="<?
                            $USER_AV = CFile::GetPath($user['PERSONAL_PHOTO']);
                            $renderImage = CFile::ResizeImageGet($user['PERSONAL_PHOTO'], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false);
                            echo $renderImage["src"];
                            ?>" alt="">
                        <?}else {?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                        <? } ?>
                        <?if($user['UF_ESIA_AUT']):?>
                                <div class="check-esia-img">
                                    <div class="check-esia-img-info">
                                        <span>Подтвержденная авторизация</span>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/check-esia-min.png" alt="">
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gosuslugi.svg" style="width:50%;"
                                            class="gos_img"/>
                                    </div>
                                </div>
                        <?endif?>
                    </div>
                </a>
            </div>
            <div class="people-s-photo-text">
                <div class="people-s-photo-text-block">
                    <h6><?=$user['NAME'].' '.$user['LAST_NAME']?></h6>
                    <div class="grid-hidden-text">
                        <?if(!empty($user['PERSONAL_CITY'])):?>
                            <span class="d-block text-gray"><?=$user['PERSONAL_CITY']?></span>
                        <?endif?>
                        <?if($user['UF_ESIA_AUT']):?>
                            <span class="d-block font-weight-bold mt-4">Подтвержденная регистрация</span>
                            <span class="d-block registration-checked mt-2"><img src="<?=SITE_TEMPLATE_PATH?>/img/gosuslugi.svg" style="width:50%;" class="gos_img"/></span>
                        <?else:?>
                            <span class="d-block font-weight-bold mt-4">Регистрация не подтверждена</span>
                        <?endif?>
                    </div>
                </div>
                <? // кнопки только для авторизированных пользователей ?>
                <? if($USER->IsAuthorized()):?>
                    <div class="people-s-photo-btn-block">
                        <?if(!in_array($user['ID'], $arResult["BLACKLIST"]['UF_USER_A'])):?>
                            <button class="btn btn-clean search-people__button" data-id="<?=$user['ID']?>">
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-message.png" alt="Отправить сообщение" title="Отправить сообщение">
                            </button>
                        <?endif?>
                        <!-- <button class="btn btn-clean"><img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-document.png" alt=""></button> -->
                        <?if(!in_array($user['ID'], $arResult["BLACKLIST"]['UF_USER_A'])):?>
                            <?if(!in_array($user['ID'], $arResult['FRENDS']) && $USER->GetID() != $user['ID']):?>
                                <button class="btn btn-clean js-add-frends" <?if(in_array($user['ID'], $arResult["BLACKLIST"]['UF_USER_B'])){?>style="display:none;"<?}?> data-id="<?=$user['ID']?>">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-add-people.png" alt="Добавить в друзья" title="Добавить в друзья">
                                </button>
                            <?elseif(in_array($user['ID'], $arResult['FRENDS'])):?>
                                <button class="btn btn-clean js-delete-frends" data-id="<?=$user['ID']?>">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-delete-people.png" alt="Удалить из друзей" title="Удалить из друзей">
                                </button>
                            <?endif?>
                        <?endif;?>
                        <?if(!in_array($user['ID'], $arResult['BLACKLIST']['UF_USER_B']) && $USER->GetID() != $user['ID']):?>
                            <button class="btn btn-clean js-add-blacklist" data-id="<?=$user['ID']?>">
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/black-list-add.png" alt="Заблокировать" title="Заблокировать">
                            </button>
                        <?elseif(in_array($user['ID'], $arResult['BLACKLIST']['UF_USER_B'])):?>
                            <button class="btn btn-clean js-delete-blacklist" data-id="<?=$user['ID']?>">
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/black-list.png" alt="Разблокировать" title="Разблокировать">
                            </button>
                        <?endif?>
                    </div>
                <? endif ?>
            </div>
        </div>
    <?endforeach?>
</div>
<?=$arResult["NAV_STRING"]?>