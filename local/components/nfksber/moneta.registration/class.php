<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CMoneta.php");

use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CMonetaRegistration extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"]
        );
        return $result;
    }

    private function hideText($text, $count_b = 3, $count_e = 2) {
        if(empty(trim($text)))
            return $text;
        $text1 = substr($text, 0, $count_b);
        $text2 = substr($text, $count_b);
        $text1 .= str_repeat("*", (strlen($text2)-$count_e));
        if($count_e != 0)
            $text1 .= substr($text, -$count_e);
        return $text1;
    }

    private function getUserData($user_id){
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('FIELDS' => array("ID", "PERSONAL_PHONE", "EMAIL"), 'SELECT' => array("UF_SPASSPORT", "UF_NPASSPORT", "UF_DATA_PASSPORT", "UF_KEM_VPASSPORT", "UF_INN", "UF_SNILS", "UF_MONETA_UNIT_ID", "UF_MONETA_ACCOUNT_ID", "UF_MONETA_DOC_ID")));
        if($arUser = $res->Fetch()) 
            foreach($arUser as $key => &$value)
                switch ($key) {
                    case "UF_DATA_PASSPORT":
                    case "UF_SPASSPORT":
                    case "UF_NPASSPORT":
                        $value = self::hideText($value, 2, 0);
                        break;
                    case "UF_KEM_VPASSPORT":
                        $value = self::hideText($value, 4);
                        break;
                    case "EMAIL":
                        $hideEmail = explode("@", $value);
                        $value = self::hideText($hideEmail[0])."@".$hideEmail[1];
                    case "ID":
                    case "PERSONAL_PHONE":
                    case "UF_MONETA_UNIT_ID":
                    case "UF_MONETA_ACCOUNT_ID":
                    case "UF_MONETA_DOC_ID":
                        $value = $value;
                        break;
                    default:
                        $value = self::hideText($value);
                }
        return $arUser;
    }

    private function getCurPage(){
        global $APPLICATION;
        $arParamsToDelete = array(
            "login",
            "login_form",
            "logout",
            "register",
            "forgot_password",
            "change_password",
            "confirm_registration",
            "confirm_code",
            "confirm_user_id",
            "logout_butt",
            "auth_service_id",
            "logout_butt",
            "backurl"
        );
        return $APPLICATION->GetCurPageParam("moneta_reg=yes", $arParamsToDelete);
    }

    private static function registerMoneta($user_id, $arFields){

        foreach($arFields as $array)
            $arData[$array['name']] = $array['value'];

        $arSelect = array("UF_SPASSPORT", "UF_NPASSPORT", "UF_DATA_PASSPORT", "UF_KEM_VPASSPORT", "UF_ESIA_AUT", "UF_ETAG_ESIA", "UF_ESIA_ID", "UF_MONETA_UNIT_ID", "UF_MONETA_ACCOUNT_ID", "UF_MONETA_DOC_ID");

        if($arData["D_S"] == "Y")
            $arSelect[] = "UF_SNILS";
        else if(strlen(intval(str_replace(array("-", " "), "", $arData["SNILS"]))) == 11)
            $arSendData["SNILS"] = $arData["SNILS"];
        else
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "not_full_snils", "ERROR_DESCRIPTION" => "Поле СНИЛС не заполнено");

        if($arData["D_I"] == "Y")
            $arSelect[] = "UF_INN";
        else if(strlen(intval(str_replace(array("-", " "), "", $arData["INN"]))) == 12)
            $arSendData["INN"] = $arData["INN"];
        else
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "not_full_inn", "ERROR_DESCRIPTION" => "Поле ИНН не заполнено");

        $phone = str_replace(array(" ", "-", "(", ")"), "", $arData['PHONE']);
        if(is_numeric($phone) && strlen($phone) == 11 && substr($phone, 0 , 2) == 89)
            $arSendData["PHONE"] = $arData["PHONE"];
        else
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "not_full_phone", "ERROR_DESCRIPTION" => "Не верный формат номера телефона");

        if(is_numeric(str_replace("-", "", $arData['DEPARTMENT'])) && strlen($arData['DEPARTMENT']) == 7)
            $arSendData["DEPARTMENT"] = $arData["DEPARTMENT"];
        else
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "not_full_department", "ERROR_DESCRIPTION" => "Поле Код подразделения не заполнен");

        /*
        if(is_numeric($arData['PAYMENT_PASS']) && strlen($arData['PAYMENT_PASS']) > 4)
            $arSendData["PAYMENT_PASS"] = $arData["PAYMENT_PASS"];
        else
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "wrong_pass_data", "ERROR_DESCRIPTION" => "Поле Платежный пароль должен состоять только из цифр, минимум из пяти");
        
        if($arData['PAYMENT_PASS'] == $arData['PAYMENT_PASS_REPEAT'])
            $arSendData["PAYMENT_PASS_REPEAT"] = $arData["PAYMENT_PASS_REPEAT"];
        else
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "wrong_pass_repeat", "ERROR_DESCRIPTION" => "Платежные пароли не совпадают");
        */
        
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('FIELDS' => array("ID", "EMAIL", "NAME", "LAST_NAME", "SECOND_NAME"), 'SELECT' => $arSelect));
        if($arUser = $res->Fetch()){
            foreach($arUser as $key => $value){
                if(substr($key, 0, 3) == "UF_") 
                    $arSendData[substr($key, 3)] = $value;
                else 
                    $arSendData[$key] = $value;
            }
        }
        
        return CMoneta::registerProfile($arSendData);
    }

    public function executeComponent()
    {
        global $USER;
        if($USER -> IsAuthorized()){
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'registerMoneta'){
                echo json_encode(self::registerMoneta($USER -> GetID(), $_REQUEST['data']));
            }else{
                $this->arResult["CURRENT_USER"] = self::getUserData($USER -> GetID());
                $this->arResult["REG_URL"] = self::getCurPage();
                $this->includeComponentTemplate();
            }
            return $this->arResult;
        }
    }
};

?>