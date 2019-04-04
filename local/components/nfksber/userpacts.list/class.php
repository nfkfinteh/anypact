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

    public function listPacts($id_iblock, $id_user) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                $arSelect = Array();
                $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "MODIFIED_USER_ID"=>$id_user);
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
        /*
        if(CModule::IncludeModule("highloadblock")){
            $arPacts['HL'] = array();
            $hlbl = 2; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
            $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 

            $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
            $entity_data_class = $entity->getDataClass(); 

            $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ID_USER" => $id_user)  // Задаем параметры фильтра выборки
            ));

            while($arData = $rsData->Fetch()){
                //var_dump($arRes);
                $arPacts['HL'][]  = $arData; 
            }
        
        }*/
        return $arPacts;
    }

    private function getSendContract($UserID){
        CModule::IncludeModule("highloadblock");
        CModule::IncludeModule("iblock");
            // получить статусы сделок
            $ID_hl_status_name_send_contract = 5;
            $hlblock = HL\HighloadBlockTable::getById($ID_hl_status_name_send_contract)->fetch(); 
            $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
            $entity_data_class = $entity->getDataClass();
            $rsData = $entity_data_class::getList();
            while($arData = $rsData->Fetch()){                
                $arStatus[$arData['ID']]  = $arData; 
            }

            // получить все подписанные сделки
            $ID_hl_send_contract = 3;
            $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract)->fetch(); 
            $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
            $entity_data_class = $entity->getDataClass();
            
            $arFilter = Array(
                Array(
                   "LOGIC"=>"OR",
                   Array(
                    "UF_ID_USER_A"=> $UserID
                   ),
                   Array(
                    "UF_ID_USER_B" => $UserID
                   )
                )
             );
            
            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order"  => array("ID" => "ASC"),
                "filter" => $arFilter
            ));            

            $i=0;            
            while($arData = $rsData->Fetch()){                 
                $arSend_Contract[$i]  = $arData; 
                if($arData['UF_ID_USER_B']==$UserID){
                    $arSend_Contract[$i]['STATUS_NAME'] = 'Я подписал договор';
                    $arSend_Contract[$i]['STATUS_ICON'] = 'send_one.png';
                } else {                         
                    $arSend_Contract[$i]['STATUS_NAME'] = $arStatus[$arData['UF_STATUS']]['UF_NAME'];
                    $arSend_Contract[$i]['STATUS_ICON'] = $arStatus[$arData['UF_STATUS']]['UF_STATUS_ICON'];
                }
                $arrID_Info_Contract[$i] =  $arData['UF_ID_CONTRACT'];
                $i++;
            }

            foreach($arrID_Info_Contract as $i=>$value ){
                $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 4, "ID" => $value), false, array(), array("ID", "NAME"));
                while($ob = $res->GetNextElement())
                {
                    $arInfo_Contract = $ob->GetFields();                    
                }
                $arSend_Contract[$i]['NAME_CONTRACT'] = $arInfo_Contract;

            }

            return $arSend_Contract;
        
    }

    private function getMessageUser($UserID){
        CModule::IncludeModule("highloadblock");
        // получить все подписанны сделки
        $ID_hl_message_user = 6;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_message_user)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($ID_hl_message_user); 
        $entity_data_class = $entity->getDataClass(); 
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ID_USER" => $UserID)
        ));
        
        $arMesage_User = array();           
        while($arData = $rsData->Fetch()){                                
            $arMesage_User[]  = $arData;
        }
        return $arMesage_User;
    }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        return $arResult;
    }

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $User_ID = CUser::GetID();
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_ID"] = $User_ID;
            $this->arResult["USER_LOGIN"] =CUser::GetLogin();
            // Все сделки созданные пользователем
            $this->arResult["INFOBLOCK_LIST"] = $this->listPacts($this->arResult["INFOBLOCK_ID"], $User_ID);
            // подписанные договора
            $this->arResult["SEND_CONTRACT"] = $this->getSendContract($User_ID);
            // сообщение пользователю
            $this->arResult["MESSAGE_USER"] = $this->getMessageUser($User_ID);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>