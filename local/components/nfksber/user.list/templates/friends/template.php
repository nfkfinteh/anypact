<div class="text-right btn-view mt-3">
    <div class="btn-view__wrap">
        <span>Вид списка</span>
        <button class="btn btn-tiled active"></button>
        <button class="btn btn-list"></button>
    </div>
</div>

<?/*?>
<div class="row grid-view friends">
    <div class="blacklist">
        <?if(!empty($arResult['BLACK_LIST_FULL'])):?>
            <div class="blacklist__title">Пользователи вчерном списке</div>
                <div class="blacklist__body">
                    <?foreach ($arResult['BLACK_LIST_FULL'] as $user):?>
                        <div class="blacklist__item">
                            <div class="blacklist__name"><?=$user['NAME']?></div>
                            <div class="blacklist__type">
                                <button class="btn btn-clean js-delete-blacklist" data-login="<?=$user['LOGIN']?>" data-type='list_black'>
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/black-list.png" alt="Удалить из черного списка" title="Удалить из черного списка">
                                </button>
                            </div>
                        </div>
                    <?endforeach?>
                </div>
            </div>
        <?else:?>
            <div class="blacklist__title">Черный список пуст</div>
            <div class="blacklist__body"></div>
        <?endif?>
    </div>
</div>
<?*/?>

<?if($arResult["USER"]){?>
    <div class="row grid-view friends js-friends__list">
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
                                <button class="btn btn-clean search-peaople__button" data-toggle="modal" data-target=".bd-message-modal-sm" data-login="<?=$user['LOGIN']?>">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-message.png" alt="Отправить сообщение" title="Отправить сообщение">
                                </button>
                            <?endif?>
                            <?if(!in_array($user['ID'], $arResult["BLACKLIST"]['UF_USER_A'])):?>
                                <!-- <button class="btn btn-clean"><img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-document.png" alt=""></button> -->
                                <?if(($arParams['FRIENDS_STATUS'] == "I" || $arParams['FRIENDS_STATUS'] == "S" || $arParams['FRIENDS_STATUS'] == "B") && $USER->GetID() != $user['ID']):?>
                                    <button class="btn btn-clean js-add-frends" <?if(in_array($user['ID'], $arResult["BLACKLIST"]['UF_USER_B'])){?>style="display:none;"<?}?> data-login="<?=$user['LOGIN']?>">
                                        <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-accept-people.png" alt="Добавить в друзья" title="Добавить в друзья">
                                    </button>
                                <?elseif(in_array($user['ID'], $arResult['FRENDS'])):?>
                                    <button class="btn btn-clean js-delete-frends" data-login="<?=$user['LOGIN']?>">
                                        <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-delete-people.png" alt="Удалить из друзей" title="Удалить из друзей">
                                    </button>
                                <?endif?>
                                <?if(($arParams['FRIENDS_STATUS'] == "I" || $arParams['FRIENDS_STATUS'] == "S") && $USER->GetID() != $user['ID']):?>
                                    <?
                                    if($arParams['FRIENDS_STATUS'] == "I"){
                                        $alt = "Оставить в подписчиках";
                                    }
                                    if($arParams['FRIENDS_STATUS'] == "S"){
                                        $alt = "Удалить из подписчиков";
                                    }
                                    ?>
                                    <button class="btn btn-clean js-delete-frends" data-login="<?=$user['LOGIN']?>">
                                        <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-delete-people.png" alt="<?=$alt?>" title="<?=$alt?>">
                                    </button>
                                <?endif;?>
                            <?endif;?>
                            <?if(!in_array($user['ID'], $arResult['BLACKLIST']['UF_USER_B']) && $USER->GetID() != $user['ID']):?>
                                <button class="btn btn-clean js-add-blacklist" data-login="<?=$user['LOGIN']?>" id="blacklist_<?=$user['ID']?>">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/black-list-add.png" alt="Добавить в черный список" title="Добавить в черный список">
                                </button>
                            <?elseif(in_array($user['ID'], $arResult['BLACKLIST']['UF_USER_B'])):?>
                                <button class="btn btn-clean js-delete-blacklist" data-login="<?=$user['LOGIN']?>" id="blacklist_<?=$user['ID']?>">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/black-list.png" alt="Удалить из черного списка" title="Удалить из черного списка">
                                </button>
                            <?endif?>
                        </div>
                    <? endif ?>
                </div>
            </div>
        <?endforeach?>
    </div>
    <?=$arResult["NAV_STRING"]?>

    <div class="modal fade bd-message-modal-sm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Новое сообщение</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="message_user" action="/response/ajax/add_new_messag_user.php">
                        <input class="login__input" type="hidden" name="login" value="">
                        <div class="form-group">
                            <label>Тема сообщения</label>
                            <input class="form-control" name="title" value="">
                        </div>
                        <div class="form-group">
                            <label>Текст сообщения</label>
                            <textarea class="form-control message-textarea" name="message-text"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-nfk d-block cardPact-bBtn submit_message">Отправить</button>
                </div>
            </div>
        </div>
    </div>
<?}else{?>
    <?php
        switch($arParams['FRIENDS_STATUS']){
            case "Y":
                $text = "друзей";
                break;
            case "O":
            case "I":
                $text = "заявок";
                break;
            case "N":
                $text = "подписок";
                break;
            
            case "S":
                $text = "подписчиков";
                break;
        }
    ?>
    <p>Список <?=$text?> пуст.</p><?if($arParams['FRIENDS_STATUS'] != "B"){?><p>Воспользуйтейтесь <a href="/search_people/">поиском</a>.</p><?}?>
<?}?>
</div>

