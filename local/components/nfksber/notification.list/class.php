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

    private static function getUserCompany($user_id){
        $res = \Bitrix\Main\UserTable::getList(array(
            'select' => array("UF_CUR_COMPANY"), 
            "order" => array("ID" => "ASC"),
            'filter' => array("ID" => $user_id)
        ));
        if($user = $res->Fetch())
            return self::checkCompany($user['UF_CUR_COMPANY'], $user_id);
    }

    private static function getUserData($user_id){
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('FIELDS' => array("ID", "NAME", "LAST_NAME", "SECOND_NAME", "PERSONAL_PHOTO")));
        if($arUser = $res->Fetch()) 
            return $arUser;
    }

    private static function getCompanyData($company_id){
        if(CModule::IncludeModule("iblock")){
            $res = CIBlockElement::GetList(array(), array("ID" => $company_id, "IBLOCK_ID" => COMPANY_IB_ID), false, false, array("ID", "NAME", "PREVIEW_PICTURE"));
            if($arCompany = $res->Fetch()) 
                return $arCompany;
        }
    }

    private static function checkCompany($id, $user_id = 0){
        if(!empty($id))
            if(CModule::IncludeModule('iblock')){
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

    private static function getNotification($arUser){

        if(!empty($arUser['COMPANY_ID']))
            $arFilter = array("UF_COMPANY_ID" => $arUser['COMPANY_ID']);
        else
            $arFilter = array("UF_USER_ID" => $arUser['ID']);

        $arRes = CNotification::GetList(array("UF_DATE_CREATE" => "desc"), $arFilter, array("*"), 5);
        if(!empty($arRes['ITEMS']))
            foreach($arRes['ITEMS'] as &$value){
                if(!empty($value['UF_FROM_COMPANY']))
                    $value['UF_FROM_COMPANY'] = self::getCompanyData($value['UF_FROM_COMPANY']);
                else if(!empty($value['UF_FROM_USER']))
                    $value['UF_FROM_USER'] = self::getUserData($value['UF_FROM_USER']);
                $value['UF_TEXT'] = CNotification::AddBB($value['UF_TEXT']);
            }
        return $arRes;
    }

    private static function readNotific($id, $arUser){
        $CNotification = new CNotification();
        $CNotification -> setReaded($id);
        $unReadCount = $CNotification -> GetUnreadCount($arUser['ID'], $arUser['COMPANY_ID']);
        return json_encode($unReadCount);
    }

    private static function deleteNotific($id, $arUser){
        $CNotification = new CNotification();
        $result = $CNotification -> delete($id);
        if($result){
            $result = $CNotification -> getCount($arUser['ID'], $arUser['COMPANY_ID']);
        }
        return json_encode($result);
    }

    private static function deleteNotificAll($arUser){
        $CNotification = new CNotification();
        if(!empty($arUser['COMPANY_ID']))
            $arFilter["UF_COMPANY_ID"] = $arUser['COMPANY_ID'];
        else if(!empty($arUser['ID']))
            $arFilter["UF_USER_ID"] = $arUser['ID'];
        $arRes = $CNotification->GetList(array(), $arFilter, array("ID"));
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

            $this -> arResult['CURRENT_USER'] = array("ID" => $USER->GetID(), "COMPANY_ID" => self::getUserCompany($USER->GetID()));

            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'readNotific'){
                echo self::readNotific($_REQUEST['id'], $this -> arResult['CURRENT_USER']);
            }else if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'deleteNotific'){
                echo self::deleteNotific($_REQUEST['id'], $this -> arResult['CURRENT_USER']);
            }else if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'deleteNotificAll'){
                echo json_encode(self::deleteNotificAll($this -> arResult['CURRENT_USER']));
            }else if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'loadMoreNotification'){
                $this->arResult = array_merge($this->arResult, self::getNotification($this -> arResult['CURRENT_USER']));
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
                $this->arResult['TOTAL_COUNT'] = CNotification::GetUnreadCount($this -> arResult['CURRENT_USER']['ID'], $this -> arResult['CURRENT_USER']['COMPANY_ID']);
                $this->arResult = array_merge($this->arResult, self::getNotification($this -> arResult['CURRENT_USER']));
                $this->includeComponentTemplate();
            }
            return $this->arResult;
        }
    }
};
