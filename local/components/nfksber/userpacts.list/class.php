<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader; 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

class CDemoSqr extends CBitrixComponent
{   

    // private $redactionMe = array();

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
                /*if(empty($this->arResult['ID_CUR_COMPANY'])){
                    // выборку объявлений делаем по свойству "Владелец договора", так как создавать и модифицировать может администратор
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "PROPERTY_PACT_USER"=>$id_user);
                }
                else{*/
                    // выборку объявлений делаем по свойству "Владелец договора", так как создавать и модифицировать может администратор
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), array("LOGIC" => "OR", array("PROPERTY_ID_COMPANY"=>$id_user), array("PROPERTY_ID_COMPANY"=>false, "CREATED_BY"=>$id_user)));
                //}
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50));
                
                while($ob = $res->GetNextElement())
                {
                    $arFields = $ob->GetFields();
                    $arFields['PROPERTIES'] = $ob->GetProperties();
                    $arFields['URL_IMG_PREVIEW'] = NULL;
                    $arFields['URL_IMG_PREVIEW'] = CFile::ResizeImageGet($arFields["PROPERTIES"]['INPUT_FILES']['VALUE'][0], ['width'=>60, 'height'=>60], BX_RESIZE_IMAGE_PROPORTIONAL )['src'];
                    $arPact[] = $arFields;
                }
            }
        return $arPact;
    }

    // подписанные договора пользователей с двух сторон
    // private function getSendContract($UserID){
    //     if(empty($this->arResult['ID_CUR_COMPANY'])){
    //         $arFilter = Array(
    //             Array(
    //                 "LOGIC" => "OR",
    //                 Array(
    //                     "UF_STATUS" => 2,
    //                     "UF_ID_USER_A"=> $UserID,
    //                     "UF_ID_COMPANY_A"=>false,
    //                 ),
    //                 Array(
    //                     "UF_STATUS" => 2,
    //                     "UF_ID_USER_B" => $UserID,
    //                     "UF_ID_COMPANY_B"=>false,
    //                 )
    //             )
    //         );
    //     }
    //     else{
    //         $arFilter = Array(
    //             Array(
    //                 "LOGIC" => "OR",
    //                 Array(
    //                     "UF_STATUS" => 2,
    //                     "UF_ID_COMPANY_A"=> $UserID
    //                 ),
    //                 Array(
    //                     "UF_STATUS" => 2,
    //                     "UF_ID_COMPANY_B" => $UserID
    //                 )
    //             )
    //         );
    //     }
    //     $arSend_Contract = $this->getSendContracts($UserID, $arFilter, false);
    //     return $arSend_Contract;
        
    // }

    // сообщения пользователей
    // private function getMessageUser($UserID){
    //     CModule::IncludeModule("highloadblock");
    //     // получить все подписанны сделки
    //     $ID_hl_message_user = 6;
    //     $hlblock = HL\HighloadBlockTable::getById($ID_hl_message_user)->fetch(); 
    //     $entity = HL\HighloadBlockTable::compileEntity($ID_hl_message_user); 
    //     $entity_data_class = $entity->getDataClass(); 
    //     $rsData = $entity_data_class::getList(array(
    //         "select" => array("*"),
    //         "order" => array("ID" => "ASC"),
    //         "filter" => array(
    //             "LOGIC" => "OR",                
    //             "UF_ID_USER" => $UserID,
    //             "UF_ID_SENDER" => $UserID
    //             )
    //     ));
        
    //     $arMesage_User = array();
    //     $i = 0 ;          
    //     while($arData = $rsData->Fetch()){                                
    //         $arMesage_User[$i]  = $arData;
    //         $rsUser = CUser::GetByID($arData["UF_ID_SENDER"]);
    //         $arUser = $rsUser->Fetch();
    //         $arMesage_User[$i]["PARAMS_SENDER_USER"]["FIO"]  = $arUser['LAST_NAME'] .' '. $arUser['NAME'] .' '. $arUser['SECOND_NAME'];
    //         $arMesage_User[$i]["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"]  = $arUser["PERSONAL_PHOTO"];
    //         $arMesage_User[$i]["PARAMS_SENDER_USER"]["ID"]  = $arUser['ID'];
    //         $arMesage_User[$i]["PARAMS_SENDER_USER"]["IN"]  = substr($arUser['NAME'], 0, 1);
            
    //         $i++;
    //     }
    //     return $arMesage_User;
    // }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        return $arResult;
    }

    // private function getRedaction($userId){
    //     /* Договора подписанные с одной стороны храняться в HL блоке со статусом 1 
    //         договора пользователя ожидающие акцепта
    //     */
    //     if(empty($this->arResult['ID_CUR_COMPANY'])){
    //         $arFilter = Array(
    //             "UF_STATUS" => array(1),
    //             "UF_ID_USER_A" => $userId,
    //             "UF_ID_COMPANY_A"=>false
    //         );
    //     }
    //     else{
    //         $arFilter = Array(
    //             "UF_STATUS" => array(1),
    //             "UF_ID_COMPANY_A" => $userId,
    //         );
    //     }
    //     $arSend_Contract = array();
    //     $arSend_Contract = $this->getSendContracts($UserID, $arFilter);

    //     /* договора имеющие редакцию подписантов храняться в отдельнов инфоблоке
    //     выборка  данных договоров*/
    //     $arFilter = [
    //         'IBLOCK_ID'=>6,
    //         'ACTIVE'=>'Y',
    //         [
    //             'LOGIC'=> 'OR',
    //             ['=PROPERTY_USER_A'=> $userId],
    //             ['=PROPERTY_USER_B'=> $userId]
    //         ]
    //     ];
    //     $arSelect = [
    //         'IBLOCK_ID',
    //         'ID',
    //         'NAME',
    //         'TIMESTAMP_X'
    //     ];

    //     $arRedaction = array();

    //     $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
    //     while($obj = $res->GetNextElement(true, false)){
    //         $arFields = $obj->GetFields();
    //         $arRedaction[$arFields['ID']] = $arFields;
    //         $arRedaction[$arFields['ID']]['PROPERTY'] = $obj->GetProperties();

    //         $send_user = $arRedaction[$arFields['ID']]['PROPERTY']['USER_B']['VALUE'];

    //         if($arRedaction[$arFields['ID']]['PROPERTY']['USER_B']['VALUE'] == $userId)
    //             $send_user = $arRedaction[$arFields['ID']]['PROPERTY']['USER_A']['VALUE'];

    //         $dbUser = CUser::GetByID($send_user);
    //         $arRedaction[$arFields['ID']]['PARAMS_SEND_USER'] = $dbUser->GetNext();
    //         $arRedaction[$arFields['ID']]['PARAMS_SEND_USER']['IN'] = substr($arRedaction[$arFields['ID']]['PARAMS_SEND_USER']["LAST_NAME"], 0, 1);
    //         $arRedaction[$arFields['ID']]['PERSONAL_PHOTO_SEND_USER']  = CFile::GetPath($arRedaction[$arFields['ID']]['PARAMS_SEND_USER']['PERSONAL_PHOTO']);
    //         $arRedaction[$arFields['ID']]['USER_B'] = [
    //             'NAME' => $arRedaction[$arFields['ID']]['PARAMS_SEND_USER']['LOGIN'],
    //             'LINK' => '/profile_user/?ID='.$arRedaction[$arFields['ID']]['PROPERTY']['USER_B']['VALUE']
    //         ];

    //         $arRedaction[$arFields['ID']]['NAME_CONTRACT'] = array("ID" => $arRedaction[$arFields['ID']]['PROPERTY']['USER_B']['VALUE'], "NAME" => $arFields['NAME']);
            
    //         $arRedaction[$arFields['ID']]['UF_STATUS'] = 2;
    //         if($arRedaction[$arFields['ID']]['PROPERTY']['USER_ID_INITIATOR']['VALUE'] == $userId)
    //             $arRedaction[$arFields['ID']]['UF_STATUS'] = 4;
    //         if($arRedaction[$arFields['ID']]['PROPERTY']['STATUS_TRADE']['VALUE'] == "0")
    //             $arRedaction[$arFields['ID']]['UF_STATUS'] = 1;
    //         if($arRedaction[$arFields['ID']]['PROPERTY']['STATUS_TRADE']['VALUE'] == 1)
    //             $arRedaction[$arFields['ID']]['UF_STATUS'] = 0;
    //         if($arRedaction[$arFields['ID']]['PROPERTY']['STATUS_TRADE']['VALUE'] == "0" && $arRedaction[$arFields['ID']]['PROPERTY']['USER_ID_INITIATOR']['VALUE'] != $userId)
    //             $arRedaction[$arFields['ID']]['UF_STATUS'] = 3;

    //         $arRedaction[$arFields['ID']]['IS_REDACTION'] = "Y";

    //         if($arRedaction[$arFields['ID']]['UF_STATUS'] == 4){
    //             $this -> redactionMe[$arFields['ID']] = $arRedaction[$arFields['ID']];
    //             unset($arRedaction[$arFields['ID']]);
    //         }

    //     }

    //     // договора изменные несколько раз
    //     $arFilter = Array(
    //         Array(
    //             "LOGIC" => "OR",
    //             Array(                 
    //                 "UF_ID_USER_A" => $userId,
    //                 "UF_STATUS" => array(3),
    //                 "!UF_ID_SEND_USER" => $userId
    //             ),
    //             Array(                 
    //                 "UF_ID_USER_B" => $userId,
    //                 "UF_STATUS" => array(3),
    //                 "!UF_ID_SEND_USER" => $userId
    //                )                  
    //          )
    //      );
    //     $arEditSend_Contract = array();
    //     $arEditSend_Contract = $this->getSendContracts($UserID, $arFilter);


    //     $returnArray = array_merge($arSend_Contract, $arRedaction, $arEditSend_Contract);
    //     return $returnArray;
    // }

    // private function getSendUserContract($userId){
    //     /* Договора подписанные текущим профилем
    //     */
    //     if(empty($this->arResult['ID_CUR_COMPANY'])){
    //         $arFilter = Array(
    //             "UF_STATUS" => array(1),
    //             "UF_ID_USER_B" => $userId,
    //             "UF_ID_COMPANY_B"=>false
    //         );
    //     }
    //     else{
    //         $arFilter = Array(
    //             "UF_STATUS" => array(1),
    //             "UF_ID_COMPANY_B"=>$userId
    //         );
    //     }

    //     $arSend_Contract = $this->getSendContracts($UserID, $arFilter, false);
    //     // договора измененные и ожидающие подписания

    //     $arFilter = Array(
    //         Array(
    //             "LOGIC" => "OR",
    //             Array(                 
    //                 "UF_ID_USER_A" => $userId,
    //                 "UF_STATUS" => array(3),
    //                 "UF_ID_SEND_USER" => $userId
    //             ),
    //             Array(                 
    //                 "UF_ID_USER_B" => $userId,
    //                 "UF_STATUS" => array(3),
    //                 "UF_ID_SEND_USER" => $userId
    //                )                   
    //          )
    //      );
    //     $arEdit_Send_Contract = $this->getSendContracts($UserID, $arFilter, false);        
        
    //     $return_array = array_merge($arSend_Contract, $arEdit_Send_Contract, $this -> redactionMe);
    //     return $return_array;
    // }

    /* 
        Выборка договоров из HL блока по статусу 
    */
    // private function getSendContracts($UserID, $arFilter, $sideA = true){
    //     $arSend_Contract = [];
    //     CModule::IncludeModule("highloadblock");
    //     CModule::IncludeModule("iblock");
            
    //         // получить статусы сделок
    //         $ID_hl_status_name_send_contract = 5;
    //         $hlblock = HL\HighloadBlockTable::getById($ID_hl_status_name_send_contract)->fetch(); 
    //         $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
    //         $entity_data_class = $entity->getDataClass();
    //         $rsData = $entity_data_class::getList();
            
    //         while($arData = $rsData->Fetch()){                
    //             $arStatus[$arData['ID']]  = $arData; 
    //         }

    //         // получить все подписанные сделки
    //         $ID_hl_send_contract = 3;
    //         $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract)->fetch(); 
    //         $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
    //         $entity_data_class = $entity->getDataClass();            
    //         $rsData = $entity_data_class::getList(array(
    //             "select" => array("*"),
    //             "order"  => array("ID" => "ASC"),
    //             "filter" => $arFilter
    //         ));            

    //         $i=0;            
    //         while($arData = $rsData->Fetch()){                 
    //             $arSend_Contract[$i]  = $arData;

    //             if($arData['UF_ID_USER_B']==$UserID){                    
    //                 $arSend_Contract[$i]['STATUS_NAME'] = 'Я подписал договор';
    //                 $arSend_Contract[$i]['STATUS_ICON'] = 'send_one.png';                    
    //             } else {                         
    //                 $arSend_Contract[$i]['STATUS_NAME'] = $arStatus[$arData['UF_STATUS']]['UF_NAME'];
    //                 $arSend_Contract[$i]['STATUS_ICON'] = $arStatus[$arData['UF_STATUS']]['UF_STATUS_ICON'];
    //             }

    //             // параметры пользователя подписавшего договор
    //             if($sideA){
    //                 $UserSending = $arData['UF_ID_USER_B'];
    //                 $companySending = $arData['UF_ID_COMPANY_B'];
    //             }else {
    //                 $UserSending = $arData['UF_ID_USER_A'];
    //                 $companySending = $arData['UF_ID_COMPANY_A'];
    //             }


    //             if(empty($this->arResult['ID_CUR_COMPANY']) || empty($companySending)){
    //                 $ParamsSendUser = CUser::GetByID($UserSending);
    //                 $ParamsSendUser = $ParamsSendUser->Fetch();
    //                 $arSend_Contract[$i]['PARAMS_SEND_USER']  = $ParamsSendUser;
    //                 $arSend_Contract[$i]['PARAMS_SEND_USER']['IN'] = substr($ParamsSendUser["LAST_NAME"], 0, 1);
    //                 $arSend_Contract[$i]['PERSONAL_PHOTO_SEND_USER']  = CFile::GetPath($ParamsSendUser['PERSONAL_PHOTO']);
    //             }
    //             elseif(!empty($companySending)){
    //                 $resCompany = CIBlockElement::GetList(
    //                     [],
    //                     ['IBLOCK_ID'=>8, 'ID'=>$companySending],
    //                     false,
    //                     false,
    //                     ['IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_PICTURE']
    //                 );
    //                 if($obj = $resCompany->GetNext()){
    //                     $arSend_Contract[$i]['PARAMS_SEND_COMPANY'] = $obj;
    //                     $arSend_Contract[$i]['PARAMS_SEND_COMPANY']['IN'] = substr($obj["NAME"], 0, 1);
    //                     if(!empty($obj['PREVIEW_PICTURE'])){
    //                         $arSend_Contract[$i]['PARAMS_SEND_COMPANY']['PREVIEW_PICTURE']  = CFile::GetPath($obj['PREVIEW_PICTURE']);
    //                     }
    //                 }

    //             }


    //             $arrID_Info_Contract[$i] =  $arData['UF_ID_CONTRACT'];
    //             $i++;
    //         }

    //         #если нет подписаных договоров
    //         if(empty($arrID_Info_Contract)) return $arSend_Contract;

    //         foreach($arrID_Info_Contract as $i=>$value ){
    //             $iblockId = CIBlockElement::GetIBlockByID($value);
    //             $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $iblockId, "ID" => $value), false, array(), array("ID", "NAME"));
    //             while($ob = $res->GetNextElement())
    //             {
    //                 $arInfo_Contract = $ob->GetFields();                    
    //             }
    //             $arSend_Contract[$i]['NAME_CONTRACT'] = $arInfo_Contract;

    //         }

    //         return $arSend_Contract;
    // }

    private static function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = HL\HighloadBlockTable::getById($HlBlockId)->fetch();   
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    private static function checkCompany($id, $user_id = 0){
        if(!empty($id))
            if(Loader::includeModule('iblock')){
                $arFilter = [
                    'IBLOCK_ID' => COMPANY_IB_ID, 
                    'ID' => $id, 
                    'ACTIVE' => 'Y'
                ];
                if(!empty($user_id))
                    $arFilter[0] = [
                        "LOGIC" => "OR",
                        "PROPERTY_STAFF" => $user_id,
                        "PROPERTY_DIRECTOR_ID" => $user_id
                    ];
                $res = CIBlockElement::GetList([], $arFilter, false, false, ['ID']);
                if($ob = $res->GetNext(true, false)){
                    return $ob['ID'];
                }
            }
        return 0;
    }

    private static function getUser($id){
        if(!empty($id) && $id != 1){
            $res = \Bitrix\Main\UserTable::getList(array(
                'select' => array(
                    "ID", 
                    "UF_CUR_COMPANY", 
                    "UF_ESIA_AUT",
                    "NAME", 
                    "LAST_NAME", 
                    "PERSONAL_PHOTO", 
                ), 
                "order" => array("ID" => "ASC"),
                'filter' => array("ID" => $id)
            ));
            if($user = $res->Fetch()){
                $fio = "";
                if(!empty($user['LAST_NAME'])) $fio .= $user['LAST_NAME'];
                if(!empty($fio)) $fio .= " ";
                if(!empty($user['NAME'])) $fio .= $user['NAME'];
                if(!empty($fio)) $fio .= " ";

                if(!empty($fio))
                    $first_letter = substr($fio, 0, 1);
                if(!empty($user['PERSONAL_PHOTO']))
                    $renderImage = CFile::ResizeImageGet($user['PERSONAL_PHOTO'], Array("width" => 60, "height" => 60), BX_RESIZE_IMAGE_EXACT, false);

                return array("ID" => $user['ID'], "TYPE" => "U", "NAME" => $fio, "PICTURE" => $renderImage['src'], "FIRST_LETTER" => $first_letter, "COMPANY_ID" => self::checkCompany($user["UF_CUR_COMPANY"], $id), "ESIA" => $user["UF_ESIA_AUT"]);

            }
        }
        return false;
    }

    private static function getCompany($id){
        if(!empty($id))
            if(Loader::includeModule('iblock')){
                $arFilter = [
                    'IBLOCK_ID' => COMPANY_IB_ID, 
                    'ID' => $id, 
                    'ACTIVE' => 'Y'
                ];
                $res = CIBlockElement::GetList([], $arFilter, false, false, ['ID', "NAME", "PREVIEW_PICTURE"]);
                if($ob = $res->Fetch()){
                    if(!empty($ob["NAME"]))
                        $first_letter = substr($ob["NAME"], 0, 1);
                    if(!empty($ob['PREVIEW_PICTURE']))
                        $renderImage = CFile::ResizeImageGet($ob['PREVIEW_PICTURE'], Array("width" => 60, "height" => 60), BX_RESIZE_IMAGE_EXACT, false);
                    return array("ID" => $ob['ID'], "TYPE" => "C", "NAME" => $ob['NAME'], "PICTURE" => $renderImage['src'], "FIRST_LETTER" => $first_letter);
                }
            }
        return false;
    }

    private static function getContract($id){
        if(Loader::includeModule("iblock"))
        {
            $res = CIBlockElement::GetList(Array(), array("ID" => $id, "IBLOCK_ID" => CONTRACTS_IB_ID), false, false, array("ID", "NAME"));
            if($ob = $res->Fetch())
            {
                return array("ID" => $ob['ID'], "NAME" => $ob['NAME']);
            }
        }
        return false;
    }

    private static function getDeal($id){
        if(Loader::includeModule("iblock"))
        {
            $res = CIBlockElement::GetList(Array(), array("ID" => $id, "IBLOCK_ID" => DEALS_IB_ID), false, false, array("ID", "NAME"));
            if($ob = $res->Fetch())
            {
                return array("ID" => $ob['ID'], "NAME" => $ob['NAME']);
            }
        }
        return false;
    }

    private static function getId($id){
        if(Loader::includeModule("iblock"))
        {
            $res = CIBlockElement::GetList(Array(), array("PROPERTY_ID_DOGOVORA" => $id, "IBLOCK_ID" => DEALS_IB_ID), false, false, array("ID"));
            if($ob = $res->Fetch())
            {
                return $ob['ID'];
            }
        }
        return false;
    }

    private function getSignedContracts($user_id, $company_id = 0){
        if(Loader::includeModule("highloadblock"))
        {
            
            if(!empty($company_id)){
                $arFilter = array(
                    "LOGIC" => "OR",
                    array("UF_ID_COMPANY_A" => $company_id),
                    array("UF_ID_COMPANY_B" => $company_id)
                );
            }else{
                $arFilter = array(
                    "LOGIC" => "OR",
                    array("UF_ID_USER_A" => $user_id),
                    array("UF_ID_USER_B" => $user_id),
                );
            }

            $entity_data_class = self::GetEntityDataClass(CONTRACT_SIGNED_HBL_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array("ID" => "ASC"),
                "filter" => array(
                    "UF_STATUS" => array(1, 2),
                    $arFilter
                )
            ));
            while($arSigned = $rsData->Fetch()){

                $arRes['ID'] = self::getId($arSigned['UF_ID_CONTRACT']);

                if($arRes['ID'] === false)
                    continue;

                if((!empty($company_id) && $arSigned['UF_ID_COMPANY_A'] == $company_id) || $arSigned['UF_ID_USER_A'] == $user_id)
                    $P = "B";
                else
                    $P = "A";
                
                if(!empty($arSigned['UF_ID_COMPANY_'.$P]))
                    $arRes['PARTNER'] = self::getCompany($arSigned['UF_ID_COMPANY_'.$P]);
                else
                    $arRes['PARTNER'] = self::getUser($arSigned['UF_ID_USER_'.$P]);

                if($arRes['PARTNER'] === false)
                    continue;

                $arRes['CONTRACT'] = self::getContract($arSigned['UF_ID_CONTRACT']);

                if($arRes['CONTRACT'] === false)
                    continue;

                if($arSigned['UF_TIME_SEND_USER_A'] > $arSigned['UF_TIME_SEND_USER_B'])
                    $arRes['DATA'] = $arSigned['UF_TIME_SEND_USER_A'];
                else
                    $arRes['DATA'] = $arSigned['UF_TIME_SEND_USER_B'];
                
                if(!empty($arSigned['UF_VER_CODE_USER_A']) && !empty($arSigned['UF_VER_CODE_USER_B']))
                {
                    $arRes['ID'] = $arSigned['ID'];
                    $this -> arResult['SIGNED_CONTRACTS'][$arRes['ID']] = $arRes;
                }
                else if(!empty($arSigned['UF_VER_CODE_USER_'.$P]))
                    $this -> arResult['SIGNED_PARTNER'][$arRes['ID']] = $arRes;
                else
                    $this -> arResult['SIGNED_USER'][$arRes['ID']] = $arRes;
            }
        }
    }

    private function getContractEdit($user_id, $company_id){
        if(Loader::includeModule("highloadblock"))
        {

            if(!empty($company_id)){
                $arFilter = array(
                    "LOGIC" => "OR",
                    array("UF_COMPANY_A" => $company_id),
                    array("UF_COMPANY_B" => $company_id)
                );
            }else{
                $arFilter = array(
                    "LOGIC" => "OR",
                    array("UF_USER_A" => $user_id),
                    array("UF_USER_B" => $user_id),
                );
            }

            $entity_data_class = self::GetEntityDataClass(CONTRACT_REDACTION_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array(),
                "filter" => array(
                    $arFilter
                )
            ));
            while($arData = $rsData->Fetch()){
                
                if((!empty($company_id) && $arData['UF_COMPANY_A'] == $company_id) || $arData['UF_USER_A'] == $user_id)
                    $P = "B";
                else
                    $P = "A";
                
                if(!empty($arData['UF_COMPANY_'.$P]))
                    $arRes['PARTNER'] = self::getCompany($arData['UF_COMPANY_'.$P]);
                else
                    $arRes['PARTNER'] = self::getUser($arData['UF_USER_'.$P]);

                if((!empty($company_id) && $arData['UF_LAST_COMPANY'] == $company_id) || $arData['UF_LAST_USER'] == $user_id)
                    $arRes['STATUS'] = "Изменен Вами";
                if((!empty($company_id) && $arData['UF_LAST_COMPANY'] == $company_id) || $arData['UF_LAST_USER'] == $user_id)
                    $arRes['STATUS'] = "Изменен контрагентом";

                $arRes['CONTRACT'] = self::getDeal($arData['UF_DEAL_ID']);
                $arRes['ID'] = $arRes['CONTRACT']['ID'];

                if(isset($this -> arResult['SIGNED_USER'][$arRes['ID']]) || isset($this -> arResult['SIGNED_PARTNER'][$arRes['ID']]))
                    continue;

                $arRes['DATA'] = $arData['UF_REDACTION_DATA'];

                $this -> arResult['CONTRACT_REDACTIONS'][] = $arRes;
            }
        }
        return false;
    }

    private function getContracts($arUser){
        $this -> getSignedContracts($arUser['ID'], $arUser['COMPANY_ID']);
        $this -> getContractEdit($arUser['ID'], $arUser['COMPANY_ID']);
    }

    public function executeComponent()
    {
        global $USER;
        if($USER -> IsAuthorized()){
            $this -> arResult['CURRENT_USER'] = self::getUser($USER -> GetID());
            // $User_ID = CUser::GetID();
            // $this->arResult["USER_ID"] = $User_ID;
            // $rsUser = CUser::GetByID($User_ID);
            // $this->arResult['USER'] = $rsUser->GetNext();
            // $this->arResult["USER_LOGIN"] =$this->arResult['USER']['LOGIN'];
            // $this->arResult['ID_CUR_COMPANY'] = $this->arResult['USER']['UF_CUR_COMPANY'];
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            if(empty($this -> arResult['CURRENT_USER']['COMPANY_ID']))
                $idProfile = $this -> arResult['CURRENT_USER']['ID'];
            else
                $idProfile = $this -> arResult['CURRENT_USER']['COMPANY_ID'];


            // сообщение пользователю
            //$this->arResult["MESSAGE_USER"] = $this->getMessageUser($User_ID);;
            // Все сделки созданные пользователем

            $this->arResult["DEALS"] = $this->listPacts($this->arResult["INFOBLOCK_ID"], $idProfile);

            $this -> getContracts($this -> arResult['CURRENT_USER']);

            // // подписанные договора
            // $this->arResult["SEND_CONTRACT"] = $this->getSendContract($idProfile);
            // //редакции
            // $this->arResult["REDACTION"] = $this->getRedaction($idProfile);
            // //Подписанные пользователем договора
            // $this->arResult["SEND_USER_PACT"] = $this->getSendUserContract($idProfile);
        }
        else
        {
            $this -> arResult['NOT_AUTH'] = "Y";
        }
        $this->includeComponentTemplate();
        
        return $this->arResult;
    }
};

?>