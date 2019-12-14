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
<?if(!empty($arResult['ITEMS'])):?>
    <div class="row grid-view">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <!-----------------профили компаний------------------->
            <div class="view-item col-lg-3 col-sm-6 col-6 mt-4 pb-3">
                <div class="people-s-photo">
                    <div class="people-s-photo-img <?if($arParams['COMPANY_ID'] != $arItem['ID']):?>js-auth_company<?endif?>" data-id="<?=$arItem['ID']?>">
                        <? if(!empty($arItem['PREVIEW_PICTURE'])){ ?>
                            <?
                            $renderImage = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false);
                            ?>
                            <img class="people-s-user-photo" src="<?=$renderImage["src"];?>">
                        <?}else {?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                        <? } ?>
                    </div>
                </div>
                <div class="people-s-photo-text">
                    <div class="people-s-photo-text-block">
                        <h6><?=$arItem['NAME']?></h6>
                        <?/*
                        <div class="grid-hidden-text">
                        </div>
                        */?>
                    </div>
                    <?if($arParams['COMPANY_ID'] != $arItem['ID']):?>
                        <div class="people-s-photo-btn-block">
                            <button class="btn btn-aut js-auth_company" data-id="<?=$arItem['ID']?>">Выбрать</button>
                        </div>
                    <?else:?>
                        <div class="people-s-photo-btn-block">
                            <a href="/profile/" class="btn btn-aut active-company">
                                Продолжить с текущим профилем
                            </a>
                        </div>
                    <?endif?>
                </div>
            </div>
        <?endforeach?>
        <!------------------профили пользователей------------------>
        <div class="view-item col-lg-3 col-sm-6 col-6 mt-4 pb-3">
            <div class="people-s-photo">
                <div class="people-s-photo-img <?if(empty($arParams['COMPANY_ID'])):?>js-auth_user<?endif?>">
                    <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png">
                </div>
            </div>
            <?$USER->Get?>
            <div class="people-s-photo-text">
                <div class="people-s-photo-text-block">
                    <h6><?=$USER->GetFullName()?></h6>
                </div>
                <?if(!empty($arParams['COMPANY_ID'])):?>
                    <div class="people-s-photo-btn-block">
                        <button class="btn btn-aut js-auth_user">Выбрать</button>
                    </div>
                <?else:?>
                    <div class="people-s-photo-btn-block">
                        <a href="/profile/" class="btn btn-aut active-company">
                            Продолжить с текущим профилем
                        </a>
                    </div>
                <?endif?>
            </div>
        </div>
    </div>
    <?=$arResult["NAV_STRING"]?>
<?else:?>
    У вас нет компании
<?endif?>

