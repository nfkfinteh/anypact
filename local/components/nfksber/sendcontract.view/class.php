<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
    Класс выводит текст подписанного договора
*/
use Bitrix\Main\Loader; 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
use Bitrix\Iblock;

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
            "DISPLAY_PROFILE" => $arParams["DISPLAY_PROFILE"],
        );
        return $result;
    }

    private function getSendContractText($IDSendItem){
        CModule::IncludeModule("highloadblock");
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
        if(!empty($arSendItem['UF_ID_COMPANY_A'])){
            //компания
            $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>8, 'ID'=>$arSendItem['UF_ID_COMPANY_A'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_INN']);
            if($obj=$res->GetNext(true, false)){
                $arCompany_A = $obj;
            }
        }
        else{
            //пользователь
            $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_A']);
            $arUser_A = $rsUser->Fetch();
        }
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
        if(!empty($arSendItem['UF_ID_COMPANY_B'])){
            //компания
            $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>8, 'ID'=>$arSendItem['UF_ID_COMPANY_B'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_INN']);
            if($obj=$res->GetNext(true, false)){
                $arCompany_B = $obj;
            }
        }
        else{
            //пользователь
            $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_B']);
            $arUser_B = $rsUser->Fetch();
        }
       //статус подписи
       if(!empty($arSendItem['UF_VER_CODE_USER_B'])){
            $hash_B = md5($arSendItem['UF_VER_CODE_USER_B']);
        }
        if(!empty($hash_A) && !empty($hash_B)){
            $Send_text = '<table style="width:100%; margin 50px 0;">';
            $Send_text .= '<tr>';
            $Send_text .= '<td style="width:44%">';
            if(!empty($arSendItem["UF_TIME_SEND_USER_A"])){
                $Send_text .= '<b>Подписано простой электронной подписью:</b>';
                if(!empty($arCompany_A)){
                    //компания
                    $Send_text .= '<br>'.$arCompany_A['NAME'];
                    $Send_text .= '<br>'.$arCompany_A['PROPERTY_INN_VALUE'];
                }
                else{
                    //пользователь
                    $Send_text .= '<br>'.$arUser_A['LAST_NAME'].' '.$arUser_A['NAME'].' '.$arUser_A['SECOND_NAME'];
                    $Send_text .= '<br>#'.$arUser_A['UF_PASSPORT'];
                }
                $Send_text .= '<br>'.$arSendItem["UF_TIME_SEND_USER_A"]->format("Y-m-d H:i:s");
                $Send_text .= '<br>'.$hash_A;
            }
            $Send_text .= '</td>';
            $Send_text .= '<td style="width:2%"></td>';
            $Send_text .= '<td style="width:44%">';
            if(!empty($arSendItem["UF_TIME_SEND_USER_B"])){
                $Send_text .= '<b>Подписано простой электронной подписью:</b>';
                if(!empty($arCompany_B)) {
                    //компания
                    $Send_text .= '<br>'.$arCompany_B['NAME'];
                    $Send_text .= '<br>'.$arCompany_B['PROPERTY_INN_VALUE'];
                }
                else{
                    //пользователь
                    $Send_text .= '<br>'.$arUser_B['LAST_NAME'].' '.$arUser_B['NAME'].' '.$arUser_B['SECOND_NAME'];
                    $Send_text .= '<br>#'.$arUser_B['UF_PASSPORT'];
                }

                $Send_text .= '<br>'.$arSendItem["UF_TIME_SEND_USER_B"]->format("Y-m-d H:i:s");
                $Send_text .= '<br>'.$hash_B;
            }
            $Send_text .= '</td>';
            $Send_text .= '</tr>';
            $Send_text .= '</table>';

            $arSend['TEXT'] = $Send_text;
        }

        $this->arResult['DATA_DOGOVOR'] = $arSendItem;
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

    /**
     * проверка для вывода 404
     * @param array $arFieldsUSER sdsds
     * @param array $arFieldsCompany dsds
     * @return bool
     */
    private function isDispalyDogovor($curCompanyID, $arDisplayProfile){
        if(!empty($curCompanyID)){
            foreach ($arDisplayProfile as $arFieldProfile){
                $arDataDisplay = [
                    'UF_ESIA_AUT'=>$this->arResult['USER']['UF_ESIA_AUT'],
                    'ID_COMPANY_ELEMENT'=>$this->arResult['DATA_DOGOVOR'][$arFieldProfile['COMPANY']],
                    'ID_COMPANY_USER'=>$this->arResult['USER']['UF_CUR_COMPANY'],
                    'ID_USER_ELEMENT'=>'',
                    'ID_USER'=>$this->arResult["ID_USER"],
                ];
                if(isDisplayElement($arDataDisplay)) return true;
            }
        }
        else{
            foreach ($arDisplayProfile as $arFieldProfile){
                $arDataDisplay = [
                    'UF_ESIA_AUT'=>$this->arResult['USER']['UF_ESIA_AUT'],
                    'ID_COMPANY_ELEMENT'=>$this->arResult['DATA_DOGOVOR'][$arFieldProfile['COMPANY']],
                    'ID_COMPANY_USER'=>$this->arResult['USER']['UF_CUR_COMPANY'],
                    'ID_USER_ELEMENT'=>$this->arResult['DATA_DOGOVOR'][$arFieldProfile['USER']],
                    'ID_USER'=>$this->arResult["ID_USER"],
                ];
                if(isDisplayElement($arDataDisplay)) return true;
            }
        }

        return false;
    }

    private function sendEmail($contract_id){
        $rsUser = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $this->arResult["PROPERTY"]['PACT_USER']['VALUE']), array("FIELDS" => array("ID", "EMAIL")));
        if($arUser = $rsUser->Fetch()){
            $rsDeal = CIBlockElement::GetList(array(), array("ID" => $this->arResult["ELEMENT_ID"]), false, false, array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"));
            if($obj = $rsDeal->GetNextElement()){
                $arDeal = $obj->GetFields();
                $arEventFields = array(
                    "EMAIL" => $arUser['EMAIL'],
                    "PDF" => $this->arResult["PDF"],
                    "CONTRACT_ID" => $contract_id,
                    "DEAL_URL" => $arDeal["DETAIL_PAGE_URL"],
                    "DEAL_NAME" => $arDeal["NAME"],
                    "USER_FIO" => $this->arResult['USER']['LAST_NAME']." ".$this->arResult['USER']['NAME']." ".$this->arResult['USER']['SECOND_NAME'],
                    "USER_ID" => $this->arResult['ID_USER']
                );
                $CNotification = new CNotification();
                $CNotification -> Add(array("USER_ID" => $arUser['ID'], "TEXT" => "Договор [URL=/my_pacts/edit_my_contract/?ID=".$arEventFields['CONTRACT_ID']."]".$arEventFields['CONTRACT_ID']."[/URL] по сделке [URL=".$arEventFields['DEAL_URL']."]".$arEventFields['DEAL_NAME']."[/URL] с пользователем был подписан. [URL=".$arEventFields['PDF']."]Ссылка на договор[/URL]", "FROM_USER" => $arEventFields['USER_ID']));
                CEvent::Send("CONTRACT_SIGNATURE_COMPLITE", SITE_ID, $arEventFields);
            }
        }
    }

    public function executeComponent()
    {
        global $USER;
        $IDSendItem = intval($_GET['ID']);
        $this->arResult["ID"] = $IDSendItem;
        $this->arResult["ID_USER"] =$USER->GetID();
        $rsUser = CUser::GetByID($this->arResult["ID_USER"]);
        if($obj = $rsUser->Fetch()){
            $this->arResult['USER'] = $obj;
        }
        $this->arResult["CONTRACT_TEXT"] = $this->getSendContractText($IDSendItem);
        $this->arResult["SEND_BLOCK"] = $this->getSendContractItem($IDSendItem);
        $arDisplayProfile = $this->arParams['DISPLAY_PROFILE'];

        //404 проверка на отображение сделки под выбранным профилем
        if(!$this->isDispalyDogovor($this->arResult['USER']['UF_CUR_COMPANY'], $arDisplayProfile))
        {
            Iblock\Component\Tools::process404(
                '',
                true,
                true,
                true
            );
        }

        $this->arResult["PDF"] = $this->getURLPDF();        
        $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));

        /*
            подписание контракта через ЕСИА
        */
        $this->arResult['SEND_CONTRACT'] = 'N';
        $this->ID_Item = $_GET['ID'];
        // если пользователь вернулся после авторизации ЕСИА
        if($_GET['code']){
            $urlEsia = $_SERVER['DOCUMENT_ROOT']."/esia_test";
            include $urlEsia."/Esia.php";
            include $urlEsia."/EsiaOmniAuth.php";
            include $urlEsia."/config_esia.php";

            $config_esia = new ConfigESIA();

            $esia = new EsiaOmniAuth($config_esia->config);
            $info   = array();
            $token  = $esia->get_token($_GET['code']);
            $info   = $esia->get_info($token);

            // echo "<pre>";
            // print($info);
            // echo "</pre>";

            /*$rsUser = CUser::GetByID($this->arResult["ID_USER"]);
            $UserParams = $rsUser->Fetch();*/
            $UserParams = $this->arResult['USER'];
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
                $this -> sendEmail($IDSendItem);

            }else{
                // выводим ошибку
                $this->arResult['SEND_CONTRACT'] = 'ERR_ID';
            }
        }elseif($_GET['PASSWORD_SIGNATURE']){
            $arPassSign = unserialize(base64_decode($_GET['PASSWORD_SIGNATURE']));
            $Params = array(
                'UF_VER_CODE_USER_A' => $arPassSign['eTag'],
                'UF_TIME_SEND_USER_A' => ConvertTimeStamp(time(), "FULL"),
                'UF_STATUS' => 2, // статус подписанного с двух сторон контракта
                'UF_ID_SEND_USER' => $this->arResult["ID_USER"] // кто подписал последним
            );
            $this->sendContract($Params);
            $this->arResult['SEND_CONTRACT'] = 'Y';
            $this -> sendEmail($IDSendItem);
        }


        $this->includeComponentTemplate();

        /*if($this->startResultCache($this->arParams['CACHE_TIME'], $IDSendItem))
        {
        }*/
        
        //return $this->arResult;
    }
};

?>