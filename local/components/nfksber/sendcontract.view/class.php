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

//        print_r($arSendItem);
        // пользователь В подписывающий
        $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_B']);
        $arUser_B = $rsUser->Fetch();
        $hash_B = md5($arSendItem['UF_VER_CODE_USER_B']);
        
        $Send_text = '<table style="width:100%; margin 50px 0;">';
        $Send_text .= '<tr>';
        $Send_text .= '<td style="width:44%">';
        $Send_text .= '<b>Подписано простой электронной подписью:</b>';
        $Send_text .= '<br>'.$arUser_A['LAST_NAME'].' '.$arUser_A['NAME'].' '.$arUser_A['SECOND_NAME'];
        $Send_text .= '<br>#'.$arUser_A['UF_PASSPORT'];
        $Send_text .= '<br>'.$arSendItem["UF_TIME_SEND_USER_A"]->format("Y-m-d H:i:s");
        $Send_text .= '<br>'.$hash_A;
        $Send_text .= '</td>';
        $Send_text .= '<td style="width:2%"></td>';
        $Send_text .= '<td style="width:44%">';
        $Send_text .= '<b>Подписано простой электронной подписью:</b>';
        $Send_text .= '<br>'.$arUser_B['LAST_NAME'].' '.$arUser_B['NAME'].' '.$arUser_B['SECOND_NAME'];
        $Send_text .= '<br>#'.$arUser_B['UF_PASSPORT'];
        $Send_text .= '<br>'.$arSendItem["UF_TIME_SEND_USER_A"]->format("Y-m-d H:i:s");
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

    // подписание контракта владельцем
    private function sendContract($Params){
        $hlbl = 3;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $result = $entity_data_class::update($this->ID_Item, $Params);
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
        if($_GET['code']){
            $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia";
            include $urlEsia."/Esia.php";
            include $urlEsia."/EsiaOmniAuth_t.php";
            include $urlEsia."/config_esia.php";

            $config_esia = new ConfigESIA();

            $esia = new EsiaOmniAuth($config_esia->config);
            $info   = array();
            $token  = $esia->get_token($_GET['code']);
            $info   = $esia->get_info($token);

            $rsUser = CUser::GetByID($this->arResult["ID_USER"]);
            $UserParams = $rsUser->Fetch();
            $ESIA_ID = $UserParams['UF_ESIA_ID'];
            // проверим идентификаторы из есиа и из профиля пользователя
            if($info['user_id'] == $ESIA_ID){
                /*
                    подписываем контракт
                    Нужно найти запись
                    статусы подписания 1-подписан с одной стороны, 2- подписан с двух сторон, 3- изменен
                */
                if(!empty($_GET['ID_SENDITEM'])){
                    $Params = array(
                        'UF_VER_CODE_USER_A' => $info['user_info']['eTag'],
                        'UF_TIME_SEND_USER_A' => ConvertTimeStamp(time(), "FULL"),
                        'UF_STATUS' => 3, // статус подписанного с двух сторон контракта
                        'UF_ID_SEND_USER' => $this->arResult["ID_USER"] // кто подписал последним
                    );
                    $this->sendContract($Params);
                }else {
                    $Params = array(
                        'UF_VER_CODE_USER_A' => $info['user_info']['eTag'],
                        'UF_TIME_SEND_USER_A' => ConvertTimeStamp(time(), "FULL"),
                        'UF_STATUS' => 2, // статус подписанного с двух сторон контракта
                        'UF_ID_SEND_USER' => $this->arResult["ID_USER"] // кто подписал последним
                    );
                    $this->sendContract($Params);
                }
                $this->arResult['SEND_CONTRACT'] = 'Y';

            }else{
                // выводим ошибку
                $this->arResult['SEND_CONTRACT'] = 'ERR_ID';
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