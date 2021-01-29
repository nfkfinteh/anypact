<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/class/CProstorSMS.php");

use Bitrix\Main\Loader,
    Bitrix\Highloadblock as HL;

function GetEntityDataClass($HlBlockId) {
    if (empty($HlBlockId) || $HlBlockId < 1)
    {
        return false;
    }
    $hlblock = HL\HighloadBlockTable::getById($HlBlockId)->fetch();   
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    return $entity_data_class;
}

$wait_sec = 60;

$api_login = "t89278485872";
$api_password = "897054";

if(($_POST['action'] == 'check' || $_POST['action'] == 'send') && check_bitrix_sessid()){
    if($_POST['action'] == 'send' && !empty($_POST['phone'])){
        $phone = str_replace(array(" ", "-", "(", ")"), "", $_POST['phone']);
        if(is_numeric($phone) && strlen($phone) == 11 && substr($phone, 0 , 2) == 89){
            global $USER;
            $userID = $USER -> GetID();
            $filterC = array();
            if($userID > 0) $filterC = array("UF_USER_ID" => $userID);
            Loader::includeModule('highloadblock');
            $entity_data_class = GetEntityDataClass(26);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID", "UF_SEND_DATE", "UF_CODE", "UF_PHONE_NUMBER", "UF_SESSION_ID", "UF_USER_ID"),
                "order" => array("UF_SEND_DATE" => "DESC"),
                "filter" => array(array(
                    "LOGIC" => "OR",
                    array("UF_PHONE_NUMBER" => $phone),
                    array("UF_SESSION_ID" => bitrix_sessid()),
                    $filterC
                ))
            ));
            while($arData = $rsData->Fetch()){
                $arSend[] = $arData;
                if($arData['UF_PHONE_NUMBER'] == $phone && $arData['UF_SESSION_ID'] == $_POST['sessid']){
                    if($userID > 0){
                        if($arData['UF_USER_ID'] == $userID){
                            $code = $arData['UF_CODE'];
                        }
                    }else{
                        $code = $arData['UF_CODE'];
                    }
                }
            }
            if(!empty($arSend)){
                $sendDate = new DateTime($arSend[0]['UF_SEND_DATE']);
                $sendDate = $sendDate->format('YmdHis');
                $diffSec = date('YmdHis') - $sendDate;
                
                if($diffSec > $wait_sec){
                    $add = true;
                }else{
                    die(json_encode(array("STATUS" => "wait", "VALUE" => $wait_sec - $diffSec)));
                }
            }else{
                $add = true;
            }
            if($add){
                if(empty($code)) $code = random_int(100000, 999999);
                $gate = new CProstorSMS($api_login, $api_password);
                $clientID = random_int(100000000, 999999999);
                $messages = [[
                    "clientId" => $clientID,
                    "phone"=> "+7".substr($phone, 1),
                    "text"=> "Kod podtverzhdeniya: " . $code,
                    "sender"=> "AnyPact"
                ]];
                $result = $gate->send($messages, 'podtverzhdenieNomeraTelephona');
                $entity_data_class::add(array(
                    "UF_PHONE_NUMBER" => $phone,
                    "UF_SEND_DATE" => date("d.m.Y H:i:s"),
                    "UF_SESSION_ID" => bitrix_sessid(),
                    "UF_USER_ID" => $userID,
                    "UF_CODE" => $code,
                    "UF_MESSAGE_TEXT" => "Kod podtverzhdeniya: " . $code,
                    "UF_CLIENT_ID" => $clientID,
                    "UF_SMS_STATUS" => $result['messages'][0]['status'],
                ));
                die(json_encode(array("STATUS" => "send", "DATA" => '<div class="check_sms"><input type="text" name="CODE" class="code_input"><button class="flat_button" id="send_code">Подтвердить</button><div>Если сообщение не дошло, вы можете <span id="sms_send_span">отправить код повторно через <span id="sms_send_timer">01:00</span></span></div><div id="code_status"></div></div>')));
            }
        }else{
            die(json_encode(array("STATUS" => "error", "ERROR_MESSAGE" => "Не верный формат номера телефона")));
        }
    }else if($_POST['action'] == 'check' && !empty($_POST['code']) && is_numeric($_POST['code']) && strlen($_POST['code']) == 6 && !empty($_POST['phone'])){
        $phone = str_replace(array(" ", "-", "(", ")"), "", $_POST['phone']);
        if(is_numeric($phone) && strlen($phone) == 11 && substr($phone, 0 , 2) == 89){
            Loader::includeModule('highloadblock');
            $entity_data_class = GetEntityDataClass(26);
            $rsData = $entity_data_class::getList(array(
                "select" => array("ID", "UF_SEND_DATE"),
                "order" => array("UF_SEND_DATE" => "DESC"),
                "filter" => array("UF_PHONE_NUMBER" => $phone, "UF_SESSION_ID" => bitrix_sessid(), "UF_CODE" => $_POST['code'])                    
            ));
            if($arData = $rsData->Fetch()){
                global $USER;
                $userID = $USER -> GetID();
                if($userID > 0){
                    $filter = Array("PERSONAL_PHONE" => $_POST["phone"]);
                    $user = new CUser;
                    $rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    while($arUser = $rsUser->Fetch()){
                        $user->Update($arUser['ID'], array("PERSONAL_PHONE" => false));
                    }
                    $satus = $user->Update($userID, array("PERSONAL_PHONE" => $_POST['phone']));
                }else{
                    $_SESSION['PHONE_NUMBER_CHECK'] = "Y";
                    $_SESSION['PHONE_NUMBER'] = $phone;
                }
                die(json_encode(array("STATUS" => "success")));
            }else{
                die(json_encode(array("STATUS" => "error", "ERROR_MESSAGE" => "Код неверен")));
            }
        }else{
            die(json_encode(array("STATUS" => "error", "ERROR_MESSAGE" => "Не верный формат номера телефона")));
        }
    }else if(empty($_POST['phone'])){
        die(json_encode(array("STATUS" => "error", "ERROR_MESSAGE" => "Не верный формат номера телефона")));
    }else{
        die(json_encode(array("STATUS" => "error", "ERROR_MESSAGE" => "Код неверен")));
    }
}
die(json_encode(array("STATUS" => "error", "ERROR_MESSAGE" => "Ошибка!")));
?>