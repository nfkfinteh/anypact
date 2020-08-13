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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>
<?if(!empty($arResult['INFOBLOCK_SECTION_LIST']['SECTIONS'])){?>
    <div class="category">
        <span class="category-name">Категории:</span>
        <div class="row">
            <div class="col-lg-7 col-md-9 col-sm-12">
                <div class="row">
                  <?foreach ($arResult['INFOBLOCK_SECTION_LIST']['SECTIONS'] as $arSection) {
                        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
                    ?>
                        <div class="col-sm-4" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
                            <a href="<?=$arSection['SECTION_PAGE_URL']?>">
                                <?=$arSection['NAME']?>
                                <span><?=$arSection['ELEMENT_CNT']?></span>
                            </a>                            
                        </div>  
                  <?}?>
                </div>
            </div>
        </div>
    </div>
<?}?>