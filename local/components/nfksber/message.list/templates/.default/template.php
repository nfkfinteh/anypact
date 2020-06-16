<div class="lk-contracts ">
    <h1 class="mb-4">Мои сообщения</h1>
    <div class="row pt-2 pb-5">
        <!--Блок с сообщениями --->
        <?if(!empty($arResult["MESSAGE_USER"])):?>
            <!--Адаптивная табличка--->
            <table class="table">
                <thead>
                <tr>
                    <th class="d-none d-sm-table-cell" scope="col" colspan="2">Контактное лицо</th>
                    <th class="d-none d-sm-table-cell" scope="col">Последнее сообщение</th>
                    <th class="d-none d-lg-table-cell" scope="col">Время</th>
                    <th class="d-none d-sm-table-cell" scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($arResult["MESSAGE_USER"] as $message) { // выборка сообщений?>
                    <?if(!empty($message['UF_DELETE']) && in_array($arResult['USER_ID'], $message['UF_DELETE'])) continue;?>
                    <tr>
                        <td class="first-face">
                            <?if(!empty($message["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"])){?>
                                <? $renderImage = CFile::ResizeImageGet($message["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false); ?>
                                <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$message["PARAMS_SENDER_USER"]["ID"]?>" target="_blank">
                                    <img src="<?=$renderImage["src"]?>" height="60" alt="">
                                </a>
                            <?}else {?>
                                <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$message["PARAMS_SENDER_USER"]["ID"]?>" target="_blank">
                                    <h3 style="text-align: center; width: 40px;"><?=$message["PARAMS_SENDER_USER"]["IN"]?></h3>
                                </a>
                            <?}?>
                        </td>
                        <td><?=$message["PARAMS_SENDER_USER"]["FIO"]?><br>
                            <span class="text-gray d-lg-none"><a href="/list_message/view_message/?id=<?= $message["ID"] ?>"><?= $message["UF_TITLE_MESSAGE"] ?></a></span>
                        </td>
                        <td class="d-none d-lg-table-cell <?if($message['UNREAD']=='Y'):?>unread-message<?else:?>text-gray<?endif?>">
                            <?= $message["LAST_MESSAGE"] ?>
                            <span class="text-gray d-lg-none"><a href="/list_message/view_message/?id=<?= $message["ID"] ?>"><?= $message["LAST_MESSAGE"] ?></a></span>
                            <span class="text-gray d-lg-none"><p class="small-text"><?= $message["UF_TIME_CREATE_MSG"]->toString() ?></p></span>
                        </td>
                        <td>
                            <span class="text-gray d-lg-none"><a href="/list_message/view_message/?id=<?= $message["ID"] ?>"><?= $message["LAST_MESSAGE"] ?></a><br></span>
                            <span class="small-mb-text text-mb-gray"><?= $message["UF_TIME_CREATE_MSG"]->toString() ?></span>
                        </td>
                        <td class="text-gray d-none d-lg-table-cell"><a href="/list_message/view_message/?id=<?= $message["ID"] ?>">Посмотреть</a></td>
                    </tr>
                <?}?>
                </tbody>
            </table>
            <?
            if ($arParams['ROWS_PER_PAGE'] > 0) {
                $APPLICATION->IncludeComponent(
                    'bitrix:main.pagenavigation',
                    $arParams['PAGEN_ID'],
                    array(
                        'NAV_OBJECT' => $arResult['nav_object'],
                        'SEF_MODE' => 'N',
                    ),
                    false
                );
            }
            ?>
        <?else:?>
            <h3>У Вас нет сообщений</h3>
        <?endif?>
    </div>
</div>