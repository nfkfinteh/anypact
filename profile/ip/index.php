<?/*  АО "НФК-Сбережения" 08.06.20 */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Индивидуальный предприниматель");
// проверяем авторизован ли пользователь
global $USER;

if(isset($_GET['REFRESH']))
    LocalRedirect("");

if(isset($_GET['PARAMS']))
    LocalRedirect("?adddsscc=33423");

if ($USER->IsAuthorized()){

    $APPLICATION->IncludeComponent(
	"nfksber:company",
	"ip",
	array(
		"IBLOCK_ID" => "8",
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "sprav",
		"PROPERTIES_SHOW" => array(
			0 => "INN",
            2 => "OGRNIP",
			4 => "ADRESS",
			5 => "INDEX",
			6 => "REGION",
			7 => "CITY",
			8 => "DISTRICT",
			9 => "LOCALITY",
			10 => "STREET",
			11 => "HOUSE",
			12 => "OFFICE",
			13 => "BANK",
			14 => "BIK",
			15 => "RAS_ACCOUNT",
			16 => "KOR_ACCOUNT",
			17 => "INN_BANK",
			18 => "KORP",
		),
		"PROPERTIES_NEED" => array(
			0 => "INN",
			2 => "OGRNIP",
			4 => "ADRESS",
			5 => "INDEX",
			6 => "CITY",
			7 => "STREET",
			10 => "HOUSE",
			12 => "BANK",
			13 => "BIK",
			14 => "RAS_ACCOUNT",
			15 => "KOR_ACCOUNT",
			16 => "INN_BANK",
			17 => "REGION",
		),
		"PROPERTIES_NUMBER" => array(
			0 => "INN",
            2 => "OGRNIP",
			4 => "INDEX",
			5 => "BIK",
			6 => "RAS_ACCOUNT",
			7 => "KOR_ACCOUNT",
			8 => "INN_BANK",
			9 => "",
		)
	),
	false
);
}else{
    LocalRedirect("/");
}
?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>