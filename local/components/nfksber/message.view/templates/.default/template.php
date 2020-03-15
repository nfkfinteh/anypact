<?
    $arMessage  =  json_decode($arResult['MESSAGES']['UF_TEXT_MESSAGE_USER'], true);
    $UserID = CUser::GetID();
    // массив пользователей
    $arrIDUserChat = array();
    foreach($arResult['UsersChart'] as $Item){
        $arrIDUserChat[] = $Item["ID"];
    }
    $arImgFormat = ['jpg', 'png', 'svg', 'jpeg'];
?>
<? // если пользователь входит в масив то открываем ему сообщения?>
<?if(in_array($UserID, $arrIDUserChat)){?>
    <input hidden value="<?=$UserID?>" id="IDUSER" />
        <div>
            <h1 class="mb-4">Мои сообщения</h1>
            <div class="row pt-2 pb-5">
                <div class="col-md-4 col-sm-12">
                    <h3 class="font-weight-bold">Участники</h3>
                    <ul class="list-person-conversation">
                        <? foreach($arResult['UsersChart'] as $user){ ?>
                            <li class="person-conversation">
                                <div class="person-conversation-photo">                                
                                    <?if ($user['PERSONAL_PHOTO'] !=''){?>
                                        <? $renderImage = CFile::ResizeImageGet($user['PERSONAL_PHOTO'], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false); ?>                               
                                        <img src="<?=$renderImage['src']?>">
                                    <?}else {?>
                                        <span class="user-first-letter" style="padding:13px;font-size: 28px;"><?=substr($user['NAME'], 0, 1);?></span>
                                    <?}?>
                                    <?/*<img src="<?=SITE_TEMPLATE_PATH?>/image/sample_face_150x150.png" alt="Васильев Александр Евгеньевич">*/?>
                                </div>
                                <div class="person-conversation-name"><?=$user['LAST_NAME']?> <?=$user['NAME']?> <?=$user['SECOND_NAME']?></div>
                            </li>
                        <?}?>
                    </ul>
                    <?/*<button class="btn btn-nfk btn-add-person w-100">+ добавить участника</button>*/?>
                    <button class="btn btn-nfk btn-add-person w-100 js-chat_delete">Удалить всю переписку</button>
                </div>
                <div class="col-md-8 col-sm-12">
                    <h3 class="font-weight-bold"><?=$arResult['MESSAGES']['UF_TITLE_MESSAGE']?></h3>
                    <div class="message-list">
                        <?if ($_REQUEST["ACTION"] == 'up_message') $GLOBALS["APPLICATION"]->RestartBuffer();?>
                        <? foreach($arMessage as $Message){?>
                            <? // сообщения текущего пользователя ?>
                            <?if($Message['user'] == $UserID){?>
                                <div class="message-block message-block-left">
                                    <div class="message-person-photo">
                                        <div class="user-avatar">                                                                              
                                            <? if($arResult['UsersChart'][$Message['user']]['PERSONAL_PHOTO'] != ''){ ?>
                                                <?                                             
                                                    $arFile = CFile::GetFileArray($arResult['UsersChart'][$Message['user']]['PERSONAL_PHOTO']);
                                                    $renderImage = CFile::ResizeImageGet($arFile, Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false);                                            
                                                ?>
                                                <img src="<?=$renderImage["src"]?>" alt=""/>
                                            <?}else {?>
                                                <span class="user-first-letter"><?=$arResult['FastUserParams'][$Message['user']]['InitialName']?></span> 
                                            <? }?>
                                        </div>
                                    </div>
                                    <div class="message-container">
                                        <div class="message-message">
                                            <p class="user-name-right"><?=$arResult['FastUserParams'][$Message['user']]['FIO']?></p>
                                            <div class="message-content">
                                                <div class="message-text">
                                                    <?if($Message['file']):?>
                                                        <?foreach ($Message['file'] as $file):?>
                                                            <?if(in_array($file['file_format'], $arImgFormat)):?>
                                                                <div class="message-text__file">
                                                                    <img src="<?=$file['src']?>" class="message-text__img">
                                                                </div>
                                                            <?else:?>
                                                                <a href="<?=$file['src']?>" class="message-text__file" target="_blank">
                                                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-file.png" class="message-text__img">
                                                                    <span class="message-text__name"><?=$file['file_name']?></span>
                                                                </a>
                                                            <?endif?>
                                                        <?endforeach?>
                                                    <?else:?>
                                                        <p><?=$Message['message']?></p>
                                                    <?endif?>
                                                    <time datetime="2019-03-01T15:12:13+03:00"><?=$Message['data']?></time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                        
                            <?}else {?>                            
                                <div class="message-block message-block-right">                        
                                    <div class="message-person-photo">
                                        <div class="user-avatar">                                        
                                            <? if($arResult['UsersChart'][$Message['user']]['PERSONAL_PHOTO'] != ''){ ?>
                                                <?                                             
                                                    $arFile = CFile::GetFileArray($arResult['UsersChart'][$Message['user']]['PERSONAL_PHOTO']);
                                                    $renderImage = CFile::ResizeImageGet($arFile, Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false);                                            
                                                ?>
                                                <img src="<?=$renderImage["src"]?>" alt="">                                            
                                            <?}else {?>
                                                <span class="user-first-letter"><?=$arResult['FastUserParams'][$Message['user']]['InitialName']?></span>
                                            <? } ?>
                                        </div>
                                    </div>
                                    <div class="message-container">
                                        <div class="message-message">
                                            <p class="user-name-left"><?=$arResult['FastUserParams'][$Message['user']]['FIO']?></p>
                                            <div class="message-content">
                                                <div class="message-text">
                                                    <?if($Message['file']):?>
                                                        <?foreach ($Message['file'] as $file):?>
                                                            <?if(in_array($file['file_format'], $arImgFormat)):?>
                                                                <div class="message-text__file">
                                                                    <img src="<?=$file['src']?>" class="message-text__img">
                                                                </div>
                                                            <?else:?>
                                                                <a href="<?=$file['src']?>" class="message-text__file" target="_blank">
                                                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-file.png" class="message-text__img">
                                                                    <span class="message-text__name"><?=$file['file_name']?></span>
                                                                </a>
                                                            <?endif?>
                                                        <?endforeach?>
                                                    <?else:?>
                                                        <p><?=$Message['message']?></p>
                                                    <?endif?>
                                                    <time datetime="2019-03-01T15:12:13+03:00"><?=$Message['data']?></time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?}?>
                        
                        <?}?>
                        <?if ($_REQUEST["ACTION"] == 'up_message') die();?>
                        <!------>
                    </div>
                    <div class="message-chat-input">
                        <!-- <button class="mr-1 mr-sm-3"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-image-file.png" alt=""></button>
                        <button class="mr-2 mr-sm-4"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-file.png" alt=""></button> -->
                        <textarea name="" id="textMessage" placeholder="Введите сообщение"></textarea>
                        <input type="file" id="uploadFile" name="uploadFile[]" multiple="multiple" style="display: none">
                        <div class="message-chat-input__buttons">
                            <button class="ml-1 mr-0 mx-sm-4" id="sendMessage"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-sent.png"></button>
                            <button class="ml-1 mr-0 mx-sm-4 message-chat-input__buttons_clip" id="sendFile"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-image-file.png"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?}else {?>
    <?  // заглушка ошибка чатом
        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/dont_chat.php", Array());            
    ?>
<?}?>