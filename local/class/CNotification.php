<?
class CNotification {
    
    public function __construct()
    {
        
    }

    public static function AddBB(string $var) {
        $search = array(
            '/\[b\](.*?)\[\/b\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '/\[img\](.*?)\[\/img\]/is',
            '/\[url\](.*?)\[\/url\]/is',
            '/\[url\=(.*?)\](.*?)\[\/url\]/is',
            '/\[br\]/is'
        );

        $replace = array(
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>',
            '<img src="$1" />',
            '<a href="$1">$1</a>',
            '<a href="$1">$2</a>',
            '<br>'
        );

        $var = preg_replace ($search, $replace, $var);
        return $var;
    }

    private static function GetEntityDataClass(int $HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($HlBlockId)->fetch();   
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    public function Add(array $arFields){
        if(!\Bitrix\Main\Loader::includeModule('highloadblock')){
            $this -> LAST_ERROR = array("ERROR" => "NOT_HIGHLOADBLOCK", "ERROR_DESCRIPTION_RUS" => "Ошибка, не удалось подключить модуль highloadblock.");
            return false;
        }
        if(empty($arFields['USER_ID']) || !is_numeric($arFields['USER_ID'])){
            $this -> LAST_ERROR = array("ERROR" => "USER_ID_EMPTY", "ERROR_DESCRIPTION_RUS" => "Ошибка, не заполнено обязательное поле ID пользователя получаемого уведомления.");
            return false;
        }
        if(empty($arFields['TEXT'])){
            $this -> LAST_ERROR = array("ERROR" => "TEXT_EMPTY", "ERROR_DESCRIPTION_RUS" => "Ошибка, не заполнено обязательное поле текст уведомления.");
            return false;
        }

        $entity_data_class = self::GetEntityDataClass(NOTIFICATION_HBL_ID);
        $date = date("d.m.Y H:i:s");
        $result = $entity_data_class::add(array(
            "UF_USER_ID" => $arFields['USER_ID'],
            "UF_DATE_CREATE" => $date,
            "UF_TEXT" => $arFields['TEXT'],
            "UF_IS_SYSTEM" => ($arFields['IS_SYSTEM'] == "Y") ? 1 : 0,
            "UF_FROM_USER" => $arFields['FROM_USER'],
            "UF_COMPANY_ID" => $arFields['COMPANY_ID'],
            "UF_FROM_COMPANY" => $arFields['FROM_COMPANY']
        ));
        $notification_id = $result->getId();

        return $notification_id;
    }

    public function Update(int $id, array $arFields){
        if(!\Bitrix\Main\Loader::includeModule('highloadblock')){
            $this -> LAST_ERROR = array("ERROR" => "NOT_HIGHLOADBLOCK", "ERROR_DESCRIPTION_RUS" => "Ошибка, не удалось подключить модуль highloadblock.");
            return false;
        }
        if(isset($arFields['TEXT']) && empty($arFields['TEXT'])){
            $this -> LAST_ERROR = array("ERROR" => "TEXT_EMPTY", "ERROR_DESCRIPTION_RUS" => "Ошибка, поле текст уведомления не может быть пустым.");
            return false;
        }

        if(isset($arFields['READED'])){
            $arUpdate['UF_READED'] = ($arFields['READED'] == "Y") ? 1 : 0;
        }
        if(isset($arFields['TEXT'])){
            $arUpdate['UF_TEXT'] = $arFields['TEXT'];
        }
        if(isset($arFields['IS_SYSTEM'])){
            $arUpdate['UF_IS_SYSTEM'] = ($arFields['IS_SYSTEM'] == "Y") ? 1 : 0;
        }
        if(isset($arFields['FROM_USER'])){
            $arUpdate['UF_FROM_USER'] = $arFields['FROM_USER'];
        }
        if(isset($arFields['FROM_COMPANY'])){
            $arUpdate['UF_FROM_COMPANY'] = $arFields['FROM_COMPANY'];
        }

        $entity_data_class = self::GetEntityDataClass(NOTIFICATION_HBL_ID);
        $result = $entity_data_class::update($id, $arUpdate);

        return $result;
    }

    public static function Delete(int $id){
        if(!\Bitrix\Main\Loader::includeModule('highloadblock'))
            return false;

        $entity_data_class = self::GetEntityDataClass(NOTIFICATION_HBL_ID);
        $entity_data_class::Delete($id);

        return true;
    }

    public static function GetList(array $arOrder = array(), array $arFilter = array(), array $arSelect = array(), int $page_size = 0){
        if(!\Bitrix\Main\Loader::includeModule('highloadblock'))
            return false;

        $arResult['TOTAL_PAGE'] = 1;
        $arNavParams = array();

        if($page_size > 0){
            $nav = new \Bitrix\Main\UI\PageNavigation("nav-notification");
            $nav->allowAllRecords(true)
                ->setPageSize($page_size)
                ->initFromUri();
            $arNavParams = array(
                "count_total" => true,
                "offset" => $nav->getOffset(),
                "limit" => $nav->getLimit()
            );
        }

        $entity_data_class = self::GetEntityDataClass(NOTIFICATION_HBL_ID);
        $rsData = $entity_data_class::getList(array_merge(array(
            "select" => $arSelect,
            "order" => $arOrder,
            "filter" => $arFilter
        ), $arNavParams));
        if($page_size > 0){
            $nav->setRecordCount($rsData->getCount());
            $arResult['TOTAL_PAGE'] = (int)$nav->getPageCount();
        }
        while($arData = $rsData->Fetch()){
            $arResult['ITEMS'][] = $arData;
        }
        return $arResult;
    }

    public static function GetUnreadCount($user_id, $company_id = 0){
        if(!\Bitrix\Main\Loader::includeModule('highloadblock'))
            return false;

        if(!empty($company_id))
            $arFilter["UF_COMPANY_ID"] = $company_id;
        else if(!empty($user_id))
            $arFilter["UF_USER_ID"] = $user_id;
        else return 0;

        $entity_data_class = self::GetEntityDataClass(NOTIFICATION_HBL_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
            "order" => array(),
            "limit" => 1,
            "count_total" => true,
            "filter" => array_merge(array("UF_READED" => 0), $arFilter)
        ));
        
        return $rsData->getCount();
    }


    public static function getCount($user_id, $company_id = 0){
        if(!\Bitrix\Main\Loader::includeModule('highloadblock'))
            return false;

        if(!empty($company_id))
            $arFilter["UF_COMPANY_ID"] = $company_id;
        else if(!empty($user_id))
            $arFilter["UF_USER_ID"] = $user_id;
        else return 0;

        $entity_data_class = self::GetEntityDataClass(NOTIFICATION_HBL_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
            "order" => array(),
            "limit" => 1,
            "count_total" => true,
            "filter" => $arFilter
        ));
        
        return $rsData->getCount();
    }
    
    public function setReaded(int $id){
        return $this -> Update($id, array("READED" => "Y"));
    }
}
