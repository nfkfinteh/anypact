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
if($arResult['IS_AJAX_REQUEST'] == 'Y'){
    $html = '';
    if(!empty($arResult['USER'])):
        foreach($arResult['USER'] as $user):
            $html .= '<div class="user-el" data-id="'.$user['ID'].'">
                    <div class="user-photo">';
                        if (!empty($user["PERSONAL_PHOTO"])) {
                            $renderImage = CFile::ResizeImageGet($user["PERSONAL_PHOTO"], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false);
                            $html .= '<img src="'.$renderImage["src"].'" height="60" alt="">';
                        } else {
                            $html .= '<h3>'.substr($user['NAME'], 0, 1).'</h3>';
                        }
                    $html .= '</div>
                    <div class="user-fio">
                        '.$user['LAST_NAME'] . " " . $user['NAME'] . " " . $user['SECOND_NAME'].'
                    </div>
                </div>';
        endforeach;
    endif;
    if(strlen($html) == 0){
        $html = '<div class="user-not_found">Пользователи не найдены</div>';
    }
    echo json_encode($html);
}else{
?>
<div class="cardPact__title">
    <h3 style="margin-bottom: 0;">Выберите пользователей</h3>
</div>
<h4 style="margin-bottom: .5rem;">(Необязательно)</h4>
<h4 style="margin-bottom: .5rem;">Только выбранным пользователям будет видно данное предложение</h4>
<div>
    <input type="text" class="editbox" id="us_name" value="" name="us_name" placeholder="Выбрать" autocomplete="off">
</div>
<div class="select-user">
    <div class="select-user-list">
        <?
        if(!empty($arResult['SELECT_USER'])):
            foreach($arResult['SELECT_USER'] as $user):
        ?>
                <div class="user-el" data-id="<?=$user['ID'];?>">
                    <a class="d-flex align-items-center profile-link" href="/profile_user/?ID=<?=$user["ID"]?>" target="_blank">
                        <div class="user-photo">
                            <?if (!empty($user["PERSONAL_PHOTO"])) {?>
                                <? $renderImage = CFile::ResizeImageGet($user["PERSONAL_PHOTO"], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false); ?>
                                <img src="<?=$renderImage["src"]?>" height="60" alt="">
                            <?} else {?>
                                <h3><?=substr($user['NAME'], 0, 1);?></h3>
                            <?}?>
                        </div>
                        <div class="user-fio">
                            <?echo $user['LAST_NAME'] . " " . $user['NAME'] . " " . $user['SECOND_NAME'];?>
                        </div>
                    </a>
                    <div class="user-delete">
                    </div>
                    <input name="<?=$arParams['INPUT_NAME']?>[]" type="hidden" value="<?=$user['ID'];?>"/>
                </div>
        <?
            endforeach;
        endif;
        ?>
    </div>
    <div class="select-user-popup custom-scroll">
        <?
        if(!empty($arResult['USER'])):
            foreach($arResult['USER'] as $user):
        ?>
                <div class="user-el<?if($user['SELECTED']):?> selected<?endif;?>" data-id="<?=$user['ID'];?>">
                    <div class="user-photo">
                        <?if (!empty($user["PERSONAL_PHOTO"])) {?>
                            <? $renderImage = CFile::ResizeImageGet($user["PERSONAL_PHOTO"], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false); ?>
                            <img src="<?=$renderImage["src"]?>" height="60" alt="">
                        <?} else {?>
                            <h3><?=substr($user['NAME'], 0, 1);?></h3>
                        <?}?>
                    </div>
                    <div class="user-fio">
                        <?echo $user['LAST_NAME'] . " " . $user['NAME'] . " " . $user['SECOND_NAME'];?>
                    </div>
                </div>
        <?
            endforeach;
        endif;
        ?>
    </div>
</div>

<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'user.select');
?>	
<script>
	var US_component = {
		params: <?=CUtil::PhpToJSObject($arParams)?>,
		signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
		siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
		ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
        templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
        inputName: '<?=$arParams['INPUT_NAME']?>',
    };
</script>
<?}?>