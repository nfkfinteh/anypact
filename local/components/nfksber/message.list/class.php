<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader; 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
use Bitrix\Iblock;;

class CDemoSqr extends CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X" => intval($arParams["X"]),
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "ROWS_PER_PAGE"=> intval($arParams["ROWS_PER_PAGE"]),
            "PAGEN_ID" => $arParams["PAGEN_ID"]
        );
        return $result;
    }

    // сообщения пользователей
    private function getMessageUser($UserID){
        CModule::IncludeModule("highloadblock");
        $ID_hl_message_user = $this->arParams['IBLOCK_ID'];
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_message_user)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($ID_hl_message_user);

        // pagen
        if (isset($this->arParams['ROWS_PER_PAGE']) && $this->arParams['ROWS_PER_PAGE']>0)
        {
            $pagenId = isset($this->arParams['PAGEN_ID']) && trim($this->arParams['PAGEN_ID']) != '' ? trim($this->arParams['PAGEN_ID']) : 'page';
            $perPage = $this->arParams['ROWS_PER_PAGE'];

            $nav = new \Bitrix\Main\UI\PageNavigation($pagenId);
            $nav->allowAllRecords(true)
                ->setPageSize($perPage)
                ->initFromUri();
        }
        else
        {
            $this->arParams['ROWS_PER_PAGE'] = 0;
        }

        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array(
                "LOGIC" => "OR",
                "UF_ID_USER" => $UserID,
                "UF_ID_SENDER" => $UserID
            ),
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
        ));

        $nav->setRecordCount($rsData->getCount());
        $this->arResult['nav_object'] = $nav;

        $arMesage_User = array();
        $i = 0 ;
        while($arData = $rsData->Fetch()){
            $arData['UF_TEXT_MESSAGE_USER'] = json_decode($arData['UF_TEXT_MESSAGE_USER']);
            $arMesage_User[$i]  = $arData;
            if($arData["UF_ID_SENDER"] != $UserID){
                $idUserTitle = $arData["UF_ID_SENDER"];
            }
            elseif($arData["UF_ID_USER"] != $UserID){
                $idUserTitle = $arData["UF_ID_USER"];
            }

            //отображение для не прочитанных сообщений
            $arMesage_User[$i]['LAST_MESSAGE'] = end($arMesage_User[$i]['UF_TEXT_MESSAGE_USER'])->message;
            if($UserID==$arMesage_User[$i]['UF_ID_RECIPIENT']){
                $arMesage_User[$i]['UNREAD'] = 'Y';
            }

            $rsUser = CUser::GetByID($idUserTitle);
            $arUser = $rsUser->Fetch();
            $arMesage_User[$i]["PARAMS_SENDER_USER"]["FIO"]  = $arUser['LAST_NAME'] .' '. $arUser['NAME'] .' '. $arUser['SECOND_NAME'];
            $arMesage_User[$i]["PARAMS_SENDER_USER"]["PERSONAL_PHOTO"]  = $arUser["PERSONAL_PHOTO"];
            $arMesage_User[$i]["PARAMS_SENDER_USER"]["ID"]  = $arUser['ID'];
            $arMesage_User[$i]["PARAMS_SENDER_USER"]["IN"]  = substr($arUser['NAME'], 0, 1);

            $i++;
        }

        return $arMesage_User;
    }

    public function executeComponent()
    {
        $User_ID = CUser::GetID();
        // сообщение пользователю
        $this->arResult["MESSAGE_USER"] = $this->getMessageUser($User_ID);

        $this->includeComponentTemplate();
    }
};

?>