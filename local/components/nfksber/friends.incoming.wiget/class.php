<<<<<<< HEAD
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Highloadblock as HL;

class FriendIncomingWiget extends CBitrixComponent
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

    private function getIncomingRequests($user_id){
        if(empty(intval($user_id)))
            return false;

        $count = 0;

        $entity_data_class = self::GetEntityDataClass(14);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_USER_B"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_USER_B" => $user_id, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_A)
        ));
        while($arData = $rsData->Fetch()){
            $count++;
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

            if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'uploadIncomingFriends'){
                $count = $this->getIncomingRequests($user_id);
                echo json_encode(array("STATUS" => "SUCCESS", "COUNT" => $count));
            }else{
                $this->arResult['COUNT'] = $this->getIncomingRequests($user_id);
                $this->includeComponentTemplate();
            }
        }
        
        return $this->arResult;
    }
};

=======
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Highloadblock as HL;

class FriendIncomingWiget extends CBitrixComponent
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

    private function getIncomingRequests($user_id){
        if(empty(intval($user_id)))
            return false;

        $count = 0;

        $entity_data_class = self::GetEntityDataClass(14);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_USER_B"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_USER_B" => $user_id, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_A)
        ));
        while($arData = $rsData->Fetch()){
            $count++;
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

            if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'uploadIncomingFriends'){
                $count = $this->getIncomingRequests($user_id);
                echo json_encode(array("STATUS" => "SUCCESS", "COUNT" => $count));
            }else{
                $this->arResult['COUNT'] = $this->getIncomingRequests($user_id);
                $this->includeComponentTemplate();
            }
        }
        
        return $this->arResult;
    }
};

>>>>>>> d9fc60356232591f7133ff592e5fe98c9f412c53
?>