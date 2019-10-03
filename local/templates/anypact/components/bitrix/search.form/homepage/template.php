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
        <form action="<?=$arResult["FORM_ACTION"]?>">
            <span class="magnifier"></span>
            <input type="text" name="q" placeholder="Поиск" value="">
			<input name="s" type="submit" class="btn btn-nfk btn-search" value="<?=GetMessage("BSF_T_SEARCH_BUTTON");?>" style="border: 1px solid #ff6416 !important;"/>
            <span class="region"><?=$arParams['LOCATION']?></span>
            <span class="deal-type" id="button_select_category">Вид сделки</span>
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
