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
                if(empty($this->arResult['ID_CUR_COMPANY'])){
                    // выборку объявлений делаем по свойству "Владелец договора", так как создавать и модифицировать может администратор
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "PROPERTY_PACT_USER"=>$id_user);
                }
                else{
                    // выборку объявлений делаем по свойству "Владелец договора", так как создавать и модифицировать может администратор
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "PROPERTY_ID_COMPANY"=>$id_user);
                }
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50));
                
                while($ob = $res->GetNextElement())
                {
                    $arFields   = $ob->GetFields();
                    $id_element = $arFields["ID"];
                    $arFields['PROPERTIES'] = $ob->GetProperties();
                    $arFields['URL_IMG_PREVIEW'] = NULL;
                    $arFields['URL_IMG_PREVIEW'] = CFile::ResizeImageGet($arFields["PROPERTIES"]['INPUT_FILES']['VALUE'][0], ['width'=>60, 'height'=>60], BX_RESIZE_IMAGE_PROPORTIONAL )['src'];
                    $arPact[]   = $arFields;
                }             
                $arPacts['ARR_SDELKI'] = $arPact;
            }
        return $arPacts;
    }

    // подписанные договора пользователей с двух сторон
    private function getSendContract($UserID){
        if(empty($this->arResult['ID_CUR_COMPANY'])){
            $arFilter = Array(
                Array(
                    "LOGIC" => "OR",
                    Array(
                        "UF_STATUS" => 2,
                        "UF_ID_USER_A"=> $UserID,
                        "UF_ID_COMPANY_A"=>false,
                    ),
                    Array(
                        "UF_STATUS" => 2,
                        "UF_ID_USER_B" => $UserID,
                        "UF_ID_COMPANY_B"=>false,
                    )
                )
            );
        }
        else{
            $arFilter = Array(
                Array(
                    "LOGIC" => "OR",
                    Array(
                        "UF_STATUS" => 2,
                        "UF_ID_COMPANY_A"=> $UserID
                    ),
                    Array(
                        "UF_STATUS" => 2,
                        "UF_ID_COMPANY_B" => $UserID
                    )
                )
            );
        }
        $arSend_Contract = $this->getSendContracts($UserID, $arFilter, false);
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
            "filter" => array(
                "LOGIC" => "OR",                
                "UF_ID_USER" => $UserID,
                "UF_ID_SENDER" => $UserID
                )
        ));
        
        $arMesage_User = array();
        $i = 0 ;          
        while($arData = $rsData->Fetch()){                                
            $arMesage_User[$i]  = $arData;
            $rsUser = CUser::GetByID($arData["UF_ID_SENDER"]);
            $arUser = $rsUser->Fetch();
            $arMesage_User[$i]["PARAMS_SENDER_USER"]["FIO"]  = $arUser['LAST_NAME'] .' '. $arUser['NAME'] .' '. $arUser['SECOND_NAME'];
            $arMesage_User[$i]["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"]  = $arUser["PERSONAL_PHOTO"];
            $arMesage_User[$i]["PARAMS_SENDER_USER"]["ID"]  = $arUser['ID'];
            $arMesage_User[$i]["PARAMS_SENDER_USER"]["IN"]  = substr($arUser['NAME'], 0, 1);
            
            $i++;
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
        if(empty($this->arResult['ID_CUR_COMPANY'])){
            $arFilter = Array(
                "UF_STATUS" => array(1),
                "UF_ID_USER_A" => $userId,
                "UF_ID_COMPANY_A"=>false
            );
        }
        else{
            $arFilter = Array(
                "UF_STATUS" => array(1),
                "UF_ID_COMPANY_A" => $userId,
            );
        }
        $arSend_Contract = array();
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

        $arRedaction = array();

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

        // договора изменные несколько раз
        $arFilter = Array(
            Array(
                "LOGIC" => "OR",
                Array(                 
                    "UF_ID_USER_A" => $userId,
                    "UF_STATUS" => array(3),
                    "!UF_ID_SEND_USER" => $userId
                ),
                Array(                 
                    "UF_ID_USER_B" => $userId,
                    "UF_STATUS" => array(3),
                    "!UF_ID_SEND_USER" => $userId
                   )                  
             )
         );
        $arEditSend_Contract = array();
        $arEditSend_Contract = $this->getSendContracts($UserID, $arFilter);


        $returnArray = array_merge($arSend_Contract, $arRedaction, $arEditSend_Contract);
        return $returnArray;
    }

    private function getSendUserContract($userId){
        /* Договора подписанные текущим профилем
        */
        if(empty($this->arResult['ID_CUR_COMPANY'])){
            $arFilter = Array(
                "UF_STATUS" => array(1),
                "UF_ID_USER_B" => $userId,
                "UF_ID_COMPANY_B"=>false
            );
        }
        else{
            $arFilter = Array(
                "UF_STATUS" => array(1),
                "UF_ID_COMPANY_B"=>$userId
            );
        }

        $arSend_Contract = $this->getSendContracts($UserID, $arFilter, false);
        // договора измененные и ожидающие подписания

        $arFilter = Array(
            Array(
                "LOGIC" => "OR",
                Array(                 
                    "UF_ID_USER_A" => $userId,
                    "UF_STATUS" => array(3),
                    "UF_ID_SEND_USER" => $userId
                ),
                Array(                 
                    "UF_ID_USER_B" => $userId,
                    "UF_STATUS" => array(3),
                    "UF_ID_SEND_USER" => $userId
                   )                   
             )
         );
        $arEdit_Send_Contract = $this->getSendContracts($UserID, $arFilter, false);        
        
        $return_array = array_merge($arSend_Contract, $arEdit_Send_Contract);
        return $return_array;
    }

    /* 
        Выборка договоров из HL блока по статусу 
    */
    private function getSendContracts($UserID, $arFilter, $sideA = true){
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
                } else {                         
                    $arSend_Contract[$i]['STATUS_NAME'] = $arStatus[$arData['UF_STATUS']]['UF_NAME'];
                    $arSend_Contract[$i]['STATUS_ICON'] = $arStatus[$arData['UF_STATUS']]['UF_STATUS_ICON'];
                }

                // параметры пользователя подписавшего договор
                if($sideA){
                    $UserSending = $arData['UF_ID_USER_B'];
                    $companySending = $arData['UF_ID_COMPANY_B'];
                }else {
                    $UserSending = $arData['UF_ID_USER_A'];
                    $companySending = $arData['UF_ID_COMPANY_A'];
                }


                if(empty($this->arResult['ID_CUR_COMPANY']) || empty($companySending)){
                    $ParamsSendUser = CUser::GetByID($UserSending);
                    $ParamsSendUser = $ParamsSendUser->Fetch();
                    $arSend_Contract[$i]['PARAMS_SEND_USER']  = $ParamsSendUser;
                    $arSend_Contract[$i]['PARAMS_SEND_USER']['IN'] = substr($ParamsSendUser["LAST_NAME"], 0, 1);
                    $arSend_Contract[$i]['PERSONAL_PHOTO_SEND_USER']  = CFile::GetPath($ParamsSendUser['PERSONAL_PHOTO']);
                }
                elseif(!empty($companySending)){
                    $resCompany = CIBlockElement::GetList(
                        [],
                        ['IBLOCK_ID'=>8, 'ID'=>$companySending],
                        false,
                        false,
                        ['IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_PICTURE']
                    );
                    if($obj = $resCompany->GetNext()){
                        $arSend_Contract[$i]['PARAMS_SEND_COMPANY'] = $obj;
                        $arSend_Contract[$i]['PARAMS_SEND_COMPANY']['IN'] = substr($obj["NAME"], 0, 1);
                        if(!empty($obj['PREVIEW_PICTURE'])){
                            $arSend_Contract[$i]['PARAMS_SEND_COMPANY']['PREVIEW_PICTURE']  = CFile::GetPath($obj['PREVIEW_PICTURE']);
                        }
                    }

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
        $rsUser = CUser::GetByID($User_ID);
        $this->arResult['USER'] = $rsUser->GetNext();
        $this->arResult["USER_LOGIN"] =$this->arResult['USER']['LOGIN'];
        $this->arResult['ID_CUR_COMPANY'] = $this->arResult['USER']['UF_CUR_COMPANY'];
        $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
        if(empty($this->arResult['ID_CUR_COMPANY'])){
            $idProfile = $User_ID;
        }
        else{
            $idProfile = $this->arResult['ID_CUR_COMPANY'];
        }


        // сообщение пользователю
        //$this->arResult["MESSAGE_USER"] = $this->getMessageUser($User_ID);;
        // Все сделки созданные пользователем
        $this->arResult["INFOBLOCK_LIST"] = $this->listPacts($this->arResult["INFOBLOCK_ID"], $idProfile);
        // подписанные договора
        $this->arResult["SEND_CONTRACT"] = $this->getSendContract($idProfile);
        //редакции
        $this->arResult["REDACTION"] = $this->getRedaction($idProfile);
        //Подписанные пользователем договора
        $this->arResult["SEND_USER_PACT"] = $this->getSendUserContract($idProfile);

        $this->includeComponentTemplate();
        
        return $this->arResult;
    }
};

?>