<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if($arResult['VIA_AJAX'] != "Y"){
    $this->addExternalCss(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/ui/trumbowyg.min.css");
    $this->addExternalCss(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/plugins/table/ui/trumbowyg.table.min.css");
    $this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/trumbowyg.min.js");
    $this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/langs/ru.min.js");
    $this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/plugins/history/trumbowyg.history.min.js");
    $this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/plugins/pasteimage/trumbowyg.pasteimage.min.js");
    $this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/plugins/table/trumbowyg.table.min.js");
    $this->addExternalJS(SITE_TEMPLATE_PATH."/js/print.min.js");
}
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
?>
<?if($arResult['NOT_AUTH'] == "Y"){?>
    <div class="error-mess">
        <img src="/local/templates/anypact/image/forbidden.png" alt="Неавторизован">
        <p class="title">Необходимо авторизоваться</p>
        <p class="text">Вам необходимо зарегистрироваться, чтобы увидеть данную страницу. Авторизуйтесь или вернитесь на <a href="/">главную</a></p>
    </div>
<?} else if($arResult['NOT_ESIA'] == "Y"){?>
    <div class="error-mess">
        <img src="/local/templates/anypact/image/forbidden.png" alt="Отсутсвуют госуслуги">
        <p class="title">Вы не авторизованы через госуслуги</p>
        <p class="text">Нет доступа к договору вернитесь на <a href="/">главную</a> или <a href="/profile/#aut_esia">подтвердите свой аккаунт с помощью учетной записи портала Госуслуг</a></p>
    </div>
<?} else if($arResult['NOT_DEAL'] == "Y"){?>
    <div class="error-mess">
        <img src="/local/templates/anypact/image/err_send.png" alt="Нет договора">
        <p class="title">Сделка недоступена</p>
        <p class="text">Сделка отсутсвует или у вас нет к нему доступа вернитесь на <a href="/">главную</a> или <a href="/my_pacts/edit_my_pact/?ACTION=ADD">создайте свою сделку</a></p>
    </div>
<?} else if($arResult['NOT_CONTRACT'] == "Y" || $arResult['NOT_COMPLETE'] == "Y"){?>
    <div class="error-mess">
        <img src="/local/templates/anypact/image/err_send.png" alt="Нет договора">
        <p class="title">Договор недоступен или не найден</p>
        <p class="text">Договор отсутсвует или у вас нет к нему доступа вернитесь на <a href="/">главную</a> или <a href="/my_pacts/edit_my_pact/?ACTION=ADD">создайте свою сделку</a></p>
    </div>
<?}else{?>
<div class="tender cardDogovor">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12" id="contract_menu">
            <div class="cardDogovor-boxTool">
                <div class="btn-block">
                    <?if($arResult['TYPE'] == "VIEW"){
                        if($arParams['COMPLETE'] == "Y") {?>
                            <h3>Файлы</h3>
                            <ul class="list-document">
                                <li class="icon-document">
                                    <span>Договор №1</span>
                                </li>
                            </ul>
                        <?} else if($arResult['CONTACT_TYPE'] == "ORIGINAL") {
                            if($arResult['DEAL']['OWNER_ID'] == $arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['DEAL']['COMPANY_ID'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])) {?>
                                <button class="new-btn" id="edit_contract">Изменить договор</button>
                            <?} else {?>
                                <button class="new-btn" id="sign_contract">Подписать договор</button>
                                <button class="new-btn" id="edit_contract">Предложить свою редакцию</button>
                            <?}
                        } else {?>
                            <?if($arResult['CONTRACT']['SIGNED_USER'] != $arResult['CURRENT_USER']['ID'] && (empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) || $this -> arResult['DEAL']['COMPANY_ID'] != $this -> arResult['CURRENT_USER']['COMPANY_ID'])) {?>
                                <button class="new-btn" id="sign_contract">Подписать договор</button>
                            <?}?>
                            <? if($arResult['CONTACT_TYPE'] == "REDACTION") { ?>
                                <button class="new-btn" id="edit_contract">Изменить редакцию</button>
                            <? } ?>
                            <button class="new-btn" id="delete_redaction">
                                <? if($arResult['CONTACT_TYPE'] == "REDACTION") { ?>
                                    <?if($arResult['DEAL']['OWNER_ID'] == $arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['DEAL']['COMPANY_ID'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])) {?>
                                        Отклонить изменения
                                    <?} else {?>
                                        Отозвать редакцию
                                    <?}?>
                                <?} else {?>
                                    <?if($arResult['CONTRACT']['SIGNED_USER'] == $arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['CONTRACT']['SIGNED_COMPANY'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])) {?>
                                        Отозвать подпись
                                    <?} else {?>
                                        Отклонить подпись
                                    <?}?>
                                <? } ?>
                            </button>
                        <?}?>
                    <?}else{?>
                        <div class="save-block">
                            <?if(empty($arResult["PATTERN_TREE"]) && empty($arResult["TREE_ELEMENTS"])){?>
                                <button class="new-btn" id="save_redaction">
                                    <?if($arResult['DEAL']['OWNER_ID'] == $arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['DEAL']['COMPANY_ID'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])) {?>
                                        Сохранить
                                    <?}else{?>
                                        Сохранить редакцию
                                    <?}?>
                                </button>
                            <?}?>
                            <button class="new-btn" id="delete_redaction">
                                Отменить
                            </button>
                        </div>
                        <?if(empty($arResult["PATTERN_TREE"]) && empty($arResult["TREE_ELEMENTS"])){?>
                            <?if(!empty($arResult["SELLER_CUSTOMER"])){ ?>
                                <div class="steps">
                                    <div class="t" id="step0">
                                        <div id="head_c">
                                            <select class="form-control form-control-lg" id="select_type_user">
                                                <option value="seller">Я <?=$arResult["SELLER_CUSTOMER"][0]?></option>
                                                <option value="customer">Я <?=$arResult["SELLER_CUSTOMER"][1]?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?}?>
                            <div>
                                <h5>Создать договор из наших шаблонов</h5>
                                <p>В правой части выберите тему договора:</p>
                                <a href="#" class="new-btn js-select-pattern">
                                    Загрузить договор из шаблона
                                </a>
                            </div>
                            <div>
                                <h5>Вставить в договор:</h5>
                                <p>Вы можете вставить в текст договора автоподстановку следующих реквизитов:</p>
                                <button class="new-btn js-btn-rquised">Таблица с реквизитами</button>
                                <button class="new-btn js-btn-fio">Моё ФИО</button>
                                <button class="new-btn js-btn-address">Мой Адрес</button>
                                <?if($arResult['DEAL']['OWNER_ID'] == $arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['DEAL']['COMPANY_ID'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])) {?>
                                    <button class="new-btn js-btn-fio-contr">ФИО Контрагента</button>
                                    <button class="new-btn js-btn-adress-contr">Адрес Контрагента</button>
                                <?}?>
                            </div>
                        <?}?>
                        <div>
                            <h5>Загрузить договор из вашего файла</h5>
                            <p31>Поддерживаемые форматы(docx, txt)<br>Размер файла не должен привышать 5мб</p31>
                            <form enctype="multipart/form-data" method="post" name="loadcontract">
                                <label for="uploadbtn" class="new-btn" id="">Загрузить свой файл</label>
                                <input id="uploadbtn" type="file" name="file[]" accept="docx/*" style="display:none;">
                            </form>
                        </div>
                    <?}?>
                </div>
            </div>
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12" id="editor_block">
            <?if($arResult['TYPE'] == "EDITOR"){?>
                <?if(!empty($arResult["PATTERN_TREE"])){?>
                    <div class="view-text">
                        <h3>Вы можете загрузить договор из шаблона или из вашего файла</h3>
                        <?foreach ($arResult["PATTERN_TREE"] as $arTree){?>
                            <div class="tree">
                                <span class="link_template deep_lvl-<?=$arTree["DEPTH_LEVEL"]?>" data-id="<?=$arTree["ID"]?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/folder_contract.png" /><?=$arTree["NAME"]?></span>
                            </div>
                        <?}?>
                    </div>
                <?}else if(!empty($arResult["TREE_ELEMENTS"])){?>
                    <div class="view-text">
                        <div class="tree-back">
                            <a href="#" class="js-select-pattern">← Назад</a>
                        </div>
                        <?if($arResult["TREE_ELEMENTS"] != "empty"){?>
                            <?foreach ($arResult["TREE_ELEMENTS"] as $element) {?>
                                <div class="tree">
                                    <a href="#" class="tree-element" data-id="<?=$element["ID"]?>">
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/document_contract.png" /><?=$element["NAME"]?>
                                    </a>
                                </div>
                            <?}?>
                        <?}else{?>
                            <p>Извините, шаблон договора появится в ближайшее время.</p>
                            <p>Можете воспользоваться шаблоном "Иной договор".</p>
                            <p>Если Вам нужно составить договор, Вы можете обратиться  к нашим специалистам info@anypact.ru</p>
                        <?}?>
                    </div>
                <?}else{?>
                    <div class="edit">
                        <textarea class="editbox" id="<?=$arParams['EDITBOX_ID'];?>"><?=$arResult["CONTRACT"]["TEXT"]?></textarea>
                    </div>
                <?}?>
            <?}else{?>
                <?if($arParams['COMPLETE'] == "Y"){?>
                    <div class="final-title">
                        <h3>Просмотр договора:</h3>
                        <div>
                            <a href="pdf.php?ID=<?=$_GET['ID']?>" target="_blank" class="btn-img" id="download_pdf"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-pdf-gray.png" alt=""></a>
                            <button class="btn-img" onclick="printJS({printable:'/contract/pdf.php?ID=<?=$_GET['ID']?>', type:'pdf'});"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-printer-gray.png" alt=""></button>
                        </div>
                    </div>
                <?}?>
                <!--Поле просмотра договора-->
                <div class="w-100 view">
                    <div class="view-text">
                        <?=$arResult["CONTRACT"]["TEXT"]?>
                        <?=$arResult["CONTRACT"]['SIGNED_TEXT']?>
                    </div>
                </div>
            <?}?>
        </div>
    </div>
