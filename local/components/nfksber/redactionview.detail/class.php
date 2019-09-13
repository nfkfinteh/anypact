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
        $db_props       = CIBlockElement::GetProperty($id_iblok, $id_element, "sort", "asc", array());         
        $array_props    = array();        
        $array_img      = array();

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

        }
        
        $array_props["IMG_FILE"] = $array_img;
        $array_props["DOGOVOR_IMG"] = $array_img_dogovor;

        if(!empty($array_props["ID_DOGOVORA"]["VALUE"])){
            $this->ID_CONTRACT = $array_props["ID_DOGOVORA"]["VALUE"];            
        }

        return $array_props;
    }

    private function convertContent($Content){
        
        $regexp 		= "/%DATE%/ui";
        $replacement 	= date("d m Y") ;
        $Content = preg_replace($regexp, $replacement, $Content);

        $regexp 		= '/<recont fio.*recont>/ui';
        $replacement 	= 'Соловьёв Игорь Владимирович' ;
        $Content = preg_replace($regexp, $replacement, $Content);
        return $Content;
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

    private function getNewRedaction($userId, $arSdelka){
        new dBug($arSdelka);
        new dBug($userId);
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
        if($this->startResultCache($this->arParams['CACHE_TIME'], $_GET['SECTION_ID'].$_GET['ELEMENT_ID'].$userId))
        {
            $this->arResult                         = array_merge($this->arResult, $this->paramsUser($this->arParams));
            // данные владельца сделки           
            $UserContractHolder                     = CUser::GetByID(CUser::GetID());
            $arrUserContractHolder                  = $UserContractHolder->Fetch();
            $this->USER_PROPERTY                    = $arrUserContractHolder;
            $this->arResult["USER_PROP"]            = $arrUserContractHolder;
            $this->arResult["USER_LOGIN"]           = CUser::GetLogin();

            $this->arResult["ELEMENT"]              = $this->getElement($this->arResult["ELEMENT_ID"]);
            $this->arResult["PROPERTY"]             = $this->getProperty($this->arResult["INFOBLOCK_ID"], $this->arResult["ELEMENT_ID"]);

            /*if(!empty($this->ID_CONTRACT)){
                $this->arResult["TEMPLATE_CONTENT"] = $this->getElement($this->ID_CONTRACT);
                $this->arResult["TEMPLATE_CONTENT_PROPERTY"]    = $this->getMultyProperty(5, $_GET["ID_TEMPLATE"]);
                $this->arResult["DOGOVOR_IMG"] = $this->getProperty(4, $this->ID_CONTRACT)['DOGOVOR_IMG'];
            }

            if(!empty($_GET["ID_TEMPLATE"])){                
                $this->arResult["TEMPLATE_CONTENT"] = $this->getElement($_GET["ID_TEMPLATE"]);
                $this->arResult["TEMPLATE_CONTENT_PROPERTY"]    = $this->getMultyProperty(5, $_GET["ID_TEMPLATE"]);
                $this->arResult["DOGOVOR_IMG"] = $this->getProperty(4, $this->arResult["ELEMENT_ID"])['DOGOVOR_IMG'];
            }*/

            #поиск имеющихся своих редакций для этой сделки по пользователю
            //$this->arResult['NEW_REDACTION'] = $this->getNewRedaction($userId, $this->arResult["ELEMENT"]);

            $this->EndResultCache();
        }

        $this->arResult["SIGN_DOGOVOR"] = $this->getSendContractItem($this->arResult['PROPERTY']['ID_DOGOVORA']['VALUE'], $this->arResult['USER_ID']);
        $this->includeComponentTemplate();
        
        return $this->arResult;
    }
};

?>