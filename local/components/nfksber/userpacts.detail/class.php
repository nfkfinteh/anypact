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
            "ELEMENT_ID" => intval($arParams["ELEMENT_ID"]),
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
                
            }
        return $arPact;
    }

    public function getContentIem($IBLOCK_ID, $ELEMENT_ID){
        if(CModule::IncludeModule("iblock")) {
            
            $getItem = CIBlockElement::GetByID($ELEMENT_ID);
            $content = array();
            // получить объект по ид
            if( $ar_res = $getItem->GetNext() ) {
                $content = $ar_res;
            }
            // получить свойства объекта
            $db_props = CIBlockElement::GetProperty($IBLOCK_ID, $ELEMENT_ID);
                while ($ar_props = $db_props->GetNext())
                {
                    $arFields[$ar_props["CODE"]] = $ar_props; 
                }                    
            $content['PROPERTIES']   = $arFields;

            // получить по ид стороны
            $ID_USER_A = $arFields['USER1'];
            $ID_USER_B = $arFields['USER2'];

            $rsUser = CUser::GetByID($ID_USER_A);
            $arUser = $rsUser->Fetch();
            $content['PROPERTIES']['USER1']['DATA_USER'] = $arUser;

            $rsUser = CUser::GetByID($ID_USER_B);
            $arUser = $rsUser->Fetch();
            $content['PROPERTIES']['USER2']['DATA_USER'] = $arUser;

            
            return $content;
        }
    }

    private function getUrlpacts($User_id){
        $hesh_user_id = md5($User_id);
        $arr_urlpacts = array();
        $arr_urlpacts["USER_ID"] = $User_id;
        $arr_urlpacts["HESH"] = $hesh_user_id;
        $arr_urlpacts["GROUP"] = mb_strimwidth ($hesh_user_id, 0, 2);
        $arr_urlpacts["USER_GROUP"] = mb_strimwidth ($hesh_user_id, 2, 4);        

        return $arr_urlpacts;
    }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["ELEMENT_ID"] = $arParams["ELEMENT_ID"];
        return $arResult;
    }

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));            
            $this->arResult["USER_PROP"] = $this->getUrlpacts(CUser::GetID());
            $this->arResult["USER_LOGIN"] =CUser::GetLogin();                                  
            //$this->arResult["INFOBLOCK_LIST"] = $this->listPacts($this->arResult["INFOBLOCK_ID"]);
            $this->arResult["CONTENT"] = $this->getContentIem($this->arResult["INFOBLOCK_ID"], $this->arResult["ELEMENT_ID"]);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>