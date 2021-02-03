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

Элемент

<?

$APPLICATION->IncludeComponent("nfksber:userpacts.detail", 
"", 
	Array(
		"IBLOCK_ID" => "3",
		"SEF_MODE" => "N",
		"SEF_FOLDER" => "/my_pacts/",
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"SEF_URL_TEMPLATES" => array(                    
				"list" => "",
				"detail" => "#ID#"
			)      
		)
);


?>
