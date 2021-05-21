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
if ($arResult['LOAD_MORE'] == "Y") {
    if (!empty($arResult['ITEMS'])) {
        foreach ($arResult['ITEMS'] as $item) : ?>
            <div class="col-lg-4 col-md-6 col-sm-12 tender-block">
                <div class="tender-post" data-id="<?= $item['ID'] ?>">
                    <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                        <div class="tender-img">
                            <? if (!empty($item['INPUT_FILES']['VALUE'])) : ?>
                                <img src="<?= CFile::GetPath($item['INPUT_FILES']['VALUE'][0]) ?>">
                            <? else : ?>
                                <img src="/local/templates/anypact/img/no_img_pacts.jpg">
                            <? endif ?>
                        </div>
                    </a>
                    <div class="tender-text">
                        <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                            <h3><?= TruncateText($item["NAME"], 30) ?></h3>
                            <p><?= $item["CREATED_DATE"] ?></p>
                            <span class="tender-price">
                                <? if ($item['PRICE_ON_REQUEST']['VALUE_ENUM'] == "Y") { ?>
                                    Цена по запросу
                                <? } else { ?>
                                    <?= $item['SUMM_PACT']['VALUE'] . ' руб.' ?>
                                <? } ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        <? endforeach ?>
        <? if (($arResult['CURRENT_STATE'] == 'Y' && (count($arResult['ITEMS']) + count($arResult['DEL_ID'])) < $arResult['ACTIVE_ITEM_COUNT']) || ($arResult['CURRENT_STATE'] == 'N' && (count($arResult['ITEMS']) + count($arResult['DEL_ID'])) < $arResult['COMPLITE_ITEM_COUNT'])) { ?>
            <div class="extra-hide-offers">

            </div>
            <div class="col-md-12 text-center">
                <span class="more-info-link">Все предложения<span></span>
            </div>
        <? }
    } else { ?>
        <h4>Записей нет</h4>
<? }
    exit();
}

?>
<div class="row new-profile_offers">
    <? if (!$arResult["BLACK_LIST"]['CLOSED']) { ?>
        <div class="col-md-12">
            <div class="new-profile_block new-profile_block-border">
                <h2>Предложения</h2>
                <button class="btn-category <? if ($arResult['CURRENT_STATE'] == 'Y') : ?>active<? endif ?>" data-state="Y" data-user="<?= $arResult['USER']['ID'] ?>" data-type="user">
                    Активные <span><?= $arResult['ACTIVE_ITEM_COUNT'] ?></span>
                </button>
                <button class="btn-category <? if ($arResult['CURRENT_STATE'] == 'N') : ?>active<? endif ?>" data-state="N" data-user="<?= $arResult['USER']['ID'] ?>" data-type="user">
                    Завершенные <span><?= $arResult['COMPLITE_ITEM_COUNT'] ?></span>
                </button>
                <div class="row mt-4 tenders__row">
                    <? if (!empty($arResult['ITEMS'])) {
                        foreach ($arResult['ITEMS'] as $item) : ?>
                            <div class="col-lg-4 col-md-6 col-sm-12 tender-block">
                                <div class="tender-post" data-id="<?= $item['ID'] ?>">
                                    <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                        <div class="tender-img">
                                            <? if (!empty($item['INPUT_FILES']['VALUE'])) : ?>
                                                <img src="<?= CFile::GetPath($item['INPUT_FILES']['VALUE'][0]) ?>">
                                            <? else : ?>
                                                <img src="/local/templates/anypact/img/no_img_pacts.jpg">
                                            <? endif ?>
                                        </div>
                                    </a>
                                    <div class="tender-text">
                                        <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                            <h3><?= TruncateText($item["NAME"], 30) ?></h3>
                                            <p><?= $item["CREATED_DATE"] ?></p>
                                            <span class="tender-price">
                                                <? if ($item['PRICE_ON_REQUEST']['VALUE_ENUM'] == "Y") { ?>
                                                    Цена по запросу
                                                <? } else { ?>
                                                    <?= $item['SUMM_PACT']['VALUE'] . ' руб.' ?>
                                                <? } ?>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <? endforeach ?>
                        <? if (($arResult['CURRENT_STATE'] == 'Y' && count($arResult['ITEMS']) < $arResult['ACTIVE_ITEM_COUNT']) || ($arResult['CURRENT_STATE'] == 'N' && count($arResult['ITEMS'])  < $arResult['COMPLITE_ITEM_COUNT'])) { ?>
                            <div class="extra-hide-offers">

                            </div>
                            <div class="col-md-12 text-center">
                                <span class="more-info-link">Все предложения<span></span>
                            </div>
                        <? } ?>
                    <? } else { ?>
                        <h4>Записей нет</h4>
                    <? } ?>
                    <?
                    $signer = new \Bitrix\Main\Security\Sign\Signer;
                    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'user.profile.pacts');
                    ?>
                    <script>
                        var UPPA_component = {
                            params: <?= CUtil::PhpToJSObject($arParams) ?>,
                            signedParamsString: '<?= CUtil::JSEscape($signedParams) ?>',
                            siteID: '<?= CUtil::JSEscape($component->getSiteId()) ?>',
                            ajaxUrl: '<?= CUtil::JSEscape($component->getPath() . '/ajax.php') ?>',
                            templateFolder: '<?= CUtil::JSEscape($templateFolder) ?>',
                        };
                    </script>
                </div>
            </div>
        </div>
    <? } ?>
</div>