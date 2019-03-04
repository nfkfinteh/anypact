<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

class GetElementsIB {
	
	public function getListElement($PARAMS){
		
		if(!CModule::IncludeModule("iblock")) return false;

		if(empty($PARAMS["Filter"]["IBLOCK_ID"])) return false;


		$obCache 					= new CPHPCache;
        $life_time 					= 3600;
        $cache_params 				= $PARAMS["Filter"];
        $cache_params['func']		= "CIBlockElement::GetList";
        $cache_params['arSelect']	= $PARAMS["SelectFild"];
        $cache_params['sort']		= $PARAMS["Order"];
        $cache_params['pageParams']	= $PARAMS["pageParams"];
        $cache_id 					= md5(serialize($cache_params));

        if($obCache->InitCache($life_time, $cache_id, "/")) {
            
            $arFields = $obCache->GetVars();

        }else {
		
			$res = CIBlockElement::GetList($PARAMS["Order"], $PARAMS["Filter"], false, $PARAMS["pageParams"], $PARAMS["SelectFild"]);		
		
			while($ob = $res->GetNextElement())
			{
			 	$arFields[] = $ob->GetFields();		 		 	
			}
		}

		if($obCache->StartDataCache()){
            $obCache->EndDataCache($arFields);
		}        

		return $arFields;

	}
}


$Params = array(
	"Order" => array(),
	"Filter" => array("IBLOCK_ID" => 3 ),
	"pageParams" => array("nPageSize"=>10),
	"SelectFild" => array("ID", "NAME", "DATE_ACTIVE_FROM")
);

$Element = new GetElementsIB();
$arrElements = $Element->getListElement($Params);

foreach ($arrElements as $value) {
	echo "<br> ".$value['NAME'];
}