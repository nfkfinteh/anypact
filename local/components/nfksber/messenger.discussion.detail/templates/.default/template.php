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
if($arResult['DISCUSSION']){?>
    <div class="discussion">
        <div id="dialog_edit">
            <form action="" method="post" name="edit_dialog">
                <div class="avatar-name-block">
                    <div class="user_profile_form_editdata_foto">
                        <?if($arResult['USER_ID'] == $arResult['DISCUSSION']['AUTHOR_ID']){?>
                            <?if(!empty($arResult['DISCUSSION']['AVATAR'])){?>
                                <div class="delete-img"></div>
                            <?}?>
                            <a href="#" class="edit_user_photo">
                                <?if(!empty($arResult['DISCUSSION']['AVATAR'])){?>
                                    <img src="<?=CFile::GetPath($arResult['DISCUSSION']['AVATAR']);?>">
                                <?}else{?>
                                    <img src="/local/templates/anypact/img/user_profile_no_foto.png">
                                <?}?>
                            </a>
                            <input type="hidden" name="DISCUSSION[AVATAR]" value="<?=$arResult['DISCUSSION']['AVATAR']?>"/>
                        <?}else{?>
                            <div class="discussion-photo">
                                <?if(!empty($arResult['DISCUSSION']['AVATAR'])){?>
                                    <img src="<?=CFile::GetPath($arResult['DISCUSSION']['AVATAR']);?>">
                                <?}else{?>
                                    <span class="user-first-letter"><?=substr($arResult['DISCUSSION']['NAME'], 0, 1);?></span>
                                <?}?>
                            </div>
                        <?}?>
                    </div>
                    <?if($arResult['USER_ID'] == $arResult['DISCUSSION']['AUTHOR_ID']){?>
                        <input name="DISCUSSION[NAME]" placeholder="Название беседы" value="<?=$arResult['DISCUSSION']['NAME']?>">
                    <?}else{?>
                        <div class="discussion-name"><div><?=$arResult['DISCUSSION']['NAME']?></div></div>
                    <?}?>
                </div>
                <?
                if($arResult['USER_ID'] == $arResult['DISCUSSION']['AUTHOR_ID']){
                    $APPLICATION->IncludeComponent(
                        "nfksber:user.select",
                        "edit_dialog",
                        Array(
                            "ACTION_VARIABLE" => "action",
                            "HLBLOCK_ID" => DIALOGUSERS_HLB_ID,
                            "INPUT_FIELD_NAME" => "UF_DIALOG_ID",
                            "OUTPUT_FIELD_NAME" => "UF_USER_ID",
                            "INPUT_NAME" => "USERS",
                            "HL_FILTER_NAME" => "status_filter",
                            "ELEMENT_ID" => $arResult['DISCUSSION']['DIALOG_ID'],
                            "SELECT_USER" => $arResult['DISCUSSION']['USERS'],
                            "ONLY_FRIENDS" => "Y"
                        )
                    );
                }else{?>
                    <div class="select-user">
                        <div class="select-user-list custom-scroll">
                            <?foreach($arResult['DISCUSSION']['USERS'] as $user){?>
                                <div class="user-el">
                                    <a class="d-flex align-items-center profile-link" href="/profile_user/?ID=<?=$user['ID']?>" target="_blank">
                                        <div class="user-photo">
                                        <?if (!empty($user['PERSONAL_PHOTO'])){?>
                                            <? $renderImage = CFile::ResizeImageGet($user['PERSONAL_PHOTO'], Array("width" => 65, "height" => 65), BX_RESIZE_IMAGE_EXACT, false); ?>
                                            <img src="<?=$renderImage['src']?>">
                                        <?}elseif(!empty($user['NAME'])){?>
                                            <h3><?=substr($user['NAME'], 0, 1);?></h3>
                                        <?}else{?>
                                            <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png">
                                        <?}?>
                                        </div>
                                        <div class="user-fio"><?=$user['LAST_NAME'] . " " . $user['NAME'] . " " . $user['SECOND_NAME']?></div>
                                    </a>
                                </div>
                            <?}?>
                        </div>
                    </div>
                <?
                }
                ?>
            </form>
        </div>
    </div>
<?}?>
<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'messenger.discussion.detail');
?>
<script>
    var MDD_component = {
        params: <?=CUtil::PhpToJSObject($arParams)?>,
        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
        templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
    };
</script>