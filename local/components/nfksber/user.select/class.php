<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CUserSelect extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ELEMENT_ID" => $arParams["ELEMENT_ID"],
            "FILTER_NAME"=> $arParams["FILTER_NAME"],
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        );
        return $result;
    }

    public function getUser($arFilter = array(), $nTopCount = 20) {

        $arUser = [];

        if(CModule::IncludeModule("main"))
        {
            //внешняя фильтрация
            if(strlen($this->arParams['FILTER_NAME'])<=0)
            {
                $arrFilter = array();
            }
            else
            {
                $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];
                if(!is_array($arrFilter))
                    $arrFilter = array();
            }
            
            $arrFilter = array('ACTIVE' => 'Y', "UF_ESIA_AUT" => 1);

            if(!empty($arFilter)){
                $arFilter = array_merge($arFilter, $arrFilter);
            }elseif($this->arResult['SELECT_USER']){
                $arFilter = array_merge($arrFilter, array("!ID" => $this->arResult['SELECT_USER']));
                $nTopCount = $nTopCount - count($this->arResult['SELECT_USER']);
                $res = CUser::GetList($order = array('LAST_NAME' => 'asc', 'NAME'=> 'asc'), $tmp = "asc", array("ID" => implode(" | ", $this->arResult['SELECT_USER'])), [ 'FIELDS' => ['ID', 'LAST_NAME', 'NAME', 'SECOND_NAME', 'PERSONAL_PHOTO'], 'NAV_PARAMS' => ['nTopCount' => $nTopCount] ]);
                while($obj = $res->getNext()) {
                    $arUser[] = array_merge($obj, array("SELECTED" => "Y"));
                }
                $this->arResult['SELECT_USER'] = $arUser;
            }else{
                $arFilter = array_merge($arrFilter, array("NAME" => "_"));
            }

            AddMessage2Log($arFilter, "arFilter");

            $res = CUser::GetList($order = array('LAST_NAME' => 'asc', 'NAME'=> 'asc'), $tmp = "asc", $arFilter, [ 'FIELDS' => ['ID', 'LAST_NAME', 'NAME', 'SECOND_NAME', 'PERSONAL_PHOTO'], 'NAV_PARAMS' => ['nTopCount' => $nTopCount] ]);
            while($obj = $res->getNext()) {
                $arUser[] = $obj;
            }
        }
        return $arUser;
    }

    public function getSelectUser() {
        if(CModule::IncludeModule("iblock") && !empty($this->arParams['ELEMENT_ID']) && !empty($this->arParams['IBLOCK_ID']))
        {
            $arFilter = Array(
                "ID" => $this->arParams['ELEMENT_ID'],
                "IBLOCK_ID" => $this->arParams['IBLOCK_ID']
            );
            $res = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "IBLOCK_ID"));
            if($obj = $res->GetNextElement()){
                $arProps = $obj->GetProperties();
                return $arProps['ACCESS_USER']['VALUE'];
            }
        }
        return array();
    }

    public function executeComponent()
    {
        global $APPLICATION;
        $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];

        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/class_user_select.log");

        $this->checkSession = check_bitrix_sessid();
        $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';

        if($this->checkSession && $this->isRequestViaAjax){
            $APPLICATION->RestartBuffer();
            $this->arResult["IS_AJAX_REQUEST"] = "Y";
            $this->arResult["USER"] = $this->getUser(array("NAME" => "%".$this->request->get('filter')."%"));
            $this->IncludeComponentTemplate();
        }
        else{
            $this->arResult["SELECT_USER"] = $this->getSelectUser();
            $this->arResult["USER"] = $this->getUser();
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>