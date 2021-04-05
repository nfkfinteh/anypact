<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
?>
<? if ($arResult['PAGE'] == 1 && $arResult['VIA_AJAX'] != "Y") { ?>
    <div class="right-menu">
        <div class="tabs">
            <div class="point" id="notification_btn" data-content-id="notification_list">
                <div class="notification-header">
                    <div class="notification-img" title="Уведомления">
                        <svg fill="#999999" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px" viewBox="0 0 655.715 655.715" style="enable-background:new 0 0 655.715 655.715;" xml:space="preserve">
                            <g>
                                <path d="M546.429,393.429V218.572C546.429,97.854,448.574,0,327.857,0S109.286,97.854,109.286,218.572v174.857L21.857,568.286
                                h198.922c10.12,49.878,54.206,87.429,107.078,87.429c52.872,0,96.958-37.551,107.078-87.429h198.922L546.429,393.429z
                                    M327.857,612c-28.502,0-52.522-18.294-61.55-43.714h123.121C380.38,593.706,356.359,612,327.857,612z M87.429,524.571
                                L153,393.429V218.572c0-96.565,78.292-174.857,174.857-174.857c96.564,0,174.857,78.292,174.857,174.857v174.857l65.571,131.143
                                H87.429z" />
                            </g>
                        </svg>
                    </div>
                    <? if ($arResult['TOTAL_COUNT'] > 0) { ?>
                        <div class="notification_count">
                            <span><? echo ($arResult['TOTAL_COUNT'] > 9) ? '9+' : $arResult['TOTAL_COUNT'] ?></span>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
        <div id="notification_list" class="content">
            <div class="list-person-conversation custom-scroll">
            <? } ?>
            <? if ($arResult['ITEMS']) {
                foreach ($arResult['ITEMS'] as $key => $value) { ?>
                    <div data-id="<?= $value['ID'] ?>" class="person-conversation<? if (!$value['UF_READED']) { ?> not-read<? } ?>">
                        <div class="person-conversation-photo<? if ($value['UF_IS_SYSTEM']) { ?> system<? } ?>">
                            <? if ($value['UF_IS_SYSTEM']) { ?>
                                <img src="<?= SITE_TEMPLATE_PATH ?>/img/map_icon.png">
                            <? } elseif (!empty($value['UF_FROM_USER']['PERSONAL_PHOTO'])) { ?>
                                <? $renderImage = CFile::ResizeImageGet($value['UF_FROM_USER']['PERSONAL_PHOTO'], array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, false); ?>
                                <a href="/profile_user/?ID=<?= $value['UF_FROM_USER']['ID']; ?>">
                                    <img src="<?= $renderImage['src'] ?>">
                                </a>
                            <? } else { ?>
                                <img src="<?= SITE_TEMPLATE_PATH ?>/image/people-search-no-phpto.png">
                            <? } ?>
                        </div>
                        <div class="notification-block">
                            <div class="notification-name-date">
                                <? if ($value['UF_IS_SYSTEM']) { ?>
                                    <div class="person-conversation-name system"><strong>Система</strong></div>
                                <? } elseif (!empty($value['UF_FROM_USER']['LAST_NAME']) || !empty($value['UF_FROM_USER']['NAME']) || !empty($value['UF_FROM_USER']['SECOND_NAME'])) { ?>
                                    <a href="/profile_user/?ID=<?= $value['UF_FROM_USER']['ID']; ?>">
                                        <div class="person-conversation-name"><strong><? echo $value['UF_FROM_USER']['NAME'] . " " . $value['UF_FROM_USER']['LAST_NAME']; ?></strong></div>
                                    </a>
                                <? } else { ?>
                                    <div class="person-conversation-name"><strong>Безымянный</strong></div>
                                <? } ?>
                                <div class="person-conversation-date">
                                    <? $dataTIme = new DateTime($value['UF_DATE_CREATE']);
                                    if ($dataTIme->format('d.m.Y') == date('d.m.Y'))
                                        echo $dataTIme->format('H:i:s');
                                    else
                                        echo $dataTIme->format('d.m.Y');
                                    ?>
                                </div>
                            </div>
                            <div class="notification-message-block">
                                <? echo $value['UF_TEXT']; ?>
                            </div>
                            <div class="delete">
                                <div class="not-delete">Удалить</div>
                            </div>
                        </div>
                    </div>
                <? }
            } elseif ($arResult['PAGE'] == 1) { ?>
                <div class="no-notification">
                    <img src="/local/templates/anypact/image/dont_chat.png" alt="Уведомления отсутсвуют" />
                    <p>Уведомления отсутсвуют</p>
                </div>
            <? } ?>
            <? if ($arResult['PAGE'] == 1 && $arResult['VIA_AJAX'] != "Y") { ?>
            </div>
            <? if ($arResult['TOTAL_COUNT'] > 0) { ?>
                <div class="delete-all">
                    <div id="delete-all-note">Удалить все</div>
                </div>
            <? } ?>
        </div>
        <?
                $signer = new \Bitrix\Main\Security\Sign\Signer;
                $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'notification.list');
        ?>
        <script>
            var NL_component = {
                params: <?= CUtil::PhpToJSObject($arParams) ?>,
                signedParamsString: '<?= CUtil::JSEscape($signedParams) ?>',
                siteID: '<?= CUtil::JSEscape($component->getSiteId()) ?>',
                ajaxUrl: '<?= CUtil::JSEscape($component->getPath() . '/ajax.php') ?>',
                templateFolder: '<?= CUtil::JSEscape($templateFolder) ?>',
            };
        </script>
    </div>
<? } ?>