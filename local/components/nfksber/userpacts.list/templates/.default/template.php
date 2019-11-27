<?
// статусы объявлений
$PactStatus = array(
    'Y' => 'Active.png',
    'N' => 'DontActive.png'
);
// статус договоров
$arrStatus = array(
    'Отменен', 'Подписан контрагентом', '', 'Изменен и подписан контрагентом'
);
// статусы договоров ожидающих подписи контрагентов
$arrStatusAwait = array(
    '', 'Ожидает подписи контрагентом', '', 'Изменен и ожидает подписи контрагентом'
);
?>
<div class="d-flex flex-wrap align-items-center position-relative">
    <h5>Мои предложения</h5>
    <a href="/my_pacts/edit_my_pact/?ACTION=ADD" class="btn btn-nfk btn-add-contract ml-auto">+ создать новое предложение</a>
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
                <? foreach ($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"] as $pact) { // выборка объявлений ?>
                <!--Запись в таблице--->
                    <div class="d-flex d-md-none justify-content-between collapse-header">
                        <div><?= $pact["NAME"] ?></div>
                        <div class="collapse-arrow position-relative"></div>
                    </div>
                    <div class="d-md-table-row collapse-body">
                        <div class="d-md-none text-gray"></div>
                        <div class="first-face d-md-table-cell">
                            <span class="d-flex align-items-center">
                                <? if(!empty($pact['URL_IMG_PREVIEW'])) {?>
                                    <img src="<?=$pact['URL_IMG_PREVIEW']?>" height="45" alt ="<?=$pact['NAME']?>" />
                                <?}?>
                                <span style="margin-left: 10px;"><?= $pact["NAME"] ?></span>
                                </span>
                        </div>
                        <div class="d-md-none text-gray">Активно до:</div>
                        <div class="d-md-table-cell"><?=$pact["ACTIVE_TO"]?></div>                    
                        <div class="d-md-none text-gray">Видимость</div>
                        <div class="d-md-table-cell">
                            <button iditem="<?= $pact["ID"]?>" active="<?= $pact["ACTIVE"]?>" class="onActive">
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/<?=$PactStatus[$pact["ACTIVE"]]?>" />
                            </button>
                        </div>                
                        <div class="d-md-table-cell">
                            <a class="button-link" href="/my_pacts/edit_my_pact/?ELEMENT_ID=<?= $pact['ID'] ?>&ACTION=EDIT" target="_blank" >Посмотреть</a>
                        </div>
                        <!-- кнопки близко, удаление только из карточки
                        <div class="d-md-table-cell">
                            <a href="#" class="button-link" data-id="<?=$pact['ID']?>" data-toggle="modal" data-target=".bd-message-modal-sm" class="modal_deleteItem">Удалить</a>
                        </div>
                        -->
                    </div>
                <!--//Запись в таблице--->
                <? } ?>
            </div>
        <!------------------------>
    <?else:?>
        <h3>У Вас нет предложений</h3>
    <?endif?>
<div style="width: 100%; height: 100px;">
</div>
<!-- Заключенные договоры -->
<div class="d-flex flex-wrap align-items-center position-relative">
    <h5>Заключенные договоры</h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые подписаны с двух сторон.</div>
