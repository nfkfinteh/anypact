<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CDemoSqr extends CBitrixComponent
{
    //Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent
    //и применяет к ним функцию htmlspecialcharsex. В данном случае такая обработка избыточна.
    //Переопределяем.    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "DISCUSSION_ID" => intval($arParams["DISCUSSION_ID"]),
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

    private function getDiscussion($discussion_id, $current_user){
        if(empty(intval($discussion_id)) || empty(intval($current_user)))
            return false;

        $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $discussion_id)
        ));
        if($arDiscussion = $rsData->Fetch()){
            $this -> arResult['DIALOG_ID'] = $arDiscussion['UF_DIALOG_ID'];
            $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_USER_ID"),
                "order" => array("ID" => "ASC"),
                "filter" => array("UF_DIALOG_ID" => $arDiscussion['UF_DIALOG_ID'], "UF_STATUS" => DIALOGUSERSTATUS_I)
            ));
            while($arData = $rsData->Fetch()){
                $arUser[] = $arData['UF_USER_ID'];
            }
            $arUser = array_unique($arUser);
            if($current_user == $arDiscussion['UF_AUTHOR_ID']){
                if(in_array($arDiscussion['UF_AVATAR'], $arUser))
                    unset($arUser[array_search($arDiscussion['UF_AVATAR'], $arUser)]);
            }else{
                $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => implode(" | ", $arUser)), array("FIELDS" => array("ID", "NAME", "LAST_NAME", "SECOND_NAME", "PERSONAL_PHOTO")));
                while($arRes = $rsUser -> GetNext())
                    $arUsers[$arRes['ID']] = $arRes;
                $arUser = $arUsers;
                if(empty($arDiscussion['UF_NAME'])){
                    foreach($arUser as $user){
                        if($user_id != $user['ID']){
                            $name .= $user['NAME'] . ", ";
                        }
                    }
                    $name = substr($name, 0, -2);
                    $arDiscussion['UF_NAME'] = $name;
                }
            }

            $arResult = array(
                "ID" => $arDiscussion['ID'],
                "DATE_CREATE" => $arDiscussion['UF_DATE_CREATE'],
                "NAME" => $arDiscussion['UF_NAME'],
                "AUTHOR_ID" => $arDiscussion['UF_AUTHOR_ID'],
                "AVATAR" => $arDiscussion['UF_AVATAR'],
                "USERS" => $arUser
            );

            return $arResult;
        }
        return false;
    }

    private function addUser($discussion_id, $user_id, $current_user){
        if(empty(intval($discussion_id)) || empty(intval($user_id)) || empty(intval($current_user)))
            return false;

        $result = true;

        $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_DIALOG_ID", "UF_AUTHOR_ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $discussion_id)
        ));
        if($arData = $rsData -> fetch()){
            if(($current_user == $arData['UF_AUTHOR_ID'] && $user_id != $current_user) || ($user_id == $current_user && $current_user != $arData['UF_AUTHOR_ID'])){
                $dialog_id = $arData['UF_DIALOG_ID'];
                $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
                $rsData = $entity_data_class::getList(array(
                    "select" => array("ID"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_USER_ID" => $user_id)
                ));
                if($arData = $rsData->Fetch()){
                    $entity_data_class::update($arData['ID'], array(
                        "UF_STATUS" => DIALOGUSERSTATUS_I
                    ));
                    $result = true;
                }elseif($user_id != $current_user){
                    $entity_data_class::add(array(
                        "UF_DIALOG_ID" => $dialog_id,
                        "UF_USER_ID" => $user_id,
                        "UF_STATUS" => DIALOGUSERSTATUS_I
                    ));

                    $result = true;
                }
                if($result){
                    $result = false;
                    $rsData = $entity_data_class::getList(array(
                        "select" => array("UF_USER_ID"),
                        "order" => array("ID" => "ASC"),
                        "filter" => array("UF_DIALOG_ID" => $dialog_id, "!UF_STATUS" => DIALOGUSERSTATUS_I)
                    ));
                    while($arData = $rsData->Fetch()){
                        $arUsers[] = $arData["UF_USER_ID"];
                    }
                    $arUsers[] = $user_id;
                    $arUsers = array_unique($arUsers);
                    $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => $user_id), array("FIELDS" => array("ID", "NAME", "LAST_NAME")));
                    if($arRes = $rsUser -> GetNext())
                        $MESSAGE_TEXT = ($user_id == $current_user) ? $arRes['NAME'] . " " . $arRes['LAST_NAME'] . " вернулся(ась) в беседу" : $arRes['NAME'] . " " . $arRes['LAST_NAME'] . " присоединился(ась) в беседу";
                    $arFields['MESSAGE_TEXT'] .= " создал(а) беседу";
                    $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
                    $rsData = $entity_data_class::add(array(
                        "UF_DIALOG_ID" => $dialog_id,
                        "UF_AUTHOR_ID" => $current_user,
                        "UF_MESSAGE_TEXT" => $MESSAGE_TEXT,
                        "UF_TIMESTAMP_X" => date("d.m.Y H:i:s"),
                        "UF_DATE_CREATE" => date("d.m.Y H:i:s"),
                        "UF_IS_SYSTEM" => 1
                    ));
                    $message_id = $rsData->getId();
                    if($message_id){
                        $entity_data_class = self::GetEntityDataClass(DIALOGS_HLB_ID);
                        $entity_data_class::update($dialog_id, array(
                            "UF_LAST_MESSAGE_DATE" => date("d.m.Y H:i:s"),
                        ));
                        $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
                        foreach ($arUsers as $id) {
                            $entity_data_class::add(array(
                                "UF_DIALOG_ID" => $dialog_id,
                                "UF_MESSAGE_ID" => $message_id,
                                "UF_USER_ID" => $id,
                                "UF_STATUS" => ($current_user == $id) ? MESSAGESTATUS_A : MESSAGESTATUS_N,
                            ));
                        }
                        $result = true;
                    }
                }
            }
        }
        return $result;
    }

    private function removeUser($discussion_id, $user_id, $current_user){
        if(empty(intval($discussion_id)) || empty(intval($user_id)) || empty(intval($current_user)))
            return false;

        $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_DIALOG_ID", "UF_AUTHOR_ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $discussion_id)
        ));
        if($arData = $rsData -> fetch()){
            if($user_id == $current_user || $current_user == $arData['UF_AUTHOR_ID']){
                $dialog_id = $arData['UF_DIALOG_ID'];
                if($user_id == $arData['UF_AUTHOR_ID']){
                    $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
                    $rsData = $entity_data_class::getList(array(
                        "select" => array("UF_USER_ID"),
                        "order" => array("ID" => "ASC"),
                        "filter" => array("UF_DIALOG_ID" => $dialog_id, "!UF_USER_ID" => $user_id)
                    ));
                    if($arData = $rsData -> fetch()){
                        $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
                        $entity_data_class::update($discussion_id, array(
                            "UF_AUTHOR_ID" => $arData['UF_USER_ID']
                        ));
                    }
                }
                
                $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
                $rsData = $entity_data_class::getList(array(
                    "select" => array("ID"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_USER_ID" => $user_id)
                ));
                if($arData = $rsData->Fetch()){
                    $entity_data_class::update($arData["ID"], array(
                        "UF_STATUS" => ($user_id == $current_user) ? DIALOGUSERSTATUS_L : DIALOGUSERSTATUS_K
                    ));
                    $rsData = $entity_data_class::getList(array(
                        "select" => array("UF_USER_ID"),
                        "order" => array("ID" => "ASC"),
                        "filter" => array("UF_DIALOG_ID" => $dialog_id, "UF_STATUS" => DIALOGUSERSTATUS_I)
                    ));
                    while($arData = $rsData->Fetch()){
                        $arUsers[] = $arData["UF_USER_ID"];
                    }
                    $arUsers[] = $user_id;
                    $arUsers = array_unique($arUsers);
                    $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => $user_id), array("FIELDS" => array("ID", "NAME", "LAST_NAME")));
                    if($arRes = $rsUser -> GetNext())
                        $MESSAGE_TEXT = ($user_id == $current_user) ? $arRes['NAME'] . " " . $arRes['LAST_NAME'] . " покинул(а) беседу" : $arRes['NAME'] . " " . $arRes['LAST_NAME'] . " выгнан(а) из беседы";
                    $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
                    $result = $entity_data_class::add(array(
                        "UF_DIALOG_ID" => $dialog_id,
                        "UF_AUTHOR_ID" => $current_user,
                        "UF_MESSAGE_TEXT" => $MESSAGE_TEXT,
                        "UF_TIMESTAMP_X" => date("d.m.Y H:i:s"),
                        "UF_DATE_CREATE" => date("d.m.Y H:i:s"),
                        "UF_IS_SYSTEM" => 1
                    ));
                    $message_id = $result->getId();
                    if($message_id){
                        $entity_data_class = self::GetEntityDataClass(DIALOGS_HLB_ID);
                        $entity_data_class::update($dialog_id, array(
                            "UF_LAST_MESSAGE_DATE" => date("d.m.Y H:i:s"),
                        ));
                        $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
                        foreach ($arUsers as $id) {
                            $entity_data_class::add(array(
                                "UF_DIALOG_ID" => $dialog_id,
                                "UF_MESSAGE_ID" => $message_id,
                                "UF_USER_ID" => $id,
                                "UF_STATUS" => ($current_user == $id) ? MESSAGESTATUS_A : MESSAGESTATUS_N,
                            ));
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function changeName($discussion_id, $name, $current_user){
        if(empty(intval($discussion_id)) || empty($name) || empty(intval($current_user)))
            return false;

        $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_AUTHOR_ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $discussion_id)
        ));
        if($arData = $rsData -> fetch()){
            if($current_user == $arData['UF_AUTHOR_ID']){
                $entity_data_class::update($discussion_id, array(
                    "UF_NAME" => $name
                ));
                return true;
            }
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

    private function changeAvatar($discussion_id, $avatar, $current_user){
        if(empty(intval($discussion_id)) || empty($avatar) || empty(intval($current_user)))
            return false;

        $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_AUTHOR_ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $discussion_id)
        ));
        if($arData = $rsData -> fetch()){
            if($current_user == $arData['UF_AUTHOR_ID']){
                // $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
                $type = explode(";", explode( '/', $avatar )[1])[0];
                $file_name_tmp = $_SERVER["DOCUMENT_ROOT"].'/upload/tmp/'.self::generateStr(20).'.'.$type;
                $ifp = fopen($file_name_tmp, "wb");

                $data = explode(',', $avatar);
            
                fwrite($ifp, base64_decode($data[1]));
                fclose($ifp);
                // $file_put_contents = file_put_contents($file_name_tmp, $data);
                if(true){
                    $arFile = \CFile::MakeFileArray($file_name_tmp);
                    //unlink($file_name_tmp);
                }
                $entity_data_class::update($discussion_id, array(
                    "UF_AVATAR" => $arFile
                ));
                return true;
            }
        }
    }

    public function executeComponent()
    {

        global $USER;
        $user_id = $USER -> GetID();

        $discussion_id = $this -> arParams['DISCUSSION_ID'];

        if(CModule::IncludeModule("highloadblock")){
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';

            if($this->checkSession && $this->isRequestViaAjax){
                if($this->request->get('action') == 'changeName'){
                    $this->arResult = $this->changeName($discussion_id, $this->request->get('name'), $user_id);
                }elseif($this->request->get('action') == 'changeAvatar'){
                    $this->arResult = $this->changeAvatar($discussion_id, $this->request->get('avatar'), $user_id);
                }elseif($this->request->get('action') == 'addUser'){
                    $this->arResult = $this->addUser($discussion_id, $this->request->get('user_id'), $user_id);
                }elseif($this->request->get('action') == 'removeUser'){
                    $this->arResult = $this->removeUser($discussion_id, $this->request->get('user_id'), $user_id);
                }elseif($this->request->get('action') == 'leaveDialog'){
                    $this->arResult = $this->removeUser($discussion_id, $user_id, $user_id);
                }elseif($this->request->get('action') == 'joinDialog'){
                    $this->arResult = $this->addUser($discussion_id, $user_id, $user_id);
                }
                if($this->arResult)
                    echo json_encode(array("STATUS" => "SUCCESS"));
                else
                    echo json_encode(array("STATUS" => "ERROR"));
            }else{
                $this->arResult['USER_ID'] = $user_id;
                $this->arResult['DISCUSSION'] = $this -> getDiscussion($discussion_id, $user_id);
                $this->includeComponentTemplate();
            }
        }
        
        return $this->arResult;
    }
};

?>