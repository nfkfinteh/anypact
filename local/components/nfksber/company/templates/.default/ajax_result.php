<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<div class="search-result">
    <?if($arResult['SEARCH']){?>
        <div class="search-result__body">
                <?foreach($arResult['SEARCH'] as $arItem){?>
                    <div class="staff_man" data-id="<?=$arItem['ID']?>">
                        <?$name = '';
                        if($arItem['NAME']) $name = $arItem['NAME'];
                        if($arItem['LAST_NAME']){
                            if($name) $name .= ' '.$arItem['LAST_NAME']; else $name = $arItem['LAST_NAME'];
                        }
                        if($name){?>
                            <p><?=$name?> (<?=$arItem['EMAIL']?>)</p>
                        <?}else{?>
                            <p><?=$arItem['EMAIL']?></p>
                        <?}?>
                        <div class="staff_znak_block add_staff<?if(is_array($arResult['ADD']) && in_array($arItem['ID'], $arResult['ADD'])){?> staff_znak-ok<?}?>"><span class="staff_znak"></span></div>
                    </div>
                <?}?>
        </div>
    <?}else{?>
        <div class="search-result__body">
            <p>К сожалению, по вашему запросу ничего не найдено.</p>
        </div>
    <?}?>
</div>