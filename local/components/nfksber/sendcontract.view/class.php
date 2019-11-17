<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
    Класс выводит текст подписанного договора
*/
use Bitrix\Main\Loader; 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

class CDemoSqr extends CBitrixComponent
{       
    private $ID_Item; // ID  записи подписанния контракта в журнале
    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X" => intval($arParams["X"]),
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "SECTION_ID" => intval($arParams["SECTION_ID"]),
            "ELEMENT_ID" => intval($arParams["ELEMENT_ID"]),
        );
        return $result;
    }

    private function getSendContractText($IDSendItem){
        CModule::IncludeModule("highloadblock");
        // получить все подписанны сделки
        $ID_hl_send_contract_text = 7;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract_text)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass(); 
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_TEXT_CONTRACT", "UF_CANTRACT_IMG"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ID_SEND_ITEM" => $IDSendItem)
        ));
                  
        while($arData = $rsData->Fetch()){            
            $arMesage_User['TEXT']  = $arData['UF_TEXT_CONTRACT'];
            $arMesage_User['IMG']  = $arData['UF_CANTRACT_IMG'];
        }
        return $arMesage_User;
    }

    public function getSendContractItem($IDSendItem){
        CModule::IncludeModule("highloadblock");
        // получить все подписанны сделки
        $ID_hl_send_contract_text = 3;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract_text)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass(); 
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $IDSendItem)
        ));
                  
        while($arData = $rsData->Fetch()){            
            $arSendItem  = $arData;
        }
        
        // получить данные пользователей по id
        // пользователь А владелец контракта        
        $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_A']);
        $arUser_A = $rsUser->Fetch();        
        //статус подписи
        $hash_A ='--';
        if(!empty($arSendItem['UF_VER_CODE_USER_A'])){
            $status_send_a = '';
            $hash_A =md5($arSendItem['UF_VER_CODE_USER_A']);
        }else{
            //$status_send_a = '<button class="btn btn-nfk" id="send_contract_owner" data-id="'.$arSendItem['ID'].'" data-user="'.$arSendItem['UF_ID_USER_A'].'" style="width:100%">Подписать договор</button>';
            $status_send_a = '<button class="btn btn-nfk" id="sign_contract" data-id="'.$arSendItem['ID'].'" data-user="'.$arSendItem['UF_ID_USER_A'].'" style="width:100%">Подписать договор</button>';
        }

        
        // пользователь В подписывающий
        $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_B']);
        $arUser_B = $rsUser->Fetch();
        $hash_B = md5($arSendItem['UF_VER_CODE_USER_B']);
        
        $Send_text = '<table style="width:100%; margin 50px 0;">';
        $Send_text .= '<tr>';
        $Send_text .= '<td style="width:50%">';
        $Send_text .= '<b>Подписано простой электронной подписью:</b>';
        $Send_text .= '<br>'.$arUser_A['LAST_NAME'].' '.$arUser_A['NAME'];
        $Send_text .= '<br>'.$hash_A;
        $Send_text .= '</td>';
        $Send_text .= '<td style="width:50%">';
        $Send_text .= '<b>Подписано простой электронной подписью:</b>';
        $Send_text .= '<br>'.$arUser_B['LAST_NAME'].' '.$arUser_B['NAME'].' '.$arUser_B['SECOND_NAME'];
        $Send_text .= '<br>'.$hash_B;
        $Send_text .= '</td>';
        $Send_text .= '</tr>';
        $Send_text .= '</table>';

        $arSend['TEXT'] = $Send_text;
        $arSend['ID']   = $status_send_a;

        $this->arResult['USERS'] = $arSendItem;
        $this->arResult['DOGOVOR_ID'] = $arSendItem['UF_ID_CONTRACT'];
        
        return $arSend;
    }
    
    // подписание контракта Через ЕСИА
    private function sendContract($hlbl, $Params){

        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $result = $entity_data_class::add($Params);
        // возвращаем id записи
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
        } else {
            $id = $result->getId();
        }

        return $id;
    }

    // подписание измененного контракта
    private function sendEditContract($hlbl, $ID, $Params){

        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $result = $entity_data_class::update($ID, $Params);

        // возвращаем id записи
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
        } else {
            $id = $result->getId();
        }
        //return $id;
    }


    private function getURLPDF(){
        $URL_PDF = '/upload/dogovor_test.pdf';
        return  $URL_PDF;
    }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["SECTION_ID"] = $arParams["SECTION_ID"];
        $arResult["ELEMENT_ID"] = $arParams["ELEMENT_ID"];
        return $arResult;
    }

    public function executeComponent()
    {
        $IDSendItem = $_GET['ID'];
        $this->arResult["ID"] = $_GET['ID'];
        global $USER;
        $this->arResult["ID_USER"] =$USER->GetID();
        $this->arResult["CONTRACT_TEXT"] = $this->getSendContractText($IDSendItem);
        $this->arResult["SEND_BLOCK"] = $this->getSendContractItem($IDSendItem);
        $this->arResult["PDF"] = $this->getURLPDF();        
        $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));

        /*
            подписание контракта через ЕСИА
        */
        $this->arResult['SEND_CONTRACT'] = 'N';
        $this->ID_Item = $_GET['ID'];
        // если пользователь вернулся после авторизации ЕСИА
        // если пользователь вернулся после авторизации ЕСИА
        if($_GET['code']){
            /*
                подписываем контракт
                статусы подписания 1-подписан с одной стороны, 2- подписан с двух сторон, 3- изменен
                контракт может подписываться с измененым текстом процедура подписания немного другая, в этом случае ЕСИА возвращает
                GET - ID_SENDITEM уже созданной записи с измененным текстом
            */
            if(!empty($_GET['ID_SENDITEM'])){
                $urlEsia = $_SERVER['DOCUMENT_ROOT'] . "/esia";
                include $urlEsia . "/Esia.php";
                include $urlEsia . "/EsiaOmniAuth_t.php";
                include $urlEsia . "/config_esia.php";

                $config_esia = new ConfigESIA();

                $esia = new EsiaOmniAuth($config_esia->config);
                $info = array();
                $token = $esia->get_token($_GET['code']);
                $info = $esia->get_info($token);

                $rsUser = CUser::GetByID($userId);
                $UserParams = $rsUser->Fetch();
                $ESIA_ID = $UserParams['UF_ESIA_ID'];

                // проверим идентификаторы из есиа и из профиля пользователя
                if ($info['user_id'] == $ESIA_ID) {
                    $hash_key = hash('md5', $info['user_info']['eTag'] . time());

                    $Params = array(
                        'UF_TIME_SEND_USER_A' => ConvertTimeStamp(time(), "FULL"),
                        'UF_VER_CODE_USER_B' => $info['user_info']['eTag'],
                        'UF_TIME_SEND_USER_B' => ConvertTimeStamp(time(), "FULL"),
                        'UF_STATUS' => 3,
                        'UF_HASH_SEND' => $hash_key,
                    );

                    $this->sendEditContract(3, $_GET['ID_SENDITEM'], $Params);
                    $this->arResult['SEND_CONTRACT'] = 'Y';
                } else {
                    // выводим ошибку
                    $this->arResult['SEND_CONTRACT'] = 'ERR_ID';
                }
            }else{
                $urlEsia = $_SERVER['DOCUMENT_ROOT'] . "/esia";
                include $urlEsia . "/Esia.php";
                include $urlEsia . "/EsiaOmniAuth_t.php";
                include $urlEsia . "/config_esia.php";

                $config_esia = new ConfigESIA();

                $esia = new EsiaOmniAuth($config_esia->config);
                $info = array();
                $token = $esia->get_token($_GET['code']);
                $info = $esia->get_info($token);

                $rsUser = CUser::GetByID($userId);
                $UserParams = $rsUser->Fetch();
                $ESIA_ID = $UserParams['UF_ESIA_ID'];
                // проверим идентификаторы из есиа и из профиля пользователя
                if ($info['user_id'] == $ESIA_ID) {
                    /*
                        подписываем контракт
                        статусы подписания 1-подписан с одной стороны, 2- подписан с двух сторон, 3- изменен
                        контракт может подписываться с измененым текстом процедура подписания немного другая, в этом случае ЕСИА возвращает
                        GET - ID_SEND уже созданной записи с измененным текстом
                    */
                    if (!empty($_GET['ID_SEND'])) {
                        echo "Договор подписан с изменениями" . $_GET['ID_SEND'];
                    } else {
                        $hash_key = hash('md5', $info['user_info']['eTag'] . time());
                        $Params = array(
                            'UF_VER_CODE_USER_A' => '',
                            'UF_ID_USER_A' => $this->arResult["CONTRACT_PROPERTY"]["CONTRACT_PROPERTY"]["USER_A"]["VALUE"], // владелец договора
                            'UF_TEL_CODE_USER_A' => '', //пока не заполняем авторизация через ЕСИА
                            'UF_TIME_SEND_USER_A' => ConvertTimeStamp(time(), "FULL"),
                            'UF_ID_CONTRACT' => $this->ID_CONTRACT,
                            'UF_ID_USER_B' => $userId, // подписавшая сторона
                            'UF_VER_CODE_USER_B' => $info['user_info']['eTag'],
                            'UF_TEL_CODE_USER_B' => '',
                            'UF_TIME_SEND_USER_B' => ConvertTimeStamp(time(), "FULL"),
                            'UF_STATUS' => 1,
                            'UF_HASH_SEND' => $hash_key,
                            'UF_ID_SEND_USER' => $userId
                        );
                        // создание записи подписания контрака
                        $id_add_item = $this->sendContract(3, $Params);
                        // создание записи с текстом
                        $Contract_params = array(
                            'UF_ID_CONTRACT' => $this->ID_CONTRACT,
                            'UF_ID_SEND_ITEM' => $id_add_item,
                            'UF_TEXT_CONTRACT' => $this->arResult["CONTRACT_PROPERTY"]["CONTRACT"]["DETAIL_TEXT"],
                            'UF_HASH' => $hash_key,
                            'UF_CANTRACT_IMG' => '',
                            'UF_ID_USER_SEND' => $userId,
                        );
                        $id_add_item = $this->sendContract(7, $Contract_params);
                        // получи
                    }
                    $this->arResult['SEND_CONTRACT'] = 'Y';
                } else {
                    // выводим ошибку
                    $this->arResult['SEND_CONTRACT'] = 'ERR_ID';
                }
            }
        }

        $this->includeComponentTemplate();

        /*if($this->startResultCache($this->arParams['CACHE_TIME'], $IDSendItem))
        {
        }*/
        
        //return $this->arResult;
    }
};

?>