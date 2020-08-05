<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class AllOkCommentList extends CBitrixComponent
{
    public static function GetAuthor($user_id = 0)
    {
        $result = array();
        if ($user_id > 0)
        {
            $user = CUser::GetByID($user_id)->Fetch();
            if ($user)
            {
                $result["ID"] = $user_id;
                $result["LOGIN"] = $user["LOGIN"];
                $result["NAME"] = ($user["NAME"] ? $user["NAME"] : $user["LOGIN"]);
                $result["FULL_NAME"] = ($user["NAME"] || $user["LAST_NAME"] ? $user["NAME"].($user["NAME"] && $user["LAST_NAME"] ? " " : "").$user["LAST_NAME"] : $user["LOGIN"]);
                $result["PERSONAL_PHOTO"] = CFile::GetPath($user['PERSONAL_PHOTO']);
            }
        }
        return $result;
    }

    public static function getAuthorSdelka($idSdelka){
        $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>3, 'ID'=>$idSdelka, 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'PROPERTY_PACT_USER']);
        if($obj=$res->GetNext(true, false)) $result = $obj;
        return $result['PROPERTY_PACT_USER_VALUE'];
    }

    public static function getEditSdelka($idComment, $iblockID){
        $hlbl = $iblockID;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID"=>$idComment)
        ));
        if($obj = $rsData->Fetch()){
            $result = $obj;
        }
        return $result;
    }

    public function getBlackList($user_a, $user_b){
        if(CModule::IncludeModule("highloadblock"))
        {
            $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(15)->fetch();
            $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array("ID" => "ASC"),
                "filter" => array("UF_USER_A" => $user_a, "UF_USER_B" => $user_b)
            ));
            while($arData = $rsData->Fetch()){
                $result = true;
            }
        }
        if(empty($result)){
            $result = false;
        }

        return $result;
    }
}