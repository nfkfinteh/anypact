<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader; 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

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
        );
        return $result;
    }

    public function listPacts($id_iblock) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                $arSelect = Array();
                $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock));
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);                
                while($ob = $res->GetNextElement())
                {
                    $arFields   = $ob->GetFields();
                    $id_element = $arFields["ID"];                    
                    $db_props = CIBlockElement::GetProperty($id_iblock, $id_element);
                    while ($ar_props = $db_props->GetNext())
                    {
                        $arFields["PROPERTIES"][$ar_props["CODE"]] = $ar_props; 
                    }                    
                    $arPact[]   = $arFields;
                }             
                $arPacts['ARR_SDELKI'] = $arPact;
            }
        if(CModule::IncludeModule("highloadblock")){
            $arPacts['HL'] = array();
            $hlbl = 2; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
            $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 

            $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
            $entity_data_class = $entity->getDataClass(); 

            $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ID_USER"=> 1)  // Задаем параметры фильтра выборки
            ));

            while($arData = $rsData->Fetch()){
                //var_dump($arRes);
                $arPacts['HL'][]  = $arData; 
            }

        }
        return $arPacts;
    }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        return $arResult;
    }

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_ID"] = CUser::GetID();
            $this->arResult["USER_LOGIN"] =CUser::GetLogin();                                  
            $this->arResult["INFOBLOCK_LIST"] = $this->listPacts($this->arResult["INFOBLOCK_ID"]);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>