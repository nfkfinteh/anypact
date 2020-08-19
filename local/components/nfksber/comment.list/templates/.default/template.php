<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>
<h5 class="mt-5">Комментарии</h5>
<div id='comment_container_list'>
    <?if ($_POST["ACTION_NAV"] == 'nav') $GLOBALS["APPLICATION"]->RestartBuffer();?>
        <?foreach ($arResult['ITEMS'] as $item):?>
            <div class="row align-items-center mt-4 comment-block">
                <div class="col-3 col-sm-2  col-lg-1">
                    <?
                    $resizeImg = "/upload/resize_image/".$item['USER']['ID']."/resize_image_65px.img";
                    if(mkdir($_SERVER['DOCUMENT_ROOT']."/upload/resize_image/".$item['USER']['ID'], 0755, true));
                    $res = CFile::ResizeImageFile($item['USER']['PERSONAL_PHOTO'], $resizeImg, array("width" => "65", "height" => "65"), BX_RESIZE_IMAGE_EXACT);
                    ?>
                    <img src="<?=$resizeImg?>" class="cardPact-comment-avatar" alt="">
                </div>
                <div class="col-9 col-sm-10 col-lg-7">
                    <div class="cardPact-comment-header">
                        <div style="float:left">
                            <span><a href="/profile_user/?ID=<?=$item['USER']['ID']?>"><?=$item['USER']['FULL_NAME']?></a></span>
                        </div>
                        <div style="float:right">
                            <?if($item['USER']['ID'] == $arResult['CURENT_USER']['ID'] && !$arResult['BLACKLIST']):?>
                                <span class="cardPact-comment-edit" data-comment="<?=$item['ID']?>">Редактировать</span><br>
                            <?endif?>
                            <?if($item['USER']['ID'] == $arResult['CURENT_USER']['ID'] || $arResult['CURENT_USER']['ID'] == $arResult['USER_CREATE_SDELKA']):?>
                                <span class="cardPact-comment-delete" data-comment="<?=$item['ID']?>">Удалить</span><br>
                            <?endif?>
                            <span class="cardPact-comment-date"><?=$item['UF_TIME_CREATE_MSG']?></span>
                        </div>

                    </div>
                </div>
                <div class="offset-3 offset-sm-2 offset-lg-1 col-9 col-sm-10 col-lg-7">
                    <div class="cardPact-comment-body">
                       <?=$item['UF_TEXT_MESSAGE']?>
                    </div>
                </div>
            </div>
        <?endforeach?>
        <?if(!$arResult['BLACKLIST']){?>
            <?if($_POST['COMMENT_EDIT']=='Y'):?>
                <div class="col-sm-10 col-lg-8" id="edit_comment_container">
                    <textarea placeholder="Оставьте Ваш комментарий"  maxlength="500"><?=trim($arResult['EDIT_COMMENT']['UF_TEXT_MESSAGE'])?></textarea>
                    <input type='hidden' name='ACTION' value='add' />
                    <button class="btn btn-nfk cardPact-comment-submit" data-coment_id="<?=$arResult['EDIT_COMMENT']['ID']?>">Сохранить</button>
                </div>
            <?else:?>
                <div class="col-sm-10 col-lg-8" id="comment_container">
                    <textarea placeholder="Оставьте Ваш комментарий"  maxlength="500"></textarea>
                    <input type='hidden' name='ACTION' value='add' />
                    <button class="btn btn-nfk cardPact-comment-submit">Комментировать</button>
                </div>
            <?endif?>
        <?}?>
        <script>
            var bitrixJS = <?=CUtil::PhpToJSObject($arResult['JS_DATA'])?>
        </script>

    <?if ($_POST["ACTION_NAV"] == 'nav') die();?>
</div>