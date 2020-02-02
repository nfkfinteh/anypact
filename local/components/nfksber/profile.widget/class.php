<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
/*
    Класс выводит информацию в карточку по сделке
*/
class UserProfile extends CBitrixComponent
{
    public $IBLOCK_ID_MESSAGE = 6;//id HL с сообщениями
    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "IS_PAGE_MESSAGE" => $arParams["IS_PAGE_MESSAGE"]
        );
        return $result;
    }

    private function getUserInfo($USER){
        $arrUserInfo["ID"] = $USER->GetID();
        $arrUserInfo["NAME"] = $USER->GetFirstName(); 
        $arrUserInfo["IN_NAME"] = substr($USER->GetFirstName(), 0, 1); 
        $arrUserInfo["SECOND_NAME"] = $USER->GetSecondName(); // отчество
        $arrUserInfo["LAST_NAME"] = $USER->GetLastName(); // фамилия
        $arrUserInfo["IN_NAMES"] = substr($USER->GetFirstName(), 0, 1).'.'.substr($USER->GetSecondName(), 0, 1).'.'; // Инициалы
        $UserParams = $USER->GetByID($arrUserInfo["ID"]);
        $UserParams = $UserParams->Fetch();
        $arrUserInfo["PERSONAL_PHOTO"] = CFile::GetPath($UserParams["PERSONAL_PHOTO"]);

        if(!empty($UserParams['UF_CUR_COMPANY'])){
            if(CModule::IncludeModule('iblock')){
                $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>8, 'ID'=>$UserParams['UF_CUR_COMPANY'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_PICTURE']);
                if($obj=$res->GetNext(true, false)){
                    $arrUserInfo['ACTIVE_COMPANY'] = $obj;
                }
            }
        }


        return $arrUserInfo;
    }

    private function resetUnreadMessage($idMessage, $idUser){
        $hlblock = HL\HighloadBlockTable::getById($this->IBLOCK_ID_MESSAGE)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $idMessage)
        ));

        while($arData = $rsData->Fetch()){
            $UF_ID_RECIPIENT  = $arData['UF_ID_RECIPIENT'];
        }

        if($UF_ID_RECIPIENT == $idUser){
            $data = array(
                "UF_ID_RECIPIENT"=>'',
            );
            $entity_data_class::update($idMessage, $data);
        }
    }

    private function getCntUnreadMessage($idUser){
        $hlblock = HL\HighloadBlockTable::getById($this->IBLOCK_ID_MESSAGE)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ID_RECIPIENT" => $idUser)
        ));

        return $rsData->getSelectedRowsCount();
    }

    public function executeComponent()
    {
        global $USER;
        CModule::IncludeModule("highloadblock");
        $this->arResult = $this->getUserInfo($USER);
        if($this->arParams['IS_PAGE_MESSAGE']=='Y'){
            $this->resetUnreadMessage(intval($_GET['id']), $this->arResult['ID']);//обновляем счетчик не прочитанных сообщений
        }
        $this->arResult['UNREAD_MESSAGE'] = $this->getCntUnreadMessage( $this->arResult['ID']);
        $this->includeComponentTemplate();

        
        return $this->arResult;
    }
};

?>