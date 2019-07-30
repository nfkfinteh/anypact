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
<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="post" class="form-filter">
	<?foreach($arResult["ITEMS"] as $arItem):
		if(array_key_exists("HIDDEN", $arItem)):
			echo $arItem["INPUT"];
		endif;
	endforeach;?>
    <span>Дата</span>
    <?foreach ($arResult['ITEMS']['DATE_ACTIVE_FROM']['INPUT_NAMES'] as $key => $input):?>
        <?
        if(!empty($arResult['ITEMS']['DATE_ACTIVE_FROM']['INPUT_VALUES'])){
            $value = $arResult['ITEMS']['DATE_ACTIVE_FROM']['INPUT_VALUES'][$key];
        }
        ?>
        <input id="<?=$input?>" class="filter-date" type="text" name="<?=$input?>" placeholder="--/--/---" value="<?=$value?>">
        <?if($key==0) echo '-';?>
    <?endforeach?>
    <span>Цена, руб.</span>
    <?foreach ($arResult['ITEMS']['PROPERTY_14']['INPUT_NAMES'] as $key => $input):?>
        <?
        if(!empty($arResult['ITEMS']['PROPERTY_14']['INPUT_VALUES'])){
            $value = $arResult['ITEMS']['PROPERTY_14']['INPUT_VALUES'][$key];
        }
        ?>
        <input class="filter-price" type="text" id="<?=$input?>" name="<?=$input?>" value="<?=$value?>">
        <?if($key==0) echo '-';?>
    <?endforeach?>

    <input type="submit" name="set_filter" class="btn btn-nfk" value="<?=GetMessage("IBLOCK_SET_FILTER")?>" style="margin-top: 15px;"/>
    <input type="hidden" name="set_filter" value="Y" />&nbsp;&nbsp;
</form>
<div id="slider"></div>
