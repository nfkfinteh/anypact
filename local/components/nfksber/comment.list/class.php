<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
}