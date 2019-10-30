<?
// статусы объявлений
$PactStatus = array(
    'Y' => 'Active.png',
    'N' => 'DontActive.png'
);
?>
<h2 class="title_line_button">Мои предложения</h2>
<a href="/my_pacts/edit_my_pact/?ACTION=ADD" class="btn btn-nfk" id="add_pact">+ создать новое предложение</a>
<?
$count_pacts = count($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"]);
if ($count_pacts > 0):?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col" colspan=2>Наименование</th>
            <th scope="col">Активно до</th>
            <th scope="col">Видимость</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <? // выборка договоров
        foreach ($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"] as $pact) {
            ?>
            <tr>
                <td scope="row" style="width: 75px;">
                    <div class="avatar_pact">
                        <? if(!empty($pact['URL_IMG_PREVIEW'])) {?>
                            <img src="<?=$pact['URL_IMG_PREVIEW']?>" height="45" alt ="<?=$pact['NAME']?>" />
                        <?}?>
                    </div>
                    <?//print_r($pact);
                    ?>
                </td>
                <td>
                    <?= $pact["NAME"] ?>
                </td>
                <td>
                    <?
                        $dateNow = strtotime (date('d.m.Y'));
                        $dateActivTo = strtotime ($pact["ACTIVE_TO"]);
                        $result = ($dateNow < $dateActivTo);
                        if($result){
                            echo $pact["ACTIVE_TO"];
                        }else{
                            echo '<a href="#">продлить</a>';
                        }
                        
                    ?>
                </td>
                <td><button iditem="<?= $pact["ID"]?>" active="<?= $pact["ACTIVE"]?>" class="onActive"><img src="<?=SITE_TEMPLATE_PATH?>/image/<?=$PactStatus[$pact["ACTIVE"]]?>" /></button></td>
                <td><a href="/my_pacts/edit_my_pact/?ELEMENT_ID=<?= $pact['ID'] ?>&ACTION=EDIT" target="_blank">Посмотреть</a>
                </td>
                <td><a href="#" data-id="<?=$pact['ID']?>" data-toggle="modal" data-target=".bd-message-modal-sm" class="modal_deleteItem">Удалить</a>
                </td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
<?else:?>
    <h3>У Вас нет предложений</h3>
<?endif?>
<div style="width: 100%; height: 100px;">
</div>
<h2 class="title_line_button">Заключенные договоры</h2>
<?if(!empty($arResult["SEND_CONTRACT"])):?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Контрагент</th>
            <th scope="col"></th>
            <th scope="col">Наименование</th>
            <th scope="col">Дата подписания контрагентом</th>
            <th scope="col">Статус</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <? // выборка договоров
        foreach ($arResult["SEND_CONTRACT"] as $pact) {
            ?>
            <tr>
                <td scope="row">
                    <div class="avatar_pact">
                        <a href="/profile_user/?ID=<?=$pact["UF_ID_USER_A"]// владелец договора?>" target="_blank">
                            <?if($pact['PERSONAL_PHOTO_SEND_USER'] != ''){?>
                                <img src="<?=$pact['PERSONAL_PHOTO_SEND_USER']?>" height="60" alt="Спил деревьев, расчистка участков, кронирование">
                            <?}else {?>
                                <h3><?=$pact['PARAMS_SEND_USER']['IN']?></h3>
                            <?}?>
                        </a>
                    </div>                    
                </td>
                <td scope="row">
                    <?=$pact['PARAMS_SEND_USER']['LAST_NAME']?> <?=$pact['PARAMS_SEND_USER']['NAME']?>
                </td>
                <td scope="row">#<?=$pact['ID']?> <?= $pact["NAME_CONTRACT"]["NAME"] ?></td>
                <td><?= $pact['UF_TIME_SEND_USER_A']->toString(); ?></td>
                <!--<td><a href="/upload/private/userfiles/<?= $pact["UF_ID_GROUP"] ?>/<?= $pact["UF_ID_USER_GROUP"] ?>/pact/<?= $pact["ID"] ?>/pact/dog_21_01_2019.pdf?" target="_blank">Посмотреть</a></td>-->
                <td><img src="<?= SITE_TEMPLATE_PATH ?>/img/<?= $pact["STATUS_ICON"] ?>"></td>
                <td><a href="/my_pacts/send_contract/?ID=<?= $pact["ID"] ?>" target="_blank">Посмотреть</a></td>
            </tr>
            <?
            $arIdContract[] = $pact["UF_ID_CONTRACT"];
        }
        ?>
        </tbody>
    </table>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных договоров</h3>
<?endif?>
<div style="width: 100%; height: 100px;">
</div>
<h2 class="title_line_button">Договоры, ожидающие подписания с моей стороны</h2>
<?if(!empty($arResult["REDACTION"])):?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Контрагент</th>
            <th scope="col"></th>
            <th scope="col">Наименование</th>
            <th scope="col">Пользователь</th>
            <th scope="col">Дата изменения</th>
            <th scope="col">Статус</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <? // выборка договоров

        foreach ($arResult["REDACTION"] as $red) {?>
            <?//if(!empty($arIdContract) && in_array($red['ID'], $arIdContract)) continue;?>
                <tr>
                    <td scope="row" style="width: 130px;">
                        <div class="avatar_pact">
                            <a href="/profile_user/?ID=<?=$red['PARAMS_SEND_USER']['ID']?>" target="_blank">
                                <?if(!empty($red['PERSONAL_PHOTO_SEND_USER'])){?>
                                    <img src="<?=$red['PERSONAL_PHOTO_SEND_USER']?>" height="60" alt="">
                                <?}else {?>
                                    <h3><?=$red['PARAMS_SEND_USER']['IN']?></h3>
                                <?}?>
                            </a>
                        </div>
                        <? //print_r($red['PARAMS_SEND_USER']);?>
                    </td>
                    <td>
                        <?=$red['PARAMS_SEND_USER']['LAST_NAME']?> <?=$red['PARAMS_SEND_USER']['NAME']?>
                    </td>
                    <td scope="row"><?= $red["NAME"] ?><?if(!empty($red['NAME_CONTRACT'])) echo '#'.$red['NAME_CONTRACT']['ID'].' '.$red['NAME_CONTRACT']['NAME']; ?></td>
                    <td><a href="<?=$red['USER_B']['LINK']?>"><?=$red['USER_B']['NAME']?></a></td>
                    <td><?= $red['TIMESTAMP_X']?></td>
                    <td><?if(!empty($red['PARAMS_SEND_USER'])){ echo "Подписан";}else { echo "Изменения";}?></td>
                    <td><a href="/my_pacts/send_contract/?ID=<?= $red["ID"] ?>" target="_blank">Посмотреть</a></td>
                </tr>
            <?
        }
        ?>
        </tbody>
    </table>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных предложенных редакций</h3>
<?endif?>
<div style="width: 100%; height: 100px;">
</div>

<h2 class="title_line_button">Договоры, подписанные с моей стороны и ожидающие подписания контрагентом</h2>
<?if(!empty($arResult["SEND_USER_PACT"])):?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Контрагент</th>
            <th scope="col"></th>
            <th scope="col">Наименование</th>
            <th scope="col">Дата подписания</th>
            <th scope="col">Статус</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <? // выборка договоров

        foreach ($arResult["SEND_USER_PACT"] as $red) {?>
            <?//if(!empty($arIdContract) && in_array($red['ID'], $arIdContract)) continue;?>
                <tr>
                    <td scope="row" style="width: 65px;">
                        <div class="avatar_pact">
                            <a href="#"><img src="<?=$red['PERSONAL_PHOTO_SEND_USER']?>" height="60" alt="Спил деревьев, расчистка участков, кронирование"></a>
                        </div>
                    </td>
                    <td>
                        <?=$red['PARAMS_SEND_USER']['LAST_NAME']?> <?=$red['PARAMS_SEND_USER']['NAME']?>
                    </td>
                    <td scope="row"><?= $red["NAME"] ?><?if(!empty($red['NAME_CONTRACT'])) echo '#'.$red['NAME_CONTRACT']['ID'].' '.$red['NAME_CONTRACT']['NAME']; ?></td>
                    <td><?= $red['UF_TIME_SEND_USER_B']?></td>
                    <td>Ожидает подписи</td>
                    <td><a href="#" data="<?=$red["ID"]?>" class="recall_send">Отозвать подпись</a></td>
                    <td><a href="/my_pacts/signature_contract/?ID=<?= $red["ID"] ?>" target="_blank">Посмотреть</a></td>
                </tr>
            <?
        }
        ?>
        </tbody>
    </table>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных предложенных редакций</h3>
<?endif?>
<div style="width: 100%; height: 100px;">
</div>
<h2 class="title_line_button">Мои сообщения</h2> <a href="#" class="btn btn-nfk" id="semd_mess">Написать сообщение</a>
<?if(!empty($arResult["MESSAGE_USER"])):?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Тема</th>
            <th scope="col">Дата</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <? // выборка договоров
        foreach ($arResult["MESSAGE_USER"] as $message) {
            ?>
            <tr>
                <td scope="row"><?= $message["UF_TITLE_MESSAGE"] ?></td>
                <td><?= $message["UF_TIME_CREATE_MSG"]->toString() ?></td>
                <td><a href="/my_pacts/view_message/?id=<?= $message["ID"] ?>" target="_blank">Посмотреть</a></td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
<?else:?>
    <h3>У Вас нет сообщений</h3>
<?endif?>

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
