<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
    Класс выводит информацию в карточку по сделке
*/

class UserProfile extends CBitrixComponent
{       
    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
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
        return $arrUserInfo;
    }

    public function executeComponent()
    {
        global $USER;
        // if($this->startResultCache($USER))
        // {
            //$this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult = $this->getUserInfo($USER);
            $this->includeComponentTemplate();
        // }
        
        return $this->arResult;
    }
};

?>