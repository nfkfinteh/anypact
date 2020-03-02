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
        <? if($key==0){?>
            <input class="filter-price" type="text" id="minmax<?=$key?>" name="<?=$input?>" value="<?=$value?>"> -
        <?}else {?>
            <input class="filter-price" type="text" id="minmax<?=$key?>" name="<?=$input?>" value="<?=$value?>" >
        <?}?>
    <?endforeach?>
    <div id="slider"></div>
    <input type="submit" name="set_filter" class="btn btn-nfk" value="<?=GetMessage("IBLOCK_SET_FILTER")?>" style="margin-top: 15px;"/>
    <input type="hidden" name="set_filter" value="Y" />&nbsp;&nbsp;
</form>
<div class="container-img">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/pioneer_leasing_avto.png">
</div>
<script>
    $(document).ready(function(){
        var minCost2 = '#minmax0'
        var maxCost2 = '#minmax1'
        /*$("#slider").slider({
            min: 0,
            max: 30000,
            values: [0,30000],
            range: true
        });*/

        $("#slider").slider({
            min: 0,
            max: 1000000,
            values: [0,700000],
            range: true,
            stop: function(event, ui) {
                $(minCost2).val($("#slider").slider("values",0));
                $(maxCost2).val($("#slider").slider("values",1));
            },
            slide: function(event, ui){
                $(minCost2).val($("#slider").slider("values",0));
                $(maxCost2).val($("#slider").slider("values",1));
            }
        });

        $(minCost2).change(function(){
            var value1=$(minCost2).val();
            var value2=$(maxCost2).val();

            if(parseInt(value1) > parseInt(value2)){
                value1 = value2;
                $(minCost2).val(value1);
            }
            $("#slider").slider("values",0,value1);
        });

        $(maxCost2).change(function(){
            var value1=$(minCost2).val();
            var value2=$(maxCost2).val();

            //if (value2 > 30000) { value2 = 30000; $(maxCost2).val(30000)}

            if(parseInt(value1) > parseInt(value2)){
                value2 = value1;
                $(maxCost2).val(value2);
            }
            $("#slider").slider("values",1,value2);
        });
    });
</script>
