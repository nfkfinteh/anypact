<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

/*
    Класс выводит информацию в карточку по сделке
*/
include_once 'autoedittext.php';

class CDemoSqr extends CBitrixComponent
{       
    public $ID_CONTRACT;
    public $USER_PROPERTY;
    private $ID_BLOCK_OFFERS = 3; // ID Инфоблока с объявлениями Клиентов
    private $ID_BLOCK_TEMPLATES_CONTRACTS = 5; // ID Инфоблока шаблона договоров
    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE"            => $arParams["CACHE_TYPE"],
            "CACHE_TIME"            => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X"                     => intval($arParams["X"]),
            "IBLOCK_ID"             => intval($arParams["IBLOCK_ID"]),
            "SECTION_ID"            => intval($arParams["SECTION_ID"]),
            "ELEMENT_ID"            => intval($arParams["ELEMENT_ID"]),
            "IBLOCK_ID_CONTRACT"    => intval($arParams["IBLOCK_ID_CONTRACT"]),
        );
        return $result;
    }

    private function getElement($id_element) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                $res = CIBlockElement::GetByID($id_element);
                if($ar_res = $res->GetNext()){
                    return $ar_res;
                }
                
            }        
    }

    // Все свойства элемента
    private function getProperty($id_iblok, $id_element){        
        $db_props           = CIBlockElement::GetProperty($id_iblok, $id_element, "sort", "asc", array());         
        $array_props        = array();        
        $array_img          = array();
        $array_unclude_file = array();

        while($ar_props = $db_props->Fetch()){ 
            
            $array_props[$ar_props["CODE"]] = $ar_props ;
            
            if ($ar_props["CODE"] == "INPUT_FILES"){
                $file_path      = CFile::GetPath($ar_props["VALUE"]);
                $array_img[]    = array("URL" => $file_path, "PROPERTY" => $ar_props);
            }

            if ($ar_props["CODE"] == "DOGOVOR_IMG"){
                $file_path      = CFile::GetPath($ar_props["VALUE"]);
                $array_img_dogovor[]    = array("URL" => $file_path, "PROPERTY" => $ar_props);
            }

            if ($ar_props["CODE"] == "MAIN_FILES"){
                $file_path = CFile::GetPath($ar_props["VALUE"]);
                $array_unclude_file[] = array("URL" => $file_path, "PROPERTY" => $ar_props);
            }

        }
        
        $array_props["IMG_FILE"]        = $array_img;
        $array_props["DOGOVOR_IMG"]     = $array_img_dogovor;
        $array_props["INCLUDE_FILES"]   = $array_unclude_file;

        if(!empty($array_props["ID_DOGOVORA"]["VALUE"])){
            $this->ID_CONTRACT = $array_props["ID_DOGOVORA"]["VALUE"];            
        }

        return $array_props;
    }

    private function convertContent($Content){
        
        $regexp         = "/%DATE%/ui";
        $replacement    = date("d m Y") ;
        $Content = preg_replace($regexp, $replacement, $Content);

        $regexp         = '/<recont fio.*recont>/ui';
        $replacement    = 'Соловьёв Игорь Владимирович' ;
        $Content = preg_replace($regexp, $replacement, $Content);
        return $Content;
    }

    //Cвойства контракта текст контаракта
    private function getPropertyContract($id_infobloc_contract){        
        // объект
        $array_props    = array(); 
        $res            = CIBlockElement::GetByID($this->ID_CONTRACT);
        
        if($ar_res = $res->GetNext(true, false)){
            $array_props["CONTRACT"] = $ar_res;
        }        
        //подготовка текста
        $Contract_template_Text = $this->convertContent($array_props["CONTRACT"]["DETAIL_TEXT"]);

        $clear_text = new autoedittext();
        // echo '<pre>';
        // print_r($this->USER_PROPERTY);
        // echo '</pre>';
        $Contract_template_Text                 = $clear_text->replaceTag($Contract_template_Text, $this->USER_PROPERTY);
        $array_props["CONTRACT"]["DETAIL_TEXT"] = str_replace("&nbsp;", "", $Contract_template_Text);
        
        // свойства контракта
        $db_props       = CIBlockElement::GetProperty($id_infobloc_contract, $this->ID_CONTRACT, "sort", "asc", array());
        
        while($ar_props = $db_props->Fetch()){ 
            $array_props["CONTRACT_PROPERTY"][$ar_props["CODE"]] = $ar_props;
        }
        
        return $array_props;
    }

    private function getListCategory(){
        $items          = GetIBlockSectionList(5, 0, Array("sort"=>"asc"), 10);
        $arr_sections   = array();

        while($arResult = $items->GetNext()){       
            $arr_sections[] = array("NAME" => $arResult["NAME"], "ID" => $arResult["ID"]);
        }

        return $arr_sections;
    }

    private function getTemplateContractCategote(){
        $IBLOCK_ID    = $this->ID_BLOCK_TEMPLATES_CONTRACTS;
        if(CModule::IncludeModule("iblock")){
            $arFilter    = Array(
                'IBLOCK_ID'=>$IBLOCK_ID,
                'GLOBAL_ACTIVE'=>'Y');
            $obSection    = CIBlockSection::GetTreeList($arFilter);
            $arThree = array();
            while($arResult = $obSection->GetNext()){
                $arThree[]= $arResult;
            }
            return $arThree;
        }
    }

    private function getMultyProperty($ID_IBLOCK, $ID_EL){
        $VALUES = array();
        $res = CIBlockElement::GetProperty($ID_IBLOCK, $ID_EL, "sort", "asc", array("CODE" => "STEPS"));
        while ($ob = $res->GetNext())
        {
            $VALUES[] = $ob['VALUE'];
        }
        return $VALUES;
    }

    function paramsUser($arParams){        
        $arResult["INFOBLOCK_ID"]       = $arParams["IBLOCK_ID"];
        $arResult["INFOBLOCK_C_ID"]     = $arParams["IBLOCK_ID_CONTRACT"];
        $arResult["SECTION_ID"]         = $arParams["SECTION_ID"];
        $arResult["ELEMENT_ID"]         = $arParams["ELEMENT_ID"];
        
        return $arResult;
    }

    public function getSendContractItem($IDContract, $userID){
        global $USER;
        CModule::IncludeModule("highloadblock");
        // получить все подписанны сделки
        $ID_hl_send_contract_text = 3;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract_text)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ID_CONTRACT" => $IDContract, "UF_ID_USER_B"=> $userID)
        ));

        while($arData = $rsData->Fetch()){
            $arSendItem  = $arData;
        }

        return $arSendItem;
    }

    // подписание контракта
    private function sendContract($Params){
        $hlbl = 3;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $result = $entity_data_class::add($Params);        
    }

    private function getNewRedaction($userId, $arSdelka){
        $arFilter = [
            'IBLOCK_ID'=>6,
            'CODE'=>$arSdelka['CODE'].'_'.$arSdelka['ID'].'_user_'.$userId,
            'ACTIVE'=>'Y',
        ];
        $arSelect = [
            'IBLOCK_ID',
            'ID'
        ];
        $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if($obj = $res->GetNext(true, false)){
            $arNewRedaction = $obj;
        }
        else{
            $arNewRedaction = false;
        }
        return $arNewRedaction;
    }

    public function executeComponent()
    {
        global $USER;
        $userId = CUser::GetID();
        $this->arResult["USER_ID"] = $userId;
        //if($this->startResultCache($this->arParams['CACHE_TIME'], $_GET['SECTION_ID'].$_GET['ELEMENT_ID'].$_GET['ID_TEMPLATE'].$userId))
        //{
            $this->arResult                         = array_merge($this->arResult, $this->paramsUser($this->arParams));
            // данные владельца сделки           
            $UserContractHolder                     = CUser::GetByID(CUser::GetID());
            $arrUserContractHolder                  = $UserContractHolder->Fetch();
            $this->USER_PROPERTY                    = $arrUserContractHolder;
            $this->arResult["USER_PROP"]            = $arrUserContractHolder;
            $this->arResult["USER_LOGIN"]           = CUser::GetLogin();

            $this->arResult["ELEMENT"]              = $this->getElement($this->arResult["ELEMENT_ID"]);
            $this->arResult["PROPERTY"]             = $this->getProperty($this->arResult["INFOBLOCK_ID"], $this->arResult["ELEMENT_ID"]);
            $this->arResult["LIST_CATEGORY"]        = $this->getListCategory();
            $this->arResult["CONTRACT_PROPERTY"]    = $this->getPropertyContract($this->arResult["INFOBLOCK_C_ID"]);
            $this->arResult["THREE_TEMPLATE"]       = $this->getTemplateContractCategote();

            if(!empty($this->ID_CONTRACT)){                
                $this->arResult["TEMPLATE_CONTENT"] = $this->getElement($this->ID_CONTRACT);
                $this->arResult["TEMPLATE_CONTENT_PROPERTY"]    = $this->getMultyProperty(5, $_GET["ID_TEMPLATE"]);
                $this->arResult["DOGOVOR_IMG"] = $this->getProperty(4, $this->ID_CONTRACT)['DOGOVOR_IMG'];
            }

            if(!empty($_GET["ID_TEMPLATE"])){                
                $this->arResult["TEMPLATE_CONTENT"] = $this->getElement($_GET["ID_TEMPLATE"]);
                $this->arResult["TEMPLATE_CONTENT_PROPERTY"]    = $this->getMultyProperty(5, $_GET["ID_TEMPLATE"]);
                $this->arResult["DOGOVOR_IMG"] = $this->getProperty(4, $this->arResult["ELEMENT_ID"])['DOGOVOR_IMG'];
            }

            #поиск имеющихся своих редакций для этой сделки по пользователю
            $this->arResult['NEW_REDACTION'] = $this->getNewRedaction($userId, $this->arResult["ELEMENT"]);

            $this->EndResultCache();
        //}
        // статус подписанного контракта
        $this->arResult['SEND_CONTRACT'] = 'N';
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

            $rsUser = CUser::GetByID($userId);
            $UserParams = $rsUser->Fetch();
            $ESIA_ID = $UserParams['UF_ESIA_ID'];
            // проверим идентификаторы из есиа и из профиля пользователя
            if($info['user_id'] == $ESIA_ID){                                 
                /*
                    подписываем контракт
                    статусы подписания 1-подписан с одной стороны, 2- подписан с двух сторон, 3- изменен
                */
                $Params = array(
                    'UF_VER_CODE_USER_A'    => '',
                    'UF_ID_USER_A'          => $this->arResult["CONTRACT_PROPERTY"]["CONTRACT_PROPERTY"]["USER_A"]["VALUE"], // владелец договора
                    'UF_TEL_CODE_USER_A'    => '', //пока не заполняем авторизация через ЕСИА
                    'UF_TIME_SEND_USER_A'   => ConvertTimeStamp(time(), "FULL"),
                    'UF_ID_CONTRACT'        => '',
                    'UF_ID_USER_B'          => $userId, // подписавшая сторона
                    'UF_VER_CODE_USER_B'    => $info['user_info']['eTag'],
                    'UF_TEL_CODE_USER_B'    => '',
                    'UF_TIME_SEND_USER_B'   => ConvertTimeStamp(time(), "FULL"),
                    'UF_STATUS'             => 1,
                    'UF_HASH_SEND'          => '',
                    'UF_ID_SEND_USER'       => $userId 
                );
                $this->sendContract($Params);
                $this->arResult['SEND_CONTRACT'] = 'Y';
            }else{
                // выводим ошибку
                $this->arResult['SEND_CONTRACT'] = 'ERR_ID';
            }
        }
        $this->arResult["SIGN_DOGOVOR"] = $this->getSendContractItem($this->arResult['PROPERTY']['ID_DOGOVORA']['VALUE'], $this->arResult['USER_ID']);
        $this->includeComponentTemplate();
        
        return $this->arResult;
    }
};

?>