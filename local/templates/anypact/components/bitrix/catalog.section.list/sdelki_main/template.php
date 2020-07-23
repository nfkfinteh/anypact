<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
?>
<div class="owl-carousel owl-theme main-carousel_sdel">
    <?foreach ($arResult['SECTIONS'] as $section):?>
        <div class="item card-deal">
            <?if(!empty($arResult['UF_FIELDS'][$section['ID']]['UF_ICON'])):?>
                <i class="icon-main <?=$arResult['UF_FIELDS'][$section['ID']]['UF_ICON']?>"></i>
            <?endif?>
            <h5 style="min-height: 54px;"><?=$section['NAME']?></h5>
            <?=$section['DESCRIPTION']?>
            <?
                $URL_Section = '/pacts/?SECTION_ID='.$section['ID'];
                // частный случай
                if($section['ID'] == "29"){
                    $URL_Section = 'https://nfksber.ru/open_account/';
                }
            ?>
            <a href="<?=$URL_Section?>" class="card-deal__button">Перейти</a>
        </div>
    <?endforeach?>
</div>