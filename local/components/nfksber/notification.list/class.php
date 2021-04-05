<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CNotificationList extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"]
        );
        return $result;
    }

    private static function getUserData($user_id){
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('FIELDS' => array("ID", "NAME", "LAST_NAME", "SECOND_NAME", "PERSONAL_PHOTO")));
        if($arUser = $res->Fetch()) 
            return $arUser;
    }

    private static function getNotification($user_id){
        $arRes = CNotification::GetList(array("UF_DATE_CREATE" => "desc"), array("UF_USER_ID" => $user_id), array("*"), 5);
        if(!empty($arRes['ITEMS']))
            foreach($arRes['ITEMS'] as &$value){
                if(!empty($value['UF_FROM_USER']))
                    $value['UF_FROM_USER'] = self::getUserData($value['UF_FROM_USER']);
                $value['UF_TEXT'] = CNotification::AddBB($value['UF_TEXT']);
            }
        return $arRes;
    }

    private static function readNotific($id, $user_id){
        $CNotification = new CNotification();
        $CNotification -> setReaded($id);
        $unReadCount = $CNotification -> GetUnreadCount($user_id);
        return json_encode($unReadCount);
    }

    private static function deleteNotific($id, $user_id){
        $CNotification = new CNotification();
        $result = $CNotification -> delete($id);
        if($result){
            $result = $CNotification -> getCount($user_id);
        }
        return json_encode($result);
    }

    private static function deleteNotificAll($user_id){
        $CNotification = new CNotification();
        $arRes = $CNotification->GetList(array(), array("UF_USER_ID" => $user_id), array("ID"));
        foreach($arRes['ITEMS'] as $value)
            $result = $CNotification -> delete($value['ID']);
        return $result;
    }

    public function executeComponent()
    {
        global $USER;
        if($USER -> IsAuthorized()){
            if(!empty(($this->request->get('nav-notification'))))
                $this->arResult["PAGE"] = intval(substr($_REQUEST['nav-notification'], 5));
            else
                $this->arResult["PAGE"] = 1;

            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'readNotific'){
                echo self::readNotific($_REQUEST['id'], $USER->GetID());
            }else if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'deleteNotific'){
                echo self::deleteNotific($_REQUEST['id'], $USER->GetID());
            }else if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'deleteNotificAll'){
                echo json_encode(self::deleteNotificAll($USER->GetID()));
            }else if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'loadMoreNotification'){
                $this->arResult = array_merge($this->arResult, self::getNotification($USER->GetID()));
                if($this->arResult['PAGE'] <= $this->arResult['TOTAL_PAGE']){
                    ob_start();
                    $this->includeComponentTemplate();
                    $result['HTML'] = ob_get_contents();
                    ob_end_clean();
                    if($this->arResult['PAGE'] == $this->arResult['TOTAL_PAGE'])
                        $result['NOT_LOAD_MORE'] = "Y";
                }else
                    $result['NOT_LOAD_MORE'] = "Y";
                echo json_encode($result);
            }else{
                $this->arResult['TOTAL_COUNT'] = CNotification::GetUnreadCount($USER->GetID());
                $this->arResult = array_merge($this->arResult, self::getNotification($USER->GetID()));
                $this->includeComponentTemplate();
            }
            return $this->arResult;
        }
    }
};
