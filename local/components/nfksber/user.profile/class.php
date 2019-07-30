<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
    Класс выводит информацию в карточку по сделке
*/

class CDemoSqr extends CBitrixComponent
{       
    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "USER_ID" => intval($arParams['USER_ID']),
        );
        return $result;
    }

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $res = CUser::GetByID($this->arParams['USER_ID']);
            $arUser = $res->GetNext();
            $this->arResult["USER"] = $arUser;
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>