</div>
<?}?>
<?if($arResult['VIA_AJAX'] != "Y"){?>
    <?
        $signer = new \Bitrix\Main\Security\Sign\Signer;
        $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'contract.action');
    ?>
    <script>
        var editor = "#<?=$arParams['EDITBOX_ID'];?>";
        $.trumbowyg.svgPath = "<?=SITE_TEMPLATE_PATH?>/module/trumbowyg/dist/ui/icons.svg";
        var CA_component = {
            params: <?= CUtil::PhpToJSObject($arParams) ?>,
            signedParamsString: '<?= CUtil::JSEscape($signedParams) ?>',
            siteID: '<?= CUtil::JSEscape($component->getSiteId()) ?>',
            ajaxUrl: '<?= CUtil::JSEscape($component->getPath() . '/ajax.php') ?>',
            templateFolder: '<?= CUtil::JSEscape($templateFolder) ?>',
        };
        var userData = <?= CUtil::PhpToJSObject($arResult['CURRENT_USER']) ?>;
        <?if(COption::GetOptionString("anypact", "block_gosuslugi", "Y") == "Y"){?>
            var sign_text = '<div class="sign-text">Внимание!<p>Удостоверьтесь в том, что Вам полностью понятны условия, подписываемых Вами Документов!</p> <p>Нажимая кнопку «Подписать», Вы безусловно соглашаетесь с условиями сделки.</p> <p>Ваша простая электронная подпись будет сформирована с помощью сервиса «Госуслуги». Успешная авторизация на Госуслугах будет означать выражение Вашей воли на подписание документов и совершение сделки (сделок) в понимании ст. 160 ГК РФ.</p><p>Примечание: если в недавнем времени вы проходили авторизацию на сайте Госуслуг, то дальнейшая авторизация может не требовать введения Вами логина/пароля от Госуслуг.</p></div>';
        <?}else{?>
            var sign_text = '<div class="sign-text">Внимание!<p>Удостоверьтесь в том, что Вам полностью понятны условия, подписываемых Вами Документов!</p> <p>Нажимая кнопку «Подписать», Вы безусловно соглашаетесь с условиями сделки.</p> <p>Ваша простая электронная подпись будет сформирована с помощью сервиса «Госуслуги». Успешная авторизация на сайте будет означать выражение Вашей воли на подписание документов и совершение сделки (сделок) в понимании ст. 160 ГК РФ.</p></div>';
        <?}?>
        var jsText = {
            DELETE_REDACTION: {TITLE: "Вы уверены?", TEXT: "<p>Вы действительно хотите это сделать?<br><br>Отменить это действие будет <b>невозможно</b>.</p>", BUTTON: "Да"}, 
            SIGN_CONTRACT: {TITLE: "Подписание договора", TEXT: sign_text, BUTTON: "Подписать"}, 
            CLOSE: "Отмена"
        };
        <?if(!empty($arResult['MESSAGE'])){?>
            $(document).ready(function(){
                showResult('#popup-error', "<?= $arResult['MESSAGE']?>");
            });
        <?}?>
    </script>
<?}?>