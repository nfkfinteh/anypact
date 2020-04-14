<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Iblock;
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
            "X" => intval($arParams["X"]),
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "SECTION_ID" => intval($arParams["SECTION_ID"]),
            "ELEMENT_ID" => intval($arParams["ELEMENT_ID"]),
            "LOCATION" => htmlspecialcharsEx($arParams["LOCATION"]),
            "FORM_SDELKA"=>$arParams["FORM_SDELKA"],
            "DOGOVOR"=>$arParams["DOGOVOR"]
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
                if(!empty($file_path)){
                    $array_img[] = array("URL" => $file_path, "PROPERTY" => $ar_props);
                }
            }
            if ($ar_props["CODE"] == "MAIN_FILES"){
                $file_path = CFile::GetFileArray($ar_props["VALUE"]);
                if(!empty($file_path)){
                    $array_incl[] = array("URL" => $file_path['SRC'], "ID" => $ar_props["PROPERTY_VALUE_ID"], "ID_FILE" => $ar_props["VALUE"], 'NAME'=>$file_path['ORIGINAL_NAME']);
                }
            }
        }

        $array_props["IMG_FILE"] = $array_img;
        $array_props["UNCLUDE_FILE"] = $array_incl;
        return $array_props;
    }    

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["SECTION_ID"] = $arParams["SECTION_ID"];
        $arResult["ELEMENT_ID"] = $arParams["ELEMENT_ID"];
        return $arResult;
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
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "SECTION_ID"=> $arItem['ID'], "INCLUDE_SUBSECTIONS" => "Y" );                    
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
            }
        return $arr_section_value;
    }

    public function getTreeCategory($ID_INF){
        $tree = CIBlockSection::GetTreeList(
            $arFilter=Array('IBLOCK_ID' => $ID_INF),
            $arSelect=Array()
        );
        while($section = $tree->GetNext()) {
            $arTree[] = $section;
        }
        //print_r($arTree);
        return $arTree;
    }

    public function getCountDogovor($idUser){
        $hlbl = 3;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
            "filter" => array(
                'LOGIC' => 'OR',
                array(
                    'UF_ID_USER_A'=>$idUser
                ),
                array(
                    'UF_ID_USER_B'=>$idUser
                )
            )
        ));

        $result = $rsData->getSelectedRowsCount();

        return $result;
    }

    public function getListCity(){
        $arFilter = [
            'IBLOCK_ID'=>7,
            'ACTIVE'=>'Y',
        ];
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'NAME'
        ];
        $res = CIBlockElement::GetList(['NAME'=>'ASC'], $arFilter, false, false, $arSelect);
        while ($obj = $res->GetNext(true, false)){
            $result[] = $obj['NAME'];
        }
        return $result;

    }

    #функция получения данных владельца договора (компании или физ лица)
    public function getContractHolder(){
        if(empty($this->arResult["PROPERTY"]["ID_COMPANY"]["VALUE"])){
            $UserContractHolder = CUser::GetByID($this->arResult["PROPERTY"]["PACT_USER"]["VALUE"]);
            $arrUserContractHolder = $UserContractHolder->Fetch();
            $USER_CONTRACT_HOLDER = array(
                "ID"    => $arrUserContractHolder["ID"],
                "NAME"  => $arrUserContractHolder["NAME"],
                "LAST_NAME" => $arrUserContractHolder["LAST_NAME"],
                "LOGIN" => $arrUserContractHolder["LOGIN"],
                "CITY"  => $arrUserContractHolder["PERSONAL_CITY"],
                "PERSONAL_PHOTO" => CFile::GetPath($arrUserContractHolder["PERSONAL_PHOTO"]),
                "TYPE" => 'user',
                "UF_BLACKLIST" => $arrUserContractHolder['UF_BLACKLIST']
            );
        }
        else{
            $CompanyContractHolder = CIBlockElement::GetList([], ['IBLOCK_ID'=>8, 'ID'=>$this->arResult["PROPERTY"]["ID_COMPANY"]["VALUE"]], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_CITY', 'PREVIEW_PICTURE']);
            if ($obj = $CompanyContractHolder->GetNext(true, false)){
                $arrCompanyContractHolder = $obj;
            }
            $USER_CONTRACT_HOLDER = array(
                "ID"    => $arrCompanyContractHolder["ID"],
                "NAME"  => $arrCompanyContractHolder["NAME"],
                "CITY"  => $arrCompanyContractHolder["PROPERTY_CITY_VALUE"],
                "PERSONAL_PHOTO" => CFile::GetPath($arrCompanyContractHolder["PREVIEW_PICTURE"]),
                "TYPE" => 'company'
            );
        }

        return $USER_CONTRACT_HOLDER;
    }

    public function executeComponent()
    {
        /*if($this->startResultCache())
        {*/
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_ID"] = CUser::GetID();
            $rsUser = CUser::GetByID($this->arResult["USER_ID"]);
            $this->arResult['USER'] = $rsUser->GetNext();

            if($this->arResult['USER']['UF_ESIA_AUT']==0 && $_REQUEST['ACTION']=='ADD')
            {
                Iblock\Component\Tools::process404(
                    '',
                    true,
                    true,
                    true
                );
            }

            $this->arResult["USER_LOGIN"] =$this->arResult['USER']['LOGIN'];
            $this->arResult["ELEMENT"] = $this->getElement($this->arResult["ELEMENT_ID"]);
            $this->arResult["PROPERTY"] = $this->getProperty($this->arResult["INFOBLOCK_ID"], $this->arResult["ELEMENT_ID"]);
            $this->arResult["INFOBLOCK_SECTION_LIST"] = $this->getTreeCategory($this->arResult["INFOBLOCK_ID"]);
            $this->arResult['DOGOVOR']['CNT'] =  $this->getCountDogovor($this->arResult["USER_ID"]);
            $this->arResult['LIST_CITY'] = $this->getListCity();
            $this->arResult["CONTRACT_HOLDER"] = $this->getContractHolder();

            //данные заполненния формы и договор
            $this->arResult['FORM_SDELKA'] = $this->arParams['FORM_SDELKA'];
            if($this->arParams['DOGOVOR']){
                $cacheName = $this->arParams['DOGOVOR'];
            }
            $cache = \Bitrix\Main\Data\Cache::createInstance();
            $cacheInitDir = 'dogovor_create_sdelka';

            if ($cache->initCache(600, $cacheName, $cacheInitDir)){
                $this->arResult['DOGOVOR'] = $cache->getVars();
            }
            else{
                $this->arResult['DOGOVOR'] = '';
            }

            if(!empty($this->arResult['DOGOVOR'])){
                $this->arResult['DOGOVOR_KEY_CASHE'] = $cacheName;
            }


            /*$GLOBALS['CACHE_MANAGER']->StartTagCache("/".SITE_ID.$this->GetRelativePath());
            $GLOBALS['CACHE_MANAGER']->RegisterTag('iblock_id_4');//Кеш будет зависить от изменений инфоблока 9
            $GLOBALS['CACHE_MANAGER']->EndTagCache();*/
            $this->includeComponentTemplate();
        //}
        
        //return $this->arResult;
    }
};

?>