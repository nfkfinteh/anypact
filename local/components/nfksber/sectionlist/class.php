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
                $items = GetIBlockSectionList($id_iblock, $ID, Array("sort"=>"asc"), 10);
                $arr_section_value['PROP_ONE_ITEM'] = 'Y';
                //
                // для отображения всех элементов в подкаталогах получим их ид
                if ($_GET['SECTION_ID'] == 0){
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock));
                }
                // фильтр для отбора всех записей включая подкатегории                 
                while($arItem = $items->GetNext())
                {                  
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "SECTION_ID"=> $arItem['ID'], "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE"=>"Y", ">DATE_ACTIVE_TO"=>ConvertTimeStamp(time(),"FULL") );                    
                    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);                    
                    $arr_Count_Iten = array();
                    // перебераем категории и считаем сколько там элементов
                    while($ob = $res->GetNextElement())
                    {
                        $arFields = $ob->GetFields();
                        $arr_Count_Iten[]['ID'] = $arFields['ID'];                     
                    }                    
                    $arItem['COUNT_IN_ITEM'] = count($arr_Count_Iten);                    
                    //
                    $arr_section_value['SECTION_LIST'][] = $arItem;                    
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
            $arSelect=Array('LEFT_MARGIN', 'NAME', 'DEPTH_LEVEL', 'ID')
        );
        $arrTree = array();
        while($section = $tree->GetNext()) {
            $arrTree[] = $section;
        }
        return  $arrTree;
    }

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));                                 
            $this->arResult["INFOBLOCK_SECTION_LIST"] = $this->listSection($this->arResult["INFOBLOCK_ID"], $this->arResult["SECTION_ID"]);
            $this->arResult["TREE_CATEGORY"] = $this->getTreeCategory($this->arResult["INFOBLOCK_ID"]);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>