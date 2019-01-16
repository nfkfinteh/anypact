<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class stepBack extends CBitrixComponent
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
        );
        return $result;
    }

    public function listSection($id_iblock, $section_id) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                //
                if($section_id > 0){                
                    $arr_section_value = GetIBlockSection($section_id);
                }
            }
        return $arr_section_value;
    }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["SECTION_ID"] = $arParams["SECTION_ID"];
        return $arResult;
    }

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));                                 
            $this->arResult["SECTION_INFO"] = $this->listSection($this->arResult["INFOBLOCK_ID"], $this->arResult["SECTION_ID"]);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>