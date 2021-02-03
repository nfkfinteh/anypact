<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CUserProfilePacts extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "USER_ID" => intval($arParams["USER_ID"]),
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
            "ITEM_COUNT" => $arParams["ITEM_COUNT"],
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

    private function getIDCompletSdel($UserID){
        CModule::IncludeModule("highloadblock");
        CModule::IncludeModule("iblock");

        $arFilter = Array(
            Array(
                "UF_STATUS" => 2,
                "UF_ID_USER_A"=> $UserID
            )
        );

        $arSend_Contract = [];

        // получить все подписанные сделки
        $ID_hl_send_contract = 3;
        $entity_data_class = self::GetEntityDataClass($ID_hl_send_contract);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order"  => array("ID" => "ASC"),
            "filter" => $arFilter
        ));

        while($arData = $rsData->Fetch()){
            $arrID_Info_Contract[] =  $arData['UF_ID_CONTRACT'];
        }

        #если нет подписаных договоров
        if(empty($arrID_Info_Contract)) return $arSend_Contract;

        foreach($arrID_Info_Contract as $i=>$value ){
            $res = CIBlockElement::GetList(
                array(),
                array("IBLOCK_ID" => $this->arParams['IBLOCK_ID'], "PROPERTY_ID_DOGOVORA" => $value),
                false,
                false,
                array("IBLOCK_ID", "ID")
            );
            while($ob = $res->GetNext(true, false)){
                $result[] = $ob['ID'];
            }
        }

        $result = array_unique($result);

        return $result;
    }

    public function getUserPacts($arNavParams, $arDelID = array()){
        $arParams = $this->arParams;
        if(CModule::IncludeModule("iblock")) {
            //фильтр для активных сделок
            $arFilter = [
                'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                'PROPERTY_PACT_USER'=>$arParams['USER_ID'],
                'PROPERTY_ID_COMPANY'=>false,
                'PROPERTY_MODERATION_VALUE'=>'Y',
            ];

            //фильтр для завершенных сделок
            $arIdSdelk = $this->getIDCompletSdel($arParams['USER_ID']);

            if(empty($arIdSdelk))
                $arIdSdelk = 0;

            $arFilterC['=ID'] = $arIdSdelk;

            $arFilterA['ACTIVE'] = 'Y';
            $arFilterA[0] = array(
                "LOGIC" => "OR",
                array("PROPERTY_INDEFINITELY" => 18),
                array(">=DATE_ACTIVE_TO" => new \Bitrix\Main\Type\DateTime())
            );

            global $USER;

            $arFilter = array_merge($arFilter, array(
            array(
                'LOGIC' => 'OR',
                array("!=PROPERTY_PRIVATE_VALUE" => "Y"),
                array(
                    "PROPERTY_PRIVATE_VALUE" => "Y",
                    "=PROPERTY_ACCESS_USER" => empty( $USER->GetID() ) ? 0 : $USER->GetID()
                ),
                array(
                    "PROPERTY_PRIVATE_VALUE" => "Y",
                    "=CREATED_BY" => empty( $USER->GetID() ) ? 0 : $USER->GetID()
                ),
            )));

            if($this -> arResult['CURRENT_STATE'] == "N"){
                $arNavParamsA = array("nPageSize" => 1);
                $arNavParamsC = $arNavParams;
            }else{
                $arNavParamsA = $arNavParams;
                $arNavParamsC = array("nPageSize" => 1);
            }

            $resC = CIBlockElement::GetList([], array_merge($arFilter, $arFilterC), false, $arNavParamsC);
            $arResult['COMPLITE_ITEM_COUNT'] = $resC->SelectedRowsCount();
            
            $resA = CIBlockElement::GetList([], array_merge($arFilter, $arFilterA), false, $arNavParamsA);
            $arResult['ACTIVE_ITEM_COUNT'] = $resA->SelectedRowsCount();

            if($this -> arResult['CURRENT_STATE'] == "N"){
                $res = $resC;
            }else{
                $res = $resA;
            }

            while ($obj = $res->GetNextElement()) {
                $arFields = $obj->GetFields();
                $arProperty = $obj->GetProperties();
                $arFields = array_merge($arFields, $arProperty);
                if(!in_array($arFields['ID'], $arDelID))
                    $arResult['ITEMS'][] = $arFields;
            }

            $navComponentParameters = array();

            $arResult["NAV_STRING"] = $res->GetPageNavStringEx(
                $navComponentObject,
                '',
                $arParams["PAGER_TEMPLATE"],
                false,
                $this,
                $navComponentParameters
            );
            $arResult["NAV_CACHED_DATA"] = null;
            $arResult["NAV_RESULT"] = $res;
            $arResult["NAV_PARAM"] = $navComponentParameters;
        }
        return $arResult;
    }

    public function getBlackList($current_user){
        $entity_data_class = self::GetEntityDataClass(15);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array(array(
                "LOGIC" => "OR",
                array("UF_USER_A" => $current_user, "UF_USER_B" => $this->arParams['USER_ID']),
                array("UF_USER_A" => $this->arParams['USER_ID'], "UF_USER_B" => $current_user),
            ))
        ));
        while($arData = $rsData->Fetch()){
            if($arData['UF_USER_A'] == $current_user){
                $result['CLOSE'] = true;
            }elseif($arData['UF_USER_B'] == $current_user){
                $result['CLOSED'] = true;
            }
        }

        if(empty($result)){
            $result = [];
        }

        return $result;
    }

    public function executeComponent()
    {
        if(CModule::IncludeModule("highloadblock")){
            if($_REQUEST['STATE_SDEL'] == 'Y'){
                $this -> arResult['CURRENT_STATE'] = 'Y';
            }
            elseif($_REQUEST['STATE_SDEL'] == 'N'){
                $this -> arResult['CURRENT_STATE'] = 'N';
            }
            else{
                $this -> arResult['CURRENT_STATE'] = 'Y';
            }
            global $USER;
            $this->arResult["CURRENT_USER"] = $USER -> GetID();
            $this->arResult["USER_ID"] = $this -> arParams['USER_ID'];
            $arNavParams = array(
                "nPageSize" => $this->arParams["ITEM_COUNT"],
                "bShowAll" => "Y"
            );
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax){
                $arIDs = $_REQUEST['DEL_ID'];

                if(empty($arIDs))
                    $arIDs = array();

                $this->arResult['DEL_ID'] = $arIDs;
                    
                $this->arResult['LOAD_MORE'] = "Y";
                $this->arResult = array_merge($this->arResult, $this->getUserPacts($arNavParams, $arIDs));
            }else{
                $this->arResult = array_merge($this->arResult, $this->getUserPacts($arNavParams));
                $this->arResult["BLACKLIST"] = $this->getBlackList($this->arResult["CURRENT_USER"]);
            }
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }
};

?>