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

function bildPosts($arPosts, $arResult){
    ob_start();
    foreach($arPosts as $arPost){?>
        <div class="new-profile-posts_container" js-like-selector data-id="<?=$arPost['ID']?>" data-type="post">
            <div class="wall-photo">
                <?if(!empty($arPost['AUTHOR']['PERSONAL_PHOTO'])){
                    $renderImage = CFile::ResizeImageGet($arPost['AUTHOR']['PERSONAL_PHOTO'], Array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, false);
                }else{
                    $renderImage['src'] = SITE_TEMPLATE_PATH."/image/people-search-no-phpto.png";
                }?>
                <a href="/profile_user/?ID=<?=$arPost['AUTHOR']['ID']?>"><img src="<?=$renderImage["src"]?>" alt=""></a>
                <h5 class="mobile"><?=$arPost['AUTHOR']['LAST_NAME']." ".$arPost['AUTHOR']['NAME']." ".$arPost['AUTHOR']['SECOND_NAME']?></h5>
            </div>
            <div class="wall-post-container new-profile_block-bb">
                <h5 class="desc"><?=$arPost['AUTHOR']['LAST_NAME']." ".$arPost['AUTHOR']['NAME']." ".$arPost['AUTHOR']['SECOND_NAME']?></h5>
                <div class="position-relative">
                    <span class="post-date">
                        <?list($date, $time) = explode(" ", $arPost['DATE_CREATE']);
                        if($date == date('d.m.Y'))
                            echo $time;
                        else
                            echo $date;
                        ?>
                    </span>
                    <?if($arPost['AUTHOR']['ID'] == $arResult['CURRENT_USER']['ID'] || $arResult['USER']['ID'] == $arResult['CURRENT_USER']['ID']){?>
                        <div class="delete-post">Удалить</div>
                    <?}?>
                </div>
                <div class="the-post">
                    <div class="the-post_item">
                        <p><?=$arPost['TEXT']?></p>
                        <?if(!empty($arPost['FILES']['IMAGES'])){
                            foreach($arPost['FILES']['IMAGES'] as $img){
                                $arImg = CFile::GetFileArray($img);?>
                                <img src="<?=$arImg['SRC']?>" title="<?=$arImg['ORIGINAL_NAME']?>">
                            <?}
                        }?>
                        <?if(!empty($arPost['FILES']['DOCS'])){?>
                            <div class="post-doc-file">
                                <?foreach($arPost['FILES']['DOCS'] as $doc){
                                    $arDoc = CFile::GetFileArray($doc);?>
                                    <a href="<?=$arDoc['SRC']?>" target="_blank">
                                        <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-file.png">
                                        <span><?=$arDoc['ORIGINAL_NAME']?></span>
                                    </a>
                                <?}?>
                            </div>
                        <?}?>
                    </div>
                    <div class="post-tools">
                        <div class="post-tools_item">
                            <div class="img tool-like <?if(in_array($arResult['CURRENT_USER']['ID'], $arPost['LIKES'])){?>active<?}?>"></div>
                            <span><?=count($arPost['LIKES'])?></span>
                        </div>
                        <a class="post-tools_item" href="#post_comments_<?=$arPost['ID']?>">
                            <div class="img tool-mess"></div>
                            <span><?=$arPost['COMMENTS']['COMMENT_TOTAL_COUNT']?></span>
                        </a>
                        <div class="post-tools_item">
                            <div class="img tool-reply-mess"></div>
                            <?
                            $link = urlencode('https://anypact.ru/profile_user/?ID='.$arResult['USER']['ID'].'&post_id='.$arPost['ID']);
                            $title = urlencode("AnyPact || Запись №".$arPost['ID']." | Профиль пользователя ".$arResult['USER']['LAST_NAME']." ".$arResult['USER']['NAME']." ".$arResult['USER']['SECOND_NAME'])
                            ?>
                            <div class="post-share-popup hide">
                                <div class="ya-share2 ya-share2_inited" data-services="vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp,telegram">
                                    <div class="ya-share2__container ya-share2__container_size_m ya-share2__container_color-scheme_normal ya-share2__container_shape_normal">
                                        <ul class="ya-share2__list ya-share2__list_direction_horizontal">
                                            <li class="ya-share2__item ya-share2__item_service_vkontakte">
                                                <a class="ya-share2__link" href="https://vk.com/share.php?url=<?=$link?>&title=<?=$title?>&utm_source=share2" rel="nofollow noopener" target="_blank" title="ВКонтакте">
                                                    <span class="ya-share2__badge">
                                                        <span class="ya-share2__icon"></span>
                                                    </span>
                                                    <span class="ya-share2__title">ВКонтакте</span>
                                                </a>
                                            </li>
                                            <li class="ya-share2__item ya-share2__item_service_facebook">
                                                <a class="ya-share2__link" href="https://www.facebook.com/sharer.php?src=sp&u=<?=$link?>&title=<?=$title?>&utm_source=share2" rel="nofollow noopener" target="_blank" title="Facebook">
                                                    <span class="ya-share2__badge">
                                                        <span class="ya-share2__icon"></span>
                                                    </span>
                                                    <span class="ya-share2__title">Facebook</span>
                                                </a>
                                            </li>
                                            <li class="ya-share2__item ya-share2__item_service_odnoklassniki">
                                                <a class="ya-share2__link" href="https://connect.ok.ru/offer?url=<?=$link?>&title=<?=$title?>&utm_source=share2" rel="nofollow noopener" target="_blank" title="Одноклассники">
                                                    <span class="ya-share2__badge">
                                                        <span class="ya-share2__icon"></span>
                                                    </span>
                                                    <span class="ya-share2__title">Одноклассники</span>
                                                </a>
                                            </li>
                                            <li class="ya-share2__item ya-share2__item_service_twitter">
                                                <a class="ya-share2__link" href="https://twitter.com/intent/tweet?text=<?=$title?>&url=<?=$link?>&utm_source=share2" rel="nofollow noopener" target="_blank" title="Twitter">
                                                    <span class="ya-share2__badge">
                                                        <span class="ya-share2__icon"></span>
                                                    </span>
                                                    <span class="ya-share2__title">Twitter</span>
                                                </a>
                                            </li>
                                            <li class="ya-share2__item ya-share2__item_service_viber">
                                                <a class="ya-share2__link" href="viber://forward?text=<?=$title?>%20<?=$link?>&utm_source=share2" rel="nofollow" target="_blank" title="Viber">
                                                    <span class="ya-share2__badge">
                                                        <span class="ya-share2__icon"></span>
                                                    </span>
                                                    <span class="ya-share2__title">Viber</span>
                                                </a>
                                            </li>
                                            <li class="ya-share2__item ya-share2__item_service_whatsapp">
                                                <a class="ya-share2__link" href="https://api.whatsapp.com/send?text=<?=$title?>%20<?=$link?>&utm_source=share2" rel="nofollow noopener" target="_blank" title="WhatsApp">
                                                    <span class="ya-share2__badge">
                                                        <span class="ya-share2__icon"></span>
                                                    </span>
                                                    <span class="ya-share2__title">WhatsApp</span>
                                                </a>
                                            </li>
                                            <li class="ya-share2__item ya-share2__item_service_telegram">
                                                <a class="ya-share2__link" href="https://t.me/share/url?url=<?=$link?>&text=<?=$title?>&utm_source=share2" rel="nofollow noopener" target="_blank" title="Telegram">
                                                    <span class="ya-share2__badge">
                                                        <span class="ya-share2__icon"></span>
                                                    </span>
                                                    <span class="ya-share2__title">Telegram</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment-block" id="post_comments_<?=$arPost['ID']?>">
                    <?if(!empty($arPost['COMMENTS']['ITEMS'])){
                        echo bildComments($arPost['COMMENTS']['ITEMS'], $arResult);
                    }?>
                </div>
                <div class="reply-post">
                    <div class="new-profile-send-post">
                        <?if($arPost['COMMENTS']['COMMENT_TOTAL_PAGE'] > 1){?>
                            <a href="#" class="more-info more-info-link">Показать остальные комментарии</a>
                        <?}?>
                        <?if($arResult['FRIEND'] || $arResult['CURRENT_USER']['ID'] == $arResult['USER']['ID']){?>
                            <div class="reply-post-send">
                                <div class="wall-photo">
                                    <a href="/profile_user/?ID=<?=$arResult['CURRENT_USER']['ID']?>">
                                        <?if(!empty($arResult['CURRENT_USER']['PERSONAL_PHOTO'])){
                                            $renderImage = CFile::ResizeImageGet($arResult['CURRENT_USER']['PERSONAL_PHOTO'], Array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, false);
                                        }else{
                                            $renderImage['src'] = SITE_TEMPLATE_PATH."/image/people-search-no-phpto.png";
                                        }?>
                                        <img src="<?=$renderImage["src"]?>" alt="">
                                    </a>
                                </div>
                                <div class="reply-form-block">
                                    <form class="form_input_file" id="comment_send_<?=$arPost['ID']?>" action="" method="post">
                                        <div class="form-flex">
                                            <textarea rows="1" placeholder="Текст сообщения" name="TEXT"></textarea>
                                            <div class="input-btns">
                                                <div class="input-files">
                                                    <label for="photo_<?=$arPost['ID']?>"><img src="<?=SITE_TEMPLATE_PATH.'/image/computer-picture.svg'?>" alt=""></label>
                                                    <label for="doc_<?=$arPost['ID']?>"><img src="<?=SITE_TEMPLATE_PATH.'/image/google-docs.svg'?>" alt=""></label>
                                                    <input id="photo_<?=$arPost['ID']?>" type="file" multiple="multiple" name="img" style="display: none;" accept="image/jpeg,image/png,image/gif,video/*">
                                                    <input id="doc_<?=$arPost['ID']?>" type="file" multiple="multiple" name="doc" style="display: none;">
                                                </div>
                                            </div>
                                            <button type="submit" class="reply-send-btn" name="COMMENTED" value="Y"></button>
                                        </div>
                                        <div class="comment-answer-to"></div>
                                        <div class="preview-img-block"></div>
                                    </form>
                                </div>
                            </div>
                        <?}?>
                    </div>
                </div>
            </div>
        </div>
    <?}
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function bildComments($arComments, $arResult){
    ob_start();
    foreach($arComments as $arComment){?>
        <div class="reply-post" js-like-selector data-id="<?=$arComment['ID']?>" data-type="comment">
            <div class="wall-photo">
                <?if(!empty($arComment['AUTHOR']['PERSONAL_PHOTO'])){
                    $renderImage = CFile::ResizeImageGet($arComment['AUTHOR']['PERSONAL_PHOTO'], Array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, false);
                }else{
                    $renderImage['src'] = SITE_TEMPLATE_PATH."/image/people-search-no-phpto.png";
                }?>
                <a href="/profile_user/?ID=<?=$arComment['AUTHOR']['ID']?>"><img src="<?=$renderImage["src"]?>" alt=""></a>
            </div>
            <div class="reply-wall-post-block">
                <div class="comment-head">
                    <h5><?=$arComment['AUTHOR']['LAST_NAME']." ".$arComment['AUTHOR']['NAME']." ".$arComment['AUTHOR']['SECOND_NAME']?></h5>
                    <?if($arComment['AUTHOR']['ID'] == $arResult['CURRENT_USER']['ID'] || $arResult['USER']['ID'] == $arResult['CURRENT_USER']['ID']){?>
                        <div class="delete-comment">Удалить</div>
                    <?}?>
                </div>
                <div class="reply-post_item">
                    <div class="the-reply-post-post_item">
                        
                        <p><?if(!empty($arComment['ANSWER_TO'])){?><a href="/profile_user/?ID=<?=$arComment['ANSWER_TO']['ID']?>" target="_blank"><?=$arComment['ANSWER_TO']['LAST_NAME']." ".$arComment['ANSWER_TO']['NAME']." ".$arComment['ANSWER_TO']['SECOND_NAME']?></a>, <?}?><?=$arComment['TEXT']?></p>
                        <?if(!empty($arComment['FILES']['IMAGES'])){
                            foreach($arComment['FILES']['IMAGES'] as $img){
                                $arImg = CFile::GetFileArray($img);?>
                                <img src="<?=$arImg['SRC']?>" title="<?=$arImg['ORIGINAL_NAME']?>">
                            <?}
                        }?>
                        <?if(!empty($arComment['FILES']['DOCS'])){?>
                            <div class="post-doc-file">
                                <?foreach($arComment['FILES']['DOCS'] as $doc){
                                    $arDoc = CFile::GetFileArray($doc);?>
                                    <a href="<?=$arDoc['SRC']?>" target="_blank">
                                        <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-file.png">
                                        <span><?=$arDoc['ORIGINAL_NAME']?></span>
                                    </a>
                                <?}?>
                            </div>
                        <?}?>
                    </div>
                    <div class="post-tools">
                        <div class="post-tools_item">
                            <span class="post-date">
                                <?list($date, $time) = explode(" ", $arComment['DATE_CREATE']);
                                if($date == date('d.m.Y'))
                                    echo $time;
                                else
                                    echo $date;
                                ?>
                            </span>
                            <?if($arResult['FRIEND'] || $arResult['CURRENT_USER']['ID'] == $arResult['USER']['ID']){?>
                                <a class="comment-answer" href="#">Ответить</a>
                            <?}?>
                        </div>
                        <div class="post-tools_item">
                            <div class="img tool-like <?if(in_array($arResult['CURRENT_USER']['ID'], $arComment['LIKES'])){?>active<?}?>"></div>
                            <span>
                            <?if(is_array($arComment['LIKES'])){
                                echo count($arComment['LIKES']);
                            }else{
                                echo 0;
                            }?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?}
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

if($arResult['UPLOAD_POST'] == "Y"){
    if(!empty($arResult['POSTS'][0])){
        echo bildPosts($arResult['POSTS'], $arResult);
    }else{
        echo json_encode(false);
    }
    exit();
}?>
<?
if($arResult['UPLOAD_COMMENT'] == "Y"){
    if(!empty($arResult['COMMENTS']['ITEMS'][0])){
        echo bildComments($arResult['COMMENTS']['ITEMS'], $arResult);
    }else{
        echo json_encode(false);
    }
    exit();
}
?>
<div class="row new-profile-wall">
    <div class="col-md-12">
        <?if($arResult['CURRENT_USER']['ID'] == $arResult['USER']['ID']){?>
            <div class="new-profile-send-post">
                <div class="wall-photo">
                    <?if(!empty($arResult['CURRENT_USER']['PERSONAL_PHOTO'])){
                        $renderImage = CFile::ResizeImageGet($arResult['CURRENT_USER']['PERSONAL_PHOTO'], Array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, false);
                    }else{
                        $renderImage['src'] = SITE_TEMPLATE_PATH."/image/people-search-no-phpto.png";
                    }?>
                    <img src="<?=$renderImage["src"]?>" alt="">
                </div>
                <form class="form_input_file" id="new_post" action="" method="post">
                    <textarea rows="1" placeholder="Текст сообщения" name="TEXT"></textarea>
                    <div class="input-btns">
                        <div class="input-files">
                            <label for="photo"><img src="<?=SITE_TEMPLATE_PATH.'/image/computer-picture.svg'?>" alt=""></label>
                            <label for="doc"><img src="<?=SITE_TEMPLATE_PATH.'/image/google-docs.svg'?>" alt=""></label>
                            <input id="photo" type="file" multiple="multiple" name="img" style="display: none;" accept="image/jpeg,image/png,image/gif,video/*">
                            <input id="doc" type="file" multiple="multiple" name="doc" style="display: none;">
                        </div>
                        <button class="btn btn-nfk" type="submit" name="PUBLISH" value="Y">Опубликовать</button>
                    </div>
                    <div class="preview-img-block">
                    </div>
                </form>
            </div>
        <?
        }
        $signer = new \Bitrix\Main\Security\Sign\Signer;
        $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'user.profile.post');
        ?>
        <script>
            var UPP_component = {
                params: <?=CUtil::PhpToJSObject($arParams)?>,
                signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
                siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
                ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
                templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
            };
        </script>
        <div class="new-profile_block new-profile_block_posts">
            <?if(!empty($arResult['POSTS'])){?>
                <h2>Публикации</h2>
                <div id="post_wall">
                    <?echo bildPosts($arResult['POSTS'], $arResult);?>
                </div>
            <?}?>
        </div>
    </div>
</div>
</div>
<?if(!empty(intval($_GET['post_id'])) && !empty($arResult['CURRENT_POST'])){?>
    <div class="row new-profile-wall popup-post hide"><div class="col-md-12"><div class="new-profile_block new-profile_block_posts"><?echo bildPosts($arResult['CURRENT_POST'], $arResult);?></div></div></div>
    <script>
        var $body = $('.popup-post');
        bindDevelopmentPost($($body));
        bindDevelopmentLike($($body));
        var data = {
                TITLE: 'Запись №<?=$arResult['CURRENT_POST'][0]['ID']?>',
                BODY: $body,
                BUTTONS: [
                    {
                        NAME: 'Закрыть',
                        CLOSE: 'Y'
                    },
                ],
                ONLOAD: (function(){
                    $('.popup-post').removeClass('hide');
                }),
                CLONE: 'N'
            };
        newAnyPactPopUp(data);
    </script>
<?}?>