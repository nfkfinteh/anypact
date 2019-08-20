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

    public function getSendContractItem($IDSendItem){
        CModule::IncludeModule("highloadblock");
        // получить все подписанны сделки
        $ID_hl_send_contract_text = 3;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract_text)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass(); 
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $IDSendItem)
        ));
                  
        while($arData = $rsData->Fetch()){            
            $arSendItem  = $arData;
        }
        
        // получить данные пользователей по id
        // пользователь А владелец контракта        
        $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_A']);
        $arUser_A = $rsUser->Fetch();        
        //статус подписи
        $hash_A ='--';
        if(!empty($arSendItem['UF_VER_CODE_USER_A'])){
            $status_send_a = '';
            $hash_A =md5($arSendItem['UF_VER_CODE_USER_A']);
        }else{
            $status_send_a = '<button class="btn btn-nfk" id="send_contract_owner" data-id='.$arSendItem['ID'].'" data-user="'.$arSendItem['UF_ID_USER_A'].'" >Подписать</button>';            
        }

        
        // пользователь В подписывающий
        $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_B']);
        $arUser_B = $rsUser->Fetch();
        $hash_B = md5($arSendItem['UF_VER_CODE_USER_B']);
        
        $Send_text = '<table style="width:100%; margin 50px 0;">';
        $Send_text .= '<tr>';
        $Send_text .= '<td style="width:50%">';
        $Send_text .= '<b>Подписано простой электронной подписью:</b>';
        $Send_text .= '<br>'.$arUser_A['LAST_NAME'].' '.$arUser_A['NAME'];
        $Send_text .= '<br>'.$hash_A;
        $Send_text .= '</td>';
        $Send_text .= '<td style="width:50%">';
        $Send_text .= '<b>Подписано простой электронной подписью:</b>';
        $Send_text .= '<br>'.$arUser_B['LAST_NAME'].' '.$arUser_B['NAME'].' '.$arUser_B['SECOND_NAME'];
        $Send_text .= '<br>'.$hash_B;
        $Send_text .= '</td>';
        $Send_text .= '</tr>';
        $Send_text .= '</table>';

        $arSend['TEXT'] = $Send_text;
        $arSend['ID']   = $status_send_a;
        
        return $arSend;
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
        if($this->startResultCache($this->arParams['CACHE_TIME'], $_GET["ID"]))
        {
            $IDSendItem = $_GET['ID'];
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["CONTRACT_TEXT"] = $this->getSendContractText($IDSendItem);
            $this->arResult["SEND_BLOCK"] = $this->getSendContractItem($IDSendItem);
            $this->arResult["PDF"] = $this->getURLPDF();
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>