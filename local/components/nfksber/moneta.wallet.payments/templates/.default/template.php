<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
function hideText($text, $count_b = 3, $count_e = 2) {
    if(empty(trim($text)))
        return $text;
    $text1 = substr($text, 0, $count_b);
    $text2 = substr($text, $count_b);
    $text1 .= str_repeat("*", strlen($text2)-$count_e);
    if($count_e != 0)
        $text1 .= substr($text, -$count_e);
    return $text1;
}

if($arResult['STATUS'] == "SUCCESS"){?>
    <div class="bank-cards-col" data-id="<?=$arResult['CART_ID']?>">
        <div class="bank-card_item">
            <img src="<?=SITE_TEMPLATE_PATH.'/image/some-card.png'?>" alt="">
        </div>
        <div class="bank-card_item">
            <h5><?=$arResult['CARD_NAME']?></h5>
            <p><? $hide = hideText($arResult['CARD_NUMBER'], 0, 4);echo substr($hide, 0, 4)." ".substr($hide, 4, 4)." ".substr($hide, 8, 4)." ".substr($hide, 12, 4);?></p>
        </div>
    </div>
<?
}else{

?>
<div class="bank-cards">
    <div class="bank-cards_container-head">
        <div class="bank-cards-col-head">
            <p>Банковские счета и карты</p>
        </div>
        <div class="bank-cards-col-head">
            <?/*?><div class="menu-wallet-dots">
                <span></span>
            </div><?*/?>
        </div>
    </div>
    <div id="cart_items">
        <?if(!empty($arResult) && is_array($arResult)){?>
            <?foreach($arResult as $key => $value){?>
                <div class="bank-cards-col" data-id="<?=$value['ID']?>">
                    <div class="bank-card_item">
                        <img src="<?=SITE_TEMPLATE_PATH.'/image/some-card.png'?>" alt="">
                    </div>
                    <div class="bank-card_item">
                        <h5><?=$value['UF_CARD_NAME'];?></h5>
                        <p><? $hide = hideText($value['UF_CARD_NUMBER'], 0, 4);echo substr($hide, 0, 4)." ".substr($hide, 4, 4)." ".substr($hide, 8, 4)." ".substr($hide, 12, 4);?></p>
                    </div>
                </div>
            <?}?>
        <?}?>
    </div>
    <?if(empty($arResult) || count($arResult) < 5 ){?>
        <div class="bank-cards-col" id="add_cart">
            <div class="bank-card_item">
                <img src="<?=SITE_TEMPLATE_PATH.'/image/add-card.svg'?>" alt="" id="addCard">
            </div>
            <div class="bank-card_item">
                <p>Добавить счет или карту</p>
            </div>
        </div>
    <?}?>
</div>
<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'moneta.wallet.payments');
?>
<script>
    var MWP_component = {
        params: <?=CUtil::PhpToJSObject($arParams)?>,
        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
        templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
    };
</script>
<?}?>