<div class="text-right btn-view mt-3">
    <span>Вид списка</span>
    <button class="btn btn-tiled active"></button>
    <button class="btn btn-list"></button>
</div>
<?if(!empty($arResult["COMPANY"])):?>
    <div class="row grid-view">
        <?foreach($arResult["COMPANY"] as $company):?>
            <div class="view-item col-md-5th col-sm-6 col-6 mt-4 pb-3">
                <div class="people-s-photo">
                    <a href="/profile_user/?ID=<?=$company['ID']?>&type=company">
                        <div class="people-s-photo-img">
                            <? if(!empty($company['PREVIEW_PICTURE'])){ ?>
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                                <img class="people-s-user-photo" src="<?=$company['PREVIEW_PICTURE']?>">
                            <?}else {?>
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                            <? } ?>
                        </div>
                    </a>
                </div>
                <div class="people-s-photo-text">
                    <div class="people-s-photo-text-block">
                        <h6><?=$company['NAME']?></h6>
                    </div>
                    <? // кнопки только для авторизированных пользователей ?>
                    <? if($USER->IsAuthorized()):?>
                    <?/*
                        <div class="people-s-photo-btn-block">
                            <button class="btn btn-clean search-peaople__button" data-toggle="modal" data-target=".bd-message-modal-sm" data-login="<?=$user['LOGIN']?>">
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-message.png" alt="">
                            </button>
                            <?if(!in_array($user['ID'], $arResult['BLACKLIST']) && $USER->GetID() != $user['ID']):?>
                                <button class="btn btn-clean js-add-blacklist" data-login="<?=$user['LOGIN']?>">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/black-list-add.png" alt="Добавить в черный список" title="Добавить в черный список">
                                </button>
                            <?elseif(in_array($user['ID'], $arResult['BLACKLIST'])):?>
                                <button class="btn btn-clean js-delete-blacklist" data-login="<?=$user['LOGIN']?>">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/black-list.png" alt="Удалить из черного списка" title="Удалить из черного списка">
                                </button>
                            <?endif?>
                        </div>
    */?>
                    <? endif ?>
                </div>
            </div>
        <?endforeach?>
    </div>
    <?=$arResult["NAV_STRING_COMPANY"]?>
<?else:?>
    <div class="grid-view__none">Контрагенты не найдены</div>
<?endif?>


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


