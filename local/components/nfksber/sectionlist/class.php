<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class sectionPacts extends CBitrixComponent
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
            "SECTION_CODE" => $arParams["SECTION_CODE"],
            "SECTION_URL" => $arParams["SECTION_URL"],
            "FILTER_NAME" => $arParams["FILTER_NAME"],
        );
        return $result;
    }

    public function listSection($id_iblock, $section_id) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                // если $ID не задан или это не число, тогда 
                // $ID будет =0, выбираем корневые разделы
                $ID =  $section_id; //false;
                // выберем папки из информационного блока $BID и раздела $ID
                $items = CIBlockSection::GetList(array("sort"=>"asc"), array("IBLOCK_ID" => $id_iblock, "SECTION_ID" => $ID, "ACTIVE" => "Y"), false);
                //$items = GetIBlockSectionList($id_iblock, $ID, Array("sort"=>"asc"), 10);
                $arr_section_value['PROP_ONE_ITEM'] = 'Y';
                //
                // для отображения всех элементов в подкаталогах получим их ид
                if ($section_id == 0){
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock));
                }

                //внешняя фильтрация
                if(strlen($this->arParams['FILTER_NAME'])<=0)
                {
                    $arrFilter = array();
                }
                else
                {
                    $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];
                    if(!is_array($arrFilter))
                        $arrFilter = array();
                }

                $items->SetUrlTemplates("", $this->arParams["SECTION_URL"]);
                // фильтр для отбора всех записей включая подкатегории                 
                while($arItem = $items->GetNext())
                {                  
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "SECTION_ID"=> $arItem['ID'], "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE"=>"Y", array(
                        "LOGIC" => "OR",
                        array("PROPERTY_INDEFINITELY" => 18),
                        array(">=DATE_ACTIVE_TO" => new \Bitrix\Main\Type\DateTime())
                    ));                    
                    $res = CIBlockElement::GetList(Array(), array_merge($arFilter, $arrFilter), false, Array(), $arSelect);                    
                    $arr_Count_Iten = array();
                    // перебераем категории и считаем сколько там элементов
                    while($ob = $res->GetNextElement())
                    {
                        $arFields = $ob->GetFields();
                        $arr_Count_Iten[]['ID'] = $arFields['ID'];                     
                    }                    
                    $arItem['ELEMENT_CNT'] = count($arr_Count_Iten);                    
                    //
                    $arr_section_value['SECTIONS'][] = $arItem;                    
                    $arr_section_value['PROP_ONE_ITEM'] = 'N';
                }
                           
                if ($arr_section_value['PROP_ONE_ITEM'] == 'Y'){                    
                    $arr_section_value['ARR_ONE_ITEM'] = GetIBlockSection($ID);
                }                
                //print_r($property_section);
            }
        return $arr_section_value;
    }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["SECTION_ID"] = $arParams["SECTION_ID"];
        return $arResult;
    }

    private function getTreeCategory($id_iblock){
        $tree = CIBlockSection::GetTreeList(
            $arFilter=Array('IBLOCK_ID' => $id_iblock, 'ACTIVE' => 'Y'),
            $arSelect=Array('LEFT_MARGIN', 'NAME', 'DEPTH_LEVEL', 'ID', 'IBLOCK_ID', 'SECTION_PAGE_URL')
        );
        $arrTree = array();
        while($section = $tree->GetNext()) {
            $arrTree[] = $section;
        }
        return  $arrTree;
    }

    public function executeComponent()
    {
        global $USER;
        if($this->startResultCache(false, array($USER->GetID())))
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));                                 
            $this->arResult['INFOBLOCK_SECTION_LIST'] = $this->listSection($this->arResult["INFOBLOCK_ID"], $this->arResult["SECTION_ID"]);
            $this->arResult["TREE_CATEGORY"] = $this->getTreeCategory($this->arResult["INFOBLOCK_ID"]);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>