<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class MessengerHLDialogList extends CBitrixComponent
{    
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

    private function getDialogs($user_id){
        if(empty(intval($user_id)))
            return array();

        $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_DIALOG_ID", "UF_DELETE_DATE", "UF_STATUS"),
            "order" => array("ID" => "DESC"),
            "filter" => array("UF_USER_ID" => $user_id)
        ));
        while($arData = $rsData->Fetch()){
            $arDialogIDsI[$arData['UF_DIALOG_ID']] = $arData['UF_DIALOG_ID'];
            $arDialogsI[$arData['UF_DIALOG_ID']] = array("ID" => $arData['UF_DIALOG_ID'], "DELETE_DATE" => (empty($arData['UF_DELETE_DATE'])) ? "01.01.2000 00:00:00" : $arData['UF_DELETE_DATE']);
        }

        $nav = new \Bitrix\Main\UI\PageNavigation("nav-dialog");
        $nav->allowAllRecords(true)
            ->setPageSize(20)
            ->initFromUri();
        $entity_data_class = self::GetEntityDataClass(DIALOGS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_LAST_MESSAGE_DATE"),
            "order" => array("UF_LAST_MESSAGE_DATE" => "DESC", "ID" => "DESC"),
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
            "filter" => array("ID" => $arDialogIDsI)
        ));
        while($arData = $rsData->Fetch()){
            $arDialogIDs[] = $arData['ID'];
            $arDialogs[] = $arDialogsI[$arData['ID']];
        }

        $nav->setRecordCount($rsData->getCount());

        $this -> arResult['TOTAL_PAGE'] = $nav->getPageCount();

        if($this -> arResult['PAGE'] > $this -> arResult['TOTAL_PAGE'])
            return false;

        $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_DIALOG_ID" => $arDialogIDs)
        ));
        while($arData = $rsData->Fetch()){
            $arDiscussion[$arData['UF_DIALOG_ID']] = $arData;
        }
        if(!is_array($arDialogs))
            return array();

        foreach($arDialogs as $key => &$dialog){
            $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_MESSAGE_ID"),
                "order" => array("UF_MESSAGE_ID" => "DESC"),
                'limit' => '1',
                "filter" => array("UF_DIALOG_ID" => $dialog['ID'], "UF_USER_ID" => $user_id, "!UF_STATUS" => 13)
            ));
            if($arData = $rsData -> fetch()){
                $messageID = $arData['UF_MESSAGE_ID'];
                $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
                $rsData = $entity_data_class::getList(array(
                    "select" => array("UF_AUTHOR_ID", "UF_DATE_CREATE", "UF_MESSAGE_TEXT", "UF_IS_SYSTEM"),
                    "order" => array("UF_DATE_CREATE" => "DESC"),
                    'limit' => '1',
                    "filter" => array("ID" => $messageID, ">UF_DATE_CREATE" => $dialog['DELETE_DATE'])
                ));
                if($arData = $rsData->Fetch()){
                    $dialog['LAST_MESSAGE_ID'] = $messageID;
                    $dialog['LAST_MESSAGE_TEXT'] = TruncateText($arData['UF_MESSAGE_TEXT'], 100);
                    $dialog['LAST_MESSAGE_DATE'] = $arData['UF_DATE_CREATE'];
                    $dialog['LAST_MESSAGE_AUTHOR_ID'] = $arData['UF_AUTHOR_ID'];         
                    $dialog['LAST_MESSAGE_SYSTEM'] = $arData['UF_IS_SYSTEM'];
                    
                    $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
                    $rsData = $entity_data_class::getList(array(
                        "select" => array("UF_USER_ID"),
                        "order" => array("UF_USER_ID" => "DESC"),
                        'limit' => '4',
                        "filter" => array("UF_DIALOG_ID" => $dialog['ID'])
                    ));
                    while($arUser = $rsData -> fetch()){
                        $arUserIDs[] = $arUser["UF_USER_ID"];
                        $dialog['USERS'][] = $arUser["UF_USER_ID"];
                    }

                    $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
                    if($dialog['LAST_MESSAGE_AUTHOR_ID'] == $user_id){
                        $rsData = $entity_data_class::getList(array(
                            "select" => array("UF_STATUS"),
                            "order" => array("ID" => "DESC"),
                            "filter" => array("UF_MESSAGE_ID" => $messageID, "!UF_USER_ID" => $user_id, "UF_STATUS" => 11)
                        ));
                        if($arData = $rsData->Fetch())
                            $dialog['LAST_MESSAGE_STATUS'] = "R";
                        else
                            $dialog['LAST_MESSAGE_STATUS'] = "N";
                    }else{
                        $rsData = $entity_data_class::getList(array(
                            "select" => array("ID"),
                            "order" => array("ID" => "DESC"),
                            "filter" => array("UF_MESSAGE_ID" => $messageID, "UF_USER_ID" => $user_id, "UF_STATUS" => 9)
                        ));
                        if($arData = $rsData->Fetch()){
                            $dialog['LAST_MESSAGE_STATUS'] = "N";
                        }else{
                            $dialog['LAST_MESSAGE_STATUS'] = "R";
                        }
                        if($dialog['LAST_MESSAGE_STATUS'] == "N"){
                            $rsData = $entity_data_class::getList(array(
                                "select" => array("ID"),
                                "order" => array("ID" => "DESC"),
                                "filter" => array("UF_DIALOG_ID" => $dialog['ID'], "UF_USER_ID" => $user_id, "UF_STATUS" => 9)
                            ));
                            $dialog['UNREAD_MESSAGE_COUNT'] = 0;
                            while($arData = $rsData->Fetch()){
                                $dialog['UNREAD_MESSAGE_COUNT']++;
                            }
                        }
                    }
                }else{
                    unset($arDialogs[$key]);
                }
            }else{
                unset($arDialogs[$key]);
            }
        }
        if($arDialogs && $arUserIDs){
            $arUserIDs = array_unique($arUserIDs);
            $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => implode(" | ", $arUserIDs)), array("FIELDS" => array("ID", "NAME", "LAST_NAME", "PERSONAL_PHOTO")));
            while($arRes = $rsUser -> GetNext())
                $arUsers[$arRes['ID']] = $arRes;
            foreach($arDialogs as &$dialog){
                $name = "";
                $avatar = "";
                if(isset($arDiscussion[$dialog['ID']])){
                    $dialog['IS_DISCUSSION'] = "Y";
                    if(!empty($arDiscussion[$dialog['ID']]['UF_NAME']))
                        $dialog['NAME'] = $arDiscussion[$dialog['ID']]['UF_NAME'];
                    if(!empty($arDiscussion[$dialog['ID']]['UF_AVATAR']))
                        $dialog['AVATAR'] = $arDiscussion[$dialog['ID']]['UF_AVATAR'];
                }
                if(empty($dialog['NAME']) || empty($dialog['AVATAR'])){
                    if(is_array($dialog['USERS']))
                        foreach($dialog['USERS'] as &$user){
                            $user = $arUsers[$user];
                            if($user_id != $user['ID']){
                                if(count($dialog['USERS']) > 2){
                                    $name .= $user['NAME'] . ", ";
                                }else{
                                    $name = $user['NAME'] . " " . $user['LAST_NAME'];
                                    $avatar = $user['PERSONAL_PHOTO'];
                                }
                            }
                        }
                    if(empty($dialog['NAME'])){
                        if(count($dialog['USERS']) > 2)
                            $name = substr($name, 0, -2);
                         $dialog['NAME'] = $name;
                    }
                    if(empty($dialog['AVATAR']))
                        $dialog['AVATAR'] = $avatar;
                }
            }
        }else{
            return false;
        }
        return $arDialogs;
    }

    function generateStr($length = 8){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    private function newDialog($arFields){
        if(empty(intval($arFields['AUTHOR_ID'])) || (!is_array($arFields['USERS']) && empty(intval($arFields['USERS']))) || empty($arFields['USERS']))
            return false;
        
        if(!is_array($arFields['USERS']))
            $arFields['USERS'] = array($arFields['USERS']);

        if(count($arFields['USERS']) < 2){
            foreach($arFields['USERS'] as $key => $value){
                $user_id = $value;
                break;
            }
            $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_DIALOG_ID", "UF_USER_ID"),
                "order" => array("ID" => "DESC"),
                "filter" => array("UF_USER_ID" => array($arFields['AUTHOR_ID'], $user_id), "UF_STATUS" => 4)
            ));
            while($arData = $rsData->Fetch()){
                $arDialogs[$arData["UF_DIALOG_ID"]][] = $arData["UF_USER_ID"];
                $arDialogsIDs[$arData["UF_DIALOG_ID"]] = $arData["UF_DIALOG_ID"];
            }

            if(!empty($arDialogs) && is_array($arDialogs)){
                foreach($arDialogs as $key => $value){
                    if(!in_array($arFields['AUTHOR_ID'], $value) || !in_array($user_id, $value)){
                        unset($arDialogsIDs[$key]);
                    }
                }
                if(!empty($arDialogsIDs) && is_array($arDialogsIDs)){
                    $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
                    $rsData = $entity_data_class::getList(array(
                        "select" => array("ID", "UF_DIALOG_ID"),
                        "order" => array("ID" => "DESC"),
                        "filter" => array("UF_DIALOG_ID" => $arDialogsIDs)
                    ));
                    while($arData = $rsData->Fetch()){
                        unset($arDialogsIDs[$arData['UF_DIALOG_ID']]);
                    }
                }
                if(!empty($arDialogsIDs) && is_array($arDialogsIDs)){
                    foreach($arDialogsIDs as $id){
                        $dialog_id = $id;
                        break;
                    }
                    if($dialog_id)
                        return $dialog_id;
                }
            }
        }

        $entity_data_class = self::GetEntityDataClass(DIALOGS_HLB_ID);
        $result = $entity_data_class::add(array(
            "UF_AUTHOR_ID" => $arFields['AUTHOR_ID'],
            "UF_LAST_MESSAGE_DATE" => date('d.m.Y H:i:s')
        ));
        $dialog_id = $result->getId();
        if($dialog_id){
            $arFields['USERS'][] = $arFields['AUTHOR_ID'];
            $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
            foreach ($arFields['USERS'] as $user_id) {
                $entity_data_class::add(array(
                    "UF_DIALOG_ID" => $dialog_id,
                    "UF_USER_ID" => $user_id,
                    "UF_STATUS" => 4
                ));
            }

            if(count($arFields['USERS']) > 2){
                if(!empty($arFields['DISCUSSION']['AVATAR'])){
					// $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $arFields['DISCUSSION']['AVATAR']));
					$type = explode(";", explode( '/', $arFields['DISCUSSION']['AVATAR'] )[1])[0];
					$file_name_tmp = $_SERVER["DOCUMENT_ROOT"].'/upload/tmp/'.self::generateStr(20).'.'.$type;
					$ifp = fopen($file_name_tmp, "wb");

					$data = explode(',', $arFields['DISCUSSION']['AVATAR']);
				
					fwrite($ifp, base64_decode($data[1]));
					fclose($ifp);
					// $file_put_contents = file_put_contents($file_name_tmp, $data);
					if(true){
						$arFile = \CFile::MakeFileArray($file_name_tmp, false, true);
						//unlink($file_name_tmp);
					}
                    /* $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $arFields['DISCUSSION']['AVATAR']));
                    $type = explode(";", explode( '/', $arFields['DISCUSSION']['AVATAR'] )[1])[0];
                    $file_name_tmp = $_SERVER["DOCUMENT_ROOT"].'/upload/tmp/'.self::generateStr(20).'.'.$type;
                    $file_put_contents = file_put_contents($file_name_tmp, $data);
                    if($file_put_contents !== false){
                        $arFile = \CFile::MakeFileArray($file_name_tmp);
                        unlink($file_name_tmp);
                    } */
                }
                $entity_data_class = self::GetEntityDataClass(DISCUSSION_HLB_ID);
                $entity_data_class::add(array(
                    "UF_AUTHOR_ID" => $arFields['AUTHOR_ID'],
                    "UF_DIALOG_ID" => $dialog_id,
                    "UF_NAME" => $arFields['DISCUSSION']['NAME'],
                    "UF_AVATAR" => $arFile,
                    "UF_DATE_CREATE" => date("d.m.Y H:i:s"),
                ));
            }
            $rsUser = CUser::GetList(($by="ID"), ($order="ASC"), array("ID" => $arFields['AUTHOR_ID']), array("FIELDS" => array("ID", "NAME", "LAST_NAME")));
            if($arRes = $rsUser -> GetNext())
                $arFields['MESSAGE_TEXT'] = $arRes['NAME'] . " " . $arRes['LAST_NAME'];
            $arFields['MESSAGE_TEXT'] .= " создал(а) беседу";
            $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
            $result = $entity_data_class::add(array(
                "UF_DIALOG_ID" => $dialog_id,
                "UF_AUTHOR_ID" => $arFields['AUTHOR_ID'],
                "UF_MESSAGE_TEXT" => trim($arFields['MESSAGE_TEXT']),
                "UF_TIMESTAMP_X" => date("d.m.Y H:i:s"),
                "UF_DATE_CREATE" => date("d.m.Y H:i:s"),
                "UF_IS_SYSTEM" => 1
            ));
            $message_id = $result->getId();
            if($message_id){
                $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
                foreach ($arFields['USERS'] as $user_id) {
                    $entity_data_class::add(array(
                        "UF_DIALOG_ID" => $dialog_id,
                        "UF_MESSAGE_ID" => $message_id,
                        "UF_USER_ID" => $user_id,
                        "UF_STATUS" => ($arFields['AUTHOR_ID'] == $user_id) ? 10 : 9,
                    ));
                }
                return $dialog_id;
            }
        }
    }

    private function getFrends($user_id){

        $entity_data_class = self::GetEntityDataClass(14);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_USER_A", "UF_USER_B"),
            "order" => array("ID" => "ASC"),
            "filter" => array(array(
                "LOGIC" => "OR",
                array("UF_USER_A" => $user_id, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y),
                array("UF_USER_B" => $user_id, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y),
            ))
        ));
        while($arData = $rsData->Fetch()){
            $result[] = $arData["UF_USER_A"];
            $result[] = $arData["UF_USER_B"];
        }

        if(empty($result)){
            $result = [];
        }

        $result = array_unique($result);

        if(isset($result[array_search($user_id, $result)]))
            unset($result[array_search($user_id, $result)]);

        return $result;
    }

    private function uploadDialogs($user_id, $arFields){
        if(empty(intval($user_id)) || empty($arFields) || !is_array($arFields))
            return array();

        foreach($arFields as $key => $value){
            $arIDs[] = $value['ID'];
            $arOldDialogs[$value['ID']] = $value;
        }

        $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_DIALOG_ID", "UF_DELETE_DATE", "UF_STATUS"),
            "order" => array("ID" => "DESC"),
            "filter" => array("UF_USER_ID" => $user_id, "UF_DIALOG_ID" => $arIDs)
        ));
        while($arData = $rsData->Fetch()){
            $arDialogIDs[] = $arData['UF_DIALOG_ID'];
            $arDialogs[] = array("ID" => $arData['UF_DIALOG_ID'], "DELETE_DATE" => (empty($arData['UF_DELETE_DATE'])) ? "01.01.2000 00:00:00" : $arData['UF_DELETE_DATE']);
        }

        if(!is_array($arDialogs))
            return array();

        foreach($arDialogs as $key => &$dialog){
            $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_MESSAGE_ID"),
                "order" => array("UF_MESSAGE_ID" => "DESC"),
                'limit' => '1',
                "filter" => array("UF_DIALOG_ID" => $dialog['ID'], "UF_USER_ID" => $user_id, "!UF_STATUS" => 13)
            ));
            if($arData = $rsData -> fetch()){
                $messageID = $arData['UF_MESSAGE_ID'];
                $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
                $rsData = $entity_data_class::getList(array(
                    "select" => array("UF_AUTHOR_ID", "UF_DATE_CREATE", "UF_MESSAGE_TEXT", "UF_IS_SYSTEM"),
                    "order" => array("UF_DATE_CREATE" => "DESC"),
                    'limit' => '1',
                    "filter" => array("ID" => $messageID, ">UF_DATE_CREATE" => $dialog['DELETE_DATE'])
                ));
                if($arData = $rsData->Fetch()){
                    list($date, $time) = explode(" ", $arData['UF_DATE_CREATE']);
                    if($date == date('d.m.Y'))
                        $dialog['MESSAGE_DATE'] = $time;
                    else
                        $dialog['MESSAGE_DATE'] = $date;
                    $dialog['MESSAGE_TEXT'] = "<span>".TruncateText($arData['UF_MESSAGE_TEXT'], 100)."</span>";
                    if($arData['UF_AUTHOR_ID'] == $user_id && $arData['UF_IS_SYSTEM'] != 1){
                        $dialog['MESSAGE_TEXT'] = "Вы: ".$dialog['MESSAGE_TEXT'];
                    }
                    
                    $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
                    $rsData = $entity_data_class::getList(array(
                        "select" => array("UF_USER_ID"),
                        "order" => array("UF_USER_ID" => "DESC"),
                        'limit' => '4',
                        "filter" => array("UF_DIALOG_ID" => $dialog['ID'])
                    ));
                    while($arUser = $rsData -> fetch()){
                        $arUserIDs[] = $arUser["UF_USER_ID"];
                        $dialog['USERS'][] = $arUser["UF_USER_ID"];
                    }

                    $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
                    if($arData['UF_AUTHOR_ID'] == $user_id){
                        $rsData = $entity_data_class::getList(array(
                            "select" => array("UF_STATUS"),
                            "order" => array("ID" => "DESC"),
                            "filter" => array("UF_MESSAGE_ID" => $messageID, "!UF_USER_ID" => $user_id, "UF_STATUS" => 11)
                        ));
                        if($arData = $rsData->Fetch())
                            $dialog['MESSAGE_STATUS'] = "R";
                        else
                            $dialog['MESSAGE_STATUS'] = "N";
                    }else{
                        $rsData = $entity_data_class::getList(array(
                            "select" => array("ID"),
                            "order" => array("ID" => "DESC"),
                            "filter" => array("UF_MESSAGE_ID" => $messageID, "UF_USER_ID" => $user_id, "UF_STATUS" => 9)
                        ));
                        if($arData = $rsData->Fetch()){
                            $dialog['MESSAGE_STATUS'] = "N";
                        }else{
                            $dialog['MESSAGE_STATUS'] = "R";
                        }
                        if($dialog['MESSAGE_STATUS'] == "N"){
                            $rsData = $entity_data_class::getList(array(
                                "select" => array("ID"),
                                "order" => array("ID" => "DESC"),
                                "filter" => array("UF_DIALOG_ID" => $dialog['ID'], "UF_USER_ID" => $user_id, "UF_STATUS" => 9)
                            ));
                            $UNREAD_MESSAGE_COUNT = 0;
                            while($arData = $rsData->Fetch()){
                                $UNREAD_MESSAGE_COUNT++;
                            }
                            if($UNREAD_MESSAGE_COUNT > 0){
                                $dialog['MESSAGE_STATUS'] = $UNREAD_MESSAGE_COUNT;
                            }
                        }
                    }
                }else{
                    unset($arDialogs[$key]);
                }
            }else{
                unset($arDialogs[$key]);
            }
        }
        $arNew = array();
        if($arDialogs){
            $dateArray = [];

            foreach($arDialogs as $key => $arr){
                $dateArray[$key] = $arr['MESSAGE_DATE'];
            }

            array_multisort($dateArray, SORT_DESC, SORT_STRING, $arDialogs);
            foreach($arDialogs as $key => $dialog){
                if($arOldDialogs[$dialog['ID']]){
                    if(trim($arOldDialogs[$dialog['ID']]['DATE']) != trim($dialog['MESSAGE_DATE']))
                        $arNew[$dialog['ID']]['DATE'] = $dialog['MESSAGE_DATE'];
                    if(trim($arOldDialogs[$dialog['ID']]['NO_READ']) != trim($dialog['MESSAGE_STATUS']))
                        $arNew[$dialog['ID']]['NO_READ'] = $dialog['MESSAGE_STATUS'];
                    if(trim($arOldDialogs[$dialog['ID']]['TEXT']) != trim($dialog['MESSAGE_TEXT']))
                        $arNew[$dialog['ID']]['TEXT'] = $dialog['MESSAGE_TEXT'];
                    if(!empty($arNew[$dialog['ID']]))
                        $arNew[$dialog['ID']]['ID'] = $dialog['ID'];
                }
            }
            $arNew['length'] = count($arNew);
        }

        return $arNew;
    }

    public function executeComponent()
    {

        global $USER;
        $user_id = $USER -> GetID();

        if(CModule::IncludeModule("highloadblock")){
            if(!empty(($this->request->get('nav-dialog'))))
                $this->arResult["PAGE"] = intval(substr($_REQUEST['nav-dialog'], 5));
            else
                $this->arResult["PAGE"] = 1;
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') != 'loadMoreDialogs'){
                if($this->request->get('action') == 'addDialog'){
                    $_REQUEST['data']["AUTHOR_ID"] = $user_id;
                    $dialog_id = $this->newDialog($_REQUEST['data']);
                    $this->arParams["DIALOG_ID"] = $dialog_id;
                    $this->arResult["DIALOGS"] = $this -> getDialogs($user_id);
                    ob_start();
                    $this->includeComponentTemplate();
                    $dialogs = ob_get_contents();
                    ob_end_clean();
                    echo json_encode(array("STATUS" => "SUCCESS", "DIALOG_ID" => $dialog_id, "DATA" => $dialogs));
                }elseif($this->request->get('action') == 'uploadDialogs'){
                    $arData = $this -> uploadDialogs($user_id, $_REQUEST['data']);
                    if(!empty($arData) && $arData['length'] > 0){
                        echo json_encode(array("STATUS" => "SUCCESS", "DATA" => $arData));
                    }else{
                        echo json_encode(array("STATUS" => "EMPTY"));
                    }
                    die();
                }
            }else{
                $this->arResult["USER_ID"] = $user_id;
                $this->arResult["DIALOGS"] = $this -> getDialogs($user_id);
                $this->includeComponentTemplate();
            }
        }
        return $this->arResult;
    }
};

?>