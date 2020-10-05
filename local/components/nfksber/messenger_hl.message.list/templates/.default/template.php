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
if($arResult['TYPE'] == 'NEW_MESSAGE'){
    if(!empty($arResult['NEW_MESSAGE'])):
        $html = '<div class="message-block message-block-right" data-id="'.$arResult['NEW_MESSAGE']['ID'].'">
            <div class="message-person-photo">
                <a href="/profile_user/?ID='.$arResult['NEW_MESSAGE']['AUTHOR_ID'].'" class="user-avatar">';
                    if(!empty($arResult['NEW_MESSAGE']['AUTHOR_PERSONAL_PHOTO'])){                                         
                        $renderImage = CFile::ResizeImageGet($arResult['NEW_MESSAGE']['AUTHOR_PERSONAL_PHOTO'], Array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, false);
                        $html .= '<img src="'.$renderImage["src"].'" alt=""/>';
                    }else {
                        $html .= '<span class="user-first-letter">'.substr($arResult['NEW_MESSAGE']['AUTHOR_NAME'], 0, 1).'</span>';
                    }
                $html .= '</a>
            </div>
            <div class="message-container not-read">
                <div class="message-message">
                    <div class="name-date">
                        <p class="user-name-right">'.$arResult['NEW_MESSAGE']['AUTHOR_NAME'] . " " . $arResult['NEW_MESSAGE']['AUTHOR_LAST_NAME'].'</p>
                        <time datetime="'.$arResult['NEW_MESSAGE']['DATE_CREATE'].'">'.$arResult['NEW_MESSAGE']['DATE_CREATE'].'</time>
                    </div>
                    <div class="message-content">
                        <div class="message-text">';
                            if($arResult['NEW_MESSAGE']['ATTACHMENTS']):
                                if(!empty($arResult['NEW_MESSAGE']['ATTACHMENTS']['IMAGE']) && is_array($arResult['NEW_MESSAGE']['ATTACHMENTS']['IMAGE'])){
                                    foreach ($arResult['NEW_MESSAGE']['ATTACHMENTS']['IMAGE'] as $img):
                                        $arFile = CFile::MakeFileArray($img);
                                        $arr = explode('.', $arFile['name']);
                                        if(!empty($arr)):
                                            $expansion = array_pop($arr);
                                            $arExpansion = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg');
                                            if(in_array(strtolower($expansion), $arExpansion)):
                                                $resizeIMG = CFile::ResizeImageGet($img, array("width" => "280", "height" => "400"), BX_RESIZE_IMAGE_PROPORTIONAL, false);
                                                $html .= '<div class="message-text__file">
                                                    <img src="'.$resizeIMG['src'].'" class="message-text__img" data-original-image-src="'.CFile::GetPath($img).'">
                                                </div>';
                                            else:
                                                $html .= '<a href="'.CFile::GetPath($img).'" class="message-text__file" target="_blank">
                                                    <img src="'.SITE_TEMPLATE_PATH.'/image/icon-file.png">
                                                    <span class="message-text__name">'.$arFile['name'].'</span>
                                                </a>';
                                            endif;
                                        endif;
                                    endforeach;
                                }
                            endif;
                            $html .= '<p>'.$arResult['NEW_MESSAGE']['MESSAGE_TEXT'].'</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        echo json_encode(array("STATUS" => "SUCCESS", "DATA" => $html, "DIALOG_ID" => $arParams['DIALOG_ID'], "MESSAGE_TEXT" => $arResult['NEW_MESSAGE']['MESSAGE_TEXT'], "DATE_CREATE" => explode(" ", $arResult['NEW_MESSAGE']['DATE_CREATE'])[1]));
    else:
        echo json_encode(array("STATUS" => "ERROR", "DATA" => "Ошибка при отправке сообщения"));
    endif;
}elseif(!empty($arResult['MESSAGES'])){
?>
    <?if($arResult['PAGE'] == 1){?>
        <div class="message-list-block">
            <div class="return-back-to-dialogs">
                <a href="/list_message/">← Назад к списку диалогов</a>
            </div>
            <div class="title-button-block">
                <h3 class="font-weight-bold"><?=$arResult['DISCUSSION']['NAME'];?></h3>
                <?if($GLOBALS['DISCUSSION_ID']){?>
                    <div class="discussion-user-count" id="discussion_users">
                        <span><?=count($arResult['DISCUSSION']['USERS'])?> участника(ов)</span>
                    </div>
                    <div class="discussion-user">
                        <span class="triangle">▲</span>
                        <div class="users-container custom-scroll">
                            <div class="users">
                                <?foreach($arResult['DISCUSSION']['USERS'] as $user){?>
                                    <div class="user-el">
                                        <a class="d-flex align-items-center profile-link" href="/profile_user/?ID=<?=$user['ID']?>" target="_blank">
                                            <div class="user-photo">
                                            <?if (!empty($user['PERSONAL_PHOTO'])){?>
                                                <? $renderImage = CFile::ResizeImageGet($user['PERSONAL_PHOTO'], Array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, false); ?>
                                                <img src="<?=$renderImage['src']?>">
                                            <?}elseif(!empty($user['NAME'])){?>
                                                <h3><?=substr($user['NAME'], 0, 1);?></h3>
                                            <?}else{?>
                                                <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png">
                                            <?}?>
                                            </div>
                                            <div class="user-fio"><?=$user['LAST_NAME'] . " " . $user['NAME'] . " " . $user['SECOND_NAME']?></div>
                                        </a>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    </div>
                <?}?>
                <div class="title-button" id="dialog_setting" title="Настройки диалога">
                    <svg width="5" height="5" viewBox="0 0 5 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="2.5" cy="2.5" r="2.5"/>
                    </svg>
                    <svg width="5" height="5" viewBox="0 0 5 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="2.5" cy="2.5" r="2.5"/>
                    </svg>
                    <svg width="5" height="5" viewBox="0 0 5 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="2.5" cy="2.5" r="2.5"/>
                    </svg>
                    <div class="dialog-setting-menu">
                        <span class="triangle">▲</span>
                        <?if($GLOBALS['DISCUSSION_ID']){?>
                            <?if($arResult['DISCUSSION_IS_AUTHOR'] == 'Y'){?>
                                <div class="menu-item" id="edit_discussion">
                                    Редактировать беседу
                                </div>
                            <?}?>
                            <?if($arResult['DISCUSSION_USER_STATUS'] == DIALOGUSERSTATUS_I){?>
                                <div class="menu-item" id="leave_discussion">
                                    Покинуть беседу
                                </div>
                            <?}?>
                            <?if($arResult['DISCUSSION_USER_STATUS'] == DIALOGUSERSTATUS_L){?>
                                <div class="menu-item" id="join_discussion">
                                    Вернуться в беседу
                                </div>
                            <?}?>
                        <?}?>
                        <div class="menu-item" id="delete_all_message">
                            Удалить все сообщения
                        </div>
                    </div>
                </div>
            </div>
            <div class="message-list-wrap" id="simple_scroll_bar">
                <div class="message-list custom-scroll">
    <?}?>
                <? foreach($arResult['MESSAGES'] as $Message){?>
                    <?if($Message['IS_SYSTEM'] != 1){?>
                        <?if($Message['AUTHOR_ID'] == $arResult['USER_ID'])
                            $class = "right";
                        else
                            $class = "left";
                        ?>
                        <div class="message-block message-block-<?=$class?>" data-id="<?=$Message['ID']?>">
                            <div class="message-person-photo">
                                <a href="/profile_user/?ID=<?=$Message['AUTHOR_ID']?>" class="user-avatar">
                                    <? if(!empty($Message['AUTHOR_PERSONAL_PHOTO'])){ ?>
                                        <?                                             
                                            $renderImage = CFile::ResizeImageGet($Message['AUTHOR_PERSONAL_PHOTO'], Array("width" => 65, "height" => 65), BX_RESIZE_IMAGE_EXACT, false);                                            
                                        ?>
                                        <img src="<?=$renderImage["src"]?>" alt=""/>
                                    <?}else {?>
                                        <span class="user-first-letter"><?=substr($Message['AUTHOR_NAME'], 0, 1);?></span> 
                                    <? }?>
                                </a>
                            </div>
                            <div class="message-container<?if($Message['STATUS'] == MESSAGESTATUS_N){?> not-read<?}?>">
                                <div class="message-message">
                                    <div class="name-date">
                                        <p class="user-name-<?=$class?>"><?=$Message['AUTHOR_NAME'] . " " . $Message['AUTHOR_LAST_NAME'];?></p>
                                        <time datetime="<?=$Message['DATE_CREATE']?>"><?=$Message['DATE_CREATE']?></time>
                                    </div>
                                    <div class="message-content">
                                        <div class="message-text">
                                            <?if($Message['ATTACHMENTS']):?>
                                                <?if(!empty($Message['ATTACHMENTS']['IMAGE']) && is_array($Message['ATTACHMENTS']['IMAGE'])){?>
                                                    <?foreach ($Message['ATTACHMENTS']['IMAGE'] as $img):
                                                        $arFile = CFile::MakeFileArray($img);
                                                        $arr = explode('.', $arFile['name']);
                                                        if(!empty($arr)){
                                                            $expansion = array_pop($arr);
                                                            $arExpansion = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg');
                                                        ?>
                                                            <?if(in_array(strtolower($expansion), $arExpansion)):?>
                                                                <div class="message-text__file">
                                                                    <?$resizeIMG = CFile::ResizeImageGet($img, array("width" => "280", "height" => "400"), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
                                                                    <img src="<?=$resizeIMG['src']?>" class="message-text__img" data-original-image-src="<?=CFile::GetPath($img);?>">
                                                                </div>
                                                            <?else:?>
                                                                <a href="<?=CFile::GetPath($img);?>" class="message-text__file" target="_blank">
                                                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-file.png">
                                                                    <span class="message-text__name"><?=$arFile['name']?></span>
                                                                </a>
                                                            <?endif?>
                                                        <?
                                                        }
                                                    endforeach?>
                                                <?}?>
                                                <?endif?>
                                            <p><?=$Message['MESSAGE_TEXT']?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?}else{?>
                        <div class="message-block-system">
                            <p><?=$Message['MESSAGE_TEXT']?></p>
                        </div>
                    <?}?>
                <?}?>
    <?if($arResult['PAGE'] == 1){?>
                </div>
            </div>
            <?if(!$arResult['BLACKLIST'] && $arResult['DISCUSSION_USER_STATUS'] == DIALOGUSERSTATUS_I):?>
                <form id="new_message_form" action="" method="post">
                    <div class="message-chat-input">
                        <div class="message-chat-input__buttons">
                            <div class="message-chat-input__buttons_clip g-cursor-pointer" id="addFile" title="Прикрипить файл"><img src="<?=SITE_TEMPLATE_PATH?>/img/plus.png"></div>
                        </div>
                        <div class="message-input">
                            <textarea id="textMessage" class="message-text-input custom-scroll" name="MESSAGE_TEXT" placeholder="Введите сообщение" data-emojiable="true" data-emoji-input="unicode"></textarea>
                        </div>
                        <input type="file" id="uploadFile" name="IMAGE[]" multiple="multiple" style="display: none" accept="*">
                        <div class="message-chat-input__buttons">
                            <button id="sendMessage" type="submit" name="SEND" value="Y" title="Отправить сообщение"><img src="<?=SITE_TEMPLATE_PATH?>/img/speech-bubble.png"></button>
                        </div>
                    </div>
                    <div class="preview-img-block">
                    </div>
                </form>
                <?
                $signer = new \Bitrix\Main\Security\Sign\Signer;
                $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'messenger_hl.message.list');
                ?>	
                <script>
                    var MML_component = {
                        params: <?=CUtil::PhpToJSObject($arParams)?>,
                        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
                        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
                        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
                        templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
                    };
                    $(function() {
                        window.emojiPicker = new EmojiPicker({
                            emojiable_selector: '[data-emojiable=true]',
                            assetsPath: '/local/templates/anypact/img/',
                            popupButtonClasses: 'fa fa-smile-o'
                        });
                        window.emojiPicker.discover();
                    });
                </script>
            <?endif;?>
        </div>
    <?}?>
<? 
}elseif($_GET['action'] != "new_dialog" && $arResult['PAGE'] == 1){?>
    <div class="no_active_dialogs">
        <img src="/local/templates/anypact/image/dont_chat.png" alt="Нет активных диалогов"/>
        <p class="dont_chat">У вас нет активных чатов</p>
        <p class="chose_dialog">Выберите диалог или <a href="?action=new_dialog">создайте чат</a></p>
    </div>
<?
}elseif($arResult['PAGE'] >= $arResult['TOTAL_PAGE']){
?>
<script>
    $('#simple_scroll_bar .message-list').off('scroll', loadMoreMessages);
</script>
<?}?>