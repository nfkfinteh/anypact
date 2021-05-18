<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if(!empty($arResult["ITEMS"])): ?>
    <? foreach($arResult["ITEMS"] as $arItem): ?>
        <? if ($arItem["DISPLAY_PROPERTIES"]["VIDEO"]["FILE_VALUE"]): ?>
            <div class="video_block">
                <video src="<?= $arItem["DISPLAY_PROPERTIES"]["VIDEO"]["FILE_VALUE"]["SRC"] ?>" controls poster="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"></video>
            </div>
        <? endif ?>
    <? endforeach ?>
<? endif ?>