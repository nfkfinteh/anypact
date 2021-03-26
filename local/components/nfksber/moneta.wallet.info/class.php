<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CMoneta.php");

use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CMonetaWalletInfo extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"]
        );
        return $result;
    }

    private static function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($HlBlockId)->fetch();   
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    private static function getUserData($user_id){
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('FIELDS' => array("ID"), 'SELECT' => array("UF_MONETA_ACCOUNT_ID", "UF_MONETA_BALANCE", "UF_MONETA_CHECKOP_ID", "UF_MONETA_CHECK_STAT")));
        if($arUser = $res->Fetch()){
            $balance = CMoneta::GetBalance($arUser['UF_MONETA_ACCOUNT_ID']);
            if($balance['STATUS'] == "SUCCESS" && $arUser['UF_MONETA_BALANCE'] != $balance['DATA']){
                $CUser = new CUser;
                $CUser -> Update($arUser['ID'], array("UF_MONETA_BALANCE" => $balance['DATA'], "UF_DATE_MODIFY" => date("d.m.Y H:i:s")));
                $arUser['UF_MONETA_BALANCE'] = $balance['DATA'];
            }
            return $arUser;
        }
        return false;
    }

    static function getPayments($user_id){
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

    private static function makeDeposit($user_id, $amount){
        if(!is_numeric($amount) && $amount < 10)
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Сумма пополнения должна быть больше 9 рублей");
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('FIELDS' => array("ID"), 'SELECT' => array("UF_MONETA_ACCOUNT_ID", "UF_MONETA_BALANCE")));
        if($arUser = $res->Fetch()){
            if(!empty($arUser['UF_MONETA_ACCOUNT_ID'])){
                return CMoneta::makeDeposit($arUser['UF_MONETA_ACCOUNT_ID'], $amount);
            }
        }
        return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Пользователь не найден или отсутствует счет");
    }

    private static function makeWithdrawal($user_id, $amount, $payment_pass, $cart_id = 0, $cart_number = 0){
        if(!is_numeric($amount) && $amount < 10)
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Сумма вывода должна быть больше 39 рублей");
        if(!is_numeric($payment_pass) && strlen($payment_pass) < 5)
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Неверный платежный пароль");

        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id, "UF_MONETA_CHECK_STAT" => "SUCCESS"), array('FIELDS' => array("ID"), 'SELECT' => array("UF_MONETA_ACCOUNT_ID")));
        if($arUser = $res->Fetch()){
            if(!empty($arUser['UF_MONETA_ACCOUNT_ID'])){

                if(is_numeric($cart_id) && $cart_id > 0){
                    $entity_data_class = self::GetEntityDataClass(MONETA_USER_CARDS_HLB_ID);
                    $rsData = $entity_data_class::getList(array(
                        "select" => array("ID", "UF_CARD_NUMBER"),
                        "order" => array("ID" => "DESC"),
                        "filter" => array("UF_USER_ID" => $user_id, "ID" => $cart_id)
                    ));
                    if($arCart = $rsData->Fetch()){
                        if($arCart['ID'] == $cart_id){
                            return CMoneta::makeWithdrawal($arUser['UF_MONETA_ACCOUNT_ID'], $payment_pass, 332, $amount, $arCart['UF_CARD_NUMBER']);
                        }
                    }
                }

                $cart_number = str_replace(" ", "", $cart_number);
                
                if(is_numeric($cart_number) && strlen($cart_number) == 16){
                    return CMoneta::makeWithdrawal($arUser['UF_MONETA_ACCOUNT_ID'], $payment_pass, 332, $amount, $cart_number);
                }else{
                    return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Не заполнен номер карты");
                }

            }
        }

        return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Пользователь не найден или отсутствует счет или карта");
    }

    private static function makeTransfer($user_id, $amount, $payment_pass, $acc_id){
        if(!is_numeric($amount) && $amount < 10)
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Сумма вывода должна быть больше 9 рублей");
        if(!is_numeric($payment_pass) && strlen($payment_pass) < 5)
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Неверный платежный пароль");
        if(!is_numeric($acc_id) && strlen($acc_id) < 2)
            return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Неверный номер счета");

        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id, "UF_MONETA_CHECK_STAT" => "SUCCESS"), array('FIELDS' => array("ID"), 'SELECT' => array("UF_MONETA_ACCOUNT_ID")));
        if($arUser = $res->Fetch()){
            if(!empty($arUser['UF_MONETA_ACCOUNT_ID'])){
                return CMoneta::makeTransfer($arUser['UF_MONETA_ACCOUNT_ID'], $payment_pass, $acc_id, $amount);
            }
        }

        return array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Пользователь не найден или отсутствует счет");
    }

    private static function sendMail($current_user_id, $user_id){
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $current_user_id." | ".$user_id), array('FIELDS' => array("ID", "EMAIL", "NAME", "LAST_NAME", "SECOND_NAME")));
        while($arUser = $res->Fetch()){
            if($current_user_id == $arUser['ID']){
                $arSendParams['SEND_ID'] = $arUser['ID'];
                $arSendParams['SEND_FIO'] = $arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME'];
                //$arSendParams['SEND_EMAIL'] = $arUser['EMAIL'];
            }
            if($user_id == $arUser['ID']){
                //$arSendParams['USER_ID'] = $arUser['ID'];
                //$arSendParams['USER_FIO'] = $arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME'];
                $arSendParams['USER_EMAIL'] = $arUser['EMAIL'];
            }
        }
        CEvent::Send("INVITATION_TO_MONETA", "s1", $arSendParams);

        return array("STATUS" => "SUCCESS");
    }

    public function executeComponent()
    {
        global $USER;
        if($USER -> IsAuthorized() &&  \Bitrix\Main\Loader::includeModule('highloadblock')){
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax){
                if($this->request->get('action') == 'depositSum'){
                    echo json_encode(self::makeDeposit($USER -> GetID(), $_REQUEST['amount']));
                }else if($this->request->get('action') == 'getWithdrawal'){
                    $this->arResult['PAYMENTS'] = self::getPayments($USER -> GetID());
                    $this->arResult['GET_WITHDRAWAL'] = "Y";
                    ob_start();
                    $this->includeComponentTemplate();
                    $html = ob_get_contents();
                    ob_end_clean();
                    echo json_encode(array("STATUS" => "SUCCESS", "HTML" => $html));
                }else if($this->request->get('action') == 'makeWithdrawal'){
                    echo json_encode(self::makeWithdrawal($USER -> GetID(), $_REQUEST['amount'], $_REQUEST['payment_pass'], $_REQUEST['cart_id'], $_REQUEST['cart_number']));
                }else if($this->request->get('action') == 'makeTransfer'){
                    echo json_encode(self::makeTransfer($USER -> GetID(), $_REQUEST['amount'], $_REQUEST['payment_pass'], $_REQUEST['acc_id']));
                }else if($this->request->get('action') == 'sendMail'){
                    echo json_encode(self::sendMail($USER -> GetID(), $_REQUEST['user_id']));
                }
            }else{
                $this->arResult['USER'] = self::getUserData($USER -> GetID());
                $this->includeComponentTemplate();
            }
            return $this->arResult;
        }
    }
};

?>