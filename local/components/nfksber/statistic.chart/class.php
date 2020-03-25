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
        
        // зарегистриованных через рекламный канал и актвированных
        $arFilter= array(
            "ACTIVE" => 'Y'       
        );
        $arParams["SELECT"] = array("UF_ESIA_ID", "UF_TYPE_REGISTR", "UF_ESIA_AUT", "UF_PAY_YANDEX");      
        $arrAllRegistESIAUsers = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter, $arParams);
        
        $AllRegistESIAUsers = array();
        $ESIAverifUser = array();
        $ActiveUserProfile = array();
        $UsersPay = array();

        while($allRegUsersESIA = $arrAllRegistESIAUsers->Fetch()){
            $AllRegistESIAUsers[] = $allRegUsersESIA;            
            // верифицорован через ЕСИА
            if($allRegUsersESIA["UF_ESIA_AUT"]==1){
                $ESIAverifUser[] = $allRegUsersESIA;                
            }            
            // заполнен один из параметров
            if(!empty($allRegUsersESIA["PERSONAL_PHONE"]) || $allRegUsersESIA["UF_ESIA_AUT"]==1 || !empty($allRegUsersESIA["PERSONAL_PHOTO"])){
                $FillUserProfile[] = $allRegUsersESIA;
            }
            // выплачено вознаграждение
            if($allRegUsersESIA["UF_PAY_YANDEX"] == "Y"){
                $UsersPay[] = $allRegUsersESIA;
            }
            // канал 
            if($allRegUsersESIA["UF_TYPE_REGISTR"] == "actionDuW0KXQsNC7YXnQstCwQ2l0eQ=="){
                $SaleCanalOne[] = $allRegUsersESIA;
            }

            if($allRegUsersESIA["actionDuWQHdvd19mcmVl"] == "actionDuW0KXQsNC7YXnQstCwQ2l0eQ=="){
                $SaleCanalOne[] = $allRegUsersESIA;
            }
        }

        $arrParamsAllRegistESIAUsers = [
            "ARR_ALL_USERS"         => $AllRegistESIAUsers,
            "COUNT_ARR_ALL_USERS"   => count($AllRegistESIAUsers)
        ];

        $ParamsVerifESIAUsers = [
            "ARR_ALL_USERS"         => $ESIAverifUser,
            "COUNT_ARR_ALL_USERS"   => count($ESIAverifUser)
        ];

        $paramsUsersFill = [
            "ARR_ALL_USERS"         => $FillUserProfile,
            "COUNT_ARR_ALL_USERS"   => count($FillUserProfile)
        ];

        $paramsUsersPay = [
            "ARR_ALL_USERS"         => $UsersPay,
            "COUNT_ARR_ALL_USERS"   => count($UsersPay)
        ];

        $this->arResult["ALL_REGIST_ESIA_USERS"]    = $arrParamsAllRegistESIAUsers;
        $this->arResult["ALL_VERIF_ESIA_USERS"]     = $ParamsVerifESIAUsers;
        $this->arResult["ALL_FILL_PARAMS_USERS"]    = $paramsUsersFill;
        $this->arResult["ALL_PAY_USERS"]            = $paramsUsersPay;

        $this->includeComponentTemplate();        
        return $this->arResult;
    }

}
?>