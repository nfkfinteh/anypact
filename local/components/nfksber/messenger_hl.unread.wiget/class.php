<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Highloadblock as HL;

class MessengerHLUnreadWiget extends CBitrixComponent
{
    //Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent
    //и применяет к ним функцию htmlspecialcharsex. В данном случае такая обработка избыточна.
    //Переопределяем.    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
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

    private function getUnreadMessageCount($user_id){
        if(empty(intval($user_id)))
            return false;

        $count = 0;

        $entity_data_class = self::GetEntityDataClass(MESSAGESTATUS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_DIALOG_ID", "UF_MESSAGE_ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_USER_ID" => $user_id, "UF_STATUS" => 9)
        ));
        while($arData = $rsData->Fetch()){
            $arMessages[] = $arData['UF_MESSAGE_ID'];
            $arDialogs[] = $arData['UF_DIALOG_ID'];
        }
        if(is_array($arDialogs) && !empty($arDialogs)){
            $arDialogs = array_unique($arDialogs);

            $entity_data_class = self::GetEntityDataClass(DIALOGUSERS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_DIALOG_ID", "UF_DELETE_DATE"),
                "order" => array("ID" => "ASC"),
                "filter" => array("UF_DIALOG_ID" => $arDialogs, "UF_USER_ID" => $user_id)
            ));
            while($arData = $rsData->Fetch()){
                $arDeleteData[$arData['UF_DIALOG_ID']] = $arData['UF_DELETE_DATE'];
            }
            $entity_data_class = self::GetEntityDataClass(MESSAGES_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID", "UF_DATE_CREATE"),
                "order" => array("ID" => "DESC"),
                "filter" => array("ID" => $arMessages)
            ));
            while($arData = $rsData->Fetch()){
                if($arDeleteData[$arData["UF_DIALOG_ID"]] < $arData['UF_DATE_CREATE'] || empty($arDeleteData[$arData["UF_DIALOG_ID"]]))
                    $count++;
            }
        }
        return $count;
    }

    public function executeComponent()
    {

        global $USER;
        $user_id = $USER -> GetID();

        if(CModule::IncludeModule("highloadblock")){
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';

            if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'uploadCountMessages'){
                $count = $this->getUnreadMessageCount($user_id);
                echo json_encode(array("STATUS" => "SUCCESS", "COUNT" => $count));
            }else{
                $this->arResult['COUNT'] = $this->getUnreadMessageCount($user_id);
                $this->includeComponentTemplate();
            }
        }
        
        return $this->arResult;
    }
};

?>