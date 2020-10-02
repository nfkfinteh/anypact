<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class MessengerHLMessageList extends CBitrixComponent
{
    //Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent
    //и применяет к ним функцию htmlspecialcharsex. В данном случае такая обработка избыточна.
    //Переопределяем.    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "DIALOG_ID" => intval($arParams["DIALOG_ID"]),
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        );
        return $result;
    }

    private function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = HL\HighloadBlockTable::getById($HlBlockId)->fetch();   
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    private function getBlackList($user_id_b, $user_id_a){
        global $USER;
        $current_user = $USER->GetID();

        $result = false;

        if(is_array($user_id_a))
            foreach($user_id_a as $id)
                if($id != $user_id_b){
                    $user_id_a = $id;
                    break;
                }
        
        if(CModule::IncludeModule("highloadblock"))
        {
            if($user_id_b != $user_id_a){
                $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(15)->fetch();
                $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();
                $rsData = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_USER_B" => $user_id_b, "UF_USER_A" => $user_id_a)
                ));
                while($arData = $rsData->Fetch()){
                    $result = true;
                }
            }
        }

        $this->arResult['BLACKLIST'] = $result;
    }

    private function getMessages($user_id, $dialog_id){
        if(empty(intval($dialog_id)) || empty(intval($user_id)))
            return false;
        
        $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_DELETE_DATE", "UF_STATUS"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_USER_ID" => $user_id)
        ));
        if($arData = $rsData->Fetch()){
            $this -> arResult['DISCUSSION_USER_STATUS'] = $arData['UF_STATUS'];
            if(empty($arData['UF_DELETE_DATE']))
                $arData['UF_DELETE_DATE'] = "01.01.2000 00:00:00";

            $nav = new \Bitrix\Main\UI\PageNavigation("nav-message");
            $nav->allowAllRecords(true)
                ->setPageSize(20)
                ->initFromUri();
            $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID", "UF_DATE_CREATE", "UF_AUTHOR_ID", "UF_MESSAGE_TEXT", "UF_IS_SYSTEM"),
                "order" => array("ID" => "DESC"),
                "count_total" => true,
                "offset" => $nav->getOffset(),
                "limit" => $nav->getLimit(),
                "filter" => array("UF_DIALOG_ID" => $dialog_id, ">UF_DATE_CREATE" => $arData['UF_DELETE_DATE'])
            ));
            
            $nav->setRecordCount($rsData->getCount());

            $this -> arResult['TOTAL_PAGE'] = $nav->getPageCount();

            if($this -> arResult['PAGE'] > $this -> arResult['TOTAL_PAGE'])
                return false;
            
            while($arData = $rsData->Fetch()){
                $arMessageIDs[] = $arData["ID"];
                $arMessage[$arData["ID"]] = array("ID" => $arData["ID"], "DATE_CREATE" => $arData["UF_DATE_CREATE"], "AUTHOR_ID" => $arData["UF_AUTHOR_ID"], "MESSAGE_TEXT" => $arData["UF_MESSAGE_TEXT"], "IS_SYSTEM" => $arData["UF_IS_SYSTEM"]);
                $arUsers[$arData["ID"]] = $arData["UF_AUTHOR_ID"];
            }
            if($arMessage){
                ksort($arMessage);
                $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
                $rsData = $entity_data_class::getList(array(
                    "select" => array("UF_STATUS", 'UF_MESSAGE_ID'),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_MESSAGE_ID" => $arMessageIDs, "UF_USER_ID" => $user_id, "!UF_STATUS" => 13)
                ));
                while($arData = $rsData->Fetch()){
                    $arMessage[$arData['UF_MESSAGE_ID']]['STATUS'] = $arData['UF_STATUS'];
                    if($arData['UF_STATUS'] == 10){
                        $arStatus[] = $arData['UF_MESSAGE_ID'];
                        $arMessage[$arData['UF_MESSAGE_ID']]['STATUS'] = 9;
                    }
                }

                $rsData = $entity_data_class::getList(array(
                    "select" => array('UF_MESSAGE_ID'),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_MESSAGE_ID" => $arStatus, "!UF_USER_ID" => $user_id, "UF_STATUS" => 11)
                ));
                while($arData = $rsData->Fetch()){
                    $arMessage[$arData['UF_MESSAGE_ID']]['STATUS'] = 11;
                }

                $arUsers = array_unique($arUsers);

                $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => implode(" | ", $arUsers)), array("FIELDS" => array("ID", "NAME", "LAST_NAME", "PERSONAL_PHOTO")));
                while($arRes = $rsUser -> GetNext()){
                    $arUser[$arRes['ID']]['AUTHOR_NAME'] = $arRes['NAME'];
                    $arUser[$arRes['ID']]['AUTHOR_LAST_NAME'] = $arRes['LAST_NAME'];
                    $arUser[$arRes['ID']]['AUTHOR_PERSONAL_PHOTO'] = $arRes['PERSONAL_PHOTO'];
                }

                foreach ($arMessage as $key => &$value){
                    if(!isset($value['STATUS'])){
                        unset($arMessage[$key]);
                    }elseif(isset($arUser[$value['AUTHOR_ID']])){
                        $value = array_merge($value, $arUser[$value['AUTHOR_ID']]);
                    }
                }

                $entity_data_class = self::GetEntityDataClass(ATTACHMENTS_HLB_ID);
                $rsData = $entity_data_class::getList(array(
                    "select" => array("ID", "UF_TYPE", "UF_LINK", "UF_IMAGE", "UF_MESSAGE_ID"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_MESSAGE_ID" => $arMessageIDs)
                ));
                while($arData = $rsData->Fetch()){
                    $arMessage[$arData['UF_MESSAGE_ID']]['ATTACHMENTS'] = array("ID" => $arData['ID'], "TYPE" => $arData['UF_TYPE'], "LINK" => $arData['UF_LINK'], "IMAGE" => $arData['UF_IMAGE']);
                }
            }
            return $arMessage;
        }
        return false;
    }

    private function newMessage($dialog_id, $user_id, $arFields){
        if(empty(intval($dialog_id)) || empty(intval($user_id)) || !is_array($arFields) || empty(trim($arFields['MESSAGE_TEXT'])))
            return false;
        
        $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_USER_ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_STATUS" => 4)
        ));
        while($arData = $rsData->Fetch()){
            $arUsers[] = $arData['UF_USER_ID'];
        }
        if($arUsers && in_array($user_id, $arUsers)){
            $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
            $date = date("d.m.Y H:i:s");
            $result = $entity_data_class::add(array(
                "UF_DIALOG_ID" => $dialog_id,
                "UF_AUTHOR_ID" => $user_id,
                "UF_MESSAGE_TEXT" => self::emoji_entitizer(trim($arFields['MESSAGE_TEXT'])),
                "UF_TIMESTAMP_X" => $date,
                "UF_DATE_CREATE" => $date,
            ));
            $message_id = $result->getId();
            if($message_id){
                $entity_data_class = self::GetEntityDataClass(DIALOGS_HLB_ID);
                $entity_data_class::update($dialog_id, array(
                    "UF_LAST_MESSAGE_DATE" => $date,
                ));
                $arMessage['ID'] = $message_id;
                $arMessage['DATE_CREATE'] = $date;
                $arMessage['AUTHOR_ID'] = $user_id;
                $arMessage['MESSAGE_TEXT'] = $arFields['MESSAGE_TEXT'];
                $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => $user_id), array("FIELDS" => array("ID", "NAME", "LAST_NAME", "PERSONAL_PHOTO")));
                if($arRes = $rsUser -> GetNext()){
                    $arMessage['AUTHOR_NAME'] = $arRes['NAME'];
                    $arMessage['AUTHOR_LAST_NAME'] = $arRes['LAST_NAME'];
                    $arMessage['AUTHOR_PERSONAL_PHOTO'] = $arRes['PERSONAL_PHOTO'];
                }
                $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
                foreach ($arUsers as $user) {
                    $entity_data_class::add(array(
                        "UF_DIALOG_ID" => $dialog_id,
                        "UF_MESSAGE_ID" => $message_id,
                        "UF_USER_ID" => $user,
                        "UF_STATUS" => ($user_id == $user) ? 10 : 9,
                    ));
                }
                if(is_array($_FILES) && !empty($_FILES) && is_array($arFields['NEED_FILES']) && !empty($arFields['NEED_FILES'])){
                    foreach ($_FILES as $attachment) {
                        if(in_array($attachment['name'], $arFields['NEED_FILES'])){
                            $arFile[] = $attachment;
                        }
                    }
                    if(/*$arLink || */!empty($arFile) && is_array($arFile)){
                        $entity_data_class = self::GetEntityDataClass(ATTACHMENTS_HLB_ID);
                        $rsData = $entity_data_class::add(array(
                            "UF_MESSAGE_ID" => $message_id,
                            "UF_TYPE" => 8,
                            //"UF_LINK" => $arLink,
                            "UF_IMAGE" => $arFile,
                        ));
                        $attachment_id = $rsData->getId();
                        if($attachment_id){
                            $rsData = $entity_data_class::getList(array(
                                "select" => array("ID", "UF_TYPE", "UF_LINK", "UF_IMAGE", "UF_MESSAGE_ID"),
                                "order" => array("ID" => "ASC"),
                                "filter" => array("ID" => $attachment_id)
                            ));
                            if($arData = $rsData->Fetch()){
                                $arMessage['ATTACHMENTS'] = array("ID" => $arData['ID'], "TYPE" => $arData['UF_TYPE'], "LINK" => $arData['UF_LINK'], "IMAGE" => $arData['UF_IMAGE']);
                            }
                        }
                    }
                }
                return $arMessage;
            }
        }
        return false;
    }

    private function emoji_entitizer($str) {
        $emoji_pattern = "/\\x{1f1ef}\\x{1f1f5}|\\x{1f1f0}\\x{1f1f7}|\\x{1f1e9}\\x{1f1ea}|\\x{1f1e8}\\x{1f1f3}|\\x{1f1fa}\\x{1f1f8}|\\x{1f1eb}\\x{1f1f7}|\\x{1f1ea}\\x{1f1f8}|\\x{1f1ee}\\x{1f1f9}|\\x{1f1f7}\\x{1f1fa}|\\x{1f1ec}\\x{1f1e7}|\\x{1f441}\\x{200d}\\x{1f5e8}|\\x{1f473}\\x{200d}\\x{2642}|\\x{1f473}\\x{200d}\\x{2640}|\\x{1f471}\\x{200d}\\x{2642}|\\x{1f471}\\x{200d}\\x{2640}|\\x{1f64d}\\x{200d}\\x{2642}|\\x{1f64d}\\x{200d}\\x{2640}|\\x{1f64e}\\x{200d}\\x{2642}|\\x{1f64e}\\x{200d}\\x{2640}|\\x{1f645}\\x{200d}\\x{2642}|\\x{1f645}\\x{200d}\\x{2640}|\\x{1f646}\\x{200d}\\x{2642}|\\x{1f646}\\x{200d}\\x{2640}|\\x{1f481}\\x{200d}\\x{2642}|\\x{1f481}\\x{200d}\\x{2640}|\\x{1f64b}\\x{200d}\\x{2642}|\\x{1f64b}\\x{200d}\\x{2640}|\\x{1f647}\\x{200d}\\x{2642}|\\x{1f647}\\x{200d}\\x{2640}|\\x{1f926}\\x{200d}\\x{2642}|\\x{1f926}\\x{200d}\\x{2640}|\\x{1f937}\\x{200d}\\x{2642}|\\x{1f937}\\x{200d}\\x{2640}|\\x{1f486}\\x{200d}\\x{2642}|\\x{1f486}\\x{200d}\\x{2640}|\\x{1f487}\\x{200d}\\x{2642}|\\x{1f487}\\x{200d}\\x{2640}|\\x{1f6b6}\\x{200d}\\x{2642}|\\x{1f6b6}\\x{200d}\\x{2640}|\\x{1f3c3}\\x{200d}\\x{2642}|\\x{1f3c3}\\x{200d}\\x{2640}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{2764}\\x{200d}\\x{1f48b}\\x{200d}\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{2764}\\x{200d}\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?|\\x{1f3cb}\\x{200d}\\x{2640}|\\x{1f3cb}\\x{200d}\\x{2642}|\\x{1f93c}\\x{200d}\\x{2640}|\\x{1f93c}\\x{200d}\\x{2642}|\\x{1f938}\\x{200d}\\x{2640}|\\x{1f938}\\x{200d}\\x{2642}|\\x{26f9}\\x{200d}\\x{2640}|\\x{26f9}\\x{200d}\\x{2642}|\\x{1f93e}\\x{200d}\\x{2640}|\\x{1f93e}\\x{200d}\\x{2642}|\\x{1f3cc}\\x{200d}\\x{2640}|\\x{1f3cc}\\x{200d}\\x{2642}|\\x{1f9d8}\\x{200d}\\x{2640}|\\x{1f9d8}\\x{200d}\\x{2642}|\\x{1f3c4}\\x{200d}\\x{2640}|\\x{1f3c4}\\x{200d}\\x{2642}|\\x{1f3ca}\\x{200d}\\x{2640}|\\x{1f3ca}\\x{200d}\\x{2642}|\\x{1f93d}\\x{200d}\\x{2640}|\\x{1f93d}\\x{200d}\\x{2642}|\\x{1f6a3}\\x{200d}\\x{2640}|\\x{1f6a3}\\x{200d}\\x{2642}|\\x{1f9d7}\\x{200d}\\x{2640}|\\x{1f9d7}\\x{200d}\\x{2642}|\\x{1f6b5}\\x{200d}\\x{2640}|\\x{1f6b5}\\x{200d}\\x{2642}|\\x{1f6b4}\\x{200d}\\x{2640}|\\x{1f6b4}\\x{200d}\\x{2642}|\\x{1f3cc}\\x{200d}\\x{2642}|\\x{1f3cc}\\x{200d}\\x{2640}|\\x{1f3c4}\\x{200d}\\x{2642}|\\x{1f3c4}\\x{200d}\\x{2640}|\\x{1f6a3}\\x{200d}\\x{2642}|\\x{1f6a3}\\x{200d}\\x{2640}|\\x{1f3ca}\\x{200d}\\x{2642}|\\x{1f3ca}\\x{200d}\\x{2640}|\\x{26f9}\\x{200d}\\x{2642}|\\x{26f9}\\x{200d}\\x{2640}|\\x{1f3cb}\\x{200d}\\x{2642}|\\x{1f3cb}\\x{200d}\\x{2640}|\\x{1f6b4}\\x{200d}\\x{2642}|\\x{1f6b4}\\x{200d}\\x{2640}|\\x{1f6b5}\\x{200d}\\x{2642}|\\x{1f6b5}\\x{200d}\\x{2640}|\\x{1f938}\\x{200d}\\x{2642}|\\x{1f938}\\x{200d}\\x{2640}|\\x{1f93c}\\x{200d}\\x{2642}|\\x{1f93c}\\x{200d}\\x{2640}|\\x{1f93d}\\x{200d}\\x{2642}|\\x{1f93d}\\x{200d}\\x{2640}|\\x{1f93e}\\x{200d}\\x{2642}|\\x{1f93e}\\x{200d}\\x{2640}|\\x{1f46f}\\x{200d}\\x{2642}|\\x{1f46f}\\x{200d}\\x{2640}|\\x{1f9d6}\\x{200d}\\x{2640}|\\x{1f9d6}\\x{200d}\\x{2642}|\\x{1f9d7}\\x{200d}\\x{2640}|\\x{1f9d7}\\x{200d}\\x{2642}|\\x{1f9d8}\\x{200d}\\x{2640}|\\x{1f9d8}\\x{200d}\\x{2642}|\\x{1f9d9}\\x{200d}\\x{2640}|\\x{1f9d9}\\x{200d}\\x{2642}|\\x{1f9da}\\x{200d}\\x{2640}|\\x{1f9da}\\x{200d}\\x{2642}|\\x{1f9db}\\x{200d}\\x{2640}|\\x{1f9db}\\x{200d}\\x{2642}|\\x{1f9dc}\\x{200d}\\x{2640}|\\x{1f9dc}\\x{200d}\\x{2642}|\\x{1f9dd}\\x{200d}\\x{2640}|\\x{1f9dd}\\x{200d}\\x{2642}|\\x{1f9de}\\x{200d}\\x{2640}|\\x{1f9de}\\x{200d}\\x{2642}|\\x{1f9df}\\x{200d}\\x{2640}|\\x{1f9df}\\x{200d}\\x{2642}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{2695}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{2695}|\\x{1f9d1}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f393}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f393}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f393}|\\x{1f9d1}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3eb}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3eb}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3eb}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{2696}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{2696}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f33e}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f33e}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f373}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f373}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f527}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f527}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3ed}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3ed}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f4bc}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f4bc}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f52c}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f52c}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f4bb}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f4bb}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3a4}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3a4}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3a8}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f3a8}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{2708}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{2708}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f680}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f680}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f692}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f692}|\\x{1f46e}\\x{200d}\\x{2642}|\\x{1f46e}\\x{200d}\\x{2640}|\\x{1f575}\\x{200d}\\x{2642}|\\x{1f575}\\x{200d}\\x{2640}|\\x{1f482}\\x{200d}\\x{2642}|\\x{1f482}\\x{200d}\\x{2640}|\\x{1f477}\\x{200d}\\x{2642}|\\x{1f477}\\x{200d}\\x{2640}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f466}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}\\x{200d}\\x{1f466}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f466}\\x{200d}\\x{1f466}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}\\x{200d}\\x{1f467}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f466}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f466}\\x{200d}\\x{1f466}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}\\x{200d}\\x{1f466}|\\x{1f468}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}\\x{200d}\\x{1f467}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f466}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f466}\\x{200d}\\x{1f466}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}\\x{200d}\\x{1f466}|\\x{1f469}[\\x{1f3fb}-\\x{1f3ff}]?\\x{200d}\\x{1f467}\\x{200d}\\x{1f467}|\\x{30}\\x{20e3}|\\x{31}\\x{20e3}|\\x{32}\\x{20e3}|\\x{33}\\x{20e3}|\\x{34}\\x{20e3}|\\x{35}\\x{20e3}|\\x{36}\\x{20e3}|\\x{37}\\x{20e3}|\\x{38}\\x{20e3}|\\x{39}\\x{20e3}|\\x{23}\\x{20e3}|\\x{2a}\\x{20e3}|\\x{1f441}\\x{200d}\\x{1f5e8}|\\x{1f3f4}\\x{200d}\\x{2620}|\\x{1f3f3}\\x{200d}\\x{1f308}|\\x{1f1fa}\\x{1f1f3}|\\x{1f1e6}\\x{1f1eb}|\\x{1f1e6}\\x{1f1fd}|\\x{1f1e6}\\x{1f1f1}|\\x{1f1e9}\\x{1f1ff}|\\x{1f1e6}\\x{1f1f8}|\\x{1f1e6}\\x{1f1e9}|\\x{1f1e6}\\x{1f1f4}|\\x{1f1e6}\\x{1f1ee}|\\x{1f1e6}\\x{1f1f6}|\\x{1f1e6}\\x{1f1ec}|\\x{1f1e6}\\x{1f1f7}|\\x{1f1e6}\\x{1f1f2}|\\x{1f1e6}\\x{1f1fc}|\\x{1f1e6}\\x{1f1fa}|\\x{1f1e6}\\x{1f1f9}|\\x{1f1e6}\\x{1f1ff}|\\x{1f1e7}\\x{1f1f8}|\\x{1f1e7}\\x{1f1ed}|\\x{1f1e7}\\x{1f1e9}|\\x{1f1e7}\\x{1f1e7}|\\x{1f1e7}\\x{1f1fe}|\\x{1f1e7}\\x{1f1ea}|\\x{1f1e7}\\x{1f1ff}|\\x{1f1e7}\\x{1f1ef}|\\x{1f1e7}\\x{1f1f2}|\\x{1f1e7}\\x{1f1f9}|\\x{1f1e7}\\x{1f1f4}|\\x{1f1e7}\\x{1f1e6}|\\x{1f1e7}\\x{1f1fc}|\\x{1f1e7}\\x{1f1f7}|\\x{1f1ee}\\x{1f1f4}|\\x{1f1fb}\\x{1f1ec}|\\x{1f1e7}\\x{1f1f3}|\\x{1f1e7}\\x{1f1ec}|\\x{1f1e7}\\x{1f1eb}|\\x{1f1e7}\\x{1f1ee}|\\x{1f1f0}\\x{1f1ed}|\\x{1f1e8}\\x{1f1f2}|\\x{1f1e8}\\x{1f1e6}|\\x{1f1ee}\\x{1f1e8}|\\x{1f1e8}\\x{1f1fb}|\\x{1f1e7}\\x{1f1f6}|\\x{1f1f0}\\x{1f1fe}|\\x{1f1e8}\\x{1f1eb}|\\x{1f1f9}\\x{1f1e9}|\\x{1f1e8}\\x{1f1f1}|\\x{1f1e8}\\x{1f1f3}|\\x{1f1e8}\\x{1f1fd}|\\x{1f1e8}\\x{1f1e8}|\\x{1f1e8}\\x{1f1f4}|\\x{1f1f0}\\x{1f1f2}|\\x{1f1e8}\\x{1f1ec}|\\x{1f1e8}\\x{1f1e9}|\\x{1f1e8}\\x{1f1f0}|\\x{1f1e8}\\x{1f1f7}|\\x{1f1e8}\\x{1f1ee}|\\x{1f1ed}\\x{1f1f7}|\\x{1f1e8}\\x{1f1fa}|\\x{1f1e8}\\x{1f1fc}|\\x{1f1e8}\\x{1f1fe}|\\x{1f1e8}\\x{1f1ff}|\\x{1f1e9}\\x{1f1f0}|\\x{1f1e9}\\x{1f1ef}|\\x{1f1e9}\\x{1f1f2}|\\x{1f1e9}\\x{1f1f4}|\\x{1f1ea}\\x{1f1e8}|\\x{1f1ea}\\x{1f1ec}|\\x{1f1f8}\\x{1f1fb}|\\x{1f1ec}\\x{1f1f6}|\\x{1f1ea}\\x{1f1f7}|\\x{1f1ea}\\x{1f1ea}|\\x{1f1ea}\\x{1f1f9}|\\x{1f1ea}\\x{1f1fa}|\\x{1f1eb}\\x{1f1f0}|\\x{1f1eb}\\x{1f1f4}|\\x{1f1eb}\\x{1f1ef}|\\x{1f1eb}\\x{1f1ee}|\\x{1f1eb}\\x{1f1f7}|\\x{1f1ec}\\x{1f1eb}|\\x{1f1f5}\\x{1f1eb}|\\x{1f1f9}\\x{1f1eb}|\\x{1f1ec}\\x{1f1e6}|\\x{1f1ec}\\x{1f1f2}|\\x{1f1ec}\\x{1f1ea}|\\x{1f1e9}\\x{1f1ea}|\\x{1f1ec}\\x{1f1ed}|\\x{1f1ec}\\x{1f1ee}|\\x{1f1ec}\\x{1f1f7}|\\x{1f1ec}\\x{1f1f1}|\\x{1f1ec}\\x{1f1e9}|\\x{1f1ec}\\x{1f1f5}|\\x{1f1ec}\\x{1f1fa}|\\x{1f1ec}\\x{1f1f9}|\\x{1f1ec}\\x{1f1ec}|\\x{1f1ec}\\x{1f1f3}|\\x{1f1ec}\\x{1f1fc}|\\x{1f1ec}\\x{1f1fe}|\\x{1f1ed}\\x{1f1f9}|\\x{1f1ed}\\x{1f1f3}|\\x{1f1ed}\\x{1f1f0}|\\x{1f1ed}\\x{1f1fa}|\\x{1f1ee}\\x{1f1f8}|\\x{1f1ee}\\x{1f1f3}|\\x{1f1ee}\\x{1f1e9}|\\x{1f1ee}\\x{1f1f7}|\\x{1f1ee}\\x{1f1f6}|\\x{1f1ee}\\x{1f1ea}|\\x{1f1ee}\\x{1f1f2}|\\x{1f1ee}\\x{1f1f1}|\\x{1f1ee}\\x{1f1f9}|\\x{1f1ef}\\x{1f1f2}|\\x{1f1ef}\\x{1f1f5}|\\x{1f1ef}\\x{1f1ea}|\\x{1f1ef}\\x{1f1f4}|\\x{1f1f0}\\x{1f1ff}|\\x{1f1f0}\\x{1f1ea}|\\x{1f1f0}\\x{1f1ee}|\\x{1f1fd}\\x{1f1f0}|\\x{1f1f0}\\x{1f1fc}|\\x{1f1f0}\\x{1f1ec}|\\x{1f1f1}\\x{1f1e6}|\\x{1f1f1}\\x{1f1fb}|\\x{1f1f1}\\x{1f1e7}|\\x{1f1f1}\\x{1f1f8}|\\x{1f1f1}\\x{1f1f7}|\\x{1f1f1}\\x{1f1fe}|\\x{1f1f1}\\x{1f1ee}|\\x{1f1f1}\\x{1f1f9}|\\x{1f1f1}\\x{1f1fa}|\\x{1f1f2}\\x{1f1f4}|\\x{1f1f2}\\x{1f1f0}|\\x{1f1f2}\\x{1f1ec}|\\x{1f1f2}\\x{1f1fc}|\\x{1f1f2}\\x{1f1fe}|\\x{1f1f2}\\x{1f1fb}|\\x{1f1f2}\\x{1f1f1}|\\x{1f1f2}\\x{1f1f9}|\\x{1f1f2}\\x{1f1ed}|\\x{1f1f2}\\x{1f1f6}|\\x{1f1f2}\\x{1f1f7}|\\x{1f1f2}\\x{1f1fa}|\\x{1f1fe}\\x{1f1f9}|\\x{1f1f2}\\x{1f1fd}|\\x{1f1eb}\\x{1f1f2}|\\x{1f1f2}\\x{1f1e9}|\\x{1f1f2}\\x{1f1e8}|\\x{1f1f2}\\x{1f1f3}|\\x{1f1f2}\\x{1f1ea}|\\x{1f1f2}\\x{1f1f8}|\\x{1f1f2}\\x{1f1e6}|\\x{1f1f2}\\x{1f1ff}|\\x{1f1f2}\\x{1f1f2}|\\x{1f1f3}\\x{1f1e6}|\\x{1f1f3}\\x{1f1f7}|\\x{1f1f3}\\x{1f1f5}|\\x{1f1f3}\\x{1f1f1}|\\x{1f1f3}\\x{1f1e8}|\\x{1f1f3}\\x{1f1ff}|\\x{1f1f3}\\x{1f1ee}|\\x{1f1f3}\\x{1f1ea}|\\x{1f1f3}\\x{1f1ec}|\\x{1f1f3}\\x{1f1fa}|\\x{1f1f3}\\x{1f1eb}|\\x{1f1f0}\\x{1f1f5}|\\x{1f1f2}\\x{1f1f5}|\\x{1f1f3}\\x{1f1f4}|\\x{1f1f4}\\x{1f1f2}|\\x{1f1f5}\\x{1f1f0}|\\x{1f1f5}\\x{1f1fc}|\\x{1f1f5}\\x{1f1f8}|\\x{1f1f5}\\x{1f1e6}|\\x{1f1f5}\\x{1f1ec}|\\x{1f1f5}\\x{1f1fe}|\\x{1f1f5}\\x{1f1ea}|\\x{1f1f5}\\x{1f1ed}|\\x{1f1f5}\\x{1f1f3}|\\x{1f1f5}\\x{1f1f1}|\\x{1f1f5}\\x{1f1f9}|\\x{1f1f5}\\x{1f1f7}|\\x{1f1f6}\\x{1f1e6}|\\x{1f1f7}\\x{1f1ea}|\\x{1f1f7}\\x{1f1f4}|\\x{1f1f7}\\x{1f1fa}|\\x{1f1f7}\\x{1f1fc}|\\x{1f1fc}\\x{1f1f8}|\\x{1f1f8}\\x{1f1f2}|\\x{1f1f8}\\x{1f1f9}|\\x{1f1f8}\\x{1f1e6}|\\x{1f1f8}\\x{1f1f3}|\\x{1f1f7}\\x{1f1f8}|\\x{1f1f8}\\x{1f1e8}|\\x{1f1f8}\\x{1f1f1}|\\x{1f1f8}\\x{1f1ec}|\\x{1f1f8}\\x{1f1fd}|\\x{1f1f8}\\x{1f1f0}|\\x{1f1f8}\\x{1f1ee}|\\x{1f1ec}\\x{1f1f8}|\\x{1f1f8}\\x{1f1e7}|\\x{1f1f8}\\x{1f1f4}|\\x{1f1ff}\\x{1f1e6}|\\x{1f1f0}\\x{1f1f7}|\\x{1f1f8}\\x{1f1f8}|\\x{1f1ea}\\x{1f1f8}|\\x{1f1f1}\\x{1f1f0}|\\x{1f1e7}\\x{1f1f1}|\\x{1f1f8}\\x{1f1ed}|\\x{1f1f0}\\x{1f1f3}|\\x{1f1f1}\\x{1f1e8}|\\x{1f1f5}\\x{1f1f2}|\\x{1f1fb}\\x{1f1e8}|\\x{1f1f8}\\x{1f1e9}|\\x{1f1f8}\\x{1f1f7}|\\x{1f1f8}\\x{1f1ff}|\\x{1f1f8}\\x{1f1ea}|\\x{1f1e8}\\x{1f1ed}|\\x{1f1f8}\\x{1f1fe}|\\x{1f1f9}\\x{1f1fc}|\\x{1f1f9}\\x{1f1ef}|\\x{1f1f9}\\x{1f1ff}|\\x{1f1f9}\\x{1f1ed}|\\x{1f1f9}\\x{1f1f1}|\\x{1f1f9}\\x{1f1ec}|\\x{1f1f9}\\x{1f1f0}|\\x{1f1f9}\\x{1f1f4}|\\x{1f1f9}\\x{1f1f9}|\\x{1f1f9}\\x{1f1f3}|\\x{1f1f9}\\x{1f1f7}|\\x{1f1f9}\\x{1f1f2}|\\x{1f1f9}\\x{1f1e8}|\\x{1f1f9}\\x{1f1fb}|\\x{1f1fa}\\x{1f1ec}|\\x{1f1fa}\\x{1f1e6}|\\x{1f1e6}\\x{1f1ea}|\\x{1f1ec}\\x{1f1e7}|\\x{1f3f4}\\x{e0067}\\x{e0062}\\x{e0065}\\x{e006e}\\x{e0067}\\x{e007f}|\\x{1f3f4}\\x{e0067}\\x{e0062}\\x{e0073}\\x{e0063}\\x{e0074}\\x{e007f}|\\x{1f3f4}\\x{e0067}\\x{e0062}\\x{e0077}\\x{e006c}\\x{e0073}\\x{e007f}|\\x{1f1fa}\\x{1f1f8}|\\x{1f1fa}\\x{1f1fe}|\\x{1f1fb}\\x{1f1ee}|\\x{1f1fa}\\x{1f1ff}|\\x{1f1fb}\\x{1f1fa}|\\x{1f1fb}\\x{1f1e6}|\\x{1f1fb}\\x{1f1ea}|\\x{1f1fb}\\x{1f1f3}|\\x{1f1fc}\\x{1f1eb}|\\x{1f1ea}\\x{1f1ed}|\\x{1f1fe}\\x{1f1ea}|\\x{1f1ff}\\x{1f1f2}|\\x{1f1ff}\\x{1f1fc}|\\x{23}\\x{20e3}|\\x{2a}\\x{20e3}|\\x{30}\\x{20e3}|\\x{31}\\x{20e3}|\\x{32}\\x{20e3}|\\x{33}\\x{20e3}|\\x{34}\\x{20e3}|\\x{35}\\x{20e3}|\\x{36}\\x{20e3}|\\x{37}\\x{20e3}|\\x{38}\\x{20e3}|\\x{39}\\x{20e3}|\\x{1f1e6}[\\x{1f1e8}-\\x{1f1ec}\\x{1f1ee}\\x{1f1f1}\\x{1f1f2}\\x{1f1f4}\\x{1f1f6}-\\x{1f1fa}\\x{1f1fc}\\x{1f1fd}\\x{1f1ff}]|\\x{1f1e7}[\\x{1f1e6}\\x{1f1e7}\\x{1f1e9}-\\x{1f1ef}\\x{1f1f1}-\\x{1f1f4}\\x{1f1f6}-\\x{1f1f9}\\x{1f1fb}\\x{1f1fc}\\x{1f1fe}\\x{1f1ff}]|\\x{1f1e8}[\\x{1f1e6}\\x{1f1e8}\\x{1f1e9}\\x{1f1eb}-\\x{1f1ee}\\x{1f1f0}-\\x{1f1f5}\\x{1f1f7}\\x{1f1fa}-\\x{1f1ff}]|\\x{1f1e9}[\\x{1f1ea}\\x{1f1ec}\\x{1f1ef}\\x{1f1f0}\\x{1f1f2}\\x{1f1f4}\\x{1f1ff}]|\\x{1f1ea}[\\x{1f1e6}\\x{1f1e8}\\x{1f1ea}\\x{1f1ec}\\x{1f1ed}\\x{1f1f7}-\\x{1f1fa}]|\\x{1f1eb}[\\x{1f1ee}-\\x{1f1f0}\\x{1f1f2}\\x{1f1f4}\\x{1f1f7}]|\\x{1f1ec}[\\x{1f1e6}\\x{1f1e7}\\x{1f1e9}-\\x{1f1ee}\\x{1f1f1}-\\x{1f1f3}\\x{1f1f5}-\\x{1f1fa}\\x{1f1fc}\\x{1f1fe}]|\\x{1f1ed}[\\x{1f1f0}\\x{1f1f2}\\x{1f1f3}\\x{1f1f7}\\x{1f1f9}\\x{1f1fa}]|\\x{1f1ee}[\\x{1f1e8}-\\x{1f1ea}\\x{1f1f1}-\\x{1f1f4}\\x{1f1f6}-\\x{1f1f9}]|\\x{1f1ef}[\\x{1f1ea}\\x{1f1f2}\\x{1f1f4}\\x{1f1f5}]|\\x{1f1f0}[\\x{1f1ea}\\x{1f1ec}-\\x{1f1ee}\\x{1f1f2}\\x{1f1f3}\\x{1f1f5}\\x{1f1f7}\\x{1f1fc}\\x{1f1fe}\\x{1f1ff}]|\\x{1f1f1}[\\x{1f1e6}-\\x{1f1e8}\\x{1f1ee}\\x{1f1f0}\\x{1f1f7}-\\x{1f1fb}\\x{1f1fe}]|\\x{1f1f2}[\\x{1f1e6}\\x{1f1e8}-\\x{1f1ed}\\x{1f1f0}-\\x{1f1ff}]|\\x{1f1f3}[\\x{1f1e6}\\x{1f1e8}\\x{1f1ea}-\\x{1f1ec}\\x{1f1ee}\\x{1f1f1}\\x{1f1f4}\\x{1f1f5}\\x{1f1f7}\\x{1f1fa}\\x{1f1ff}]|\\x{1f1f4}\\x{1f1f2}|\\x{1f1f5}[\\x{1f1e6}\\x{1f1ea}-\\x{1f1ed}\\x{1f1f0}-\\x{1f1f3}\\x{1f1f7}-\\x{1f1f9}\\x{1f1fc}\\x{1f1fe}]|\\x{1f1f6}\\x{1f1e6}|\\x{1f1f7}[\\x{1f1ea}\\x{1f1f4}\\x{1f1f8}\\x{1f1fa}\\x{1f1fc}]|\\x{1f1f8}[\\x{1f1e6}-\\x{1f1ea}\\x{1f1ec}-\\x{1f1f4}\\x{1f1f7}-\\x{1f1f9}\\x{1f1fb}\\x{1f1fd}-\\x{1f1ff}]|\\x{1f1f9}[\\x{1f1e6}\\x{1f1e8}\\x{1f1e9}\\x{1f1eb}-\\x{1f1ed}\\x{1f1ef}-\\x{1f1f4}\\x{1f1f7}\\x{1f1f9}\\x{1f1fb}\\x{1f1fc}\\x{1f1ff}]|\\x{1f1fa}[\\x{1f1e6}\\x{1f1ec}\\x{1f1f2}\\x{1f1f8}\\x{1f1fe}\\x{1f1ff}]|\\x{1f1fb}[\\x{1f1e6}\\x{1f1e8}\\x{1f1ea}\\x{1f1ec}\\x{1f1ee}\\x{1f1f3}\\x{1f1fa}]|\\x{1f1fc}[\\x{1f1eb}\\x{1f1f8}]|\\x{1f1fd}\\x{1f1f0}|\\x{1f1fe}[\\x{1f1ea}\\x{1f1f9}]|\\x{1f1ff}[\\x{1f1e6}\\x{1f1f2}\\x{1f1fc}]|[\\x{fe00}-\\x{fe0f}\\x{1f91a}\\x{1f600}\\x{1f601}\\x{1f602}\\x{1f923}\\x{1f603}\\x{1f604}\\x{1f605}\\x{1f606}\\x{1f609}\\x{1f60a}\\x{1f60b}\\x{1f60e}\\x{1f60d}\\x{1f618}\\x{1f617}\\x{1f619}\\x{1f61a}\\x{263a}\\x{1f642}\\x{1f917}\\x{1f929}\\x{1f914}\\x{1f928}\\x{1f610}\\x{1f611}\\x{1f636}\\x{1f644}\\x{1f60f}\\x{1f623}\\x{1f625}\\x{1f62e}\\x{1f910}\\x{1f62f}\\x{1f62a}\\x{1f62b}\\x{1f634}\\x{1f60c}\\x{1f61b}\\x{1f61c}\\x{1f61d}\\x{1f924}\\x{1f612}\\x{1f613}\\x{1f614}\\x{1f615}\\x{1f643}\\x{1f911}\\x{1f632}\\x{2639}\\x{1f641}\\x{1f616}\\x{1f61e}\\x{1f61f}\\x{1f624}\\x{1f622}\\x{1f62d}\\x{1f626}\\x{1f627}\\x{1f628}\\x{1f629}\\x{1f92f}\\x{1f62c}\\x{1f630}\\x{1f631}\\x{1f633}\\x{1f92a}\\x{1f635}\\x{1f621}\\x{1f620}\\x{1f92c}\\x{1f637}\\x{1f912}\\x{1f915}\\x{1f922}\\x{1f92e}\\x{1f927}\\x{1f607}\\x{1f920}\\x{1f921}\\x{1f925}\\x{1f92b}\\x{1f92d}\\x{1f9d0}\\x{1f913}\\x{1f608}\\x{1f47f}\\x{1f479}\\x{1f47a}\\x{1f480}\\x{2620}\\x{1f47b}\\x{1f47d}\\x{1f47e}\\x{1f916}\\x{1f4a9}\\x{1f63a}\\x{1f638}\\x{1f639}\\x{1f63b}\\x{1f63c}\\x{1f63d}\\x{1f640}\\x{1f63f}\\x{1f63e}\\x{1f648}\\x{1f649}\\x{1f64a}\\x{1f476}\\x{1f9d2}\\x{1f466}\\x{1f467}\\x{1f9d1}\\x{1f468}\\x{1f469}\\x{1f9d3}\\x{1f474}\\x{1f475}\\x{1f933}\\x{1f4aa}\\x{1f448}\\x{1f449}\\x{261d}\\x{1f446}\\x{1f595}\\x{1f447}\\x{270c}\\x{1f91e}\\x{1f596}\\x{1f918}\\x{1f919}\\x{1f590}\\x{270b}\\x{1f44c}\\x{1f44d}\\x{1f44e}\\x{270a}\\x{1f44a}\\x{1f91b}\\x{1f91c}\\x{1f91a}\\x{1f44b}\\x{1f91f}\\x{270d}\\x{1f44f}\\x{1f450}\\x{1f64c}\\x{1f932}\\x{1f64f}\\x{1f91d}\\x{1f485}\\x{1f442}\\x{1f443}\\x{1f463}\\x{1f440}\\x{1f441}\\x{1f9e0}\\x{1f445}\\x{1f444}\\x{1f48b}\\x{1f498}\\x{2764}\\x{1f493}\\x{1f494}\\x{1f495}\\x{1f496}\\x{1f497}\\x{1f499}\\x{1f49a}\\x{1f49b}\\x{1f9e1}\\x{1f49c}\\x{1f5a4}\\x{1f49d}\\x{1f49e}\\x{1f49f}\\x{2763}\\x{1f48c}\\x{1f4a4}\\x{1f4a2}\\x{1f4a3}\\x{1f4a5}\\x{1f4a6}\\x{1f4a8}\\x{1f4ab}\\x{1f4ac}\\x{1f5e8}\\x{1f5ef}\\x{1f4ad}\\x{1f934}\\x{1f478}\\x{1f472}\\x{1f9d5}\\x{1f9d4}\\x{1f935}\\x{1f470}\\x{1f930}\\x{1f931}\\x{1f47c}\\x{1f483}\\x{1f57a}\\x{1f6c0}\\x{1f6cc}\\x{1f574}\\x{1f5e3}\\x{1f464}\\x{1f465}\\x{1f46b}\\x{1f436}\\x{1f431}\\x{1f42d}\\x{1f439}\\x{1f430}\\x{1f98a}\\x{1f43b}\\x{1f43c}\\x{1f428}\\x{1f42f}\\x{1f981}\\x{1f42e}\\x{1f437}\\x{1f43d}\\x{1f438}\\x{1f435}\\x{1f648}\\x{1f649}\\x{1f64a}\\x{1f412}\\x{1f414}\\x{1f427}\\x{1f426}\\x{1f424}\\x{1f423}\\x{1f425}\\x{1f986}\\x{1f985}\\x{1f989}\\x{1f987}\\x{1f43a}\\x{1f417}\\x{1f434}\\x{1f984}\\x{1f41d}\\x{1f41b}\\x{1f98b}\\x{1f40c}\\x{1f41e}\\x{1f41c}\\x{1f99f}\\x{1f997}\\x{1f577}\\x{1f578}\\x{1f982}\\x{1f422}\\x{1f40d}\\x{1f98e}\\x{1f996}\\x{1f995}\\x{1f419}\\x{1f991}\\x{1f990}\\x{1f99e}\\x{1f980}\\x{1f421}\\x{1f420}\\x{1f41f}\\x{1f42c}\\x{1f433}\\x{1f40b}\\x{1f988}\\x{1f40a}\\x{1f405}\\x{1f406}\\x{1f993}\\x{1f98d}\\x{1f418}\\x{1f99b}\\x{1f98f}\\x{1f42a}\\x{1f42b}\\x{1f992}\\x{1f998}\\x{1f403}\\x{1f402}\\x{1f404}\\x{1f40e}\\x{1f416}\\x{1f40f}\\x{1f411}\\x{1f999}\\x{1f410}\\x{1f98c}\\x{1f415}\\x{1f429}\\x{1f408}\\x{1f413}\\x{1f983}\\x{1f99a}\\x{1f99c}\\x{1f9a2}\\x{1f54a}\\x{1f407}\\x{1f99d}\\x{1f9a1}\\x{1f401}\\x{1f400}\\x{1f43f}\\x{1f994}\\x{1f43e}\\x{1f409}\\x{1f432}\\x{1f335}\\x{1f384}\\x{1f332}\\x{1f333}\\x{1f334}\\x{1f331}\\x{1f33f}\\x{2618}\\x{1f340}\\x{1f38d}\\x{1f38b}\\x{1f343}\\x{1f342}\\x{1f341}\\x{1f344}\\x{1f41a}\\x{1f33e}\\x{1f490}\\x{1f337}\\x{1f339}\\x{1f940}\\x{1f33a}\\x{1f338}\\x{1f33c}\\x{1f33b}\\x{1f31e}\\x{1f31d}\\x{1f31b}\\x{1f31c}\\x{1f31a}\\x{1f315}\\x{1f316}\\x{1f317}\\x{1f318}\\x{1f311}\\x{1f312}\\x{1f313}\\x{1f314}\\x{1f319}\\x{1f30e}\\x{1f30d}\\x{1f30f}\\x{1f4ab}\\x{2b50}\\x{1f31f}\\x{2728}\\x{26a1}\\x{2604}\\x{1f4a5}\\x{1f525}\\x{1f32a}\\x{1f308}\\x{2600}\\x{1f324}\\x{26c5}\\x{1f325}\\x{2601}\\x{1f326}\\x{1f327}\\x{26c8}\\x{1f329}\\x{1f328}\\x{2744}\\x{2603}\\x{26c4}\\x{1f32c}\\x{1f4a8}\\x{1f4a7}\\x{1f4a6}\\x{2614}\\x{2602}\\x{1f30a}\\x{1f32b}\\x{1f34f}\\x{1f34e}\\x{1f350}\\x{1f34a}\\x{1f34b}\\x{1f34c}\\x{1f349}\\x{1f347}\\x{1f353}\\x{1f348}\\x{1f352}\\x{1f351}\\x{1f96d}\\x{1f34d}\\x{1f965}\\x{1f95d}\\x{1f345}\\x{1f346}\\x{1f951}\\x{1f966}\\x{1f96c}\\x{1f952}\\x{1f336}\\x{1f33d}\\x{1f955}\\x{1f954}\\x{1f360}\\x{1f950}\\x{1f96f}\\x{1f35e}\\x{1f956}\\x{1f968}\\x{1f9c0}\\x{1f95a}\\x{1f373}\\x{1f95e}\\x{1f953}\\x{1f969}\\x{1f357}\\x{1f356}\\x{1f9b4}\\x{1f32d}\\x{1f354}\\x{1f35f}\\x{1f355}\\x{1f96a}\\x{1f959}\\x{1f32e}\\x{1f32f}\\x{1f957}\\x{1f958}\\x{1f96b}\\x{1f35d}\\x{1f35c}\\x{1f372}\\x{1f35b}\\x{1f363}\\x{1f371}\\x{1f95f}\\x{1f364}\\x{1f359}\\x{1f35a}\\x{1f358}\\x{1f365}\\x{1f960}\\x{1f96e}\\x{1f362}\\x{1f361}\\x{1f367}\\x{1f368}\\x{1f366}\\x{1f967}\\x{1f9c1}\\x{1f370}\\x{1f382}\\x{1f36e}\\x{1f36d}\\x{1f36c}\\x{1f36b}\\x{1f37f}\\x{1f369}\\x{1f36a}\\x{1f330}\\x{1f95c}\\x{1f36f}\\x{1f95b}\\x{1f37c}\\x{2615}\\x{1f375}\\x{1f964}\\x{1f376}\\x{1f37a}\\x{1f37b}\\x{1f942}\\x{1f377}\\x{1f943}\\x{1f378}\\x{1f379}\\x{1f37e}\\x{1f944}\\x{1f374}\\x{1f37d}\\x{1f963}\\x{1f961}\\x{1f962}\\x{1f9c2}\\x{26bd}\\x{1f3c0}\\x{1f3c8}\\x{26be}\\x{1f94e}\\x{1f3be}\\x{1f3d0}\\x{1f3c9}\\x{1f94f}\\x{1f3b1}\\x{1f3d3}\\x{1f3f8}\\x{1f3d2}\\x{1f3d1}\\x{1f94d}\\x{1f3cf}\\x{1f945}\\x{26f3}\\x{1f3f9}\\x{1f3a3}\\x{1f94a}\\x{1f94b}\\x{1f3bd}\\x{1f6f9}\\x{1f6f7}\\x{26f8}\\x{1f94c}\\x{1f3bf}\\x{26f7}\\x{1f3c2}\\x{1f93a}\\x{1f3c7}\\x{1f3c6}\\x{1f947}\\x{1f948}\\x{1f949}\\x{1f3c5}\\x{1f396}\\x{1f3f5}\\x{1f397}\\x{1f3ab}\\x{1f39f}\\x{1f3aa}\\x{1f939}\\x{1f939}\\x{1f3ad}\\x{1f3a8}\\x{1f3ac}\\x{1f3a4}\\x{1f3a7}\\x{1f3bc}\\x{1f3b9}\\x{1f941}\\x{1f3b7}\\x{1f3ba}\\x{1f3b8}\\x{1f3bb}\\x{1f3b2}\\x{265f}\\x{1f3af}\\x{1f3b3}\\x{1f3ae}\\x{1f3b0}\\x{1f9e9}\\x{1f93a}\\x{1f3c7}\\x{26f7}\\x{1f3c2}\\x{1f3ce}\\x{1f3cd}\\x{1f939}\\x{1f385}\\x{1f936}\\x{1f697}\\x{1f695}\\x{1f699}\\x{1f68c}\\x{1f68e}\\x{1f3ce}\\x{1f693}\\x{1f691}\\x{1f692}\\x{1f690}\\x{1f69a}\\x{1f69b}\\x{1f69c}\\x{1f6f4}\\x{1f6b2}\\x{1f6f5}\\x{1f3cd}\\x{1f6a8}\\x{1f694}\\x{1f68d}\\x{1f698}\\x{1f696}\\x{1f6a1}\\x{1f6a0}\\x{1f69f}\\x{1f683}\\x{1f68b}\\x{1f69e}\\x{1f69d}\\x{1f684}\\x{1f685}\\x{1f688}\\x{1f682}\\x{1f686}\\x{1f687}\\x{1f68a}\\x{1f689}\\x{2708}\\x{1f6eb}\\x{1f6ec}\\x{1f6e9}\\x{1f4ba}\\x{1f6f0}\\x{1f680}\\x{1f6f8}\\x{1f681}\\x{1f6f6}\\x{26f5}\\x{1f6a4}\\x{1f6e5}\\x{1f6f3}\\x{26f4}\\x{1f6a2}\\x{2693}\\x{26fd}\\x{1f6a7}\\x{1f6a6}\\x{1f6a5}\\x{1f68f}\\x{1f5fa}\\x{1f5ff}\\x{1f5fd}\\x{1f5fc}\\x{1f3f0}\\x{1f3ef}\\x{1f3df}\\x{1f3a1}\\x{1f3a2}\\x{1f3a0}\\x{26f2}\\x{26f1}\\x{1f3d6}\\x{1f3dd}\\x{1f3dc}\\x{1f30b}\\x{26f0}\\x{1f3d4}\\x{1f5fb}\\x{1f3d5}\\x{26fa}\\x{1f3e0}\\x{1f3e1}\\x{1f3d8}\\x{1f3da}\\x{1f3d7}\\x{1f3ed}\\x{1f3e2}\\x{1f3ec}\\x{1f3e3}\\x{1f3e4}\\x{1f3e5}\\x{1f3e6}\\x{1f3e8}\\x{1f3ea}\\x{1f3eb}\\x{1f3e9}\\x{1f492}\\x{1f3db}\\x{26ea}\\x{1f54c}\\x{1f54d}\\x{1f54b}\\x{26e9}\\x{1f6e4}\\x{1f6e3}\\x{1f5fe}\\x{1f391}\\x{1f3de}\\x{1f305}\\x{1f304}\\x{1f320}\\x{1f387}\\x{1f386}\\x{1f307}\\x{1f306}\\x{1f3d9}\\x{1f303}\\x{1f30c}\\x{1f309}\\x{1f301}\\x{231a}\\x{1f4f1}\\x{1f4f2}\\x{1f4bb}\\x{2328}\\x{1f5a5}\\x{1f5a8}\\x{1f5b1}\\x{1f5b2}\\x{1f579}\\x{1f5dc}\\x{1f4bd}\\x{1f4be}\\x{1f4bf}\\x{1f4c0}\\x{1f4fc}\\x{1f4f7}\\x{1f4f8}\\x{1f4f9}\\x{1f3a5}\\x{1f4fd}\\x{1f39e}\\x{1f4de}\\x{260e}\\x{1f4df}\\x{1f4e0}\\x{1f4fa}\\x{1f4fb}\\x{1f399}\\x{1f39a}\\x{1f39b}\\x{1f9ed}\\x{23f1}\\x{23f2}\\x{23f0}\\x{1f570}\\x{231b}\\x{23f3}\\x{1f4e1}\\x{1f50b}\\x{1f50c}\\x{1f4a1}\\x{1f526}\\x{1f56f}\\x{1f9ef}\\x{1f6e2}\\x{1f4b8}\\x{1f4b5}\\x{1f4b4}\\x{1f4b6}\\x{1f4b7}\\x{1f4b0}\\x{1f4b3}\\x{1f48e}\\x{2696}\\x{1f9f0}\\x{1f527}\\x{1f528}\\x{2692}\\x{1f6e0}\\x{26cf}\\x{1f529}\\x{2699}\\x{1f9f1}\\x{26d3}\\x{1f9f2}\\x{1f52b}\\x{1f4a3}\\x{1f9e8}\\x{1f52a}\\x{1f5e1}\\x{2694}\\x{1f6e1}\\x{1f6ac}\\x{26b0}\\x{26b1}\\x{1f3fa}\\x{1f52e}\\x{1f4ff}\\x{1f9ff}\\x{1f488}\\x{2697}\\x{1f52d}\\x{1f52c}\\x{1f573}\\x{1f48a}\\x{1f489}\\x{1f9ec}\\x{1f9a0}\\x{1f9eb}\\x{1f9ea}\\x{1f321}\\x{1f9f9}\\x{1f9fa}\\x{1f9fb}\\x{1f6bd}\\x{1f6b0}\\x{1f6bf}\\x{1f6c1}\\x{1f6c0}\\x{1f9fc}\\x{1f9fd}\\x{1f9f4}\\x{1f6ce}\\x{1f511}\\x{1f5dd}\\x{1f6aa}\\x{1f6cb}\\x{1f6cf}\\x{1f6cc}\\x{1f9f8}\\x{1f5bc}\\x{1f6cd}\\x{1f6d2}\\x{1f381}\\x{1f388}\\x{1f38f}\\x{1f380}\\x{1f38a}\\x{1f389}\\x{1f38e}\\x{1f3ee}\\x{1f390}\\x{1f9e7}\\x{2709}\\x{1f4e9}\\x{1f4e8}\\x{1f4e7}\\x{1f48c}\\x{1f4e5}\\x{1f4e4}\\x{1f4e6}\\x{1f3f7}\\x{1f4ea}\\x{1f4eb}\\x{1f4ec}\\x{1f4ed}\\x{1f4ee}\\x{1f4ef}\\x{1f4dc}\\x{1f4c3}\\x{1f4c4}\\x{1f4d1}\\x{1f9fe}\\x{1f4ca}\\x{1f4c8}\\x{1f4c9}\\x{1f5d2}\\x{1f5d3}\\x{1f4c6}\\x{1f4c5}\\x{1f5d1}\\x{1f4c7}\\x{1f5c3}\\x{1f5f3}\\x{1f5c4}\\x{1f4cb}\\x{1f4c1}\\x{1f4c2}\\x{1f5c2}\\x{1f5de}\\x{1f4f0}\\x{1f4d3}\\x{1f4d4}\\x{1f4d2}\\x{1f4d5}\\x{1f4d7}\\x{1f4d8}\\x{1f4d9}\\x{1f4da}\\x{1f4d6}\\x{1f516}\\x{1f9f7}\\x{1f517}\\x{1f4ce}\\x{1f587}\\x{1f4d0}\\x{1f4cf}\\x{1f9ee}\\x{1f4cc}\\x{1f4cd}\\x{2702}\\x{1f58a}\\x{1f58b}\\x{2712}\\x{1f58c}\\x{1f58d}\\x{1f4dd}\\x{270f}\\x{1f50d}\\x{1f50e}\\x{1f50f}\\x{1f510}\\x{1f512}\\x{1f513}\\x{1f573}\\x{1f453}\\x{1f576}\\x{1f454}\\x{1f455}\\x{1f456}\\x{1f9e3}\\x{1f9e4}\\x{1f9e5}\\x{1f9e6}\\x{1f457}\\x{1f458}\\x{1f459}\\x{1f45a}\\x{1f45b}\\x{1f45c}\\x{1f45d}\\x{1f6cd}\\x{1f392}\\x{1f45e}\\x{1f45f}\\x{1f460}\\x{1f461}\\x{1f462}\\x{1f451}\\x{1f452}\\x{1f3a9}\\x{1f393}\\x{1f9e2}\\x{26d1}\\x{1f4ff}\\x{1f484}\\x{1f48d}\\x{1f48e}\\x{2764}\\x{1f9e1}\\x{1f49b}\\x{1f49a}\\x{1f499}\\x{1f49c}\\x{1f5a4}\\x{1f494}\\x{2763}\\x{1f495}\\x{1f49e}\\x{1f493}\\x{1f497}\\x{1f496}\\x{1f498}\\x{1f49d}\\x{1f49f}\\x{262e}\\x{271d}\\x{262a}\\x{1f549}\\x{2638}\\x{2721}\\x{1f52f}\\x{1f54e}\\x{262f}\\x{2626}\\x{1f6d0}\\x{26ce}\\x{2648}\\x{2649}\\x{264a}\\x{264b}\\x{264c}\\x{264d}\\x{264e}\\x{264f}\\x{2650}\\x{2651}\\x{2652}\\x{2653}\\x{1f194}\\x{269b}\\x{1f251}\\x{2622}\\x{2623}\\x{1f4f4}\\x{1f4f3}\\x{1f236}\\x{1f21a}\\x{1f238}\\x{1f23a}\\x{1f237}\\x{2734}\\x{1f19a}\\x{1f4ae}\\x{1f250}\\x{3299}\\x{3297}\\x{1f234}\\x{1f235}\\x{1f239}\\x{1f232}\\x{1f170}\\x{1f171}\\x{1f18e}\\x{1f191}\\x{1f17e}\\x{1f198}\\x{274c}\\x{2b55}\\x{1f6d1}\\x{26d4}\\x{1f4db}\\x{1f6ab}\\x{1f4af}\\x{1f4a2}\\x{2668}\\x{1f6b7}\\x{1f6af}\\x{1f6b3}\\x{1f6b1}\\x{1f51e}\\x{1f4f5}\\x{1f6ad}\\x{2757}\\x{2755}\\x{2753}\\x{2754}\\x{203c}\\x{2049}\\x{1f505}\\x{1f506}\\x{303d}\\x{26a0}\\x{1f6b8}\\x{1f531}\\x{269c}\\x{1f530}\\x{267b}\\x{2705}\\x{1f22f}\\x{1f4b9}\\x{2747}\\x{2733}\\x{274e}\\x{1f310}\\x{1f4a0}\\x{24c2}\\x{1f300}\\x{1f4a4}\\x{1f3e7}\\x{1f6be}\\x{267f}\\x{1f17f}\\x{1f233}\\x{1f202}\\x{1f6c2}\\x{1f6c3}\\x{1f6c4}\\x{1f6c5}\\x{1f6b9}\\x{1f6ba}\\x{1f6bc}\\x{1f6bb}\\x{1f6ae}\\x{1f3a6}\\x{1f4f6}\\x{1f201}\\x{1f523}\\x{2139}\\x{1f524}\\x{1f521}\\x{1f520}\\x{1f196}\\x{1f197}\\x{1f199}\\x{1f192}\\x{1f195}\\x{1f193}\\x{1f51f}\\x{1f522}\\x{23cf}\\x{25b6}\\x{23f8}\\x{23ef}\\x{23f9}\\x{23fa}\\x{23ed}\\x{23ee}\\x{23e9}\\x{23ea}\\x{23eb}\\x{23ec}\\x{25c0}\\x{1f53c}\\x{1f53d}\\x{27a1}\\x{2b05}\\x{2b06}\\x{2b07}\\x{2197}\\x{2198}\\x{2199}\\x{2196}\\x{2195}\\x{2194}\\x{21aa}\\x{21a9}\\x{2934}\\x{2935}\\x{1f500}\\x{1f501}\\x{1f502}\\x{1f504}\\x{1f503}\\x{1f3b5}\\x{1f3b6}\\x{2795}\\x{2796}\\x{2797}\\x{2716}\\x{267e}\\x{1f4b2}\\x{1f4b1}\\x{2122}\\x{a9}\\x{ae}\\x{1f51a}\\x{1f519}\\x{1f51b}\\x{1f51d}\\x{1f51c}\\x{3030}\\x{27b0}\\x{27bf}\\x{2714}\\x{2611}\\x{1f518}\\x{26aa}\\x{26ab}\\x{1f534}\\x{1f535}\\x{1f53a}\\x{1f53b}\\x{1f538}\\x{1f539}\\x{1f536}\\x{1f537}\\x{1f533}\\x{1f532}\\x{25aa}\\x{25ab}\\x{25fe}\\x{25fd}\\x{25fc}\\x{25fb}\\x{2b1b}\\x{2b1c}\\x{1f508}\\x{1f507}\\x{1f509}\\x{1f50a}\\x{1f514}\\x{1f515}\\x{1f4e3}\\x{1f4e2}\\x{1f4ac}\\x{1f4ad}\\x{1f5ef}\\x{2660}\\x{2663}\\x{2665}\\x{2666}\\x{1f0cf}\\x{1f3b4}\\x{1f004}\\x{1f550}\\x{1f551}\\x{1f552}\\x{1f553}\\x{1f554}\\x{1f555}\\x{1f556}\\x{1f557}\\x{1f558}\\x{1f559}\\x{1f55a}\\x{1f55b}\\x{1f55c}\\x{1f55d}\\x{1f55e}\\x{1f55f}\\x{1f560}\\x{1f561}\\x{1f562}\\x{1f563}\\x{1f564}\\x{1f565}\\x{1f566}\\x{1f567}\\x{1f3f3}\\x{1f3f4}\\x{1f3c1}\\x{1f6a9}\\x{1f38c}\\x{1f3e0}\\x{1f3e1}\\x{1f3eb}\\x{1f943}\\x{1f942}\\x{1f91d}\\x{1f929}\\x{1f973}\\x{1f924}\\x{1f3e2}\\x{0031}\\x{0032}\\x{0033}\\x{0034}\\x{0035}\\x{0036}\\x{0037}\\x{0038}\\x{0039}\\x{0030}\\x{1f51f}\\x{1f522}\\x{0023}\\x{1f523}\\x{2b06}\\x{2b07}\\x{2b05}\\x{27a1}\\x{1f520}\\x{1f521}\\x{1f524}\\x{2197}\\x{2196}\\x{2198}\\x{2199}\\x{2194}\\x{2195}\\x{1f504}\\x{25c0}\\x{25b6}\\x{1f53c}\\x{1f53d}\\x{21a9}\\x{21aa}\\x{2139}\\x{23ea}\\x{23e9}\\x{23eb}\\x{23ec}\\x{2935}\\x{2934}\\x{1f197}\\x{1f500}\\x{1f501}\\x{1f502}\\x{1f195}\\x{1f199}\\x{1f192}\\x{1f193}\\x{1f196}\\x{1f4f6}\\x{1f3a6}\\x{1f201}\\x{1f22f}\\x{1f233}\\x{1f235}\\x{1f234}\\x{1f232}\\x{1f250}\\x{1f239}\\x{1f23a}\\x{1f236}\\x{1f21a}\\x{1f6bb}\\x{1f6b9}\\x{1f6ba}\\x{1f6bc}\\x{1f6be}\\x{1f6b0}\\x{1f6ae}\\x{1f17f}\\x{267f}\\x{1f6ad}\\x{1f237}\\x{1f238}\\x{1f202}\\x{24c2}\\x{1f6c2}\\x{1f6c4}\\x{1f6c5}\\x{1f6c3}\\x{1f251}\\x{3299}\\x{3297}\\x{1f191}\\x{1f198}\\x{1f194}\\x{1f6ab}\\x{1f51e}\\x{1f4f5}\\x{1f6af}\\x{1f6b1}\\x{1f6b3}\\x{1f6b7}\\x{1f6b8}\\x{26d4}\\x{2733}\\x{2747}\\x{274e}\\x{2705}\\x{2734}\\x{1f49f}\\x{1f19a}\\x{1f4f3}\\x{1f4f4}\\x{1f170}\\x{1f171}\\x{1f18e}\\x{1f17e}\\x{1f4a0}\\x{27bf}\\x{267b}\\x{2648}\\x{2649}\\x{264a}\\x{264b}\\x{264c}\\x{264d}\\x{264e}\\x{264f}\\x{2650}\\x{2651}\\x{2652}\\x{2653}\\x{26ce}\\x{1f52f}\\x{1f3e7}\\x{1f4b9}\\x{1f4b2}\\x{1f4b1}\\x{00a9}\\x{00ae}\\x{2122}\\x{274c}\\x{203c}\\x{2049}\\x{2757}\\x{2753}\\x{2755}\\x{2754}\\x{2b55}\\x{1f51d}\\x{1f51a}\\x{1f519}\\x{1f51b}\\x{1f51c}\\x{1f503}\\x{1f55b}\\x{1f567}\\x{1f550}\\x{1f55c}\\x{1f551}\\x{1f55d}\\x{1f552}\\x{1f55e}\\x{1f553}\\x{1f55f}\\x{1f554}\\x{1f560}\\x{1f555}\\x{1f556}\\x{1f557}\\x{1f558}\\x{1f559}\\x{1f55a}\\x{1f561}\\x{1f562}\\x{1f563}\\x{1f564}\\x{1f565}\\x{1f566}\\x{2716}\\x{2795}\\x{2796}\\x{2797}\\x{2660}\\x{2665}\\x{2663}\\x{2666}\\x{1f4ae}\\x{1f4af}\\x{2714}\\x{2611}\\x{1f518}\\x{1f517}\\x{27b0}\\x{3030}\\x{303d}\\x{1f531}\\x{25fc}\\x{25fb}\\x{25fe}\\x{25fd}\\x{25aa}\\x{25ab}\\x{1f53a}\\x{1f532}\\x{1f533}\\x{26ab}\\x{26aa}\\x{1f534}\\x{1f535}\\x{1f53b}\\x{2b1c}\\x{2b1b}\\x{1f536}\\x{1f537}\\x{1f538}\\x{1f539}\\x{1f3e3}\\x{1f3e5}\\x{1f3e6}\\x{1f3ea}\\x{1f3e9}\\x{1f3e8}\\x{1f492}\\x{26ea}\\x{1f3ec}\\x{1f3e4}\\x{1f307}\\x{1f306}\\x{1f3ef}\\x{1f3f0}\\x{26fa}\\x{1f3ed}\\x{1f5fc}\\x{1f5fe}\\x{1f5fb}\\x{1f304}\\x{1f305}\\x{1f303}\\x{1f5fd}\\x{1f309}\\x{1f3a0}\\x{1f3a1}\\x{26f2}\\x{1f3a2}\\x{1f6a2}\\x{26f5}\\x{1f6a4}\\x{1f6a3}\\x{2693}\\x{1f680}\\x{2708}\\x{1f4ba}\\x{1f681}\\x{1f682}\\x{1f68a}\\x{1f689}\\x{1f69e}\\x{1f686}\\x{1f684}\\x{1f685}\\x{1f688}\\x{1f687}\\x{1f69d}\\x{1f683}\\x{1f68b}\\x{1f68e}\\x{1f68c}\\x{1f68d}\\x{1f699}\\x{1f698}\\x{1f697}\\x{1f695}\\x{1f696}\\x{1f69b}\\x{1f69a}\\x{1f6a8}\\x{1f693}\\x{1f694}\\x{1f692}\\x{1f691}\\x{1f690}\\x{1f6b2}\\x{1f6a1}\\x{1f69f}\\x{1f6a0}\\x{1f69c}\\x{1f488}\\x{1f68f}\\x{1f3ab}\\x{1f6a6}\\x{1f6a5}\\x{26a0}\\x{1f6a7}\\x{1f530}\\x{26fd}\\x{1f3ee}\\x{1f3b0}\\x{2668}\\x{1f5ff}\\x{1f3aa}\\x{1f3ad}\\x{1f4cd}\\x{1f6a9}\\x{1f436}\\x{1f43a}\\x{1f431}\\x{1f38d}\\x{1f49d}\\x{1f38e}\\x{1f392}\\x{1f393}\\x{1f38f}\\x{1f386}\\x{1f387}\\x{1f390}\\x{1f391}\\x{1f383}\\x{1f47b}\\x{1f385}\\x{1f384}\\x{1f381}\\x{1f38b}\\x{1f389}\\x{1f38a}\\x{1f388}\\x{1f38c}\\x{1f52e}\\x{1f3a5}\\x{1f4f7}\\x{1f4f9}\\x{1f4fc}\\x{1f4bf}\\x{1f4c0}\\x{1f4bd}\\x{1f4be}\\x{1f4bb}\\x{1f4f1}\\x{260e}\\x{1f4de}\\x{1f4df}\\x{1f4e0}\\x{1f4e1}\\x{1f4fa}\\x{1f4fb}\\x{1f50a}\\x{1f509}\\x{1f508}\\x{1f507}\\x{1f514}\\x{1f515}\\x{1f4e3}\\x{1f4e2}\\x{23f3}\\x{231b}\\x{23f0}\\x{231a}\\x{1f513}\\x{1f512}\\x{1f50f}\\x{1f510}\\x{1f511}\\x{1f50e}\\x{1f4a1}\\x{1f526}\\x{1f506}\\x{1f505}\\x{1f50c}\\x{1f50b}\\x{1f50d}\\x{1f6c0}\\x{1f6c1}\\x{1f6bf}\\x{1f6bd}\\x{1f527}\\x{1f529}\\x{1f528}\\x{1f6aa}\\x{1f6ac}\\x{1f4a3}\\x{1f52b}\\x{1f52a}\\x{1f48a}\\x{1f489}\\x{1f4b0}\\x{1f4b4}\\x{1f4b5}\\x{1f4b7}\\x{1f4b6}\\x{1f4b3}\\x{1f4b8}\\x{1f4f2}\\x{1f4e7}\\x{1f4e5}\\x{1f4e4}\\x{2709}\\x{1f4e9}\\x{1f4e8}\\x{1f4ef}\\x{1f4eb}\\x{1f4ea}\\x{1f4ec}\\x{1f4ed}\\x{1f4ee}\\x{1f4e6}\\x{1f4dd}\\x{1f4c4}\\x{1f4c3}\\x{1f4d1}\\x{1f4ca}\\x{1f4c8}\\x{1f4c9}\\x{1f4dc}\\x{1f4cb}\\x{1f4c5}\\x{1f4c6}\\x{1f4c7}\\x{1f4c1}\\x{1f4c2}\\x{2702}\\x{1f4cc}\\x{1f4ce}\\x{2712}\\x{270f}\\x{1f4cf}\\x{1f4d0}\\x{1f4d5}\\x{1f4d7}\\x{1f4d8}\\x{1f4d9}\\x{1f4d3}\\x{1f4d4}\\x{1f4d2}\\x{1f4da}\\x{1f4d6}\\x{1f516}\\x{1f4db}\\x{1f52c}\\x{1f52d}\\x{1f4f0}\\x{1f3a8}\\x{1f3ac}\\x{1f3a4}\\x{1f3a7}\\x{1f3bc}\\x{1f3b5}\\x{1f3b6}\\x{1f3b9}\\x{1f3bb}\\x{1f3ba}\\x{1f3b7}\\x{1f3b8}\\x{1f47e}\\x{1f3ae}\\x{1f0cf}\\x{1f3b4}\\x{1f004}\\x{1f3b2}\\x{1f3af}\\x{1f3c8}\\x{1f3c0}\\x{26bd}\\x{26be}\\x{1f3be}\\x{1f3b1}\\x{1f3c9}\\x{1f3b3}\\x{26f3}\\x{1f6b5}\\x{1f6b4}\\x{1f3c1}\\x{1f3c7}\\x{1f3c6}\\x{1f3bf}\\x{1f3c2}\\x{1f3ca}\\x{1f3c4}\\x{1f3a3}\\x{2615}\\x{1f375}\\x{1f376}\\x{1f37c}\\x{1f37a}\\x{1f37b}\\x{1f378}\\x{1f379}\\x{1f377}\\x{1f374}\\x{1f355}\\x{1f354}\\x{1f35f}\\x{1f357}\\x{1f356}\\x{1f35d}\\x{1f35b}\\x{1f364}\\x{1f371}\\x{1f363}\\x{1f365}\\x{1f359}\\x{1f358}\\x{1f35a}\\x{1f35c}\\x{1f372}\\x{1f362}\\x{1f361}\\x{1f373}\\x{1f35e}\\x{1f369}\\x{1f36e}\\x{1f366}\\x{1f368}\\x{1f367}\\x{1f382}\\x{1f370}\\x{1f36a}\\x{1f36b}\\x{1f36c}\\x{1f36d}\\x{1f36f}\\x{1f34e}\\x{1f34f}\\x{1f34a}\\x{1f34b}\\x{1f352}\\x{1f347}\\x{1f349}\\x{1f353}\\x{1f351}\\x{1f348}\\x{1f34c}\\x{1f350}\\x{1f34d}\\x{1f360}\\x{1f346}\\x{1f345}\\x{1f33d}\\x{1f42d}\\x{1f439}\\x{1f430}\\x{1f438}\\x{1f42f}\\x{1f428}\\x{1f43b}\\x{1f437}\\x{1f43d}\\x{1f42e}\\x{1f417}\\x{1f435}\\x{1f412}\\x{1f434}\\x{1f411}\\x{1f418}\\x{1f43c}\\x{1f427}\\x{1f426}\\x{1f424}\\x{1f425}\\x{1f423}\\x{1f414}\\x{1f40d}\\x{1f422}\\x{1f41b}\\x{1f41d}\\x{1f41c}\\x{1f41e}\\x{1f40c}\\x{1f419}\\x{1f41a}\\x{1f420}\\x{1f41f}\\x{1f42c}\\x{1f433}\\x{1f40b}\\x{1f404}\\x{1f40f}\\x{1f400}\\x{1f403}\\x{1f405}\\x{1f407}\\x{1f409}\\x{1f40e}\\x{1f410}\\x{1f413}\\x{1f415}\\x{1f416}\\x{1f401}\\x{1f402}\\x{1f432}\\x{1f421}\\x{1f40a}\\x{1f42b}\\x{1f42a}\\x{1f406}\\x{1f408}\\x{1f429}\\x{1f43e}\\x{1f490}\\x{1f338}\\x{1f337}\\x{1f340}\\x{1f339}\\x{1f33b}\\x{1f33a}\\x{1f341}\\x{1f343}\\x{1f342}\\x{1f33f}\\x{1f33e}\\x{1f344}\\x{1f335}\\x{1f334}\\x{1f332}\\x{1f333}\\x{1f330}\\x{1f331}\\x{1f33c}\\x{1f310}\\x{1f31e}\\x{1f31d}\\x{1f31a}\\x{1f311}\\x{1f312}\\x{1f313}\\x{1f314}\\x{1f315}\\x{1f316}\\x{1f317}\\x{1f318}\\x{1f31c}\\x{1f31b}\\x{1f319}\\x{1f30d}\\x{1f30e}\\x{1f30f}\\x{1f30b}\\x{1f30c}\\x{1f320}\\x{2b50}\\x{2600}\\x{26c5}\\x{2601}\\x{26a1}\\x{2614}\\x{2744}\\x{26c4}\\x{1f300}\\x{1f301}\\x{1f308}\\x{1f30a}\\x{1f604}\\x{1f603}\\x{1f600}\\x{1f60a}\\x{263a}\\x{1f609}\\x{1f60d}\\x{1f618}\\x{1f61a}\\x{1f617}\\x{1f619}\\x{1f61c}\\x{1f61d}\\x{1f61b}\\x{1f633}\\x{1f601}\\x{1f614}\\x{1f60c}\\x{1f612}\\x{1f61e}\\x{1f623}\\x{1f622}\\x{1f602}\\x{1f62d}\\x{1f62a}\\x{1f625}\\x{1f630}\\x{1f605}\\x{1f613}\\x{1f629}\\x{1f62b}\\x{1f628}\\x{1f631}\\x{1f620}\\x{1f621}\\x{1f624}\\x{1f616}\\x{1f606}\\x{1f60b}\\x{1f637}\\x{1f60e}\\x{1f634}\\x{1f635}\\x{1f632}\\x{1f61f}\\x{1f626}\\x{1f627}\\x{1f608}\\x{1f47f}\\x{1f62e}\\x{1f62c}\\x{1f610}\\x{1f615}\\x{1f62f}\\x{1f636}\\x{1f607}\\x{1f60f}\\x{1f611}\\x{1f472}\\x{1f473}\\x{1f46e}\\x{1f477}\\x{1f482}\\x{1f476}\\x{1f466}\\x{1f467}\\x{1f468}\\x{1f469}\\x{1f474}\\x{1f475}\\x{1f471}\\x{1f47c}\\x{1f478}\\x{1f63a}\\x{1f638}\\x{1f63b}\\x{1f63d}\\x{1f63c}\\x{1f640}\\x{1f63f}\\x{1f639}\\x{1f63e}\\x{1f479}\\x{1f47a}\\x{1f648}\\x{1f649}\\x{1f64a}\\x{1f480}\\x{1f47d}\\x{1f4a9}\\x{1f525}\\x{2728}\\x{1f31f}\\x{1f4ab}\\x{1f4a5}\\x{1f4a2}\\x{1f4a6}\\x{1f4a7}\\x{1f4a4}\\x{1f4a8}\\x{1f442}\\x{1f440}\\x{1f443}\\x{1f445}\\x{1f444}\\x{1f44d}\\x{1f44e}\\x{1f44c}\\x{1f44a}\\x{270a}\\x{270c}\\x{1f44b}\\x{270b}\\x{1f450}\\x{1f446}\\x{1f447}\\x{1f449}\\x{1f448}\\x{1f64c}\\x{1f64f}\\x{261d}\\x{1f44f}\\x{1f4aa}\\x{1f6b6}\\x{1f3c3}\\x{1f483}\\x{1f46b}\\x{1f46a}\\x{1f46c}\\x{1f46d}\\x{1f48f}\\x{1f491}\\x{1f46f}\\x{1f646}\\x{1f645}\\x{1f481}\\x{1f64b}\\x{1f486}\\x{1f487}\\x{1f485}\\x{1f470}\\x{1f64e}\\x{1f64d}\\x{1f647}\\x{1f3a9}\\x{1f451}\\x{1f452}\\x{1f45f}\\x{1f45e}\\x{1f461}\\x{1f460}\\x{1f462}\\x{1f455}\\x{1f454}\\x{1f45a}\\x{1f457}\\x{1f3bd}\\x{1f456}\\x{1f458}\\x{1f459}\\x{1f4bc}\\x{1f45c}\\x{1f45d}\\x{1f45b}\\x{1f453}\\x{1f380}\\x{1f302}\\x{1f484}\\x{1f49b}\\x{1f499}\\x{1f49c}\\x{1f49a}\\x{2764}\\x{1f494}\\x{1f497}\\x{1f493}\\x{1f495}\\x{1f496}\\x{1f49e}\\x{1f498}\\x{1f48c}\\x{1f48b}\\x{1f48d}\\x{1f48e}\\x{1f464}\\x{1f465}\\x{1f4ac}\\x{1f463}\\x{1f4ad}\\x{2712}\\x{2714}\\x{2716}\\x{271d}\\x{2721}\\x{2728}\\x{2733}\\x{2734}\\x{2744}\\x{2747}\\x{274c}\\x{274e}\\x{2753}-\\x{2755}\\x{2757}\\x{2763}\\x{2764}\\x{2795}-\\x{2797}\\x{27a1}\\x{27b0}\\x{27bf}\\x{2934}\\x{2935}\\x{2b05}-\\x{2b07}\\x{2b1b}\\x{2b1c}\\x{2b50}\\x{2b55}\\x{3030}\\x{303d}\\x{1f004}\\x{1f0cf}\\x{1f170}\\x{1f171}\\x{1f17e}\\x{1f17f}\\x{1f18e}\\x{1f191}-\\x{1f19a}\\x{1f201}\\x{1f202}\\x{1f21a}\\x{1f22f}\\x{1f232}-\\x{1f23a}\\x{1f250}\\x{1f251}\\x{1f300}-\\x{1f321}\\x{1f324}-\\x{1f393}\\x{1f396}\\x{1f397}\\x{1f399}-\\x{1f39b}\\x{1f39e}-\\x{1f3f0}\\x{1f3f3}-\\x{1f3f5}\\x{1f3f7}-\\x{1f4fd}\\x{1f4ff}-\\x{1f53d}\\x{1f549}-\\x{1f54e}\\x{1f550}-\\x{1f567}\\x{1f56f}\\x{1f570}\\x{1f573}-\\x{1f579}\\x{1f587}\\x{1f58a}-\\x{1f58d}\\x{1f590}\\x{1f595}\\x{1f596}\\x{1f5a5}\\x{1f5a8}\\x{1f5b1}\\x{1f5b2}\\x{1f5bc}\\x{1f5c2}-\\x{1f5c4}\\x{1f5d1}-\\x{1f5d3}\\x{1f5dc}-\\x{1f5de}\\x{1f5e1}\\x{1f5e3}\\x{1f5ef}\\x{1f5f3}\\x{1f5fa}-\\x{1f64f}\\x{1f680}-\\x{1f6c5}\\x{1f6cb}-\\x{1f6d0}\\x{1f6e0}-\\x{1f6e5}\\x{1f6e9}\\x{1f6eb}\\x{1f6ec}\\x{1f6f0}\\x{1f6f3}\\x{1f910}-\\x{1f918}\\x{1f980}-\\x{1f984}\\x{1f9c0}\\x{3297}\\x{3299}\\x{a9}\\x{ae}\\x{203c}\\x{2049}\\x{2122}\\x{2139}\\x{2194}-\\x{2199}\\x{21a9}\\x{21aa}\\x{231a}\\x{231b}\\x{2328}\\x{2388}\\x{23cf}\\x{23e9}-\\x{23f3}\\x{23f8}-\\x{23fa}\\x{24c2}\\x{25aa}\\x{25ab}\\x{25b6}\\x{25c0}\\x{25fb}-\\x{25fe}\\x{2600}-\\x{2604}\\x{260e}\\x{2611}\\x{2614}\\x{2615}\\x{2618}\\x{261d}\\x{2620}\\x{2622}\\x{2623}\\x{2626}\\x{262a}\\x{262e}\\x{262f}\\x{2638}-\\x{263a}\\x{2648}-\\x{2653}\\x{2660}\\x{2663}\\x{2665}\\x{2666}\\x{2668}\\x{267b}\\x{267f}\\x{2692}-\\x{2694}\\x{2696}\\x{2697}\\x{2699}\\x{269b}\\x{269c}\\x{26a0}\\x{26a1}\\x{26aa}\\x{26ab}\\x{26b0}\\x{26b1}\\x{26bd}\\x{26be}\\x{26c4}\\x{26c5}\\x{26c8}\\x{26ce}\\x{26cf}\\x{26d1}\\x{26d3}\\x{26d4}\\x{26e9}\\x{26ea}\\x{26f0}-\\x{26f5}\\x{26f7}-\\x{26fa}\\x{26fd}\\x{2702}\\x{2705}\\x{2708}-\\x{270d}\\x{270f}]/u";
        function entity($matches) { return '&#'.hexdec(bin2hex(mb_convert_encoding("$matches[0]", 'UTF-32', 'UTF-8'))).';'; }
        return preg_replace_callback($emoji_pattern, 'entity', $str);
    }

    private function deleteAllMessage($dialog_id, $user_id){
        if(empty(intval($dialog_id)) || empty(intval($user_id)))
            return false;

        $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_USER_ID" => $user_id)
        ));
        if($arData = $rsData->Fetch()){
            $entity_data_class::update($arData['ID'], array(
                "UF_DELETE_DATE" => date('d.m.Y H:i:s'),
            ));
            return true;
        }
        return false;
    }

    private function readMessage($dialog_id, $user_id){
        if(empty(intval($dialog_id)) || empty(intval($user_id)))
            return false;

        $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_DIALOG_ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_USER_ID" => $user_id)
        ));
        if($arData = $rsData->Fetch()){
            $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID"),
                "order" => array("ID" => "ASC"),
                "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_USER_ID" => $user_id, "UF_STATUS" => 9)
            ));
            while($arData = $rsData->Fetch()){
                $entity_data_class::update($arData['ID'], array(
                    "UF_STATUS" => 11,
                ));
            }
            return true;
        }
        return false;
    }

    private function generateStr($length = 8){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    private function uploadMessages($dialog_id, $user_id, $date){
        if(empty(intval($dialog_id)) || empty(intval($user_id)) || empty($date))
            return false;
    
        $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_DATE_CREATE", "UF_AUTHOR_ID", "UF_MESSAGE_TEXT", "UF_IS_SYSTEM"),
            "order" => array("ID" => "DESC"),
            "filter" => array("UF_DIALOG_ID" => $dialog_id, ">UF_DATE_CREATE" => $date)
        ));
        
        while($arData = $rsData->Fetch()){
            $arMessageIDs[] = $arData["ID"];
            $arMessage[$arData["ID"]] = array("ID" => $arData["ID"], "DATE_CREATE" => $arData["UF_DATE_CREATE"], "AUTHOR_ID" => $arData["UF_AUTHOR_ID"], "MESSAGE_TEXT" => $arData["UF_MESSAGE_TEXT"], "IS_SYSTEM" => $arData["UF_IS_SYSTEM"]);
            $arUsers[$arData["ID"]] = $arData["UF_AUTHOR_ID"];
        }

        if($arMessage){
            ksort($arMessage);

            $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_STATUS", 'UF_MESSAGE_ID'),
                "order" => array("ID" => "ASC"),
                "filter" => array("UF_MESSAGE_ID" => $arMessageIDs, "UF_USER_ID" => $user_id, "!UF_STATUS" => 13)
            ));
            while($arData = $rsData->Fetch()){
                $arMessage[$arData['UF_MESSAGE_ID']]['STATUS'] = $arData['UF_STATUS'];
                if($arData['UF_STATUS'] == 10){
                    $arStatus[] = $arData['UF_MESSAGE_ID'];
                    $arMessage[$arData['UF_MESSAGE_ID']]['STATUS'] = 9;
                }
            }
            $rsData = $entity_data_class::getList(array(
                "select" => array('UF_MESSAGE_ID'),
                "order" => array("ID" => "ASC"),
                "filter" => array("UF_MESSAGE_ID" => $arStatus, "!UF_USER_ID" => $user_id, "UF_STATUS" => 11)
            ));
            while($arData = $rsData->Fetch()){
                $arMessage[$arData['UF_MESSAGE_ID']]['STATUS'] = 11;
            }
            

            $arUsers = array_unique($arUsers);

            $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => implode(" | ", $arUsers)), array("FIELDS" => array("ID", "NAME", "LAST_NAME", "PERSONAL_PHOTO")));
            while($arRes = $rsUser -> GetNext()){
                $arUser[$arRes['ID']]['AUTHOR_NAME'] = $arRes['NAME'];
                $arUser[$arRes['ID']]['AUTHOR_LAST_NAME'] = $arRes['LAST_NAME'];
                $arUser[$arRes['ID']]['AUTHOR_PERSONAL_PHOTO'] = $arRes['PERSONAL_PHOTO'];
            }

            foreach ($arMessage as $key => &$value){
                if(!isset($value['STATUS'])){
                    unset($arMessage[$key]);
                }elseif(isset($arUser[$value['AUTHOR_ID']])){
                    $value = array_merge($value, $arUser[$value['AUTHOR_ID']]);
                }
            }

            $entity_data_class = self::GetEntityDataClass(ATTACHMENTS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID", "UF_TYPE", "UF_LINK", "UF_IMAGE", "UF_MESSAGE_ID"),
                "order" => array("ID" => "ASC"),
                "filter" => array("UF_MESSAGE_ID" => $arMessageIDs)
            ));
            while($arData = $rsData->Fetch()){
                $arMessage[$arData['UF_MESSAGE_ID']]['ATTACHMENTS'] = array("ID" => $arData['ID'], "TYPE" => $arData['UF_TYPE'], "LINK" => $arData['UF_LINK'], "IMAGE" => $arData['UF_IMAGE']);
            }
        }
        $this -> arResult['MESSAGE_IDS'] = $arMessageIDs;
        return $arMessage;
    }

    public function getDiscussion($dialog_id, $user_id){
        if(empty(intval($dialog_id)) || empty(intval($user_id)))
            return false;

        $arDiscussion = false;

        $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_USER_ID"),
            "order" => array("UF_USER_ID" => "DESC"), 
            "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_STATUS" => 4)
        ));
        while($arUser = $rsData -> fetch()){
            $arUserIDs[] = $arUser["UF_USER_ID"];
        }
        $arUserIDs = array_unique($arUserIDs);
        $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => implode(" | ", $arUserIDs)), array("FIELDS" => array("ID", "NAME", "LAST_NAME", "PERSONAL_PHOTO")));
        while($arRes = $rsUser -> GetNext())
            $arDiscussion['USERS'][] = $arRes;

        $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_NAME", "UF_AUTHOR_ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_DIALOG_ID" => $dialog_id)
        ));
        if($arData = $rsData->Fetch()){
            global $DISCUSSION_ID;
            $DISCUSSION_ID = $arData['ID'];
            $arDiscussion["ID"] = $arData['ID'];
            $arDiscussion["NAME"] = $arData['UF_NAME'];

            self::getBlackList($user_id, $arData['UF_AUTHOR_ID']);
            if($arData['UF_AUTHOR_ID'] == $user_id)
                $this -> arResult['DISCUSSION_IS_AUTHOR'] = "Y";

            if(empty($arDiscussion["NAME"])){
                $arDiscussion["NAME"] = "";
                foreach($arDiscussion['USERS'] as $key => $user)
                    if($key < 3)
                        $arDiscussion["NAME"] .= $user['NAME'] . ", ";
                    else{
                        $arDiscussion["NAME"] = substr($arDiscussion["NAME"], 0, -2);
                        $arDiscussion["NAME"] .= '.....';
                        break;
                    }
                $arDiscussion["NAME"] = substr($arDiscussion["NAME"], 0, -2);
            }
        }else{
            unset($GLOBALS['DISCUSSION_ID']);
            self::getBlackList($user_id, $arDiscussion['USERS']);
            $arDiscussion['NAME'] = $arDiscussion['USERS'][0]['LAST_NAME'] . " " . $arDiscussion['USERS'][0]['NAME'];
        }

        return $arDiscussion;

    }

    public function executeComponent()
    {

        global $USER;
        $user_id = $USER -> GetID();
        $dialog_id = $this -> arParams['DIALOG_ID'];

        if(!empty($_FILES) && $_REQUEST['action'] == "addFile"){
            $data = unserialize(file_get_contents('request.ddd'));
            unlink('request.ddd');
            if($_REQUEST['REQUEST_ID'] == $data['REQUEST_ID'])
                $arData = $data['data'];
        }

        if(CModule::IncludeModule("highloadblock")){
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';

            if(!empty(($this->request->get('nav-message'))))
                $this->arResult["PAGE"] = intval(substr($_REQUEST['nav-message'], 5));
            else
                $this->arResult["PAGE"] = 1;

            if($this->checkSession && $this->isRequestViaAjax){
                if($this->request->get('action') == "addMessage" || $this->request->get('action') == "addFile"){
                    if($_REQUEST['data']['ADD_FILE'] == "Y"){
                        $_REQUEST['REQUEST_ID'] = self::generateStr(5);
                        file_put_contents('request.ddd', serialize($_REQUEST));
                        echo json_encode(array("STATUS" => "SUCCESS", "REQUEST_ID" => $_REQUEST['REQUEST_ID']));
                        die();
                    }
                    $this->arResult["TYPE"] = "NEW_MESSAGE";
                    if($this->request->get('action') == "addMessage")
                        $arData = $_REQUEST['data'];
                    $this->arResult["NEW_MESSAGE"] = $this->newMessage($dialog_id, $user_id, $arData);
                }elseif($this->request->get('action') == "deleteAllMessages"){
                    $this->arResult["TYPE"] = "EMPTY_MESSAGE";
                    $this->deleteAllMessage($dialog_id, $user_id);
                }elseif($this->request->get('action') == "readMessage"){
                    if($this->readMessage($dialog_id, $user_id))
                    echo json_encode(array("STATUS" => "SUCCESS"));
                    die();
                }elseif($this->request->get('action') == "uploadMessages"){
                    $this->arResult["PAGE"] = 2;
                    $this->arResult["MESSAGES"] = $this->uploadMessages($dialog_id, $user_id, $_REQUEST['date']);
                    $this->arResult["USER_ID"] = $user_id;
                    if(!empty($this->arResult["MESSAGES"])){
                        ob_start();
                        $this->includeComponentTemplate();
                        $html = ob_get_contents();
                        ob_end_clean();
                        echo json_encode(array("STATUS" => "SUCCESS", "DATE" => date("d.m.Y H:i:s"), "MESSAGES" => $html, "MESSAGE_IDS" => $this -> arResult['MESSAGE_IDS']));
                    }else{
                        echo json_encode(array("STATUS" => "EMPTY"));
                    }
                    die();
                }elseif($this->request->get('action') == "loadMoreMessages"){
                    $this->arResult["USER_ID"] = $user_id;
                    $this->arResult["MESSAGES"] = $this -> getMessages($user_id, $dialog_id);
                }
            }else{
                $this->arResult["USER_ID"] = $user_id;
                $this->arResult["MESSAGES"] = $this -> getMessages($user_id, $dialog_id);
                $this->arResult["DISCUSSION"] = $this -> getDiscussion($dialog_id, $user_id);
            }
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>