<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(file_exists($_SERVER['DOCUMENT_ROOT']."/local/class/CFormatHTMLText.php"))
  require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CFormatHTMLText.php");
if(file_exists($_SERVER['DOCUMENT_ROOT']."/response/ajax/class/getdocument.php"))
  require_once($_SERVER['DOCUMENT_ROOT']."/response/ajax/class/getdocument.php");

use Bitrix\Main\Loader,
    Bitrix\Highloadblock as HL;

class CContractAction extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        if(empty($arParams['EDITBOX_ID']))
            $arParams['EDITBOX_ID'] = "editor";
        if(empty($arParams['COMPLETE']))
            $arParams['COMPLETE'] = "N";
        if(empty($arParams['ACTION_VARIABLE']))
            $arParams['ACTION_VARIABLE'] = "action";
        if(empty($arParams['USER_ID']))
            $arParams['USER_ID'] = 0;
        if(empty($arParams['NEW_DEAL']))
            $arParams['NEW_DEAL'] = "N";
            
        $result = array(
            "ELEMENT_ID" => $arParams['ELEMENT_ID'],
            "EDITBOX_ID" => $arParams['EDITBOX_ID'],
            "COMPLETE" => $arParams['COMPLETE'],
            "ACTION_VARIABLE" => $arParams['ACTION_VARIABLE'],
            "USER_ID" => $arParams['USER_ID'],
            "NEW_DEAL" => $arParams['NEW_DEAL'],
            "DEAL_DATA" => $arParams['DEAL_DATA'],
            "COMPANY_ID" => $arParams['COMPANY_ID'],
        );
        return $result;
    }

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

    private static function formattingText($text){
        return CFormatHTMLText::TextFormatting($text, array('<a>', '<img>'));
    }

    private static function pasteTextData($text, $arUser){
        return str_replace(array('<span style="background: cornflowerblue;">@ФИО_КОНТРАГЕНТА@</span>', '<span style="background: cornflowerblue;">@АДРЕС_КОНТРАГЕНТА@</span>'), array($arUser['FIO']['VALUE'], $arUser['ADDRESS']['VALUE']), $text);
    }

    private function sendNotification($arUser, $not_text, $email_type = false, $add_url = ""){
        $arEventFields = array(
            "EMAIL" => $arUser['EMAIL'],
            "CONTRACT_ID" => $this -> arResult['CONTRACT']['ID'],
            "DEAL_ID" => $this -> arResult['DEAL']["ID"],
            "DEAL_CONTRACT_ID" => $this -> arResult['DEAL']["CONTRACT_ID"],
            "DEAL_URL" => $this -> arResult['DEAL']["DETAIL_PAGE_URL"],
            "DEAL_NAME" => $this -> arResult['DEAL']["NAME"],
            "USER_FIO" => $this->arResult['CURRENT_USER']['FIO'],
            "USER_ID" => $this->arResult['CURRENT_USER']['ID'],
            "ADD_URL" => $add_url
        );
        $not_text = str_replace(array("#CONTRACT_ID#", "#DEAL_ID#", "#DEAL_CONTRACT_ID#", "#DEAL_URL#", "#DEAL_NAME#"), array($arEventFields['CONTRACT_ID'], $arEventFields['DEAL_ID'], $arEventFields['DEAL_CONTRACT_ID'], $arEventFields['DEAL_URL'], $arEventFields['DEAL_NAME']), $not_text);

        $arNot["USER_ID"] = $arUser['ID'];

        if(!empty($arUser['COMPANY_ID']))
            $arNot['COMPANY_ID'] = $arUser['COMPANY_ID'];
        
        if(!empty($this->arResult['CURRENT_USER']['COMPANY_ID'])){
            $not_text = str_replace("Пользователь", "Организация", $not_text);
            if(!empty($add_url))
                $not_text = str_replace($add_url, "&COMPANY_ID=".$this->arResult['CURRENT_USER']['COMPANY_ID'], $not_text);
            $arNot['FROM_COMPANY'] = $this->arResult['CURRENT_USER']['COMPANY_ID'];
            $arEventFields['ADD_URL'] = "&COMPANY_ID=".$this->arResult['CURRENT_USER']['COMPANY_ID'];
        }else{
            $arNot['FROM_USER'] = $this->arResult['CURRENT_USER']['ID'];
        }

        $arNot["TEXT"] = $not_text;

        $CNotification = new CNotification();
        $CNotification -> Add($arNot);
        if($email_type)
            CEvent::Send($email_type, SITE_ID, $arEventFields);
    }

    private static function getTextFromFile($arFiles){
        
        if(empty($arFiles['file']))
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_file", "MESSAGE" => "Ошибка! Файл отсутсвует");

        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/getTextFromFile.log");
        AddMessage2Log($arFiles, "arFiles");

        if($arFiles['file']['size'] < 5000000){
            $DOCX = new getdocument();
            $tmp_location = $arFiles['file']['tmp_name'];
            $ext_file = $DOCX->getExtension($arFiles['file']['name']);
            AddMessage2Log($ext_file, "ext_file");
            AddMessage2Log($tmp_location, "tmp_location");
            switch ($ext_file) {
                case 'docx':
                    $content = $DOCX->readDOCX2($tmp_location);
                    break;
                case 'txt':
                    $content = $DOCX->readFileTXT($tmp_location);
                    break;
                default:
                    return array("STATUS" => "ERROR", "ERROR_TYPE" => "file_wrong_format", "MESSAGE" => "Ошибка! Неверный формат файла. Используйте один из слевующих фарматов: docx, txt");
                    break;
            }
            return array("STATUS" => "SUCCESS", "TEXT" => $content);
        }else{
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "file_size_overflow", "MESSAGE" => "Ошибка! Размер файла не должен превышать 5мб");
        }
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
        return false;
    }

    private static function getContractNew($id){
        if(Loader::includeModule("iblock"))
        {
            $res = CIBlockElement::GetList(Array(), array("ID" => $id, "IBLOCK_ID" => CONTRACTS_IB_ID), false, false, array("ID", "DETAIL_TEXT", "PROPERTY_USER_A", "PROPERTY_COMPANY_A"));
            if($ob = $res->Fetch())
            {
                return array("ID" => $ob['ID'], "USER_A" => $ob['PROPERTY_USER_A_VALUE'], "COMPANY_A" => $ob['PROPERTY_COMPANY_A_VALUE'], "TEXT" => $ob['DETAIL_TEXT']);
            }
        }
        return false;
    }

    private function getContractEdit($id, $user_id, $user_b = 0, $company_id = 0, $company_b = 0){
        if(Loader::includeModule("highloadblock"))
        {
            if(!empty($company_id))
                $user_id = 0;
            if(!empty($company_b))
                $user_b = 0;

            $filer = array();
            if($this -> arResult['DEAL']['OWNER_ID'] == $user_id || (!empty($company_id) && $this -> arResult['DEAL']['COMPANY_ID'] == $company_id))
            {
                if(!empty($company_id))
                    $filer["UF_COMPANY_A"] = $company_id;
                else
                    $filer["UF_USER_A"] = $user_id;
                
                if(!empty($company_b))
                    $filer["UF_COMPANY_B"] = $company_b;
                else if(!empty($user_b))
                    $filer["UF_USER_B"] = $user_b;
            }
            else
            {
                if(!empty($company_id))
                    $filer["UF_COMPANY_B"] = $company_id;
                else
                    $filer["UF_USER_B"] = $user_id;
            }

            $entity_data_class = self::GetEntityDataClass(CONTRACT_REDACTION_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array(),
                "filter" => array_merge(array("UF_DEAL_ID" => $id), $filer)
            ));
            if($arData = $rsData->Fetch()){
                return array("ID" => $arData['ID'], "USER_A" => $arData['UF_USER_A'], "USER_B" => $arData['UF_USER_B'], "COMPANY_A" => $arData['UF_COMPANY_A'], "COMPANY_B" => $arData['UF_COMPANY_B'], "REDACTION_DATA" => $arData['UF_REDACTION_DATA'], "TEXT" => $arData['UF_TEXT'], "LAST_USER" => $arData['UF_LAST_USER'], "LAST_COMPANY" => $arData["UF_LAST_COMPANY"]);
            }
        }
        return false;
    }

    private function getSignedContract($contract_id, $user_id, $user_b = 0, $company_id = 0, $company_b = 0){

        $arRes = false;

        if(Loader::includeModule("highloadblock"))
        {
            $filer = array();

            if(!empty($company_id))
                $user_id = 0;
            if(!empty($company_b))
                $user_b = 0;

            if($this -> arResult['DEAL']['OWNER_ID'] == $user_id || (!empty($company_id) && $this -> arResult['DEAL']['COMPANY_ID'] == $company_id))
            {
                if(!empty($company_id))
                    $filer["UF_ID_COMPANY_A"] = $company_id;
                else
                    $filer["UF_ID_USER_A"] = $user_id;
                
                if(!empty($company_b))
                    $filer["UF_ID_COMPANY_B"] = $company_b;
                else if(!empty($user_b))
                    $filer["UF_ID_USER_B"] = $user_b;
            }
            else
            {
                if(!empty($company_id))
                    $filer["UF_ID_COMPANY_B"] = $company_id;
                else
                    $filer["UF_ID_USER_B"] = $user_id;
            }

            $entity_data_class = self::GetEntityDataClass(CONTRACT_SIGNED_HBL_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array(),
                "filter" => array_merge(array("UF_ID_CONTRACT" => $contract_id, "UF_STATUS" => 1), $filer)
            ));
            if($arData = $rsData -> fetch()){
                if(!empty($arData['UF_VER_CODE_USER_B'])){
                    $signed_data = $arData['UF_TIME_SEND_USER_B'];
                    $signed_user = $arData['UF_ID_USER_B'];
                }else if(!empty($arData['UF_VER_CODE_USER_A'])){
                    $signed_data = $arData['UF_TIME_SEND_USER_A'];
                    $signed_user = $arData['UF_ID_USER_A'];
                }else{
                    return false;
                }
                $arRes = array("ID" => $arData['ID'], "USER_A" => $arData['UF_ID_USER_A'], "USER_B" => $arData['UF_ID_USER_B'], "COMPANY_A" => $arData['UF_ID_COMPANY_A'], "COMPANY_B" => $arData['UF_ID_COMPANY_B'], "SIGNED_DATA" => $signed_data, "SIGNED_USER" => $signed_user);
            }
            $entity_data_class = self::GetEntityDataClass(CONTRACT_TEXT_HBL_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID", "UF_TEXT_CONTRACT"),
                "order" => array(),
                "filter" => array("UF_ID_SEND_ITEM" => $arRes['ID'])
            ));
            if($arData = $rsData -> fetch()){
                $arRes['CONTRACT_TEXT_ID'] = $arData['ID'];
                $arRes['TEXT'] = $arData['UF_TEXT_CONTRACT'];
            }
        }
        
        return $arRes;
    }

    private function getContractFinal($id, $user_id, $company_id = 0){
        $arRes = false;

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
                    "ID" => $id,
                    $arFilter
                )
            ));
            if($arSigned = $rsData->Fetch()){

                $arRes['ID'] = $arSigned['ID'];
                
                $arRes['USER_A'] = $arSigned['UF_ID_USER_A'];
                $arRes['USER_B'] = $arSigned['UF_ID_USER_B'];

                $arRes['COMPANY_A'] = $arSigned['UF_ID_COMPANY_A'];
                $arRes['COMPANY_B'] = $arSigned['UF_ID_COMPANY_B'];
                
                // получить данные пользователей по id
                // пользователь А владелец контракта
                if(!empty($arSigned['UF_ID_COMPANY_A'])){
                    //компания
                    $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>COMPANY_IB_ID, 'ID'=>$arSigned['UF_ID_COMPANY_A'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_INN']);
                    if($obj=$res->GetNext(true, false)){
                        $arCompany_A = $obj;
                    }
                } else {
                    //пользователь
                    $rsUser = CUser::GetByID($arSigned['UF_ID_USER_A']);
                    $arUser_A = $rsUser->Fetch();
                }
                //статус подписи
                if(!empty($arSigned['UF_VER_CODE_USER_A'])){
                    $hash_A = md5($arSigned['UF_VER_CODE_USER_A']);
                }

                // пользователь В подписывающий
                if(!empty($arSigned['UF_ID_COMPANY_B'])){
                    //компания
                    $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>COMPANY_IB_ID, 'ID'=>$arSigned['UF_ID_COMPANY_B'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_INN']);
                    if($obj=$res->GetNext(true, false)){
                        $arCompany_B = $obj;
                    }
                }
                else{
                    //пользователь
                    $rsUser = CUser::GetByID($arSigned['UF_ID_USER_B']);
                    $arUser_B = $rsUser->Fetch();
                }
                //статус подписи
                if(!empty($arSigned['UF_VER_CODE_USER_B'])){
                    $hash_B = md5($arSigned['UF_VER_CODE_USER_B']);
                }
                if(!empty($hash_A) && !empty($hash_B)){
                    $signed_text = '<table style="width:100%; margin 50px 0;">';
                    $signed_text .= '<tr>';
                    $signed_text .= '<td style="width:44%">';
                    $signed_text .= '<b>Подписано простой электронной подписью:</b>';
                    if(!empty($arCompany_A)){
                        //компания
                        $signed_text .= '<br>'.$arCompany_A['NAME'];
                        $signed_text .= '<br>'.$arCompany_A['PROPERTY_INN_VALUE'];
                    }
                    else{
                        //пользователь
                        $signed_text .= '<br>'.$arUser_A['LAST_NAME'].' '.$arUser_A['NAME'].' '.$arUser_A['SECOND_NAME'];
                        $signed_text .= '<br>#'.$arUser_A['UF_PASSPORT'];
                    }
                    $DateTime = new DateTime($arSigned["UF_TIME_SEND_USER_A"]);
                    $signed_text .= '<br>'.$DateTime->format("Y-m-d H:i:s");
                    $signed_text .= '<br>'.$hash_A;
                    $signed_text .= '</td>';
                    $signed_text .= '<td style="width:2%"></td>';
                    $signed_text .= '<td style="width:44%">';
                    $signed_text .= '<b>Подписано простой электронной подписью:</b>';
                    if(!empty($arCompany_B)) {
                        //компания
                        $signed_text .= '<br>'.$arCompany_B['NAME'];
                        $signed_text .= '<br>'.$arCompany_B['PROPERTY_INN_VALUE'];
                    }
                    else{
                        //пользователь
                        $signed_text .= '<br>'.$arUser_B['LAST_NAME'].' '.$arUser_B['NAME'].' '.$arUser_B['SECOND_NAME'];
                        $signed_text .= '<br>#'.$arUser_B['UF_PASSPORT'];
                    }
                    $DateTime = new DateTime($arSigned["UF_TIME_SEND_USER_B"]);
                    $signed_text .= '<br>'.$DateTime->format("Y-m-d H:i:s");
                    $signed_text .= '<br>'.$hash_B;
                    $signed_text .= '</td>';
                    $signed_text .= '</tr>';
                    $signed_text .= '</table>';

                    $arRes['SIGNED_TEXT'] = $signed_text;

                    $entity_data_class = self::GetEntityDataClass(CONTRACT_TEXT_HBL_ID);
                    $rsData = $entity_data_class::getList(array(
                        "select" => array("ID", "UF_TEXT_CONTRACT"),
                        "order" => array(),
                        "filter" => array("UF_ID_SEND_ITEM" => $arRes['ID'])
                    ));
                    if($arData = $rsData -> fetch()){
                        $arRes['CONTRACT_TEXT_ID'] = $arData['ID'];
                        $arRes['TEXT'] = $arData['UF_TEXT_CONTRACT'];
                    }
                }
            }
        }

        return $arRes;
    }

    private static function getPatternTree(){
        $arThree = false;
        if(Loader::includeModule("iblock")){
            $obSection = CIBlockSection::GetTreeList(array('IBLOCK_ID' => CONTRACT_PATTERN_IB_ID, 'GLOBAL_ACTIVE' => 'Y'), array("ID", "DEPTH_LEVEL", "NAME"));
            while($arResult = $obSection -> Fetch()){
                $arThree[] = $arResult;
            }
        }
        return $arThree;
    }

    private static function getSellerCustomer($id){
        $res = CIBlockElement::GetProperty(CONTRACT_PATTERN_IB_ID, $id, "sort", "asc", array("CODE" => "STEPS"));
        if ($ob = $res->GetNext())
            if(!empty($ob['VALUE']))
                return explode(",", $ob['VALUE']);
        return false;
    }

    private static function getPatternList($id){
        if(Loader::includeModule("iblock"))
        {
            if(empty($id) || !is_numeric($id)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_tree_id", "MESSAGE" => "Ошибка! Отсутствуют данные о разделе шаблона");
            }
            $res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => CONTRACT_PATTERN_IB_ID, "IBLOCK_SECTION_ID" => $id), false, false, array("ID", "NAME"));
            while($ob = $res->GetNext())
            {
                $arRes[] = $ob;
            }
        }
        if(empty($arRes))
            $arRes = "empty";
        return array("STATUS" => "SUCCESS", "DATA" => $arRes);
    }

    private static function getPatternElement($id){
        if(Loader::includeModule("iblock"))
        {
            if(empty($id) || !is_numeric($id)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_tree_id", "MESSAGE" => "Ошибка! Отсутствуют данные о разделе шаблона");
            }
            $res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => CONTRACT_PATTERN_IB_ID, "ID" => $id), false, false, array("ID", "DETAIL_TEXT"));
            if($ob = $res->GetNext())
            {
                $arRes = array("PATTERN_ID" => $ob['ID'], "TEXT" => $ob['DETAIL_TEXT']);
            }
        }
        if(empty($arRes))
            $arRes = "empty";
        return array("STATUS" => "SUCCESS", "DATA" => $arRes);
    }

    private function createContract($arFields){
        $arRes = array();
        if(Loader::includeModule("iblock"))
        {
            if(empty($arFields['USER_A']) || !is_numeric($arFields['USER_A'])){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_user_a", "MESSAGE" => "Ошибка! Отсутствуют данные о владельце договора");
            }
            if(empty($arFields['TEXT'])){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_text", "MESSAGE" => "Ошибка! Текст договора не может быть пустым");
            }
            
            $CIBlockElement = new CIBlockElement();
            $ID = $CIBlockElement -> Add($arLoadProductArray = array(
                'IBLOCK_ID' => CONTRACTS_IB_ID,
                'ACTIVE' => 'Y',
                'NAME' => (empty($this->arResult['DEAL']['NAME']) ? "Договор пользльзователя ".$arFields['USER_A'] : $this->arResult['DEAL']['NAME']),
                "DETAIL_TEXT_TYPE" =>"html",
                'DETAIL_TEXT' => $arFields['TEXT'],
                'PROPERTY_VALUES' => array('USER_A' => $arFields['USER_A'])
            ));
            
            if($ID){
                if($this->arResult['DEAL']['ID'])
                    $CIBlockElement -> SetPropertyValuesEx($this->arResult['DEAL']['ID'], DEALS_IB_ID, array("ID_DOGOVORA" => $ID));
                else{
                    //записываем в кэш
                    // К сожалению это вынужденный остаточный код от прошлых программистов для передачи данных о договоре в новую сделку
                    // Переделывать нет времени поэтому оставил как есть
                    $cacheName = md5($arFields['USER_A'].'_'.rand(1, 100000));
                    $cache = \Bitrix\Main\Data\Cache::createInstance();
                    $cacheInitDir = 'dogovor_create_sdelka';

                    if (!$cache->initCache(600, $cacheName, $cacheInitDir)){
                        $cache->startDataCache();
                        $cache->endDataCache($arLoadProductArray);
                    }

                    if ($cache->initCache(600, $cacheName, $cacheInitDir)){
                        $arRes = array("CACHE_NAME" => $cacheName);
                    }
                }
                return array_merge(array("STATUS" => "SUCCESS", "CONTRACT_ID" => $ID, "MESSAGE" => "Договор успешно создан"), $arRes);
            }
            else 
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "error_create_contract", "MESSAGE" => "Ошибка! При создании договора произошла ошибка. Текст ошибки: ".$CIBlockElement->LAST_ERROR);
        }
    }

    private static function updateContract($id, $text){
        if(Loader::includeModule("iblock"))
        {
            if(empty($id) || !is_numeric($id)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_contract_id", "MESSAGE" => "Ошибка! Отсутствуют данные о договоре");
            }
            if(empty($text)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_text", "MESSAGE" => "Ошибка! Текст договора не может быть пустым");
            }

            $CIBlockElement = new CIBlockElement();
            $res = $CIBlockElement -> Update($id, array("DETAIL_TEXT" => $text));

            return array("STATUS" => "SUCCESS", "MESSAGE" => "Договор успешно обновлен");
        }
    }

    private function closeContract($id, $user_id){
        if(Loader::includeModule("highloadblock"))
        {
            if(empty($id) || !is_numeric($id)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_signed_id", "MESSAGE" => "Ошибка! Отсутствуют данные о подписаном договоре");
            }
            $entity_data_class = self::GetEntityDataClass(CONTRACT_SIGNED_HBL_ID);
            $entity_data_class::update($id, array("UF_STATUS" => 3));

            $ADD_URL = "";

            if($this -> arResult['DEAL']['OWNER_ID'] == $user_id)
                $arUser = $this -> arResult['USER'];
            else{
                $arUser = self::getUser($this -> arResult['DEAL']['OWNER_ID']);
                $ADD_URL = "&USER_ID=".$user_id;
            }
            
            if($this -> arResult['CONTRACT']['SIGNED_USER'] == $user_id)
            {
                $message = "Подпись была отозвана";
                $not_mess = "Пользователь отозвал свою подпись с договора №[URL=/contract/?ID=#DEAL_ID#".$ADD_URL."]#DEAL_CONTRACT_ID#[/URL] по сделке [URL=#DEAL_URL#]#DEAL_NAME#[/URL]";
                $event = "SIGNATURE_REVOKED";
            }
            else
            {
                $message = "Подпись была отклонена";
                $not_mess = "Пользователь отклонил вашу подпись с договора №[URL=/contract/?ID=#DEAL_ID#".$ADD_URL."]#DEAL_CONTRACT_ID#[/URL] по сделке [URL=#DEAL_URL#]#DEAL_NAME#[/URL]";
                $event = "SIGNATURE_REJECTED";
            }

            $this -> sendNotification(
                $arUser, 
                $not_mess, 
                $event,
                $ADD_URL
            );
            
            return array("STATUS" => "SUCCESS", "MESSAGE" => $message);
        }
    }

    private function createRedaction($arFields){
        if(Loader::includeModule("highloadblock"))
        {
            if(empty($arFields['DEAL_ID']) || !is_numeric($arFields['DEAL_ID'])){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_deal_id", "MESSAGE" => "Ошибка! Отсутствуют данные о сделке");
            }
            if(empty($arFields['USER_A']) || !is_numeric($arFields['USER_A'])){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_user_a", "MESSAGE" => "Ошибка! Отсутствуют данные о владельце договора");
            }
            if(empty($arFields['USER_B']) || !is_numeric($arFields['USER_B'])){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_user_b", "MESSAGE" => "Ошибка! Отсутствуют данные о контрагенте");
            }
            if(empty($arFields['TEXT'])){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_text", "MESSAGE" => "Ошибка! Текст договора не может быть пустым");
            }

            $entity_data_class = self::GetEntityDataClass(CONTRACT_REDACTION_HLB_ID);
            $result = $entity_data_class::add(array(
                "UF_USER_A" => $arFields['USER_A'],
                "UF_USER_B" => $arFields['USER_B'],
                "UF_COMPANY_A" => $arFields['COMPANY_A'],
                "UF_COMPANY_B" => $arFields['COMPANY_B'],
                "UF_REDACTION_DATA" => date("d.m.Y H:i:s"),
                "UF_TEXT" => $arFields['TEXT'],
                "UF_LAST_USER" => $arFields['USER_B'],
                "UF_LAST_COMPANY" => $this -> arResult['CURRENT_USER']['COMPANY_ID'],
                "UF_DEAL_ID" => $arFields['DEAL_ID'],
            ));

            $arUser = self::getUser($arFields['USER_A']);

            $ADD_URL = "&USER_ID=".$arFields['USER_B'];

            $this -> sendNotification(
                $arUser, 
                "Пользователь создал редакцию вашего договора №[URL=/contract/?ID=#DEAL_ID#".$ADD_URL."]#DEAL_CONTRACT_ID#[/URL] по сделке [URL=#DEAL_URL#]#DEAL_NAME#[/URL]", 
                "REDACTION_CREATE",
                $ADD_URL
            );

            return array("STATUS" => "SUCCESS", "REDACTION_ID" => $result->getId(), "MESSAGE" => "Редкация успешно создана");
        }
    }

    private function updateRedaction($id, $user_id, $text){
        if(Loader::includeModule("highloadblock"))
        {
            if(empty($id) || !is_numeric($id)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_redaction_id", "MESSAGE" => "Ошибка! Отсутствуют данные о редакции");
            }
            if(empty($user_id) || !is_numeric($user_id)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_user", "MESSAGE" => "Ошибка! Отсутствуют данные о текущем пользователе");
            }
            if(empty($text)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_text", "MESSAGE" => "Ошибка! Текст договора не может быть пустым");
            }

            $entity_data_class = self::GetEntityDataClass(CONTRACT_REDACTION_HLB_ID);
            $entity_data_class::update($id, array("UF_LAST_USER" => $user_id, "UF_TEXT" => $text, "UF_REDACTION_DATA" => date("d.m.Y H:i:s"), "UF_LAST_COMPANY" => $this -> arResult['CURRENT_USER']['COMPANY_ID']));

            $ADD_URL = "";

            if($this -> arResult['CONTRACT']['USER_A'] == $user_id)
                $arUser = $this -> arResult['USER'];
            else{
                $arUser = self::getUser($this -> arResult['CONTRACT']['USER_A']);
                $ADD_URL = "&USER_ID=".$user_id;
            }

            $this -> sendNotification(
                $arUser, 
                "Пользователь обновил редакцию договора №[URL=/contract/?ID=#DEAL_ID#".$ADD_URL."]#DEAL_CONTRACT_ID#[/URL] по сделке [URL=#DEAL_URL#]#DEAL_NAME#[/URL]", 
                "REDACTION_UPDATE",
                $ADD_URL
            );

            return array("STATUS" => "SUCCESS", "MESSAGE" => "Редкация успешно обновлена");
        }
    }

    private function deleteRedaction($id){
        if(Loader::includeModule("highloadblock"))
        {
            if(empty($id) || !is_numeric($id)){
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_redaction_id", "MESSAGE" => "Ошибка! Отсутствуют данные о редакции");
            }
            $entity_data_class = self::GetEntityDataClass(CONTRACT_REDACTION_HLB_ID);
            $entity_data_class::Delete($id);

            if($this -> arResult['CONTRACT']['USER_A'] == $this -> arResult['CURRENT_USER']['ID'])
                $arUser = $this -> arResult['USER'];
            else
                $arUser = self::getUser($this -> arResult['CONTRACT']['USER_A']);

            $this -> sendNotification(
                $arUser, 
                "Пользователь удалил редакцию договора №[URL=/contract/?ID=#DEAL_ID#]#DEAL_CONTRACT_ID#[/URL] по сделке [URL=#DEAL_URL#]#DEAL_NAME#[/URL]", 
                "REDACTION_DELETED"
            );

            return array("STATUS" => "SUCCESS", "MESSAGE" => "Редакция была удалена");
        }
    }

    private function createSignedContract($arContract, $eTag, $hashKey){
        if(Loader::includeModule("highloadblock"))
        {

            if($arContract['USER_A'] == $this -> arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $arContract['COMPANY_A'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])){
                $user_a = $this -> arResult['CURRENT_USER'];
                $user_b = $this -> arResult['USER'];
                $U = "A";
            }else{
                $user_b = $this -> arResult['CURRENT_USER'];
                $user_a = array("ID" => $arContract['USER_A'], "COMPANY_ID" => $arContract['COMPANY_A']);
                $U = "B";
            }
            $signed_data = ConvertTimeStamp(time(), "FULL");
            $arFields = array(
                'UF_VER_CODE_USER_'.$U => $eTag,
                'UF_ID_USER_A' => $user_a['ID'],
                'UF_ID_COMPANY_A'=> $user_a['COMPANY_ID'],
                'UF_ID_USER_B' => $user_b['ID'],
                'UF_ID_COMPANY_B'=> $user_b['COMPANY_ID'],
                'UF_TIME_SEND_USER_'.$U => $signed_data,
                'UF_ID_CONTRACT' => $this -> arResult['DEAL']['CONTRACT_ID'],
                'UF_STATUS' => 1,
                'UF_HASH_SEND' => $hashKey,
                'UF_ID_SEND_USER' => $this -> arResult['CURRENT_USER']['ID'],
                'UF_ID_SEND_COMPANY' => $this -> arResult['CURRENT_USER']['COMPANY_ID'],
            );
            $entity_data_class = self::GetEntityDataClass(CONTRACT_SIGNED_HBL_ID);
            $result = $entity_data_class::add($arFields);
            if($signetId = $result->getId()){
                $arFields = array(
                    'UF_ID_CONTRACT' => $this -> arResult['DEAL']['CONTRACT_ID'],
                    'UF_ID_SEND_ITEM' => $signetId,
                    'UF_TEXT_CONTRACT' => $arContract['TEXT'],
                    'UF_HASH' => $hashKey,
                    'UF_ID_USER_SEND' => $this -> arResult['CURRENT_USER']['ID'],
                    'UF_ID_SEND_COMPANY' => $this -> arResult['CURRENT_USER']['COMPANY_ID'],
                );
                $entity_data_class = self::GetEntityDataClass(CONTRACT_TEXT_HBL_ID);
                $result = $entity_data_class::add($arFields);
                if($arRes['CONTRACT_TEXT_ID'] = $result->getId()){
                    $arRes['ID'] = $signetId;
                    $arRes['USER_A'] = $user_a['ID'];
                    $arRes['USER_B'] = $user_b['ID'];
                    $arRes['COMPANY_A'] = $user_a['COMPANY_ID'];
                    $arRes['COMPANY_B'] = $user_b['COMPANY_ID'];
                    $arRes['SIGNED_DATA'] = $signed_data;
                    $arRes['SIGNED_USER'] = $this -> arResult['CURRENT_USER']['ID'];
                    $arRes['SIGNED_COMPANY'] = $this -> arResult['CURRENT_USER']['COMPANY_ID'];
                    $arRes['TEXT'] = $arContract['TEXT'];
                    $this -> arResult['CONTRACT'] = $arRes;
                    $this -> arResult['CONTACT_TYPE'] = "SIGNED";
                    $this -> arResult['MESSAGE'] = "Договор был подписан, ожидайте подписи конторагентом";

                    if($U == "A"){
                        $arUser = $this -> arResult['USER'];
                        $not_text = "Пользователь подписал договор №[URL=/contract/?ID=#DEAL_ID#]#DEAL_CONTRACT_ID#[/URL] по сделке [URL=#DEAL_URL#]#DEAL_NAME#[/URL] с вашими изменениями";
                        $email_type = "CONTRACT_SIGNATURE_EDIT";
                    }
                    else
                    {
                        $ADD_URL = "&USER_ID=".$this -> arResult['CURRENT_USER']['ID'];
                        $arUser = self::getUser($arContract['USER_A']);
                        $not_text = "Пользователь подписал ваш договор №[URL=/contract/?ID=#DEAL_ID#".$ADD_URL."]#DEAL_CONTRACT_ID#[/URL] по сделке [URL=#DEAL_URL#]#DEAL_NAME#[/URL]";
                        $email_type = "CONTRACT_SIGNATURE";
                    }
                    
                    $this -> sendNotification(
                        $arUser, 
                        $not_text, 
                        $email_type,
                        $ADD_URL
                    );
                }else{
                    $this -> arResult['MESSAGE'] = "Ошибка! Не удалось создать конечный договор";
                }
            }else{
                $this -> arResult['MESSAGE'] = "Ошибка! Не удалось поставить подпись";
            }
        }
    }

    private function signedContract($arContract, $eTag, $hashKey){
        if(Loader::includeModule("highloadblock"))
        {

            if($arContract['USER_A'] == $this -> arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $arContract['COMPANY_A'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])){
                $U = "A";
            }else{
                $U = "B";
            }
            $signed_data = ConvertTimeStamp(time(), "FULL");
            $arFields = array(
                'UF_ID_COMPANY_'.$U => $this -> arResult['CURRENT_USER']['COMPANY_ID'],
                'UF_VER_CODE_USER_'.$U => $eTag,
                'UF_TIME_SEND_USER_'.$U => $signed_data,
                'UF_STATUS' => 2,
                'UF_HASH_SEND' => $hashKey,
                'UF_ID_SEND_USER' => $this -> arResult['CURRENT_USER']['ID'],
                'UF_ID_SEND_COMPANY' => $this -> arResult['CURRENT_USER']['COMPANY_ID'],
            );
            $entity_data_class = self::GetEntityDataClass(CONTRACT_SIGNED_HBL_ID);
            $entity_data_class::update($arContract['ID'], $arFields);

            $arFields = array(
                'UF_HASH' => $hashKey,
                'UF_ID_USER_SEND' => $this -> arResult['CURRENT_USER']['ID'],
                'UF_ID_SEND_COMPANY' => $this -> arResult['CURRENT_USER']['COMPANY_ID'],
            );
            $entity_data_class = self::GetEntityDataClass(CONTRACT_TEXT_HBL_ID);
            $entity_data_class::update($arContract['CONTRACT_TEXT_ID'], $arFields);

            $this -> arParams['COMPLETE'] = "Y";
            $this -> arParams['ELEMENT_ID'] = $this -> arResult['CONTRACT']['ID'];
            $this -> arResult['CONTRACT'] = $this -> getContractFinal($this -> arParams['ELEMENT_ID'], $this -> arResult['CURRENT_USER']['ID']);
            $this -> arResult['MESSAGE'] = "Договор заключен";

            if($U == "A")
                $arUser = $this -> arResult['USER'];
            else
                $arUser = self::getUser($arContract['USER_A']);

            $this -> sendNotification(
                $arUser, 
                "Договор №[URL=/contract/?ID=#CONTRACT_ID#&COMPLETE=Y]#DEAL_CONTRACT_ID#[/URL] по сделке [URL=#DEAL_URL#]#DEAL_NAME#[/URL] с пользователем был подписан. [URL=/contract/pdf.php?ID=#CONTRACT_ID#]Ссылка на договор[/URL]", 
                "CONTRACT_SIGNATURE_COMPLITE"
            );
        }
    }

    private static function getUser($id){
        $res = \Bitrix\Main\UserTable::getList(array(
            'select' => array(
                "ID", 
                "NAME", 
                "LAST_NAME", 
                "SECOND_NAME", 
                "PERSONAL_ZIP", 
                "PERSONAL_COUNTRY", 
                "PERSONAL_STATE", 
                "PERSONAL_CITY",
                "UF_REGION", 
                "UF_STREET", 
                "UF_N_HOUSE", 
                "UF_N_HOUSING", 
                "UF_N_APARTMENT",
                "EMAIL",
                "UF_CUR_COMPANY"
            ), 
            "order" => array("ID" => "ASC"),
            'filter' => array("ID" => $id, "UF_ESIA_AUT" => 1, "!=UF_ETAG_ESIA" => "", "!=UF_ESIA_ID" => "")
        ));
        if($user = $res->Fetch()){
            $address = "";
            if(!empty($user['PERSONAL_ZIP'])) $address .= $user['PERSONAL_ZIP'];
            if(!empty($address)) $address .= " ";
            if(!empty($user['PERSONAL_COUNTRY'])) $address .= GetCountryByID($user['PERSONAL_COUNTRY'], "ru");
            if(!empty($address)) $address .= " ";
            if(!empty($user['PERSONAL_STATE'])) $address .= $user['PERSONAL_STATE'];
            if(!empty($address)) $address .= ", ";
            if(!empty($user['PERSONAL_CITY'])) $address .= "г.".$user['PERSONAL_CITY'];
            if(!empty($address)) $address .= ", ";
            if(!empty($user['UF_REGION'])) $address .= $user['UF_REGION']." район";
            if(!empty($address)) $address .= ", ";
            if(!empty($user['UF_STREET'])) $address .= "ул. ".$user['UF_STREET'];
            if(!empty($address)) $address .= ", ";
            if(!empty($user['UF_N_HOUSE'])) $address .= "дом ".$user['UF_N_HOUSE'];
            if(!empty($address)) $address .= ", ";
            if(!empty($user['UF_N_HOUSING'])) $address .= "копус ".$user['UF_N_HOUSING'];
            if(!empty($address)) $address .= ", ";
            if(!empty($user['UF_N_APARTMENT'])) $address .= "кв. ".$user['UF_N_APARTMENT'];
            $fio = "";
            if(!empty($user['LAST_NAME'])) $fio .= $user['LAST_NAME'];
            if(!empty($fio)) $fio .= " ";
            if(!empty($user['NAME'])) $fio .= $user['NAME'];
            if(!empty($fio)) $fio .= " ";
            if(!empty($user['SECOND_NAME'])) $fio .= $user['SECOND_NAME'];
            if(!empty($fio)) $fio .= " ";
            return array("ID" => $user['ID'], "EMAIL" => $user['EMAIL'], "COMPANY_ID" => self::checkCompany($user["UF_CUR_COMPANY"], $id), "FIO" => array("NAME" => "ФИО", "VALUE" => $fio), "ADDRESS" => array("NAME" => "Адрес", "VALUE" => $address));
        }
        return false;
    }

    private static function getDeal($id, $user_id, $company_id = 0){
        if(Loader::includeModule("iblock"))
        {
            if(empty($id))
                return false;
            if(!empty($company_id))
                $user_id = 0;
            $arFilter = array(
                "=ID" => $id, 
                "IBLOCK_ID" => DEALS_IB_ID, 
                array(
                    "LOGIC" => "OR",
                    array(
                        "ACTIVE" => "Y", 
                        "PROPERTY_MODERATION" => 7
                    ),
                    array("=CREATED_BY" => empty( $user_id ) ? 0 : $user_id),
                    array("=PROPERTY_ID_COMPANY" => empty( $company_id ) ? 0 : $company_id)
                ),
                array(
                    "LOGIC" => "OR",
                    array("PROPERTY_INDEFINITELY" => 18),
                    array(">=DATE_ACTIVE_TO" => new \Bitrix\Main\Type\DateTime()),
                    array("=CREATED_BY" => empty( $user_id ) ? 0 : $user_id),
                    array("=PROPERTY_ID_COMPANY" => empty( $company_id ) ? 0 : $company_id)
                ),
                array(
                    'LOGIC' => 'OR',
                    array("!=PROPERTY_PRIVATE_VALUE" => 10),
                    array(
                        "PROPERTY_PRIVATE_VALUE" => 10,
                        "=PROPERTY_ACCESS_USER" => empty( $user_id ) ? 0 : $user_id
                    ),
                    array("=CREATED_BY" => empty( $user_id ) ? 0 : $user_id),
                    array("=PROPERTY_ID_COMPANY" => empty( $company_id ) ? 0 : $company_id)
                )
            );
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array("ID", "NAME", "CREATED_BY", "PROPERTY_ID_DOGOVORA", "PROPERTY_ID_COMPANY", "DETAIL_PAGE_URL"));
            if($ob = $res->GetNextElement())
            {
                $arFields = $ob->GetFields();
                return array("ID" => $arFields['ID'], "NAME" => $arFields['NAME'], "OWNER_ID" => $arFields['CREATED_BY'], "CONTRACT_ID" => $arFields['PROPERTY_ID_DOGOVORA_VALUE'], "DETAIL_PAGE_URL" => $arFields['DETAIL_PAGE_URL'], "COMPANY_ID" => $arFields["PROPERTY_ID_COMPANY_VALUE"]);
            }
        }
        return false;
    }

    private function getContract($type = "SIGNED"){

        $this -> arResult['CONTACT_TYPE'] = $type;

        if($this -> arResult['CONTACT_TYPE'] == "SIGNED")
        {
            $this -> arResult['CONTRACT'] = $this -> getSignedContract($this -> arResult['DEAL']['CONTRACT_ID'], $this -> arResult['CURRENT_USER']['ID'], $this -> arResult['USER']['ID'], $this -> arResult['CURRENT_USER']['COMPANY_ID'], $this -> arResult['COMPANY']['ID']);

            if(!$this -> arResult['CONTRACT'])
                $this -> arResult['CONTACT_TYPE'] = "REDACTION";
        }
        if($this -> arResult['CONTACT_TYPE'] == "REDACTION")
        {
            $this -> arResult['CONTRACT'] = $this -> getContractEdit($this -> arParams['ELEMENT_ID'], $this -> arResult['CURRENT_USER']['ID'], $this -> arResult['USER']['ID'], $this -> arResult['CURRENT_USER']['COMPANY_ID'], $this -> arResult['COMPANY']['ID']);

            if(!$this -> arResult['CONTRACT'])
                $this -> arResult['CONTACT_TYPE'] = "ORIGINAL";
        }
        if($this -> arResult['CONTACT_TYPE'] == "ORIGINAL")
            $this -> arResult['CONTRACT'] = self::getContractNew($this -> arResult['DEAL']['CONTRACT_ID']);
    }

    public function executeComponent()
    {
        global $USER;

        // По умолчанию VIEW(Редактор в режиме просмотра), так же есть режим редактирования EDITOR
        if(empty($this -> arResult['TYPE']))
            $this -> arResult['TYPE'] = "VIEW";

        if(!empty($USER -> GetID()))
        {
            $arRes = false;

            $this -> arResult['CURRENT_USER'] = self::getUser($USER -> GetID());

            if($this -> arResult['CURRENT_USER'])
            {
                $this -> arResult['DEAL'] = self::getDeal($this -> arParams['ELEMENT_ID'], $this -> arResult['CURRENT_USER']['ID'], $this -> arResult['CURRENT_USER']['COMPANY_ID']);

                // Завершенный договор, ELEMENT_ID = CONTRACT_ID
                if($this -> arParams['COMPLETE'] == "Y")
                {
                    $this -> arResult['CONTRACT'] = $this -> getContractFinal($this -> arParams['ELEMENT_ID'], $this -> arResult['CURRENT_USER']['ID'], $this -> arResult['CURRENT_USER']['COMPANY_ID']);

                    if(!$this -> arResult['CONTRACT'])
                    {
                        $this -> arResult['NOT_COMPLETE'] = "Y";
                    }
                }
                else
                {
                    // Если сделка еще не создана то подставлем данные в фейковую сделку
                    if($this -> arParams['NEW_DEAL'] == "Y")
                    {
                        $this -> arResult['DEAL'] = array("NAME" => $this -> arParams['DEAL_DATA']['adName'], "OWNER_ID" => $this -> arResult['CURRENT_USER']['ID'], "COMPANY_ID" => $this -> arResult['CURRENT_USER']['COMPANY_ID']);
                    }
                    if($this -> arResult['DEAL'])
                    {
                        $this->checkSession = check_bitrix_sessid();
                        $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';

                        // Загрузка договора из файла
                        if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'uploadFile')
                        {
                            $arRes = self::getTextFromFile($_FILES);
                            $this -> arResult['TYPE'] = "EDITOR";
                        }
                        // Выбор раздела шаблона
                        if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'selectTree')
                        {
                            $this -> arResult['TYPE'] = "EDITOR";
                            $arRes = self::getPatternList($_REQUEST['ID']);
                            if($arRes['STATUS'] == "SUCCESS") 
                            {
                                $this -> arResult['TREE_ELEMENTS'] = $arRes['DATA'];
                                unset($arRes['DATA']);
                            }
                        }
                        // Выбор шаблона договора
                        else if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'selectPattern')
                        {
                            $this -> arResult['TYPE'] = "EDITOR";
                            $arRes = self::getPatternElement($_REQUEST['ID']);
                            if($arRes['STATUS'] == "SUCCESS") 
                            {
                                $arRes['DATA']['USER_A'] = $this -> arResult['CURRENT_USER']['ID'];
                                $this -> arResult['CONTRACT'] = $arRes['DATA'];
                                unset($arRes['DATA']);
                                $this -> arResult['SELLER_CUSTOMER'] = self::getSellerCustomer($_REQUEST['ID']);
                            }
                        }
                        else if($this -> arResult['DEAL']['OWNER_ID'] != $this -> arResult['CURRENT_USER']['ID'] && empty($this -> arResult['DEAL']['CONTRACT_ID']))
                        {
                            $this -> arResult['NOT_CONTRACT'] = "Y";
                        }
                        else if(!empty($this -> arResult['DEAL']['CONTRACT_ID']))
                        {
                            if(!empty($this -> arParams['USER_ID']) && $this -> arResult['CURRENT_USER']['ID'] != $this -> arParams['USER_ID'])
                                $this -> arResult['USER'] = self::getUser($this -> arParams['USER_ID']);

                            if(!empty($this -> arParams['COMPANY_ID']) && $this -> arResult['CURRENT_USER']['COMPANY_ID'] != $this -> arParams['COMPANY_ID'])
                                $this -> arResult['COMPANY']['ID'] = self::checkCompany($this -> arParams['COMPANY_ID']);
                            
                            // Получаем договора
                            // Есть 3 вида договора, оригинальзый(ORIGINAL), редакция(REDACTION), подписанный(SIGNED)
                            // Все они хранятся в разных сущностях
                            $this -> getContract();

                            // Подписание договора
                            if(!$this->request->isPost() && $this->request->get('via_ajax') == 'Y' && $this->request->get($this -> arParams['ACTION_VARIABLE']) == 'esiaSign')
                            {
                                
                                if($this -> arResult['TYPE'] == "VIEW")
                                {
                                    if(($this -> arResult['DEAL']['OWNER_ID'] == $this -> arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['DEAL']['COMPANY_ID'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])) && $this -> arResult['CONTACT_TYPE'] == "ORIGINAL")
                                    {
                                        $this -> arResult['MESSAGE'] = "Ошибка! Нельзя подписать свой договор";
                                    }
                                    else if($this -> arResult['CONTACT_TYPE'] == "SIGNED" && ($this -> arResult['CURRENT_USER']['ID'] ==  $this -> arResult['CONTRACT']['SIGNED_USER'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['CURRENT_USER']['COMPANY_ID'] ==  $this -> arResult['CONTRACT']['SIGNED_COMPANY']))){
                                        $this -> arResult['MESSAGE'] = "Ошибка! Вы уже подписали этот договор";
                                    }
                                    else
                                    {
                                        if ( !empty( $_REQUEST["code"] ) )
                                        {
                                            
                                            $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia_test";
                                            include $urlEsia."/Esia.php";
                                            include $urlEsia."/EsiaOmniAuth.php";
                                            include $urlEsia."/config_esia.php";
                                        
                                            $config_esia = new ConfigESIA();
                                        
                                            $esia = new EsiaOmniAuth($config_esia->config);
                                            $info   = array();
                                            $token  = $esia->get_token($_REQUEST['code']);
                                            $info   = $esia->get_info($token);

                                            if( isset( $info['user_docs']['elements'] ) > 0 && $info['user_info']['trusted'] && $info['user_docs']['elements'][0]['vrfStu'] == "VERIFIED")
                                            {

                                                $ob = \Bitrix\Main\UserTable::getList(array(
                                                    'select' => array("ID"), 
                                                    "order" => array("ID" => "ASC"),
                                                    'filter' => array("UF_ESIA_ID" => $info['user_id'])
                                                ));
                                                if($esiaUser = $ob->Fetch() && $esiaUser['ID'] == $this -> arResult['CURRENT_USER']['ID'])
                                                {
                                                    $hashKey = hash('md5', $info['user_info']['eTag'] . time());
                                                    if($this -> arResult['CONTACT_TYPE'] == "SIGNED")
                                                    {
                                                        $this -> signedContract($this -> arResult['CONTRACT'], $info['user_info']['eTag'], $hashKey);
                                                    }
                                                    else
                                                    {
                                                        $this -> createSignedContract($this -> arResult['CONTRACT'], $info['user_info']['eTag'], $hashKey);
                                                    }
                                                }
                                                else
                                                {
                                                    $this -> arResult['MESSAGE'] = "Ошибка! Неверный пользователь или вы неавторизованы через Госуслуги";
                                                }
                                            }
                                        }elseif($_REQUEST['PASSWORD_SIGNATURE'] && COption::GetOptionString("anypact", "block_gosuslugi", "Y") == "Y"){
                                            $arPassSign = unserialize(base64_decode($_REQUEST['PASSWORD_SIGNATURE']));
                                            if($this -> arResult['CONTACT_TYPE'] == "SIGNED")
                                            {
                                                $this -> signedContract($this -> arResult['CONTRACT'], $arPassSign['eTag'], $arPassSign['hash']);
                                            }
                                            else
                                            {
                                                $this -> createSignedContract($this -> arResult['CONTRACT'], $arPassSign['eTag'], $arPassSign['hash']);
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $this -> arResult['MESSAGE'] = "Ошибка! Нельзя подписать договор во время изменения";
                                }
                            }
                            // AJAX события
                            else if($this->checkSession && $this->isRequestViaAjax)
                            {
                                $this -> arResult['VIA_AJAX'] = "Y";

                                if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'editContract')
                                {
                                    if($this -> arResult['CONTACT_TYPE'] != "SIGNED")
                                    {
                                        $this -> arResult['TYPE'] = "EDITOR";
                                        $arRes['STATUS'] = "SUCCESS";
                                    }
                                    else
                                    {
                                        $arRes['STATUS'] = "ERROR";
                                        $arRes['MESSAGE'] = "Ошибка! Нельзя изменить подписанный договор";
                                    }
                                }
                                else if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'deleteRedaction')
                                {
                                    if($this -> arResult['CONTACT_TYPE'] != "ORIGINAL" && $this -> arResult['TYPE'] == "VIEW")
                                    {
                                        if($this -> arResult['CONTACT_TYPE'] == "REDACTION")
                                            $arRes = $this -> deleteRedaction($this -> arResult['CONTRACT']['ID']);
                                        else if($this -> arResult['CONTACT_TYPE'] == "SIGNED")
                                            $arRes = $this -> closeContract($this -> arResult['CONTRACT']['ID'], $this -> arResult['CURRENT_USER']['ID']);
                                        
                                        if($arRes['STATUS'] == "SUCCESS") 
                                        {
                                            $this -> getContract("REDACTION");
                                        }
                                    }
                                    else
                                    {
                                        $arRes['STATUS'] = "SUCCESS";
                                    }
                                }
                                else if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'saveRedaction')
                                {
                                    if($this -> arResult['DEAL']['OWNER_ID'] != $this -> arResult['CURRENT_USER']['ID'] && (empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) || $this -> arResult['DEAL']['COMPANY_ID'] != $this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['CONTACT_TYPE'] == "ORIGINAL")
                                    {
                                        $arRedaction = array(
                                            "USER_A" => $this -> arResult['DEAL']['OWNER_ID'], 
                                            "USER_B" => $this -> arResult['CURRENT_USER']['ID'], 
                                            "COMPANY_A" => $this -> arResult['DEAL']['COMPANY_ID'], 
                                            "COMPANY_B" => $this -> arResult['CURRENT_USER']['COMPANY_ID'], 
                                            "DEAL_ID" => $this -> arResult['DEAL']['ID'], 
                                            "TEXT" => self::formattingText($_REQUEST['TEXT'])
                                        );
                                        $arRes = $this -> createRedaction($arRedaction);

                                        if($arRes['STATUS'] == "SUCCESS") 
                                        {
                                            
                                            $arRedaction['ID'] = $arRes['REDACTION_ID'];
                                            $this -> arResult['CONTRACT'] = $arRedaction;
                                            $this -> arResult['CONTACT_TYPE'] = "REDACTION";
                                        }
                                    }
                                    else
                                    {
                                        $contract_text = self::formattingText($_REQUEST['TEXT']);

                                        if($this -> arResult['CONTACT_TYPE'] == "REDACTION")
                                            $arRes = $this -> updateRedaction($this -> arResult['CONTRACT']['ID'], $this -> arResult['CURRENT_USER']['ID'], $contract_text);
                                        else{
                                            $arRes = self::updateContract($this -> arResult['CONTRACT']['ID'], $contract_text);
                                            $arRes['SCRIPT'] = 'document.location.href = "/my_pacts/edit_my_pact/?ELEMENT_ID='.$this -> arResult['DEAL']['ID'].'&ACTION=EDIT"';
                                        }
                                        
                                        if($arRes['STATUS'] == "SUCCESS") 
                                        {
                                            $this -> arResult['CONTRACT']['TEXT'] = $contract_text;
                                        }
                                    }
                                }
                                else if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'signContract')
                                {
                                    if($this -> arResult['TYPE'] == "VIEW")
                                    {
                                        if(($this -> arResult['DEAL']['OWNER_ID'] == $this -> arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['DEAL']['COMPANY_ID'] == $this -> arResult['CURRENT_USER']['COMPANY_ID'])) && $this -> arResult['CONTACT_TYPE'] == "ORIGINAL")
                                        {
                                            $arRes['STATUS'] = "ERROR";
                                            $arRes['MESSAGE'] = "Ошибка! Нельзя подписать свой договор";
                                        }
                                        else if($this -> arResult['CONTACT_TYPE'] == "SIGNED" && ($this -> arResult['CURRENT_USER']['ID'] ==  $this -> arResult['CONTRACT']['SIGNED_USER'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['CURRENT_USER']['COMPANY_ID'] ==  $this -> arResult['CONTRACT']['SIGNED_COMPANY']))){
                                            $arRes['STATUS'] = "ERROR";
                                            $arRes['MESSAGE'] =  "Ошибка! Вы уже подписали этот договор";
                                        }
                                        else
                                        {
                                            $arRes['STATUS'] = "SUCCESS";
                                            // Подписание
                                            // Если госуслуги не работают и подписание идет через Логин и Пароль
                                            if(COption::GetOptionString("anypact", "block_gosuslugi", "Y") == "Y")
                                            {
                                                $arRes['SCRIPT'] = 'newAnyPactPopUp({TITLE: jsText.SIGN_CONTRACT.TITLE, BODY: $("#regpopup_autarisation_deal"),
                                                    BUTTONS: [
                                                        {
                                                            NAME: jsText.CLOSE,
                                                            SECONDARY: "Y",
                                                            CLOSE: "Y"
                                                        },
                                                        {
                                                            NAME: jsText.SIGN_CONTRACT.BUTTON,
                                                            CALLBACK: (function () {
                                                                let login = $(".new-pu-body").find("#user_aut_login_deal").val();
                                                                let password  = $(".new-pu-body").find("#user_aut_pass_deal").val();
                                                                var res = passwordSignature(login, password).then(function(data) {
                                                                    $result = JSON.parse(data);
                                                                    if($result["TYPE"]=="ERROR"){
                                                                        $(".new-pu-body").find("#message_error_aut_deal").html("&#8226; "+$result["VALUE"]);
                                                                    }
                                                                    if($result["TYPE"]=="SUCCES"){
                                                                        document.location.href = document.location.href.replace(new RegExp("#","g"), "") + 
                                                                        "&via_ajax=Y&'.bitrix_sessid_get().'&action=esiaSign" +
                                                                        "&PASSWORD_SIGNATURE=" + $result["PASSWORD_SIGNATURE"];
                                                                    }
                                                                });
                                                            })
                                                        },
                                                    ],
                                                    ONLOAD: function(){
                                                        $(".new-pu-body").find("#regpopup_autarisation_deal").show();
                                                        $(".new-pu-body").find("#submit_button_aut_user_deal").remove();
                                                    }
                                                });';
                                            }
                                            // Госуслуги
                                            else
                                            {
                                                $link = '/contract/?via_ajax=Y&sessid='.bitrix_sessid_get().'&action=esiaSign&ID='.$this -> arResult['DEAL']['ID'];
                                                if(!empty($this -> arResult['USER']['ID']))
                                                    $link .= '&USER_ID='.$this -> arResult['USER']['ID'];
                                                $link = base64_encode($link);
                                                $arRes['SCRIPT'] = 'document.location.href = "/profile/aut_esia.php?returnurl='.$link.'"';
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $arRes['STATUS'] = "ERROR";
                                        $arRes['MESSAGE'] = "Ошибка! Нельзя подписать договор во время изменения";
                                    }
                                }
                                // Получаем разделы шаблонов
                                else if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'getPatterns')
                                {
                                    $arRes['STATUS'] = "SUCCESS";
                                    $this -> arResult['TYPE'] = "EDITOR";
                                    $this -> arResult['PATTERN_TREE'] = self::getPatternTree();
                                }
                            }
                        }
                        // Новый договор
                        else
                        {
                            $this -> arResult['TYPE'] = "EDITOR";

                            if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'getPatterns')
                            {
                                $arRes['STATUS'] = "SUCCESS";
                            }
                            if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'deleteRedaction')
                            {
                                $arRes['SCRIPT'] = 'document.location.href = "/my_pacts/edit_my_pact/?ELEMENT_ID='.$this -> arResult['DEAL']['ID'].'&ACTION=EDIT"';
                            }
                            else if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'saveRedaction')
                            {
                                $arContract = array(
                                    "USER_A" => $this -> arResult['CURRENT_USER']['ID'],
                                    "TEXT" => self::formattingText($_REQUEST['TEXT'])
                                );
                                $arRes = $this -> createContract($arContract);

                                if($arRes['STATUS'] == "SUCCESS") 
                                {
                                    if($this -> arParams['NEW_DEAL'] == "Y")
                                    {
                                        // Из той же оперы прошлых прогеров
                                        $_SESSION['FORM_SDELKA'] = $this -> arParams['DEAL_DATA'];
                                        $arRes['SCRIPT'] = 'document.location.href = "/my_pacts/edit_my_pact/?ACTION=ADD&dogovor='.$arRes['CACHE_NAME'].'"';
                                    }
                                    else
                                    {
                                        $arRes['SCRIPT'] = 'document.location.href = "/my_pacts/edit_my_pact/?ELEMENT_ID='.$this -> arResult['DEAL']['ID'].'&ACTION=EDIT"';
                                        $arContract['ID'] = $arRes['CONTRACT_ID'];
                                        $this -> arResult['CONTRACT'] = $arContract;
                                        $this -> arResult['CONTACT_TYPE'] = "ORIGINAL";
                                    }
                                }
                            }
                            else
                            {
                                $this -> arResult['PATTERN_TREE'] = self::getPatternTree();
                            }
                        }
                    }
                    else
                    {
                        $this -> arResult['NOT_DEAL'] = "Y";
                    }
                }
            }
            else
            {
                $this -> arResult['NOT_ESIA'] = "Y";
            }
        }
        else
        {
            $this -> arResult['NOT_AUTH'] = "Y";
        }
        // Если данные запрашиваются через AJAX, то возварщаем JSON
        if($arRes)
        {
            ob_start();
            // Заменяем текст договора если был загружен файл
            if($this->request->get($this -> arParams['ACTION_VARIABLE']) == 'uploadFile' && $arRes['STATUS'] == "SUCCESS"){
                $this -> arResult['CONTRACT']['TEXT'] = self::formattingText($arRes['TEXT']);
            }
        }

        // Подставляем данные в тексте договора
        if(isset($this -> arResult['CONTRACT']['TEXT']))
        {
            if($this -> arResult['CONTACT_TYPE'] != "ORIGINAL" && !empty($this -> arResult['USER']['ID']) && ($this -> arResult['DEAL']['OWNER_ID'] == $this -> arResult['CURRENT_USER']['ID'] || (!empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) && $this -> arResult['CURRENT_USER']['COMPANY_ID'] ==  $this -> arResult['DEAL']['COMPANY_ID'])))
                $this -> arResult['CONTRACT']['TEXT'] = self::pasteTextData($this -> arResult['CONTRACT']['TEXT'], $this -> arResult['USER']);
            else if($this -> arResult['DEAL']['OWNER_ID'] != $this -> arResult['CURRENT_USER']['ID'] && (empty($this -> arResult['CURRENT_USER']['COMPANY_ID']) || $this -> arResult['DEAL']['COMPANY_ID'] != $this -> arResult['CURRENT_USER']['COMPANY_ID']))
                $this -> arResult['CONTRACT']['TEXT'] = self::pasteTextData($this -> arResult['CONTRACT']['TEXT'], $this -> arResult['CURRENT_USER']);
        }

        $this->includeComponentTemplate();

        // Если данные запрашиваются через AJAX, то возварщаем JSON
        if($arRes)
        {
            $arRes['HTML'] = ob_get_contents();
            ob_end_clean();
            echo json_encode($arRes);
        }
        return $this->arResult;
    }
};
