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
<?if(!empty($arResult['BLACK_LIST_FULL'])):?>
    <div class="blacklist__title">Черный список</div>
    <div class="blacklist__body">
        <?foreach ($arResult['BLACK_LIST_FULL'] as $user):?>
            <div class="blacklist__item">
                <div class="blacklist__name"><?=$user['NAME']?></div>
                <div class="blacklist__type">
                    <button class="btn btn-clean js-delete-blacklist" data-login="<?=$user['LOGIN']?>" data-type='list_black' data-id='<?=$user['ID']?>'>
                        <img src="<?=SITE_TEMPLATE_PATH?>/image/black-list.png" alt="Удалить из черного списка" title="Удалить из черного списка">
                    </button>
                </div>
            </div>
        <?endforeach?>
    </div>
    </div>
<?else:?>
    <div class="blacklist__title">Черный список пуст</div>
    <div class="blacklist__body">
    </div>
    </div>
<?endif?>