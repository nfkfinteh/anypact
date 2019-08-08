<div>
    <?if(empty($arResult['ERROR'])):?>
        <div class="row pt-2">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="user-avatar">
                    <span class="user-first-letter"><?=$arResult['USER']['IN_NAME']?></span>
                    <img src="<?=$arResult['USER']['IMG_URL']?>">                    
                </div>
                <h3 class="font-weight-bold mt-4"><?=$arResult['USER']['LAST_NAME']?> <?=$arResult['USER']['NAME']?> <?=$arResult['USER']['SECOND_NAME']?></h3>
                <a href="#" class="btn btn-nfk btn-uprofile" data-toggle="modal" data-target=".bd-comment-modal-sm">Оставить отзыв</a>
                <a href="#" class="btn btn-nfk btn-uprofile" data-toggle="modal" data-target=".bd-message-modal-sm">Отправить сообщение</a>
                <span class="d-block mt-4">Частное лицо</span>
                <span class="d-block mt-3"><?=$arResult['USER']['PERSONAL_CITY']?>, <?=$arResult['USER']['PERSONAL_STREET']?></span>
                <?if($arResult['USER']['UF_ESIA_AUT']==1):?>
                    <span class="d-block font-weight-bold mt-4">Подтвержденная регистрация</span>
                    <span class="d-block registration-checked mt-2"><img src="https://gu-st.ru/st/img/logo_nobeta.0a1f5dfe6b.svg" style="width:50%;"/></span>
                <?endif?>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12 tenders-list pt-2">
                <button class="btn-category <?if($arResult['CURRENT_STATE']=='Y'):?>active<?endif?>" data-state="Y" data-user="<?=$arResult['USER']['ID']?>">Активные <span class="text-gray"><?=$arResult['ACTIVE_ITEMS']?></span></button>
                <button class="btn-category <?if($arResult['CURRENT_STATE']=='N'):?>active<?endif?>" data-state="N" data-user="<?=$arResult['USER']['ID']?>">Завершенные <span class="text-black-50"><?=$arResult['COMPLETED_ITEMS']?></span></button>
                <div class="row mt-4">
                    <?if (!empty($arResult['ITEMS'])) { 
                        foreach ($arResult['ITEMS'] as $item):?>
                            <div class="col-lg-4 col-md-6 col-sm-12 tender-block">
                                <div class="tender-post">
                                    <a href="/pacts/view_pact/?ELEMENT_ID=<?=$item['ID']?>">
                                        <div class="tender-img">
                                            <?if(!empty($item['PREVIEW_PICTURE'])):?>
                                                <img src="<?=CFile::GetPath($item['PREVIEW_PICTURE'])?>">
                                            <?else:?>
                                                <img src="<?=CFile::GetPath($item['DETAIL_PICTURE'])?>">
                                            <?endif?>
                                            <span><?=$item['CREATED_DATE']?></span>
                                        </div>
                                    </a>
                                    <div class="tender-text">
                                        <a href="/pacts/view_pact/?ELEMENT_ID=<?=$item['ID']?>">
                                            <h3><?=$item['NAME']?></h3>
                                            <?if(!empty($item['PREVIEW_TEXT'])):?>
                                                <p><?=TruncateText($item['PREVIEW_TEXT'], 150)?></p>
                                            <?else: ?>
                                                <p><?=strip_tags(TruncateText($item['DETAIL_TEXT'], 150))?></p>
                                            <?endif?>

                                            <span class="tender-price"><?=$item['SUMM_PACT']['VALUE'].' руб.'?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?endforeach?>
                    <?}else {?>
                        <h4>Записей нет</h4>
                    <?}?>
                </div>
            </div>
        </div>
    <?else:?>
        <div class="row pt-2">
            <?=$arResult['ERROR']?>
        </div>
    <?endif?>
</div>
<?=$arResult["NAV_STRING"]?>
</div>

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
                <form action="/response/ajax/add_new_messag_user.php">
                    <input type="hidden" name="login" value="<?=$arResult['USER']['LOGIN']?>">
                    <div class="form-group">
                        <textarea class="form-control " name="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn submit_message">Отправить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-comment-modal-sm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Новый отзыв</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/response/ajax/add_new_comment.php">
                    <input type="hidden" name="login" value="<?=$arResult['USER']['LOGIN']?>">
                    <div class="form-group">
                        <textarea class="form-control " name="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn submit_message">Отправить</button>
            </div>
        </div>
    </div>
</div>





