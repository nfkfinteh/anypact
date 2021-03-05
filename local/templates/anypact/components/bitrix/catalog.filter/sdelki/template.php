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
<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="form-filter">
	<?foreach($arResult["ITEMS"] as $arItem):
		if(array_key_exists("HIDDEN", $arItem)):
			echo $arItem["INPUT"];
		endif;
	endforeach;?>
    <span style="margin-top: 0">Ключевое слово</span>
    <input type="text" name="<?=$arResult['ITEMS']['NAME']['INPUT_NAME']?>" value="<?=$arResult['ITEMS']['NAME']['INPUT_VALUE']?>" size="15" maxlength="50" class="filter-key" placeholder="Например, продать автомобиль" />
    <span class="filter-date__title">
        Дата <a href="javascript:undefined" class="filter-date_reset" style="display: none">Сбросить</a>
    </span>
    <?foreach ($arResult['ITEMS']['DATE_ACTIVE_FROM']['INPUT_NAMES'] as $key => $input):?>
        <?
        if(!empty($arResult['ITEMS']['DATE_ACTIVE_FROM']['INPUT_VALUES'])){
            $value = $arResult['ITEMS']['DATE_ACTIVE_FROM']['INPUT_VALUES'][$key];
        }
        ?>
        <input id="<?=$input?>" class="filter-date js-filter-date" type="text" name="<?=$input?>" placeholder="--/--/---" value="<?=$value?>" readonly>
        <?if($key==0) echo '-';?>
    <?endforeach?>

    <select id="LOCATION_CITY" name="<?=$arResult['ITEMS']['PROPERTY_22']['INPUT_NAME']?>" class="selectbox-select select__margin js-location-city" placeholder="Выберите город" >
        <option value="">Выбор города</option>
        <? foreach($arResult['LIST_CITY'] as $item):?>
            <option value="<?=$item?>" <?if($arResult['ITEMS']['PROPERTY_22']['INPUT_VALUE'] == $item):?>selected<?endif?>>
                <?=$item?>
            </option>
        <? endforeach?>
    </select>
    <span>Цена, руб.</span>
    <?foreach ($arResult['ITEMS']['PROPERTY_14']['INPUT_NAMES'] as $key => $input):?>
        <? if($key==0):?>
            <?
            if(!empty($arResult['ITEMS']['PROPERTY_14']['INPUT_VALUES'][$key])){
                $value = $arResult['ITEMS']['PROPERTY_14']['INPUT_VALUES'][$key];
            }
            else{
                $value = $arResult['arrPrice']['LEFT'];
            }
            ?>
            <input class="filter-price" type="text" id="minmax<?=$key?>" name="<?=$input?>" value="<?=$value?>"> -
        <?else:?>
            <?
            if(!empty($arResult['ITEMS']['PROPERTY_14']['INPUT_VALUES'][$key])){
                $value = $arResult['ITEMS']['PROPERTY_14']['INPUT_VALUES'][$key];
            }
            else{
                $value = $arResult['arrPrice']['RIGHT'];
            }
            ?>
            <input class="filter-price" type="text" id="minmax<?=$key?>" name="<?=$input?>" value="<?=$value?>" >
        <?endif?>
    <?endforeach?>
    <div id="slider"></div>
    <input type="submit" name="set_filter" class="btn btn-nfk" value="Поиск" style="margin-top: 15px;"/>
    <input type="submit" name="del_filter" class="btn btn-nfk" value="Сбросить" style="margin-top: 15px;"/>
    <input type="hidden" name="set_filter" value="Y" />&nbsp;&nbsp;
</form>
<div class="container-img">
    <a href="https://pioneer-leasing.ru/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/pioneer_leasing_avto.jpg" alt="Пионер-лизинг"></a>
</div>

<script type="text/javascript">
    var smartFilter = <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>;
</script>
