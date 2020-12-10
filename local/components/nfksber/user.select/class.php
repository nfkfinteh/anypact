<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CUserSelect extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ELEMENT_ID" => $arParams["ELEMENT_ID"],
            "FILTER_NAME"=> $arParams["FILTER_NAME"],
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
            "HLBLOCK_ID" => $arParams["HLBLOCK_ID"],
            "INPUT_FIELD_NAME" => $arParams["INPUT_FIELD_NAME"],
            "OUTPUT_FIELD_NAME" => $arParams["OUTPUT_FIELD_NAME"],
            "INPUT_NAME" => $arParams["INPUT_NAME"],
            "HL_FILTER_NAME" => $arParams["HL_FILTER_NAME"],
            "SELECT_USER" => $arParams["SELECT_USER"],
            "ONLY_FRIENDS" => $arParams["ONLY_FRIENDS"]
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

    public function getUser($arFilter = array(), $nTopCount = 20, $only_friends = "N") {

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

            $arrFilter = array_merge($arrFilter, array('ACTIVE' => 'Y', "UF_ESIA_AUT" => 1));
            global $USER;
            if($only_friends == "Y"){
                $IDs = implode(" | ", $this -> getFrends($USER -> GetID()));
                if(empty($IDs))
                    $IDs = 0;
                $arrFilter = array_merge($arrFilter, array("ID" => $IDs));
                unset($arrFilter["UF_ESIA_AUT"]);
            }
            if(!empty($arFilter)){
                $arFilter = array_merge($arFilter, $arrFilter);
            }elseif($this->arResult['SELECT_USER']){
                $arFilter = array_merge($arrFilter, array("!ID" => $this->arResult['SELECT_USER']));
                $nTopCount = $nTopCount - count($this->arResult['SELECT_USER']);
                $res = CUser::GetList($order = array('LAST_NAME' => 'asc', 'NAME'=> 'asc'), $tmp = "asc", array("ID" => implode(" | ", $this->arResult['SELECT_USER'])), [ 'FIELDS' => ['ID', 'LAST_NAME', 'NAME', 'SECOND_NAME', 'PERSONAL_PHOTO'], 'NAV_PARAMS' => ['nTopCount' => $nTopCount] ]);
                while($obj = $res->getNext()) 
                    if($obj['ID'] != $USER -> GetID())
                        $arUser[] = array_merge($obj, array("SELECTED" => "Y"));
                $this->arResult['SELECT_USER'] = $arUser;
            }else{
                $arFilter = array_merge($arrFilter, array("NAME" => "_"));
            }

            $res = CUser::GetList($order = array('LAST_NAME' => 'asc', 'NAME'=> 'asc'), $tmp = "asc", $arFilter, [ 'FIELDS' => ['ID', 'LAST_NAME', 'NAME', 'SECOND_NAME', 'PERSONAL_PHOTO'], 'NAV_PARAMS' => ['nTopCount' => $nTopCount] ]);
            while($obj = $res->getNext()) 
                if($obj['ID'] != $USER -> GetID())
                    $arUser[] = $obj;
        }
        return $arUser;
    }

    public function getSelectUser() {
        if(CModule::IncludeModule("iblock") && !empty($this->arParams['ELEMENT_ID']) && !empty($this->arParams['IBLOCK_ID']))
        {
            if(!empty($this->arParams['INPUT_FIELD_NAME'])){
                $arFilter[$this->arParams['INPUT_FIELD_NAME']] = $this->arParams['ELEMENT_ID'];
            }else{
                $arFilter["ID"] = $this->arParams['ELEMENT_ID'];
            }
            $arFilter = Array(
                "IBLOCK_ID" => $this->arParams['IBLOCK_ID']
            );
            $res = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "IBLOCK_ID"));
            if($obj = $res->GetNextElement()){
                $arProps = $obj->GetProperties();
                return $arProps[$this->arParams['OUTPUT_FIELD_NAME']]['VALUE'];
            }
        }elseif(CModule::IncludeModule("highloadblock") && !empty($this->arParams['ELEMENT_ID']) && !empty($this->arParams['HLBLOCK_ID'])){
            $entity_data_class = self::GetEntityDataClass($this->arParams['HLBLOCK_ID']);
            //внешняя фильтрация
            if(strlen($this->arParams['HL_FILTER_NAME'])<=0)
            {
                $arrFilter = array();
            }
            else
            {
                $arrFilter = $GLOBALS[$this->arParams['HL_FILTER_NAME']];
                if(!is_array($arrFilter))
                    $arrFilter = array();
            }
            
            $arrFilter = array_merge($arrFilter, array($this->arParams['INPUT_FIELD_NAME'] => $this->arParams['ELEMENT_ID']));
            $rsData = $entity_data_class::getList(array(
                "select" => array($this->arParams['OUTPUT_FIELD_NAME']),
                "order" => array("ID" => "ASC"),
                "filter" => $arrFilter
            ));
            $arUsers = array();
            while($arData = $rsData->Fetch()){
                if(is_array($arData[$this->arParams['OUTPUT_FIELD_NAME']]))
                    $arUsers = array_merge($arUsers, $arData[$this->arParams['OUTPUT_FIELD_NAME']]);
                else
                    $arUsers[] = $arData[$this->arParams['OUTPUT_FIELD_NAME']];
            }
            return $arUsers;
        }
        return array();
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

        if(array_search($user_id, $result) && isset($result[array_search($user_id, $result)]))
            unset($result[array_search($user_id, $result)]);

        return $result;
    }

    public function executeComponent()
    {
        global $APPLICATION;
        if(CModule::IncludeModule("highloadblock"))
        {
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this -> arParams['ONLY_FRIENDS'] == "Y"){
                $only_friends = "Y";
            }else{
                $only_friends = "N";
            }
            if($this->checkSession && $this->isRequestViaAjax){
                $APPLICATION->RestartBuffer();
                $this->arResult["IS_AJAX_REQUEST"] = "Y";
                $this->arResult["USER"] = $this->getUser(array("NAME" => "%".$this->request->get('filter')."%"), 20, $only_friends);
                $this->IncludeComponentTemplate();
            }
            else{
                if(!empty($this -> arParams['SELECT_USER']) && is_array($this -> arParams['SELECT_USER']))
                    $this->arResult["SELECT_USER"] = $this -> arParams['SELECT_USER'];
                else
                    $this->arResult["SELECT_USER"] = $this->getSelectUser();
                $this->arResult["USER"] = $this->getUser(array(), 20, $only_friends);
                $this->includeComponentTemplate();
            }
        }
        return $this->arResult;
    }
};

?>