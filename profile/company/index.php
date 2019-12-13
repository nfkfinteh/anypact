<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Компания");
// проверяем авторизован ли пользователь
global $USER;

if ($USER->IsAuthorized()){

    $APPLICATION->IncludeComponent(
	"nfksber:company",
	".default",
	array(
		"IBLOCK_ID" => "8",
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "sprav",
		"PROPERTIES_SHOW" => array(
			0 => "INN",
			1 => "KPP",
			2 => "OGRN",
			3 => "ADRESS",
			4 => "INDEX",
			5 => "REGION",
			6 => "CITY",
			7 => "DISTRICT",
			8 => "LOCALITY",
			9 => "STREET",
			10 => "HOUSE",
			11 => "OFFICE",
			12 => "BANK",
			13 => "BIK",
			14 => "RAS_ACCOUNT",
			15 => "KOR_ACCOUNT",
			16 => "INN_BANK",
			17 => "",
		)
	),
	false
);
}else{
    LocalRedirect("/");
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>