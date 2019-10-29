<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

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
        );
        return $result;
    }

    public function listPacts($id_iblock, $id_user) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                $arSelect = Array();
                // выборку объявлений делаем по свойству "Владелец договора", так как создавать и модифицировать может администратор
                $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "PROPERTY_PACT_USER"=>$id_user);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);                
                
                while($ob = $res->GetNextElement())
                {
                    $arFields   = $ob->GetFields();
                    $id_element = $arFields["ID"];
                    $arFields['URL_IMG_PREVIEW'] = CFile::GetPath($arFields['DETAIL_PICTURE']);                    
                    $db_props = CIBlockElement::GetProperty($id_iblock, $id_element);
                    while ($ar_props = $db_props->GetNext())
                    {
                        $arFields["PROPERTIES"][$ar_props["CODE"]] = $ar_props; 
                    }                    
                    $arPact[]   = $arFields;
                }             
                $arPacts['ARR_SDELKI'] = $arPact;
            }
        return $arPacts;
    }

    // подписанные договора пользователей с двух сторон
    private function getSendContract($UserID){
        $arFilter = Array(
            Array(
               "LOGIC" => "OR",
               Array(
                "UF_STATUS" => 2,
                "UF_ID_USER_A"=> $UserID
               ),
               Array(
                "UF_STATUS" => 2,
                "UF_ID_USER_B" => $UserID
               )                   
            )
         );
        $arSend_Contract = $this->getSendContracts($UserID, $arFilter); 
        return $arSend_Contract;
        
    }

    // сообщения пользователей
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
            "filter" => array("UF_USERS_ID" => $UserID)
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

    private function getRedaction($userId){
        /* Договора подписанные с одной стороны храняться в HL блоке со статусом 1 
            договора пользователя ожидающие акцепта
        */
        $arFilter = Array(
            Array(
                "LOGIC" => "AND",
                Array(
                 "UF_STATUS" => 1,
                 
                ),
                Array(                 
                 "UF_ID_USER_A" => $userId
                )                   
             )
         );
        $arSend_Contract = $this->getSendContracts($UserID, $arFilter);

        /* договора имеющие редакцию подписантов храняться в отдельнов инфоблоке
        выборка  данных договоров*/
        $arFilter = [
            'IBLOCK_ID'=>6,
            'ACTIVE'=>'Y',
            [
                'LOGIC'=> 'OR',
                ['=PROPERTY_USER_A'=> $userId],
                ['=PROPERTY_USER_B'=> $userId]
            ]
        ];
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'NAME',
            'TIMESTAMP_X'
        ];
        $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while($obj = $res->GetNextElement(true, false)){
            $arFields = $obj->GetFields();
            $arRedaction[$arFields['ID']] = $arFields;
            $arRedaction[$arFields['ID']]['PROPERTY'] = $obj->GetProperties();

            $dbUser = CUser::GetByID($arRedaction[$arFields['ID']]['PROPERTY']['USER_B']['VALUE']);
            $arRedaction[$arFields['ID']]['USER_B'] = [
                'NAME' => $dbUser->GetNext()['LOGIN'],
                'LINK' => '/profile_user/?ID='.$arRedaction[$arFields['ID']]['PROPERTY']['USER_B']['VALUE']
            ];

        }

        $returnArray = array_merge($arSend_Contract, $arRedaction);
        return $returnArray;
    }

    private function getSendUserContract($userId){
        /* Договора подписанные текущим пользователем
        */
        $arFilter = Array(
            Array(
                "LOGIC" => "AND",
                Array(
                 "UF_STATUS" => 1,
                 
                ),
                Array(                 
                 "UF_ID_USER_B" => $userId
                )                   
             )
         );
        $arSend_Contract = $this->getSendContracts($UserID, $arFilter);

        return $arSend_Contract;
    }

    /* 
        Выборка договоров из HL блока по статусу 
    */
    private function getSendContracts($UserID, $arFilter){
        $arSend_Contract = [];
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
                    // параметры пользователя подписавшего договор
                    $ParamsSendUser = CUser::GetByID($arData['UF_ID_USER_A']);
                    $ParamsSendUser = $ParamsSendUser->Fetch();
                    $arSend_Contract[$i]['PARAMS_SEND_USER']  = $ParamsSendUser;
                    $arSend_Contract[$i]['PARAMS_SEND_USER']['IN'] = substr($ParamsSendUser["LAST_NAME"], 0, 1);
                    $arSend_Contract[$i]['PERSONAL_PHOTO_SEND_USER']  = CFile::GetPath($ParamsSendUser['PERSONAL_PHOTO']);
                } else {                         
                    $arSend_Contract[$i]['STATUS_NAME'] = $arStatus[$arData['UF_STATUS']]['UF_NAME'];
                    $arSend_Contract[$i]['STATUS_ICON'] = $arStatus[$arData['UF_STATUS']]['UF_STATUS_ICON'];
                    // параметры пользователя подписавшего договор
                    $ParamsSendUser = CUser::GetByID($arData['UF_ID_USER_B']);
                    $ParamsSendUser = $ParamsSendUser->Fetch();
                    $arSend_Contract[$i]['PARAMS_SEND_USER']  = $ParamsSendUser;
                    $arSend_Contract[$i]['PARAMS_SEND_USER']['IN'] = substr($ParamsSendUser["LAST_NAME"], 0, 1);
                    $arSend_Contract[$i]['PERSONAL_PHOTO_SEND_USER']  = CFile::GetPath($ParamsSendUser['PERSONAL_PHOTO']);      

                }

                $arrID_Info_Contract[$i] =  $arData['UF_ID_CONTRACT'];
                $i++;
            }

            #если нет подписаных договоров
            if(empty($arrID_Info_Contract)) return $arSend_Contract;

            foreach($arrID_Info_Contract as $i=>$value ){
                $iblockId = CIBlockElement::GetIBlockByID($value);
                $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $iblockId, "ID" => $value), false, array(), array("ID", "NAME"));
                while($ob = $res->GetNextElement())
                {
                    $arInfo_Contract = $ob->GetFields();                    
                }
                $arSend_Contract[$i]['NAME_CONTRACT'] = $arInfo_Contract;

            }

            return $arSend_Contract;
    }

    public function executeComponent()
    {
        $User_ID = CUser::GetID();
        $this->arResult["USER_ID"] = $User_ID;

        // подписанные договора
        $this->arResult["SEND_CONTRACT"] = $this->getSendContract($User_ID);

        // сообщение пользователю
        $this->arResult["MESSAGE_USER"] = $this->getMessageUser($User_ID);;

        // кэш отключен
        /*if($this->startResultCache($this->arParams['CACHE_TIME'], $User_ID))
        {*/
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_LOGIN"] =CUser::GetLogin();
            
            // Все сделки созданные пользователем
            $this->arResult["INFOBLOCK_LIST"] = $this->listPacts($this->arResult["INFOBLOCK_ID"], $User_ID);

            //редакции
            $this->arResult["REDACTION"] = $this->getRedaction($User_ID);

            //Подписанные пользователем договора
            $this->arResult["SEND_USER_PACT"] = $this->getSendUserContract($User_ID);

        /*    $this->EndResultCache();
        }*/
        $this->includeComponentTemplate();
        
        return $this->arResult;
    }
};

?>