</div>
<?if(!empty($arResult["SEND_CONTRACT"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table">
    <div class="d-none d-md-table-row t-head">
        <div class="d-md-table-cell">Контрагент</div>
        <div class="d-md-table-cell">Наименование</div>
        <div class="d-md-table-cell">Дата подписания</div>
        <!-- <div class="d-md-table-cell">Статус</div>             -->
        <div class="d-md-table-cell"></div>
    </div>
    <? foreach ($arResult["SEND_CONTRACT"] as $red) { // выборка договоров?>
        <!--Запись в таблице--->
        <div class="d-flex d-md-none justify-content-between collapse-header">
            <div><?if(!empty($red['NAME_CONTRACT'])) echo '#'.$red['NAME_CONTRACT']['ID'].' '.$red['NAME_CONTRACT']['NAME']; ?></div>
            <div class="collapse-arrow position-relative"></div>
        </div>
        <div class="d-md-table-row collapse-body">
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
            <div class="d-md-table-cell d-none"><?if(!empty($red['NAME_CONTRACT'])) echo $red['NAME_CONTRACT']['NAME']; ?></div>
            <div class="d-md-none text-gray">Дата подписания</div>
            <div class="d-md-table-cell"><?=$red['UF_TIME_SEND_USER_B']?></div>
            <!-- <div class="d-md-none text-gray">Статус</div> -->
            <!-- <div class="d-md-table-cell"><?=$arrStatus[$red['UF_STATUS']]?></div>                 -->
            <div class="d-md-table-cell"><a class="button-link" href="/my_pacts/sign_contract/?ID=<?=$red["ID"]?>">Посмотреть</a></div>
        </div>
    <!--//Запись в таблице--->
    <? } ?>
    </div>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных договоров</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div
>
<!-- Договора которые подписал контрагент -->
<div class="d-flex flex-wrap align-items-center position-relative">
    <h5>Договоры, ожидающие подписания с моей стороны</h5>
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
        </div>
        <? foreach ($arResult["REDACTION"] as $red) { // выборка договоров?>
            <!--Запись в таблице--->
            <div class="d-flex d-md-none justify-content-between collapse-header">
                <div><?if(!empty($red['NAME_CONTRACT'])) echo '#'.$red['NAME_CONTRACT']['ID'].' '.$red['NAME_CONTRACT']['NAME']; ?></div>
                <div class="collapse-arrow position-relative"></div>
            </div>
            <div class="d-md-table-row collapse-body">
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
                <div class="d-md-table-cell d-none"><?if(!empty($red['NAME_CONTRACT'])) echo $red['NAME_CONTRACT']['NAME']; ?></div>
                <div class="d-md-none text-gray">Дата подписания</div>
                <div class="d-md-table-cell"><?=$red['UF_TIME_SEND_USER_B']?></div>
                <div class="d-md-none text-gray">Статус</div>
                <div class="d-md-table-cell"><?=$arrStatus[$red['UF_STATUS']]?></div>                
                <div class="d-md-table-cell"><a class="button-link" href="/my_pacts/send_contract/?ID=<?=$red["ID"]?>">Посмотреть</a></div>
            </div>
        <!--//Запись в таблице--->
        <? } ?>
    </div>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У вас нет договоров, ожидающих подписания с Вашей стороны</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div>

<!-- Подписанные договоры -->
<div class="d-flex flex-wrap align-items-center position-relative">
    <h5>Договоры, подписанные с моей стороны и ожидающие подписания контрагентом</h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые подписаны с Вашей стороны и ожидающие подписания со стороны контрагента.</div>
</div>
<?if(!empty($arResult["SEND_USER_PACT"])):?>
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
        <? foreach ($arResult["SEND_USER_PACT"] as $red) { // выборка договоров?>
            <!--Запись в таблице--->
            <div class="d-flex d-md-none justify-content-between collapse-header">
                <div><?if(!empty($red['NAME_CONTRACT'])) echo '#'.$red['NAME_CONTRACT']['ID'].' '.$red['NAME_CONTRACT']['NAME']; ?></div>
                <div class="collapse-arrow position-relative"></div>
            </div>
            <div class="d-md-table-row collapse-body">
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
                <div class="d-md-table-cell d-none" style="width: 24%;"><?if(!empty($red['NAME_CONTRACT'])) echo $red['NAME_CONTRACT']['NAME']; ?></div>
                <div class="d-md-none text-gray">Дата подписания</div>
                <div class="d-md-table-cell"><?=$red['UF_TIME_SEND_USER_B']?></div>
                <div class="d-md-none text-gray">Статус</div>
                <div class="d-md-table-cell" style="width: 18%;"><?=$arrStatusAwait[$red['UF_STATUS']]?></div>
                <div class="d-md-table-cell">
                <a href="#" data="<?=$red["ID"]?>" class="recall_send">Отозвать подпись</a>
                </div>
                <div class="d-md-table-cell">
                    <a class="button-link" href="/my_pacts/signature_contract/?ID=<?=$red["ID"]?>">Посмотреть</a>
                </div>
            </div>
        <!--//Запись в таблице--->
        <? } ?>
    </div>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных договоров</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div>
<div style="clear: both"></div>
<!--Блок с сообщениями --->
<div class="d-flex flex-wrap align-items-center position-relative">
    <h5>Мои сообщения</h5>
    <!-- <a href="#" class="btn btn-nfk btn-add-contract ml-auto">Написать сообщение</a> -->
</div>
<div style="display:none;">
    <pre>
        <? print_r($arResult["MESSAGE_USER"]);?>
    </pre>
</div>
<?if(!empty($arResult["MESSAGE_USER"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table">
        <div class="d-md-table-row t-head">
            <div class="d-md-table-cell">От</div>
            <div class="d-md-table-cell">Тема</div>
            <div class="d-md-table-cell">Дата</div>
            <div class="d-md-table-cell">Статус</div>
            <div class="d-md-table-cell"></div>
        </div>
        <? foreach ($arResult["MESSAGE_USER"] as $message) { // выборка сообщений?>
            <!--Запись в таблице--->
            <div class="d-md-table-row collapse-body">
                <div class="d-md-none text-gray"><?=$red['PARAMS_SEND_USER']['IN']?></div>
                <div class="first-face d-md-table-cell">
                    <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$message["PARAMS_SENDER_USER"]["ID"]?>" target="_blank" style="text-decoration: none;">
                        <?if(!empty($message["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"])){?>
                            <? $renderImage = CFile::ResizeImageGet($message["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false); ?>
                            <img src="<?=$renderImage["src"]?>" height="60" alt="">
                        <?}else {?>
                            <h3><?=$message["PARAMS_SENDER_USER"]["IN"]?></h3>
                        <?}?>
                        <span style="margin-left: 10px;"><?=$message["PARAMS_SENDER_USER"]["FIO"]?></span>
                    </a>
                </div>
                <div class="d-md-table-cell d-none" style="width: 24%;"><?= $message["UF_TITLE_MESSAGE"] ?></div>
                <div class="d-md-none text-gray">Дата подписания</div>
                <div class="d-md-table-cell"><?= $message["UF_TIME_CREATE_MSG"]->toString() ?></div>
                <div class="d-md-none text-gray">Статус</div>
                <div class="d-md-table-cell" style="width: 18%;">xx</div>
                <div class="d-md-table-cell">
                <a href="/my_pacts/view_message/?id=<?= $message["ID"] ?>" target="_blank">Посмотреть</a>
                </div>
            </div>
        <!--//Запись в таблице--->
        <? } ?>
    </div>
<?else:?>
    <h3>У Вас нет сообщений</h3>
<?endif?>
<table class="table">
        <thead>
            <tr>
                <th class="d-none d-sm-table-cell" scope="col" colspan="2">Отправитель</th>
                <th class="d-none d-lg-table-cell" scope="col"></th>
                <th class="d-none d-sm-table-cell text-right" scope="col">Время</th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($arResult["MESSAGE_USER"] as $message) { // выборка сообщений?>
                <tr>
                    <td class="first-face">
                            <?if(!empty($message["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"])){?>
                                <? $renderImage = CFile::ResizeImageGet($message["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false); ?>
                                <img src="<?=$renderImage["src"]?>" height="60" alt="">
                            <?}else {?>
                                <h3><?=$message["PARAMS_SENDER_USER"]["IN"]?></h3>
                            <?}?>                    
                    </td>
                    <td><?=$message["PARAMS_SENDER_USER"]["FIO"]?><br>
                        <span class="text-gray d-lg-none"><?= $message["UF_TITLE_MESSAGE"] ?></span>
                    </td>
                    <td class="text-gray d-none d-lg-table-cell"><?= $message["UF_TITLE_MESSAGE"] ?></td>
                    <td class="text-right"><?= $message["UF_TIME_CREATE_MSG"]->toString() ?></td>
                </tr>
            <?}?>       
        </tbody>
    </table>
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
    $(".info-btn").hover(function () {
        if (window.innerWidth > 767)
            $(this).next().fadeToggle();
    });
</script>