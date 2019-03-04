<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
    Класс выводит текст подписанного договора
*/
use Bitrix\Main\Loader; 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

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
        );
        return $result;
    }

    private function getSendContractText($IDSendItem){
        CModule::IncludeModule("highloadblock");
        // получить все подписанны сделки
        $ID_hl_send_contract_text = 7;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract_text)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass(); 
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_TEXT_CONTRACT"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ID_SEND_ITEM" => $IDSendItem)
        ));
                  
        while($arData = $rsData->Fetch()){            
            $arMesage_User  = $arData['UF_TEXT_CONTRACT'];
        }
        return $arMesage_User;
    }

    private function getURLPDF(){
        $URL_PDF = '/upload/dogovor_test.pdf';
        return  $URL_PDF;
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
            $IDSendItem = 11;
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["CONTRACT_TEXT"] = $this->getSendContractText($IDSendItem);
            $this->arResult["PDF"] = $this->getURLPDF();
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>