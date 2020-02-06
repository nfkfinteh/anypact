<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class ControlRegUser extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE"            => $arParams["CACHE_TYPE"],
            "CACHE_TIME"            => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X"                     => intval($arParams["X"]),
            "IBLOCK_ID"             => intval($arParams["IBLOCK_ID"]),
            "SECTION_ID"            => intval($arParams["SECTION_ID"]),
            "ELEMENT_ID"            => intval($arParams["ELEMENT_ID"]),
            "IBLOCK_ID_CONTRACT"    => intval($arParams["IBLOCK_ID_CONTRACT"]),
            "TYPE_USER_PROF"        => intval($arParams["TYPE_USER_PROF"])
        );
        return $result;
    }

    function paramsUser($arParams){        
        $arResult["INFOBLOCK_ID"]       = $arParams["IBLOCK_ID"];
        $arResult["INFOBLOCK_C_ID"]     = $arParams["IBLOCK_ID_CONTRACT"];
        $arResult["SECTION_ID"]         = $arParams["SECTION_ID"];
        $arResult["ELEMENT_ID"]         = $arParams["ELEMENT_ID"];
        
        return $arResult;
    }
    
    public function executeComponent()
    {
        
        // по умолчанию "and"
        //$GLOBALS["FILTER_logic"] = "or";

        $arFilter= array(
            "ACTIVE" => 'Y',
            "UF_TYPE_REGISTR" => "action%"            
        );

        $arParams["SELECT"] = array("UF_ESIA_ID", "UF_TYPE_REGISTR", "UF_ESIA_AUT");
        
        $elementsResult = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter, $arParams);
        
        while ($rsUser = $elementsResult->Fetch()) {
            $ID_ORDER = rand(100000, 999999);
            $rsUser["PAY_PARAMS"] = base64_encode($rsUser["ID"].'#100.00#'.$rsUser["ID"].'#'.$rsUser["PERSONAL_PHONE"].'#'.$ID_ORDER);
            $arFilterUserRegistAction[] = $rsUser;
        } 

        $this->arResult["USER_REGIST_ACTION"] = $arFilterUserRegistAction;

        $this->includeComponentTemplate();        
        return $this->arResult;
    }

}
?>