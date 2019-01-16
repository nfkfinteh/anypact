<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CDemoSqr extends CBitrixComponent
{
    //Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent
    //и применяет к ним функцию htmlspecialcharsex. В данном случае такая обработка избыточна.
    //Переопределяем.    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X" => intval($arParams["X"]),
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "SECTION_ID" => intval($arParams["SECTION_ID"]),
            "ELEMENT_ID" => intval($arParams["ELEMENT_ID"]),
        );
        return $result;
    }

    public function getElement($id_element) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                $res = CIBlockElement::GetByID($id_element);
                if($ar_res = $res->GetNext()){
                    return $ar_res;
                }
                
            }        
    }

    public function getProperty($id_iblok, $id_element){
        
        $db_props = CIBlockElement::GetProperty(3, $id_element, "sort", "asc", array());         
        $array_props = array();
        $ar_props = $db_props->Fetch();
        while($ar_props = $db_props->Fetch()){ 
            $array_props[] = $ar_props ;            
        }

        return $array_props;
    }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["SECTION_ID"] = $arParams["SECTION_ID"];
        $arResult["ELEMENT_ID"] = $arParams["ELEMENT_ID"];
        return $arResult;
    }

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_ID"] = CUser::GetID();
            $this->arResult["USER_LOGIN"] =CUser::GetLogin();
            $this->arResult["ELEMENT"] = $this->getElement($this->arResult["ELEMENT_ID"]);
            $this->arResult["PROPERTY"] = $this->getProperty($this->arResult["IBLOCK_ID"], $this->arResult["ELEMENT_ID"]);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>