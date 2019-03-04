<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
    Класс выводит информацию в карточку по сделке
*/

class CDemoSqr extends CBitrixComponent
{       
    private $ID_CONTRACT;
    
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

    private function getElement($id_element) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                $res = CIBlockElement::GetByID($id_element);
                if($ar_res = $res->GetNext()){
                    return $ar_res;
                }
                
            }        
    }

    // Все свойства элемента
    private function getProperty($id_iblok, $id_element){        
        $db_props = CIBlockElement::GetProperty($id_iblok, $id_element, "sort", "asc", array());         
        $array_props = array();        
        $array_img = array();
        while($ar_props = $db_props->Fetch()){ 
            
            $array_props[$ar_props["CODE"]] = $ar_props ;
            
            if ($ar_props["CODE"] == "INPUT_FILES"){
                $file_path = CFile::GetPath($ar_props["VALUE"]);
                $array_img[] = array("URL" => $file_path, "PROPERTY" => $ar_props);
            }
        }
        
        $array_props["IMG_FILE"] = $array_img;
        
        if (!empty($array_props["ID_DOGOVORA"]["VALUE"])){
            $this->ID_CONTRACT = $array_props["ID_DOGOVORA"]["VALUE"];
        }
        return $array_props;
    }

    private function getListCategory(){
        $items = GetIBlockSectionList(5, 0, Array("sort"=>"asc"), 10);
        $arr_sections = array();
        while($arResult = $items->GetNext()){       
            $arr_sections[] = array("NAME" => $arResult["NAME"], "ID" => $arResult["ID"]);
        }
        return $arr_sections;
    }

    private function getContractParams(){        
        if(!empty($this->ID_CONTRACT)){            
            if(CModule::IncludeModule("iblock"))
            {
                $res = CIBlockElement::GetByID($this->ID_CONTRACT);
                if($ar_res = $res->GetNext()){
                    return $ar_res['DETAIL_TEXT'];
                }
                
            }
        }else {
            return $ID_CONTRACT;
        }
        
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
            $this->arResult["PROPERTY"] = $this->getProperty($this->arResult["INFOBLOCK_ID"], $this->arResult["ELEMENT_ID"]);
            $this->arResult["LIST_CATEGORY"] = $this->getListCategory();
            $this->arResult["TEXT_CONTRACT"] = $this->getContractParams();
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>