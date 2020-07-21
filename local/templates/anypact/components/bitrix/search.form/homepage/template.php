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
$this->setFrameMode(true);?>

<div class="search">
    <form action="<?=$arResult["FORM_ACTION"]?>" onsubmit="ym(64629523,'reachGoal','search');">
        <span class="magnifier"></span>
        <input type="text" name="q" placeholder="Введите ваш запрос" value="">
        <input name="s" type="submit" class="btn btn-nfk btn-search d-none d-md-inline" value="<?=GetMessage("BSF_T_SEARCH_BUTTON");?>" style="border: 1px solid #ff6416 !important;"/>
        <a class="region d-none d-md-inline" href="#city_choose"><?=$arParams['LOCATION']?></a>
        <span class="deal-type" id="button_select_category">
            <span class="d-none d-md-inline">Вид сделки</span>
            <span class="dropdown-arrow-deal"></span>
        </span>
        <?
        $APPLICATION->IncludeComponent(
            "nfksber:sectionlist",
            "homepage",
            Array(
                "IBLOCK_ID" => "3",
                "SECTION_ID" => 0,
            )
        );
        ?>
    </form>
</div>
