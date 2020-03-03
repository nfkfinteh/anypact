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

    private function getHLSingl($IDHL, $params){
        CModule::IncludeModule('highloadblock');
        $hlblock = HL\HighloadBlockTable::getById($IDHL)->fetch();
        $entity  = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList($params);
        $result = $rsData->fetch();
        return $result;
    }
    
    public function executeComponent()
    {
        
        // по умолчанию "and"
        //$GLOBALS["FILTER_logic"] = "or";

        // цифры для статистики
        // всего зарегистированных через рекламный канал
        $arFilter= array(            
            "UF_TYPE_REGISTR" => "action%"            
        );
        $arParams["SELECT"] = array("UF_TYPE_REGISTR");        
        $arrAllRegistUsers = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter, $arParams);
        $AllRegistrActionUsers = array();
        while($allRegUsers = $arrAllRegistUsers->Fetch()){
            $AllRegistrActionUsers[] = $allRegUsers;
        }
        $arrParamsAllRegistUsers = [
            "ARR_ALL_USERS"         => $AllRegistrActionUsers,
            "COUNT_ARR_ALL_USERS"   => count($AllRegistrActionUsers)
        ];
        $this->arResult["ALL_REGIST_USERS"] = $arrParamsAllRegistUsers;
        
        // зарегистриованных через рекламный канал и верефицированных через есиа
        $arFilter= array(
            "ACTIVE" => 'Y',                   
            "UF_TYPE_REGISTR" => "action%"            
        );
        $arParams["SELECT"] = array("UF_TYPE_REGISTR");        
        $arrAllRegistESIAUsers = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter, $arParams);
        $AllRegistESIAUsers = array();
        while($allRegUsers = $arrAllRegistESIAUsers->Fetch()){
            $AllRegistESIAUsers[] = $allRegUsers;
        }
        $arrParamsAllRegistESIAUsers = [
            "ARR_ALL_USERS"         => $AllRegistESIAUsers,
            "COUNT_ARR_ALL_USERS"   => count($AllRegistESIAUsers)
        ];
        $this->arResult["ALL_REGIST_ESIA_USERS"] = $arrParamsAllRegistESIAUsers;

        // данные для таблицы
        $arFilter= array(
            "ACTIVE" => 'Y',
            "UF_TYPE_REGISTR" => "action%"            
        );

        $arParams["SELECT"] = array("UF_ESIA_ID", "UF_TYPE_REGISTR", "UF_ESIA_AUT", "UF_PAY_YANDEX");
        
        $elementsResult = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter, $arParams);
        
        $FilterSumPay = array();
        $SummPay =  $this->getHLSingl(13, $FilterSumPay); //'100.00' сумма должна быть строкой с точкой и двумя знаками после нее        
        
        while ($rsUser = $elementsResult->Fetch()) {
            $ID_ORDER = rand(100000, 999999);
            $rsUser["PAY_PARAMS"] = base64_encode($rsUser["ID"].'#'.$SummPay["UF_SUMM_PAY"].'#'.$rsUser["ID"].'#'.$rsUser["PERSONAL_PHONE"].'#'.$ID_ORDER);
            $arFilterUserRegistAction[] = $rsUser;
        } 
        $this->arResult["USER_REGIST_ACTION"] = $arFilterUserRegistAction;

        $this->includeComponentTemplate();        
        return $this->arResult;
    }

}
?>