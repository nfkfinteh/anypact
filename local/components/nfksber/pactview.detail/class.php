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
        );
        return $result;
    }

    public function listAllPacts($id_iblock, $section_id) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                // для отображения всех элементов в подкаталогах получим их ид                
                if ($_GET['SECTION_ID'] == 0){
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock));
                }else {
                    // фильтр для отбора всех записей включая подкатегории 
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "SECTION_ID"=> $section_id, "INCLUDE_SUBSECTIONS" => "Y" );
                }

                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);                
                // перебираем элементы
                while($ob = $res->GetNextElement())
                {
                    $arFields   = $ob->GetFields();
                    $id_element = $arFields["ID"];
                    $id_img = $arFields["PREVIEW_PICTURE"];                     
                    $db_props = CIBlockElement::GetProperty($id_iblock, $id_element);
                    while ($ar_props = $db_props->GetNext())
                    {
                        $arFields["PROPERTIES"][$ar_props["CODE"]] = $ar_props; 
                    }
                    $arFields['URL_IMG_PREVIEW'] = NULL;
                    $arFields['URL_IMG_PREVIEW'] = CFile::GetPath($id_img);
                    $arPact[]   = $arFields;
                    // добавим url img                    
                }             
                
            }
        return $arPact;
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
            $this->arResult["USER_ID"] = CUser::GetID();
            $this->arResult["USER_LOGIN"] =CUser::GetLogin();                                  
            $this->arResult["INFOBLOCK_LIST"] = $this->listAllPacts($this->arResult["INFOBLOCK_ID"], $this->arResult["SECTION_ID"]);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>