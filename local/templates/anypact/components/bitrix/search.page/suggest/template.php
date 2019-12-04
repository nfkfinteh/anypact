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
<div class="search-page">
<form action="" method="get">
	<?$APPLICATION->IncludeComponent(
		"bitrix:search.suggest.input",
		"",
		array(
			"NAME" => "q",
			"VALUE" => $arResult["REQUEST"]["~QUERY"],
			"INPUT_SIZE" => 40,
			"DROPDOWN_SIZE" => 10,
			"FILTER_MD5" => $arResult["FILTER_MD5"],
		),
		$component
	);?>
<input type="submit" value="<?=GetMessage("SEARCH_GO")?>" />
<input type="hidden" name="how" value="<?echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />
<?if($arParams["SHOW_WHEN"]):?>
	<script>
	var switch_search_params = function()
	{
		var sp = document.getElementById('search_params');
		var flag;
		var i;

		if(sp.style.display == 'none')
		{
			flag = false;
			sp.style.display = 'block'
		}
		else
		{
			flag = true;
			sp.style.display = 'none';
		}
		/*
		var from = document.getElementsByName('from');
		for(i = 0; i < from.length; i++)
			if(from[i].type.toLowerCase() == 'text')
				from[i].disabled = flag;
*/
		var to = document.getElementsByName('to');
		for(i = 0; i < to.length; i++)
			if(to[i].type.toLowerCase() == 'text')
				to[i].disabled = flag;

		return false;
	}
	</script>
	<br /><a class="search-page-params" href="#" onclick="return switch_search_params()"><?echo GetMessage('CT_BSP_ADDITIONAL_PARAMS')?></a>
	<div id="search_params" class="search-page-params" style="display:<?echo $arResult["REQUEST"]["FROM"] || $arResult["REQUEST"]["TO"]? 'block': 'none'?>">
		<?$APPLICATION->IncludeComponent(
			'bitrix:main.calendar',
			'',
			array(
				'SHOW_INPUT' => 'Y',
				'INPUT_NAME' => 'from',
				'INPUT_VALUE' => $arResult["REQUEST"]["~FROM"],
				'INPUT_NAME_FINISH' => 'to',
				'INPUT_VALUE_FINISH' =>$arResult["REQUEST"]["~TO"],
				'INPUT_ADDITIONAL_ATTR' => 'size="10"',
			),
			null,
			array('HIDE_ICONS' => 'Y')
		);?>
	</div>
<?endif?>
</form><br />

<?if(isset($arResult["REQUEST"]["ORIGINAL_QUERY"])):
	?>
	<div class="search-language-guess">
		<?echo GetMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?>
	</div><br /><?
endif;?>

<?if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false):?>
<?elseif($arResult["ERROR_CODE"]!=0):?>
	<p><?=GetMessage("SEARCH_ERROR")?></p>
	<?ShowError($arResult["ERROR_TEXT"]);?>
	<p><?=GetMessage("SEARCH_CORRECT_AND_CONTINUE")?></p>
	<br /><br />
	<p><?=GetMessage("SEARCH_SINTAX")?><br /><b><?=GetMessage("SEARCH_LOGIC")?></b></p>
	<table border="0" cellpadding="5">
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OPERATOR")?></td><td valign="top"><?=GetMessage("SEARCH_SYNONIM")?></td>
			<td><?=GetMessage("SEARCH_DESCRIPTION")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_AND")?></td><td valign="top">and, &amp;, +</td>
			<td><?=GetMessage("SEARCH_AND_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OR")?></td><td valign="top">or, |</td>
			<td><?=GetMessage("SEARCH_OR_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_NOT")?></td><td valign="top">not, ~</td>
			<td><?=GetMessage("SEARCH_NOT_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top">( )</td>
			<td valign="top">&nbsp;</td>
			<td><?=GetMessage("SEARCH_BRACKETS_ALT")?></td>
		</tr>
	</table>
<?elseif(count($arResult["USERS"])>0):?>
    <?//блок  пользователями?>
    <div class="row">
        <div class="col-lg-12">
            <h3>Пользователи</h3>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <?foreach($arResult["USERS"] as $arItem):?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="tender-post">
                            <a href="/profile_user/?ID=<?=$arItem['ID']?>">
                                <div class="tender-img">
                                    <?if (!isset($arItem['PERSONAL_PHOTO'])){ ?>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/no_img_pacts.jpg">
                                    <?} else {?>
                                        <img src="<?=CFile::GetPath($arItem['PERSONAL_PHOTO'])?>">
                                    <?}?>
                                </div>
                            </a>
                            <div class="tender-text">
                                <a href="/profile_user/?ID=<?=$arItem['ID']?>">
                                    <h3><?=$arItem["NAME"]?> <?=$arItem["LAST_NAME"]?></h3>
                                </a>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
    <? //пагинация ?>
    <?if($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["USER_NAV_STRING"];?>
<?elseif(count($arResult["SEARCH"])>0):?>
    <div class="row">
        <div class="col-lg-12">
            <h3>Сделки</h3>
        </div>
        <div class="col-lg-12">
            <div class="row">
            <?foreach($arResult["SEARCH"] as $arItem):?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="tender-post">
                        <a href="/pacts/view_pact/?ELEMENT_ID=<?=$arItem['ITEM_ID']?>">
                            <div class="tender-img">
                              <?if (!isset($pact['URL_IMG_PREVIEW'])){ ?>
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/no_img_pacts.jpg" alt="">
                              <?} else {?>
                                <img src="<?=$pact['URL_IMG_PREVIEW']?>" alt="">
                              <?}?>
                                <span><?=$pact["CREATED_DATE"]?></span>
                            </div>
                        </a>
                        <div class="tender-text">
                            <a href="/pacts/view_pact/?ELEMENT_ID=<?=$arItem['ITEM_ID']?>">
                                <h3><?echo $arItem["TITLE_FORMATED"]?></h3>
                                <p><?echo $arItem["BODY_FORMATED"]?></p>
                                <?/*<span class="tender-price">до <?=$pact['PROPERTIES']['SUMM_PACT']['VALUE']?> руб.</span>*/?>
                            </a>
                            <small><?=GetMessage("SEARCH_MODIFIED")?> <?=$arItem["DATE_CHANGE"]?></small>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
            </div>
        </div>
    </div>
    <? //пагинация ?>
    <?if($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["NAV_STRING"]?>
    <p>
        <?if($arResult["REQUEST"]["HOW"]=="d"):?>
            <a href="<?=$arResult["URL"]?>&amp;how=r"><?=GetMessage("SEARCH_SORT_BY_RANK")?></a>&nbsp;|&nbsp;<b><?=GetMessage("SEARCH_SORTED_BY_DATE")?></b>
        <?else:?>
            <b><?=GetMessage("SEARCH_SORTED_BY_RANK")?></b>&nbsp;|&nbsp;<a href="<?=$arResult["URL"]?>&amp;how=d"><?=GetMessage("SEARCH_SORT_BY_DATE")?></a>
        <?endif;?>
    </p>
<?else:?>
    <?ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));?>
<?endif;?>
</div>