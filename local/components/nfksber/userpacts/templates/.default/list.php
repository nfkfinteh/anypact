<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$APPLICATION->IncludeComponent("nfksber:userpacts.list", 
"", 
	Array(
		"IBLOCK_ID" => "3",
		"SEF_MODE" => "N",
		"SEF_FOLDER" => "/my_pacts/",
		"SEF_URL_TEMPLATES" => array(                    
				"list" => "",
				"detail" => "#ID#"
			)      
		)
);


?>
