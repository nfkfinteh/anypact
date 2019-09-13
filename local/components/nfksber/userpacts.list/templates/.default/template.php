<h2 class="title_line_button">Мои предложения</h2><a href="/my_pacts/edit_my_pact/?ACTION=ADD" class="btn btn-nfk"
                                                     id="add_pact">+ создать новое предложение</a>
<?
$count_pacts = count($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"]);
if ($count_pacts > 0):?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col" colspan=2>Наименование</th>
            <th scope="col">Дата</th>
            <th scope="col">Статус</th>
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
                <td><?= $pact["CREATED_DATE"] ?></td>
                <td><?= $pact["PROPERTIES"]["PACT_STATUS"]["VALUE_XML_ID"] ?></td>
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
    <h3>У вас нет сделок</h3>
<?endif?>
<div style="width: 100%; height: 100px;">
</div>
<h2 class="title_line_button">Мои подписанные договора</h2>
<?if(!empty($arResult["SEND_CONTRACT"])):?>
    <table class="table">
        <thead>
        <tr>
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
                <td scope="row"><?= $pact["NAME_CONTRACT"]["NAME"] ?></td>
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
    <h3>У вас нет подписанных договоров</h3>
<?endif?>
<div style="width: 100%; height: 100px;">
</div>

<h2 class="title_line_button">Предложенные редакции</h2>
<?if(!empty($arResult["REDACTION"])):?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Наименование</th>
            <th scope="col">Пользователь</th>
            <th scope="col">Дата изменеия</th>
            <th scope="col">Статус</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <? // выборка договоров

        foreach ($arResult["REDACTION"] as $red) {
            ?>
            <?if(!empty($arIdContract) && in_array($red['ID'], $arIdContract)) continue;?>
                <tr>
                    <td scope="row"><?= $red["NAME"] ?></td>
                    <td><a href="<?=$red['USER_B']['LINK']?>"><?=$red['USER_B']['NAME']?></a></td>
                    <td><?= $red['TIMESTAMP_X']?></td>
                    <td>статус</td>
                    <td><a href="/my_pacts/send_redaction/?ID=<?= $red["ID"] ?>" target="_blank">Посмотреть</a></td>
                </tr>
            <?
        }
        ?>
        </tbody>
    </table>
<?else:?>
    <div style="clear: both"></div>
    <h3>У вас нет подписанных предложенных редакций</h3>
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
                <td scope="row"><?= $message["UF_TEXT_MESSAGE_USER"] ?></td>
                <td><?= $message["UF_TIME_CREATE_MSG"]->toString() ?></td>
                <td><a href="/my_pacts/view_message/?id=<?= $pact["ID"] ?>" target="_blank">Посмотреть</a></td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
<?else:?>
    <h3>У вас нет сообщений</h3>
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
