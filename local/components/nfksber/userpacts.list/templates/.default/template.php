<?
// статусы объявлений
$PactStatus = array(
    'Y' => 'Active.png',
    'N' => 'DontActive.png'
);
// статус договоров
$arrStatus = array(
    'Отменен', 'Подписан контрагентом', 'Изменен конторагентом', 'Изменен и подписан контрагентом', 'Изменен Вами'
);
// статусы договоров ожидающих подписи контрагентов
$arrStatusAwait = array(
    '', 'Ожидает подписи контрагентом', '', '', 'Изменен Вами и ожидает подписи контрагентом'
);
?>
<div class="d-flex flex-wrap align-items-center">
    <h5>Мои предложения <?if(!empty($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"])){?>(<?=count($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Ваши предложения о заключении сделок.</div>
    <a href="/my_pacts/edit_my_pact/?ACTION=ADD" class="btn btn-nfk btn-add-contract ml-auto <?if($arResult['USER']['UF_ESIA_AUT']!=1):?>disabled<?endif?>">
        + создать новое предложение
    </a>
</div>
    <? $count_pacts = count($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"]);
    if ($count_pacts > 0):?>
        <!--Адаптивная табличка объявления--->
        <div class="d-md-table">
                <div class="d-none d-md-table-row t-head">
                    <div class="d-md-table-cell">Наименование</div>
                    <div class="d-md-table-cell">Активно до</div>
                    <div class="d-md-table-cell">Видимость</div>
                    <div class="d-md-table-cell"></div>
                    <!-- кнопки близко, удаление только из карточки <div class="d-md-table-cell"></div>  -->              
                </div>
                <? foreach ($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"] as $key => $pact) { // выборка объявлений ?>
                <!--Запись в таблице--->
                    <div class="d-flex d-md-none justify-content-between collapse-header">
                        <div><?= $pact["NAME"] ?></div>
                        <div class="collapse-arrow position-relative"></div>
                    </div>
                    <div class="d-md-table-row collapse-body" <?if(array_key_first($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"]) !== $key){?>style="display: none;"<?}?>>
                        <div class="d-md-none text-gray"></div>
                        <div class="first-face d-md-table-cell">
                            <span class="d-flex align-items-center">
                                <? if(!empty($pact['URL_IMG_PREVIEW'])) {?>
                                    <img src="<?=$pact['URL_IMG_PREVIEW']?>" height="45" alt ="<?=$pact['NAME']?>" />
                                <?}?>
                                <span style="margin-left: 10px;"><?=$pact["NAME"]?> <?if($pact['PROPERTIES']['MODERATION']['VALUE'] !='Y'):?>(на модерации)<?endif?></span>
                                </span>
                        </div>
                        <div class="d-md-none text-gray">Активно до:</div>
                        <div class="d-md-table-cell"><?=$pact["ACTIVE_TO"]?></div>
                        <?if($pact['PROPERTIES']['MODERATION']['VALUE'] =='Y'):?>
                            <div class="d-md-none text-gray">Видимость</div>
                            <div class="d-md-table-cell">
                                <button iditem="<?= $pact["ID"]?>" active="<?= $pact["ACTIVE"]?>" class="onActive">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/<?=$PactStatus[$pact["ACTIVE"]]?>" />
                                </button>
                            </div>
                            <div class="d-md-table-cell">
                                <a class="button-link" href="/my_pacts/edit_my_pact/?ELEMENT_ID=<?= $pact['ID'] ?>&ACTION=EDIT">Посмотреть</a>
                            </div>
                        <?else:?>
                            <div class="d-md-none text-gray">Видимость</div>
                            <div class="d-md-table-cell">
                                <button class="onActive" disabled>
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/<?=$PactStatus['N']?>" />
                                </button>
                            </div>
                            <div class="d-md-table-cell">
                                <a class="button-link" href="/my_pacts/edit_my_pact/?ELEMENT_ID=<?= $pact['ID'] ?>&ACTION=EDIT">Посмотреть</a>
                            </div>
                        <?endif?>
                        <?/*кнопки близко, удаление только из карточки
                        <div class="d-md-table-cell">
                            <a href="#" class="button-link" data-id="<?=$pact['ID']?>" data-toggle="modal" data-target=".bd-message-modal-sm" class="modal_deleteItem">Удалить</a>
                        </div>
                        */?>
                    </div>
                <!--//Запись в таблице--->
                <? } ?>
            </div>
            <?if(count($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"]) > 1){?>
                <div class="expand-list">
                    <div class="hide-show-scroll">Показать все</div>
                </div>
            <?}?>
        <!------------------------>
    <?else:?>
        <h3>У Вас нет предложений</h3>
        <!-- <button class="info-btn">?</button> -->
        <div class="info-content">В данном разделе содержатся Ваши предложения о заключении сделок.</div>
    <?endif?>
<div style="width: 100%; height: 100px;">
</div>
<!-- Заключенные договоры -->
<div class="d-flex flex-wrap align-items-center">
    <h5>Заключенные договоры <?if(!empty($arResult["SEND_CONTRACT"])){?>(<?=count($arResult["SEND_CONTRACT"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые подписаны с двух сторон.</div>
</div>
<?if(!empty($arResult["SEND_CONTRACT"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table">
    <div class="d-none d-md-table-row t-head">
        <div class="d-md-table-cell">Контрагент</div>
        <div class="d-md-table-cell">Наименование</div>
        <div class="d-md-table-cell">Дата и время подписания</div>
        <!-- <div class="d-md-table-cell">Статус</div>             -->
        <div class="d-md-table-cell"></div>
    </div>
    <? foreach ($arResult["SEND_CONTRACT"] as $key => $red) { // выборка договоров?>
        <!--Запись в таблице--->
        <div class="d-flex d-md-none justify-content-between collapse-header">
            <div><?if(!empty($red['NAME_CONTRACT'])) echo '#'.$red['NAME_CONTRACT']['ID'].' '.$red['NAME_CONTRACT']['NAME']; ?></div>
            <div class="collapse-arrow position-relative"></div>
        </div>
        <div class="d-md-table-row collapse-body" <?if(0 !== $key){?>style="display: none;"<?}?>>
            <?if($red['PARAMS_SEND_COMPANY']):?>
                <div class="d-md-none text-gray"><?=$red['PARAMS_SEND_COMPANY']['IN']?></div>
                <div class="first-face d-md-table-cell">
                    <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$red['PARAMS_SEND_COMPANY']['ID']?>&type=company" style="text-decoration: none;">
                        <?if(!empty($red['PARAMS_SEND_COMPANY']['PREVIEW_PICTURE'])){?>
                            <img src="<?=$red['PARAMS_SEND_COMPANY']['PREVIEW_PICTURE']?>" height="60" alt="">
                        <?}else {?>
                            <h3><?=$red['PARAMS_SEND_COMPANY']['IN']?></h3>
                        <?}?>
                        <span style="margin-left: 10px;"><?=$red['PARAMS_SEND_COMPANY']['NAME']?></span>
                    </a>
                </div>
            <?else:?>
                <div class="d-md-none text-gray"><?=$red['PARAMS_SEND_USER']['IN']?></div>
                <div class="first-face d-md-table-cell">
                    <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$red['PARAMS_SEND_USER']['ID']?>" style="text-decoration: none;">
                        <?if(!empty($red['PERSONAL_PHOTO_SEND_USER'])){?>
                            <img src="<?=$red['PERSONAL_PHOTO_SEND_USER']?>" height="60" alt="">
                        <?}else {?>
                            <h3><?=$red['PARAMS_SEND_USER']['IN']?></h3>
                        <?}?>
                        <span style="margin-left: 10px;"><?=$red['PARAMS_SEND_USER']['LAST_NAME']?> <?=$red['PARAMS_SEND_USER']['NAME']?></span>
                    </a>
                </div>
            <?endif?>

            <div class="d-md-table-cell d-none"><?if(!empty($red['NAME_CONTRACT'])) echo $red['NAME_CONTRACT']['NAME']; ?></div>
            <div class="d-md-none text-gray">Дата и время подписания</div>
            <div class="d-md-table-cell"><?=$red['UF_TIME_SEND_USER_B']?></div>
            <!-- <div class="d-md-none text-gray">Статус</div> -->
            <!-- <div class="d-md-table-cell"><?=$arrStatus[$red['UF_STATUS']]?></div>                 -->
            <div class="d-md-table-cell"><a class="button-link" href="/my_pacts/sign_contract/?ID=<?=$red["ID"]?>">Посмотреть</a></div>
        </div>
    <!--//Запись в таблице--->
    <? } ?>
    </div>
    <?if(count($arResult["SEND_CONTRACT"]) > 1){?>
        <div class="expand-list">
            <div class="hide-show-scroll">Показать все</div>
        </div>
    <?}?>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных договоров</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div
>
<!-- Договора которые подписал контрагент -->
<div class="d-flex flex-wrap align-items-center">
    <h5>Договоры, ожидающие подписания с моей стороны <?if(!empty($arResult["REDACTION"])){?>(<?=count($arResult["REDACTION"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые уже подписаны контрагентом и ожидают подписания с Вашей стороны.</div>
</div>
<?if(!empty($arResult["REDACTION"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table">
        <div class="d-none d-md-table-row t-head">
            <div class="d-md-table-cell">Контрагент</div>
            <div class="d-md-table-cell">Наименование</div>
            <div class="d-md-table-cell">Дата подписания</div>
            <div class="d-md-table-cell">Статус</div>            
            <div class="d-md-table-cell"></div>
            <div class="d-md-table-cell"></div>
        </div>
        <? foreach ($arResult["REDACTION"] as $key => $red) { // выборка договоров?>
            <!--Запись в таблице--->
            <div class="d-flex d-md-none justify-content-between collapse-header">
                <div><?if(!empty($red['NAME_CONTRACT'])) echo '#'.$red['NAME_CONTRACT']['ID'].' '.$red['NAME_CONTRACT']['NAME']; ?></div>
                <div class="collapse-arrow position-relative"></div>
            </div>
            <div class="d-md-table-row collapse-body" <?if(array_key_first($arResult["REDACTION"]) !== $key){?>style="display: none;"<?}?>>
                <?if($red['PARAMS_SEND_COMPANY']):?>
                    <div class="d-md-none text-gray"><?=$red['PARAMS_SEND_COMPANY']['IN']?></div>
                    <div class="first-face d-md-table-cell">
                        <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$red['PARAMS_SEND_COMPANY']['ID']?>&type=company" target="_blank" style="text-decoration: none;">
                            <?if(!empty($red['PARAMS_SEND_COMPANY']['PREVIEW_PICTURE'])){?>
                                <img src="<?=$red['PARAMS_SEND_COMPANY']['PREVIEW_PICTURE']?>" height="60" alt="">
                            <?}else {?>
                                <h3><?=$red['PARAMS_SEND_COMPANY']['IN']?></h3>
                            <?}?>
                            <span style="margin-left: 10px;"><?=$red['PARAMS_SEND_COMPANY']['NAME']?></span>
                        </a>
                    </div>
                <?else:?>
                    <div class="d-md-none text-gray"><?=$red['PARAMS_SEND_USER']['IN']?></div>
                    <div class="first-face d-md-table-cell">
                        <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$red['PARAMS_SEND_USER']['ID']?>" target="_blank" style="text-decoration: none;">
                            <?if(!empty($red['PERSONAL_PHOTO_SEND_USER'])){?>
                                <img src="<?=$red['PERSONAL_PHOTO_SEND_USER']?>" height="60" alt="">
                            <?}else {?>
                                <h3><?=$red['PARAMS_SEND_USER']['IN']?></h3>
                            <?}?>
                            <span style="margin-left: 10px;"><?=$red['PARAMS_SEND_USER']['LAST_NAME']?> <?=$red['PARAMS_SEND_USER']['NAME']?></span>
                        </a>
                    </div>
                <?endif?>
                <div class="d-md-table-cell d-none"><?if(!empty($red['NAME_CONTRACT'])) echo $red['NAME_CONTRACT']['NAME']; ?></div>
                <div class="d-md-none text-gray">Дата подписания</div>
                <div class="d-md-table-cell"><?=$red['UF_TIME_SEND_USER_B']?></div>
                <div class="d-md-none text-gray">Статус</div>
                <div class="d-md-table-cell"><?=$arrStatus[$red['UF_STATUS']]?></div>
                <div class="d-md-table-cell">
                    <?if ($red["IS_REDACTION"] == "Y") {?>
                        <a href="#" data="<?=$red["ID"]?>" class="deactive_send">Отклонить</a>
                    <?}else{?>
                        <a href="#" data="<?=$red["ID"]?>" class="recall_send">Отклонить</a>
                    <?}?>
                </div>
                <?if ($red["IS_REDACTION"] == "Y") {?>
                    <div class="d-md-table-cell"><a class="button-link" href="/my_pacts/send_redaction/?ID=<?=$red["ID"]?>">Посмотреть</a></div>
                <?}else{?>
                    <div class="d-md-table-cell"><a class="button-link" href="/my_pacts/send_contract/?ID=<?=$red["ID"]?>">Посмотреть</a></div>
                <?}?>
            </div>
        <!--//Запись в таблице--->
        <? } ?>
    </div>
    <?if(count($arResult["REDACTION"]) > 1){?>
        <div class="expand-list">
            <div class="hide-show-scroll">Показать все</div>
        </div>
    <?}?>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У вас нет договоров, ожидающих подписания с Вашей стороны</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div>

<!-- Подписанные договоры -->
<div class="d-flex flex-wrap align-items-center">
<h5>Договоры, подписанные с моей стороны и ожидающие подписания контрагентом <?if(!empty($arResult["SEND_USER_PACT"])){?>(<?=count($arResult["SEND_USER_PACT"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые подписаны с Вашей стороны и ожидающие подписания со стороны контрагента.</div>
</div>
<?if(!empty($arResult["SEND_USER_PACT"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table">
        <div class="d-none d-md-table-row t-head">
            <div class="d-md-table-cell">Контрагент</div>
            <div class="d-md-table-cell">Наименование</div>
            <div class="d-md-table-cell">Дата и время подписания</div>
            <div class="d-md-table-cell">Статус</div>
            <div class="d-md-table-cell"></div>
            <div class="d-md-table-cell"></div>
        </div>
        <? foreach ($arResult["SEND_USER_PACT"] as $key => $red) { // выборка договоров?>
            <!--Запись в таблице--->
            <div class="d-flex d-md-none justify-content-between collapse-header">
                <div><?if(!empty($red['NAME_CONTRACT'])) echo '#'.$red['NAME_CONTRACT']['ID'].' '.$red['NAME_CONTRACT']['NAME']; ?></div>
                <div class="collapse-arrow position-relative"></div>
            </div>
            <div class="d-md-table-row collapse-body" <?if(array_key_first($arResult["SEND_USER_PACT"]) !== $key){?>style="display: none;"<?}?>>
                <?if($red['PARAMS_SEND_COMPANY']):?>
                    <div class="d-md-none text-gray"><?=$red['PARAMS_SEND_COMPANY']['IN']?></div>
                    <div class="first-face d-md-table-cell">
                        <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$red['PARAMS_SEND_COMPANY']['ID']?>&type=company" target="_blank" style="text-decoration: none;">
                            <?if(!empty($red['PARAMS_SEND_COMPANY']['PREVIEW_PICTURE'])){?>
                                <img src="<?=$red['PARAMS_SEND_COMPANY']['PREVIEW_PICTURE']?>" height="60" alt="">
                            <?}else {?>
                                <h3><?=$red['PARAMS_SEND_COMPANY']['IN']?></h3>
                            <?}?>
                            <span style="margin-left: 10px;"><?=$red['PARAMS_SEND_COMPANY']['NAME']?></span>
                        </a>
                    </div>
                <?else:?>
                    <div class="d-md-none text-gray"><?=$red['PARAMS_SEND_USER']['IN']?></div>
                    <div class="first-face d-md-table-cell">
                        <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$red['PARAMS_SEND_USER']['ID']?>" target="_blank" style="text-decoration: none;">
                            <?if(!empty($red['PERSONAL_PHOTO_SEND_USER'])){?>
                                <img src="<?=$red['PERSONAL_PHOTO_SEND_USER']?>" height="60" alt="">
                            <?}else {?>
                                <h3><?=$red['PARAMS_SEND_USER']['IN']?></h3>
                            <?}?>
                            <span style="margin-left: 10px;"><?=$red['PARAMS_SEND_USER']['LAST_NAME']?> <?=$red['PARAMS_SEND_USER']['NAME']?></span>
                        </a>
                    </div>
                <?endif?>
                <div class="d-md-table-cell d-none" style="width: 24%;"><?if(!empty($red['NAME_CONTRACT'])) echo $red['NAME_CONTRACT']['NAME']; ?></div>
                <div class="d-md-none text-gray">Дата и время подписания</div>
                <div class="d-md-table-cell"><?=$red['UF_TIME_SEND_USER_B']?></div>
                <div class="d-md-none text-gray">Статус</div>
                <div class="d-md-table-cell" style="width: 18%;"><?=$arrStatusAwait[$red['UF_STATUS']]?></div>
                <div class="d-md-table-cell">
                <?if ($red["IS_REDACTION"] == "Y") {?>
                    <a href="#" data="<?=$red["ID"]?>" class="deactive_send">Отменить</a>
                <?}else{?>
                    <a href="#" data="<?=$red["ID"]?>" class="recall_send">Отозвать подпись</a>
                <?}?>
                </div>
                <div class="d-md-table-cell">
                    <?if ($red["IS_REDACTION"] == "Y") {?>
                        <a class="button-link" href="/my_pacts/send_redaction/?ID=<?=$red["ID"]?>">Посмотреть</a>
                    <?}else{?>
                        <a class="button-link" href="/my_pacts/signature_contract/?ID=<?=$red["ID"]?>">Посмотреть</a>
                    <?}?>
                    
                </div>
            </div>
        <!--//Запись в таблице--->
        <? } ?>
    </div>
    <?if(count($arResult["SEND_USER_PACT"]) > 1){?>
        <div class="expand-list">
            <div class="hide-show-scroll">Показать все</div>
        </div>
    <?}?>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных договоров</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div>
<div style="clear: both"></div>
<div class="modal fade bd-message-modal-sm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Новое сообщение</div>
                <button type="button" class="close deleteItem-modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>Вы действительно хотите удалить сделку?</div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn deleteItem" data-id="">ОК</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".collapse-header").click(function () {
        $(this).toggleClass("open");
        $(this).next().toggleClass("open");
    });
    $(".info-btn").click(function () {
        if (window.innerWidth <= 767)
            $(this).next().slideToggle();
    });
    // $(".info-btn").hover(function () {
    //     if (window.innerWidth > 767)
    //         $(this).next().fadeToggle(50);
    // });

    $('.info-btn').mousemove(function(e){
        var X = e.pageX;
        var Y = e.pageY;
        var top = Y  + 10 + 'px';
        var left = X  + 10 + 'px';
        var id = $(this).next();
        id.css({
            display:"block",
            top: top,
            left: left
        });
    });
    $('.info-btn').mouseout (function(){
        var id = $(this).next();
        id.css({
            display:"none"
        });
    });
</script>

    <!-- окно предупреждения удаления сделки -- -->
    <noindex>
        <div id="dealDeleteWarning" class="bgpopup" style="display:none;">
            <div class="container">
                <div class="row align-items-center justify-content-center">            
                    <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                        <div class="regpopup_win">     
                            <div id="signpopup_close">Х</div>                                       
                            <div class="regpopup_autorisation">
                                <label id="deactive_send_label">Вы уверены что хотите отклонить договор?</label>
                                <label id="recall_send_label">Вы уверены что хотите отозвать подпись?</label>
                                <a href="#" class="btn btn-nfk" id="delete_deal" style="width:45%;">Да</a>
                                <button class="btn btn-nfk" id="close_sign_popup" style="width:45%">Отмена</button>                      
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </noindex>
    <!-- \\окно предупреждения подписания по ЕСИА -->   