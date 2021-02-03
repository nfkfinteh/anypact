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
if($arResult['COUNT'] > 0){?>
    <div class="global-unread-message-count"><?=$arResult['COUNT']?></div>
<?
}
?>
<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'messenger_hl.unread.wiget');
?>	
<script>
    var MUW_component = {
        params: <?=CUtil::PhpToJSObject($arParams)?>,
        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
        templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
    };
</script>