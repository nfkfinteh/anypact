<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
if($_GET['action'] == "new_dialog"){?>
    <div id="dialog_new">
        <h3 class="font-weight-bold">Создание чата</h3>
        <div>
            <form action="?new_dialog=Y" method="post" name="new_dialog">
                <div class="avatar-name-block">
                    <div class="user_profile_form_editdata_foto">
                        <a href="#" class="edit_user_photo">
                            <img src="/local/templates/anypact/img/user_profile_no_foto.png">
                        </a>
                        <input type="hidden" name="DISCUSSION[AVATAR]"/>
                    </div>
                    <input name="DISCUSSION[NAME]" placeholder="Название беседы">
                </div>
                <?
                $APPLICATION->IncludeComponent(
                    "nfksber:user.select",
                    "new_dialog",
                    Array(
                        "ACTION_VARIABLE" => "action",
                        "HLBLOCK_ID" => DIALOGUSERS_HLB_ID,
                        "INPUT_FIELD_NAME" => "UF_DIALOG_ID",
                        "OUTPUT_FIELD_NAME" => "UF_USER_ID",
                        "INPUT_NAME" => "USERS",
                        "HL_FILTER_NAME" => "status_filter",
                        "ONLY_FRIENDS" => "Y"
                    )
                );
                ?>
                <div class="button-block">
                    <a href="/list_message/" class="flat_button secondary">Отмена</a>
                    <button type="submit" class="flat_button" name="SAVE" value="Y">Создать</button>
                </div>
            </form>
        </div>
    </div>
    <?
    $signer = new \Bitrix\Main\Security\Sign\Signer;
    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'messenger_hl.dialog.list');
    ?>	
    <script>
        var MDL_component = {
            params: <?=CUtil::PhpToJSObject($arParams)?>,
            signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
            siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
            ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
            templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
        };
    </script>
<?}else{?>
    <?if($arResult['PAGE'] == 1){?>
        <div id="dialog_list"<?if($arParams['DIALOG_ID'] > 0){?> class="dialog-hide"<?}?>>
            <div class="title-button-block">
                <h3 class="font-weight-bold">Диалоги</h3><a href="?action=new_dialog" class="title-button" id="new_dialog" title="Создать диалог">+</a>
            </div>
            <div class="list-person-conversation custom-scroll">
    <?}?>
            <?if($arResult['DIALOGS']){
                foreach($arResult['DIALOGS'] as $key => $dialog){ ?>
                    <a href="?chat=<?=$dialog['ID']?>" data-chat-id="<?=$dialog['ID']?>" class="person-conversation<?if($dialog['ID'] == $arParams['DIALOG_ID']){?> active<?}?>">
                        <div class="person-conversation-photo">
                            <?if (!empty($dialog['AVATAR'])){?>
                                <? $renderImage = CFile::ResizeImageGet($dialog['AVATAR'], Array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, false); ?>
                                <img src="<?=$renderImage['src']?>">
                            <?}elseif(!empty($dialog['NAME'])){?>
                                <span class="user-first-letter" style="padding:13px;font-size: 28px;"><?=substr($dialog['NAME'], 0, 1);?></span>
                            <?}else{?>
                                <?if($dialog['IS_DISCUSSION']){?>
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png">
                                <?}else{?>
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png">
                                <?}?>
                            <?}?>
                        </div>
                        <div class="dialog-block">
                            <div class="dialog-name-date">
                                <div class="person-conversation-name"><strong><?=trim($dialog['NAME'])?></strong></div>
                                <div class="person-conversation-date">
                                    <?list($date, $time) = explode(" ", $dialog['LAST_MESSAGE_DATE']);
                                    if($date == date('d.m.Y'))
                                        echo $time;
                                    else
                                        echo $date;
                                    ?>
                                </div>
                            </div>
                            <div class="dialog-message-block">
                                <div class="person-conversation-message"><?if($dialog['LAST_MESSAGE_AUTHOR_ID'] == $arResult['USER_ID'] && $dialog['LAST_MESSAGE_SYSTEM'] != 1){?>Вы: <?}?><span><?=$dialog['LAST_MESSAGE_TEXT']?></span></div>
                                <?if($dialog['LAST_MESSAGE_STATUS'] == "N"){?>
                                <div class="unread-message<?if(!empty($dialog['UNREAD_MESSAGE_COUNT'])){?> unread-message-count<?}?>">
                                        <?if(!empty($dialog['UNREAD_MESSAGE_COUNT']))
                                            echo $dialog['UNREAD_MESSAGE_COUNT'];?>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    </a>
                <?}
            }elseif($arResult['PAGE'] == 1){?>
                <div class="no_active_dialogs">
                    <img src="/local/templates/anypact/image/dont_chat.png" alt="Нет активных диалогов"/>
                    <p>У вас нет активных диалогов</p>
                    <a href="?action=new_dialog">Написать сообщение</a>
                </div>
            <?}elseif($arResult['PAGE'] >= $arResult['TOTAL_PAGE']){?>
                <script>
                    $('#dialog_list .list-person-conversation').off('scroll', loadMoreDialogs);
                </script>
            <?}?>
    <?if($arResult['PAGE'] == 1){?>
            </div>
        </div>
        <?
        $signer = new \Bitrix\Main\Security\Sign\Signer;
        $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'messenger_hl.dialog.list');
        ?>	
        <script>
            var MDL_component = {
                params: <?=CUtil::PhpToJSObject($arParams)?>,
                signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
                siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
                ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
                templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
            };
        </script>
    <?}?>
<?}?>