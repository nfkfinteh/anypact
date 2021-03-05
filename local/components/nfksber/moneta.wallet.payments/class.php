<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CMoneta.php");

use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CMonetaWalletHistory extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"]
        );
        return $result;
    }

    private function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($HlBlockId)->fetch();   
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    function getPayments($user_id){
        $entity_data_class = self::GetEntityDataClass(MONETA_USER_CARDS_HLB_ID);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_USER_ID" => $user_id)
        ));
        while($arData = $rsData->Fetch()){
            $arPayments[] = $arData;
        }

        return $arPayments;   
    }

    function addPayment($user_id, $cart_id, $cart_name, $cart_number){

        if(strlen($cart_name) == 0 || strlen($cart_name) > 50){
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Название карты не должно быть пустым и должен быть менее 50 символов");
        }

        $cart_number = str_replace(" ", "", $cart_number);

        if(strlen($cart_number) != 16 || !is_numeric($cart_number)){
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Не заполнен номер карты");
        }

        if($cart_id > 0 && is_numeric($cart_id)){
            $entity_data_class = self::GetEntityDataClass(MONETA_USER_CARDS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID"),
                "order" => array("ID" => "DESC"),
                "filter" => array("UF_USER_ID" => $user_id)
            ));
            while($arData = $rsData->Fetch()){
                $arCart[$arData['ID']] = 1;
            }
            if($arCart[$cart_id] === 1){
                $entity_data_class::update($cart_id, array("UF_CARD_NUMBER" => $cart_number, "UF_CARD_NAME" => $cart_name));
                return array("STATUS" => "SUCCESS", "TYPE" => "UPDATE", "CART_ID" => $cart_id, "CARD_NUMBER" => $cart_number, "CARD_NAME" => $cart_name);
            }
            if(count($arCart) >= 5){
                return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Нельзя добавить больше 5 банковских карт");
            }
        }

        $entity_data_class = self::GetEntityDataClass(MONETA_USER_CARDS_HLB_ID);
        $result = $entity_data_class::add(array(
            "UF_USER_ID" => $user_id,
            "UF_CARD_NAME" => $cart_name,
            "UF_CARD_NUMBER" => $cart_number,
        ));
        $cart_id = $result->getId();

        return array("STATUS" => "SUCCESS", "TYPE" => "ADD", "CART_ID" => $cart_id, "CARD_NUMBER" => $cart_number, "CARD_NAME" => $cart_name);
    }

    public function deleteCart($user_id, $cart_id)
    {
        if(is_numeric($cart_id) && $cart_id > 0){
            $entity_data_class = self::GetEntityDataClass(MONETA_USER_CARDS_HLB_ID);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID"),
                "order" => array("ID" => "DESC"),
                "filter" => array("UF_USER_ID" => $user_id, "ID" => $cart_id)
            ));
            if($arData = $rsData->Fetch())
                if($cart_id == $arData['ID']){
                    $entity_data_class::delete($cart_id);
                    return array("STATUS" => "SUCCESS");
                }
        }else{
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Неверный ид карты");
        }
        return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Карта не найдена");
    }

    public function executeComponent()
    {
        global $USER;
        if($USER -> IsAuthorized() &&  \Bitrix\Main\Loader::includeModule('highloadblock')){
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax){
                if($this->request->get('action') == 'cartOperation'){
                    $this->arResult = self::addPayment($USER -> GetID(), $_REQUEST['cart_id'], $_REQUEST['cart_name'], $_REQUEST['cart_number']);
                    if($this->arResult['STATUS'] == "SUCCESS"){
                        ob_start();
                        $this->includeComponentTemplate();
                        $html = ob_get_contents();
                        ob_end_clean();
                        $this->arResult['HTML'] = $html;
                        unset($this->arResult['CARD_NUMBER']);
                        unset($this->arResult['CARD_NAME']);
                    }
                    echo json_encode($this->arResult);
                }else if($this->request->get('action') == 'deleteCart'){
                    echo json_encode(self::deleteCart($USER -> GetID(), $_REQUEST['cart_id']));
                }
            }else{
                $this->arResult = self::getPayments($USER -> GetID());
                $this->includeComponentTemplate();
            }
            return $this->arResult;
        }
    }
};

?>