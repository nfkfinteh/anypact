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
        // $arFilter= array(                     
        // );

        if(!empty($this->request->get('DATE_REGISTER_FROM'))){
            $dateFrom = $this->request->get('DATE_REGISTER_FROM');
        }else{
            $dateFrom = '01.01.1900';
        }
        if(!empty($this->request->get('DATE_REGISTER_TO'))){
            $dateTo = $this->request->get('DATE_REGISTER_TO');
        }else{
            $dateTo = '01.01.2900';
        }

        $arFilter= array(
            array(
                "LOGIC" => "AND",
                '>=DATE_REGISTER' => $dateFrom,
                '<=DATE_REGISTER' => $dateTo
            )
        );
        $arParams["SELECT"] = array("UF_TYPE_REGISTR");        
        // $arrAllRegistUsers = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter, $arParams);
        $arrAllRegistUsers = Bitrix\Main\UserTable::getList(
            array(
                "order" => array("DATE_REGISTER" => "DESC"),
                'select' => $arParams["SELECT"],
                'filter' => $arFilter
            )
        );
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
        // $arFilter= array(
        //     "ACTIVE" => 'Y'       
        // );
        // $arParams["SELECT"] = array("UF_ESIA_ID", "UF_TYPE_REGISTR", "UF_ESIA_AUT", "UF_PAY_YANDEX");      
        // $arrAllRegistESIAUsers = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter, $arParams);
        $arFilter= array(
            "ACTIVE" => 'Y',
            array(
                "LOGIC" => "AND",
                '>=DATE_REGISTER' => $dateFrom,
                '<=DATE_REGISTER' => $dateTo
            )
        );
        $arParams["SELECT"] = array("*", "UF_ESIA_ID", "UF_TYPE_REGISTR", "UF_ESIA_AUT", "UF_PAY_YANDEX");
        $arrAllRegistESIAUsers = Bitrix\Main\UserTable::getList(
            array(
                "order" => array("DATE_REGISTER" => "DESC"),
                'select' => $arParams["SELECT"],
                'filter' => $arFilter
            )
        );
        
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
            if(!empty($allRegUsersESIA["PERSONAL_PHONE"]) && $allRegUsersESIA["UF_ESIA_AUT"]==1 && !empty($allRegUsersESIA["PERSONAL_PHOTO"])){
                $FillUserProfile[] = $allRegUsersESIA;
            }
            if(!empty($allRegUsersESIA["PERSONAL_PHONE"])){
                $PhoneUserProfile[] = $allRegUsersESIA;
            }
            // выплачено вознаграждение
            if($allRegUsersESIA["UF_PAY_YANDEX"] == "Y"){
                $UsersPay[] = $allRegUsersESIA;
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

        $paramsUsersPhone = [
            "ARR_ALL_USERS"         => $PhoneUserProfile,
            "COUNT_ARR_ALL_USERS"   => count($PhoneUserProfile)
        ];

        $paramsUsersPay = [
            "ARR_ALL_USERS"         => $UsersPay,
            "COUNT_ARR_ALL_USERS"   => count($UsersPay)
        ];

        $this->arResult["ALL_REGIST_ESIA_USERS"]    = $arrParamsAllRegistESIAUsers;
        $this->arResult["ALL_VERIF_ESIA_USERS"]     = $ParamsVerifESIAUsers;
        $this->arResult["ALL_FILL_PARAMS_USERS"]    = $paramsUsersFill;
        $this->arResult["ALL_PHONE_USERS"]          = $paramsUsersPhone;
        $this->arResult["ALL_PAY_USERS"]            = $paramsUsersPay;

        // данные для таблицы
        $arFilter= array(
            "ACTIVE" => 'Y',
            array(
                "LOGIC" => "AND",
                '>=DATE_REGISTER' => $dateFrom,
                '<=DATE_REGISTER' => $dateTo
            )
        );

        $arParams["SELECT"] = array("*", "UF_ESIA_ID", "UF_TYPE_REGISTR", "UF_ESIA_AUT", "UF_PAY_YANDEX");
        
        // $elementsResult = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter, $arParams);.
        $elementsResult = Bitrix\Main\UserTable::getList(
            array(
                "order" => array("DATE_REGISTER" => "ASC"),
                'select' => $arParams["SELECT"],
                'filter' => $arFilter
            )
        );
        
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