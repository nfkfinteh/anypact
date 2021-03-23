<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CMoneta.php");

use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CMonetaBalance extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
        );
        return $result;
    }

    private function getUserData($user_id){
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('FIELDS' => array("ID"), 'SELECT' => array("UF_MONETA_UNIT_ID", "UF_MONETA_ACCOUNT_ID", "UF_MONETA_BALANCE", "UF_MONETA_DOC_ID")));
        if($arUser = $res->Fetch()) 
            return $arUser;
        return false;
    }

    public function executeComponent()
    {
        global $USER;
        if($USER -> IsAuthorized()){
            $this->arResult["CURRENT_USER"] = self::getUserData($USER -> GetID());
            $this->includeComponentTemplate();
            return $this->arResult;
        }
    }
};

?>