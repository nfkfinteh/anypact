<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult["ITEMS"])) : ?>
    <? foreach ($arResult["ITEMS"] as $arItem) : ?>
        <? if ($arItem["DISPLAY_PROPERTIES"]["VIDEO"]["FILE_VALUE"]) : ?>
            <div class="video_block">
                <video controls poster="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>">
                    <source src="<?= $arItem["DISPLAY_PROPERTIES"]["VIDEO"]["FILE_VALUE"]["SRC"] ?>" src="video/bt-1400.mp4" type='video/mp4' />
                </video>
            </div>
        <? endif ?>
    <? endforeach ?>
<? endif ?>