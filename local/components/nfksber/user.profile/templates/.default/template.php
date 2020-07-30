<?
if(!empty($arResult['USER']['~UF_BLACKLIST'])){
    $arBlackList = json_decode($arResult['USER']['~UF_BLACKLIST']);
}
else{
    $arBlackList = [];
}
?>
<div style="padding-bottom: 50px;">
    <?if(empty($arResult['ERROR'])):?>
        <div class="row pt-2">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="user-avatar">
                    <span class="user-first-letter"><?=$arResult['USER']['IN_NAME']?></span>
                    <img src="<?=$arResult['USER']['IMG_URL']?>">                    
                </div>
                <h3 class="font-weight-bold mt-4"><?=$arResult['USER']['LAST_NAME']?> <?=$arResult['USER']['NAME']?> <?=$arResult['USER']['SECOND_NAME']?></h3>

                <?if($arResult['TYPE_HOLDER'] == 'user'):?>
                    <?if(!in_array($arResult['USER']['ID'], $arResult['FRENDS']) && $arParams['CURRENT_USER'] != $arResult['USER']['ID']):?>
                        <a href="#" class="btn btn-nfk btn-uprofile js-add-frends" data-login="<?=$arResult['USER']['LOGIN']?>">
                            Добавить в друзья
                        </a>
                    <?elseif(in_array($arResult['USER']['ID'], $arResult['FRENDS'])):?>
                        <a href="#" class="btn btn-nfk btn-uprofile js-delete-frends" data-login="<?=$arResult['USER']['LOGIN']?>">
                            Удалить из друзей
                        </a>
                    <?endif?>

                    <?if(!in_array($arResult['USER']['ID'], $arResult['BLACK_LIST']) && $arParams['CURRENT_USER'] != $arResult['USER']['ID']):?>
                        <button class="btn btn-nfk btn-uprofile js-add-blacklist" data-login="<?=$arResult['USER']['LOGIN']?>">
                            Добавить в ЧС
                        </button>
                    <?else:?>
                        <button class="btn btn-nfk btn-uprofile js-delete-blacklist" data-login="<?=$arResult['USER']['LOGIN']?>">
                            Удалить из ЧС
                        </button>
                    <?endif?>

                    <?if(!empty($arParams['CURRENT_USER']) && $arResult['USER']['ID'] !=$arParams['CURRENT_USER']):?>
                        <?if(!in_array($arParams['CURRENT_USER'], $arBlackList)):?>
                            <a href="#" class="btn btn-nfk btn-uprofile" data-toggle="modal" data-target=".bd-comment-modal-sm">Оставить отзыв</a>
                            <a href="#" class="btn btn-nfk btn-uprofile" data-toggle="modal" data-target=".bd-message-modal-sm">Отправить сообщение</a>
                        <?endif?>
                        <?if(!empty($arResult['COMPANY_CURRENT_USER'])):?>
                            <label class="company list__title">Назначить представителем компании:</label>
                            <select class="company list__select">
                                <?foreach($arResult['COMPANY_CURRENT_USER'] as $comp):?>
                                    <option value="<?=$comp['ID']?>"><?=$comp['NAME']?></option>
                                <?endforeach?>
                            </select>

                            <div class="js-company__btn" data-user="<?=$arResult['USER']['ID']?>">
                                <?if($arResult['COMPANY_CURRENT_USER'][0]['STAFF_NO_ACTIVE']):?>
                                    <a href="#" class="btn btn-nfk btn-uprofile disabled">
                                        Заявка на модерации
                                    </a>
                                <?elseif($arResult['COMPANY_CURRENT_USER'][0]['STAFF']):?>
                                    <a href="#" class="btn btn-nfk btn-uprofile js-delete-staff" data-company="<?=$arResult['COMPANY_CURRENT_USER'][0]['ID']?>">
                                        Удалить представителя
                                    </a>
                                <?else:?>
                                    <a href="#" class="btn btn-nfk btn-uprofile js-add-staff" data-company="<?=$arResult['COMPANY_CURRENT_USER'][0]['ID']?>">
                                        Сделать представителем
                                    </a>
                                <?endif?>
                            </div>
                        <?endif?>
                        <?if(!empty($arResult['DEAL_CURRENT_USER'])):?>
                            <label class="deal list__title">Выбор сделки</label>
                            <select class="deal list__select">
                                <?foreach($arResult['DEAL_CURRENT_USER'] as $deal):?>
                                    <option value="<?=$deal['ID']?>"><?=$deal['NAME']?></option>
                                <?endforeach?>
                            </select>

                            <div class="js-deal__btn" data-user="<?=$arResult['USER']['ID']?>">
                                <?if($arResult['DEAL_CURRENT_USER'][0]['ACCESS']):?>
                                    <a href="#" class="btn btn-nfk btn-uprofile js-delete-access" data-deal="<?=$arResult['DEAL_CURRENT_USER'][0]['ID']?>">
                                        Закрыть доступ
                                    </a>
                                <?else:?>
                                    <a href="#" class="btn btn-nfk btn-uprofile js-add-access" data-deal="<?=$arResult['DEAL_CURRENT_USER'][0]['ID']?>">
                                        Предоставить доступ
                                    </a>
                                <?endif?>
                            </div>
                        <?endif?>
                    <?endif?>
                    <?if($arResult['USER']['PERSONAL_GENDER']=='M'):?>
                        <span class="d-block mt-4">Пол: мужской</span>
                    <?else:?>
                        <span class="d-block mt-4">Пол: женский</span>
                    <?endif?>

                    <?if(!empty($arResult['USER']['PERSONAL_BIRTHDAY']) && $arResult['USER']['UF_DISPLAY_DATE'] == 1):?>
                        <span class="d-block mt-4">Дата рождения: <?=$arResult['USER']['PERSONAL_BIRTHDAY']?></span>
                    <?endif?>

                    <span class="d-block mt-4">Частное лицо</span>

                    <span class="d-block mt-3">Город: <?=$arResult['USER']['PERSONAL_CITY']?> <?//$arResult['USER']['PERSONAL_STREET']?></span>
                    <?if(!empty($arResult['USER']['PERSONAL_PHONE']) && $arResult['USER']['UF_DISPLAY_PHONE'] == 1):?>
                        <span class="d-block mt-4">Телефон: <a href="tel:<?=$arResult['USER']['PERSONAL_PHONE']?>"><?=$arResult['USER']['PERSONAL_PHONE']?></a></span>
                    <?endif?>
                    <?if(!empty($arResult['COMPANY'])):?>
                        <span class="d-block mt-3">Представитель компании: </span>
                        <?foreach ($arResult['COMPANY'] as $company):?>
                            <div>
                                <a href="/profile_user/?type=company&ID=<?=$company['ID']?>">
                                    <?=$company['NAME']?>
                                </a>
                            </div>
                        <?endforeach?>
                    <?endif?>
                    <?if($arResult['USER']['UF_ESIA_AUT']==1):?>
                        <span class="d-block font-weight-bold mt-4">Подтвержденная регистрация</span>
                        <span class="d-block registration-checked mt-2"><img src="<?=SITE_TEMPLATE_PATH?>/img/gosuslugi.svg" style="width:50%;"/></span>
                    <?endif?>
                <?elseif($arResult['TYPE_HOLDER'] == 'company'):?>
                    <span class="d-block mt-4">Юридическое лицо</span>
                    <span class="d-block mt-3"><?=$arResult['USER']['PROPERTY']['CITY']['VALUE']?> <?=$arResult['USER']['PROPERTY']['ADRESS']['VALUE']?></span>
                    <?if(!empty($arResult['STAFF'])):?>
                        <span class="d-block mt-3">Представители компании: </span>
                        <?foreach ($arResult['STAFF'] as $staff):?>
                            <div>
                                <a href="/profile_user/?ID=<?=$staff['ID']?>">
                                    <?=$staff['NAME'].' '.$staff['LAST_NAME']?>
                                </a>
                            </div>
                        <?endforeach?>
                    <?endif?>
                <?endif?>


            </div>
            <div class="col-lg-9 col-md-8 col-sm-12 tenders-list pt-2">
                <button class="btn-category <?if($arResult['CURRENT_STATE']=='Y'):?>active<?endif?>" data-state="Y" data-user="<?=$arResult['USER']['ID']?>" data-type="<?=$arResult['TYPE_HOLDER']?>">
                    Активные <span class="text-gray"><?=$arResult['ACTIVE_ITEMS']?></span>
                </button>
                <button class="btn-category <?if($arResult['CURRENT_STATE']=='N'):?>active<?endif?>" data-state="N" data-user="<?=$arResult['USER']['ID']?>" data-type="<?=$arResult['TYPE_HOLDER']?>">
                    Завершенные <span class="text-black-50"><?=$arResult['COMPLETED_ITEMS']?></span>
                </button>
                <div class="row mt-4 tenders__row">
                    <?if (!empty($arResult['ITEMS'])) { 
                        foreach ($arResult['ITEMS'] as $item):?>
                            <div class="col-lg-4 col-md-6 col-sm-12 tender-block">
                                <div class="tender-post">
                                    <a href="/pacts/view_pact/?ELEMENT_ID=<?=$item['ID']?>">
                                        <div class="tender-img">
                                            <?if(!empty($item['INPUT_FILES']['VALUE'])):?>
                                                <img src="<?=CFile::GetPath($item['INPUT_FILES']['VALUE'][0])?>">
                                            <?else:?>
                                                <img src="/local/templates/anypact/img/no_img_pacts.jpg">
                                            <?endif?>
                                        </div>
                                    </a>
                                    <div class="tender-text">
                                        <a href="/pacts/view_pact/?ELEMENT_ID=<?=$item['ID']?>">
                                            <h3><?=substr($item["NAME"], 0, 30)?></h3>
                                            <p><?=$item["CREATED_DATE"]?></p>
                                            <?if(!empty($item['PREVIEW_TEXT'])):?>
                                                <p><?=TruncateText($item['PREVIEW_TEXT'], 150)?></p>
                                            <?else: ?>
                                                <p><?=strip_tags(TruncateText($item['DETAIL_TEXT'], 150))?></p>
                                            <?endif?>
                                            <span class="tender-price">
                                                <?if($item['PRICE_ON_REQUEST']['VALUE_ENUM'] == "Y"){?>
                                                    Цена по запросу
                                                <?}else{?>
                                                    <?=$item['SUMM_PACT']['VALUE'].' руб.'?>
                                                <?}?>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?endforeach?>
                    <?}else {?>
                        <h4>Записей нет</h4>
                    <?}?>
                </div>
                <?=$arResult["NAV_STRING"]?>
                <div class="mt-4 tenders__row">
                    <?if($arResult['TYPE_HOLDER']=='user'):?>
                        <?if($arResult['USER']['UF_ABOUT']):?>
                            <div class="tenders__title">
                                О себе
                            </div>
                            <div class="tenders__about">
                                <?=$arResult['USER']['UF_ABOUT']?>
                            </div>
                        <?endif?>
                    <?elseif($arResult['TYPE_HOLDER']=='company'):?>
                        <?if($arResult['USER']['PREVIEW_TEXT']):?>
                            <div class="tenders__title">
                                О компании
                            </div>
                            <div class="tenders__about">
                                <?=$arResult['USER']['PREVIEW_TEXT']?>
                            </div>
                        <?endif?>
                    <?endif?>
                </div>
            </div>
        </div>
    <?else:?>
        <div class="row pt-2">
            <?=$arResult['ERROR']?>
        </div>
    <?endif?>
</div>
</div>

<script>
    var bitrixCompanyList = <?=CUtil::PhpToJSObject($arResult['COMPANY_CURRENT_USER'])?>
    var bitrixDealList = <?=CUtil::PhpToJSObject($arResult['DEAL_CURRENT_USER'])?>
</script>

<div class="modal fade bd-message-modal-sm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Новое сообщение пользователю</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/response/ajax/add_new_messag_user.php">
                    <div class="form-group">
                        <label>Тема сообщения</label>
                        <input class="form-control" name="title" value="">
                    </div>
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