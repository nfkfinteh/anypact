<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->IncludeComponent("nfksber:allpacts.list", 
"", 
	Array(
		"IBLOCK_ID" => "3",
		"SEF_MODE" => "N",
		"SEF_FOLDER" => "/pacts/",
		"SECTION_ID" => $_GET['SECTION_ID'],
		"SEF_URL_TEMPLATES" => array(                    
				"list" => "",
				"detail" => "#ID#"
			)      
		)
);


?>
