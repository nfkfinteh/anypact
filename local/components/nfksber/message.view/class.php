<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader; 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

class CDemoSqr extends CBitrixComponent
{
    private $ID_Message;
    private $ID_HL;
    private $arIDUsers;

    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X" => intval($arParams["X"]),
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "ELEMENT_ID" => intval($arParams["ELEMENT_ID"]),
        );
        return $result;
    }

    

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["ELEMENT_ID"] = $arParams["ELEMENT_ID"];
        return $arResult;
    }

    private function getMessage(){
        
        CModule::IncludeModule("highloadblock");
        // получить все подписанны сделки        
        $hlblock = HL\HighloadBlockTable::getById($this->ID_HL)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $this->ID_Message)
        ));

        while($arData = $rsData->Fetch()){
            $arSendItem  = $arData;
            $this->arIDUsers = $arData['UF_USERS_ID'];
        }

        return $arSendItem;
    }

    private function listUsers(){
       // global $USER;
        $arUserParams = Array();
        foreach ($this->arIDUsers as $IDUser) {
                $ObjUser = CUser::GetByID($IDUser);
                $arUserParams = $ObjUser->Fetch();
                $arUserParams['PERSONAL_PHOTO'] = CFile::GetPath($arUserParams['PERSONAL_PHOTO']);
                $arParams[$arUserParams['ID']]  = $arUserParams;
        }
        return $arParams;
    }

    public function executeComponent()
    {
        /*if($this->startResultCache())
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));              
            $this->includeComponentTemplate();
        }*/
        
        $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));              
        // получить сообщения
        if(!empty($_GET['id'])){
            $this->ID_Message = $_GET['id'];
        }
        $this->ID_HL    = 6;
        $this->arResult['MESSAGES'] = $this->getMessage();
        $this->arResult['UsersChart'] = $this->listUsers();
        foreach($this->arResult['UsersChart'] as $user){
            $this->arResult['FastUserParams'][$user['ID']]['FIO'] = $user['LAST_NAME'] .' '.$user['NAME'].' '. $user['SECOND_NAME'] ;
        }
        $this->includeComponentTemplate();
        
        return $this->arResult;
    }
};

?>