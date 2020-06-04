<?/*  АО "НФК-Сбережения" 03.06.2020 */
class CCustUser extends CUser {
    public static function GetList(&$by, &$order, $arFilter=Array(), $arParams=Array())
    {
        /** @global CUserTypeManager $USER_FIELD_MANAGER */
        global $DB, $USER_FIELD_MANAGER;

        $err_mess = (CUser::err_mess())."<br>Function: GetList<br>Line: ";

        if (is_array($by))
        {
            $bSingleBy = false;
            $arOrder = $by;
        }
        else
        {
            $bSingleBy = true;
            $arOrder = array($by=>$order);
        }

        static $obUserFieldsSql;
        if (!isset($obUserFieldsSql))
        {
            $obUserFieldsSql = new CUserTypeSQL;
            $obUserFieldsSql->SetEntity("USER", "U.ID");
            $obUserFieldsSql->obWhere->AddFields(array(
                "F_LAST_NAME" => array(
                    "TABLE_ALIAS" => "U",
                    "FIELD_NAME" => "U.LAST_NAME",
                    "MULTIPLE" => "N",
                    "FIELD_TYPE" => "string",
                    "JOIN" => false,
                ),
            ));
        }
        $obUserFieldsSql->SetSelect($arParams["SELECT"]);
        $obUserFieldsSql->SetFilter($arFilter);
        $obUserFieldsSql->SetOrder($arOrder);

        $arFields_m = array("ID", "ACTIVE", "LAST_LOGIN", "LOGIN", "EMAIL", "NAME", "LAST_NAME", "SECOND_NAME", "TIMESTAMP_X", "PERSONAL_BIRTHDAY", "IS_ONLINE", "IS_REAL_USER");
        $arFields = array(
            "DATE_REGISTER", "PERSONAL_PROFESSION", "PERSONAL_WWW", "PERSONAL_ICQ", "PERSONAL_GENDER", "PERSONAL_PHOTO", "PERSONAL_PHONE", "PERSONAL_FAX",
            "PERSONAL_MOBILE", "PERSONAL_PAGER", "PERSONAL_STREET", "PERSONAL_MAILBOX", "PERSONAL_CITY", "PERSONAL_STATE", "PERSONAL_ZIP", "PERSONAL_COUNTRY", "PERSONAL_NOTES",
            "WORK_COMPANY", "WORK_DEPARTMENT", "WORK_POSITION", "WORK_WWW", "WORK_PHONE", "WORK_FAX", "WORK_PAGER", "WORK_STREET", "WORK_MAILBOX", "WORK_CITY", "WORK_STATE",
            "WORK_ZIP", "WORK_COUNTRY", "WORK_PROFILE", "WORK_NOTES", "ADMIN_NOTES", "XML_ID", "LAST_NAME", "SECOND_NAME", "STORED_HASH", "CHECKWORD_TIME", "EXTERNAL_AUTH_ID",
            "CONFIRM_CODE", "LOGIN_ATTEMPTS", "LAST_ACTIVITY_DATE", "AUTO_TIME_ZONE", "TIME_ZONE", "TIME_ZONE_OFFSET", "PASSWORD", "CHECKWORD", "LID", "LANGUAGE_ID", "TITLE",
        );
        $arFields_all = array_merge($arFields_m, $arFields);

        $arSelectFields = array();
        $online_interval = (array_key_exists("ONLINE_INTERVAL", $arParams) && intval($arParams["ONLINE_INTERVAL"]) > 0 ? $arParams["ONLINE_INTERVAL"] : CUser::GetSecondsForLimitOnline());
        if (isset($arParams['FIELDS']) && is_array($arParams['FIELDS']) && count($arParams['FIELDS']) > 0 && !in_array("*", $arParams['FIELDS']))
        {
            foreach ($arParams['FIELDS'] as $field)
            {
                $field = strtoupper($field);
                if ($field == 'TIMESTAMP_X' || $field == 'DATE_REGISTER' || $field == 'LAST_LOGIN')
                    $arSelectFields[$field] = $DB->DateToCharFunction("U.".$field)." ".$field.", U.".$field." ".$field."_DATE";
                elseif ($field == 'PERSONAL_BIRTHDAY')
                    $arSelectFields[$field] = $DB->DateToCharFunction("U.PERSONAL_BIRTHDAY", "SHORT")." PERSONAL_BIRTHDAY, U.PERSONAL_BIRTHDAY PERSONAL_BIRTHDAY_DATE";
                elseif ($field == 'IS_ONLINE')
                    $arSelectFields[$field] = "IF(U.LAST_ACTIVITY_DATE > DATE_SUB(NOW(), INTERVAL ".$online_interval." SECOND), 'Y', 'N') IS_ONLINE";
                elseif ($field == 'IS_REAL_USER')
                    $arSelectFields[$field] = "IF(U.EXTERNAL_AUTH_ID IN ('".join("', '", CUser::GetExternalUserTypes())."'), 'N', 'Y') IS_REAL_USER";
                elseif (in_array($field, $arFields_all))
                    $arSelectFields[$field] = 'U.'.$field;
            }
        }
        if (empty($arSelectFields))
        {
            $arSelectFields[] = 'U.*';
            $arSelectFields['TIMESTAMP_X'] =    $DB->DateToCharFunction("U.TIMESTAMP_X")." TIMESTAMP_X";
            $arSelectFields['IS_ONLINE'] =    "IF(U.LAST_ACTIVITY_DATE > DATE_SUB(NOW(), INTERVAL ".$online_interval." SECOND), 'Y', 'N') IS_ONLINE";
            $arSelectFields['DATE_REGISTER'] =    $DB->DateToCharFunction("U.DATE_REGISTER")." DATE_REGISTER";
            $arSelectFields['LAST_LOGIN'] =    $DB->DateToCharFunction("U.LAST_LOGIN")." LAST_LOGIN";
            $arSelectFields['PERSONAL_BIRTHDAY'] =    $DB->DateToCharFunction("U.PERSONAL_BIRTHDAY", "SHORT")." PERSONAL_BIRTHDAY";
        }

        $arSqlSearch = Array();
        $strJoin = "";

        if(is_array($arFilter))
        {
            foreach ($arFilter as $key => $val)
            {
                $key = strtoupper($key);
                if(is_array($val))
                {
                    if(count($val) <= 0)
                        continue;
                }
                elseif
                (
                    $key != "LOGIN_EQUAL_EXACT"
                    && $key != "CONFIRM_CODE"
                    && $key != "!CONFIRM_CODE"
                    && $key != "LAST_ACTIVITY"
                    && $key != "!LAST_ACTIVITY"
                    && $key != "LAST_LOGIN"
                    && $key != "!LAST_LOGIN"
                    && $key != "EXTERNAL_AUTH_ID"
                    && $key != "!EXTERNAL_AUTH_ID"
                    && $key != "IS_REAL_USER"
                )
                {
                    if(strlen($val) <= 0 || $val === "NOT_REF")
                        continue;
                }
                $match_value_set = array_key_exists($key."_EXACT_MATCH", $arFilter);
                switch($key)
                {
                case "ID":
                    $arSqlSearch[] = GetFilterQuery("U.ID",$val,"N");
                    break;
                case ">ID":
                    $arSqlSearch[] = "U.ID > ".intval($val);
                    break;
                case "!ID":
                    $arSqlSearch[] = "U.ID <> ".intval($val);
                    break;
                case "ID_EQUAL_EXACT":
                    $arSqlSearch[] = "U.ID='".intval($val)."'";
                    break;
                case "TIMESTAMP_1":
                    $arSqlSearch[] = "U.TIMESTAMP_X >= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"D.M.Y"),"d.m.Y")."')";
                    break;
                case "TIMESTAMP_2":
                    $arSqlSearch[] = "U.TIMESTAMP_X <= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"D.M.Y")." 23:59:59","d.m.Y")."')";
                    break;
                case "TIMESTAMP_X_1":
                    $arSqlSearch[] = "U.TIMESTAMP_X >= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;
                case "TIMESTAMP_X_2":
                    $arSqlSearch[] = "U.TIMESTAMP_X <= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;
                case "LAST_LOGIN_1":
                    $arSqlSearch[] = "U.LAST_LOGIN >= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"D.M.Y"),"d.m.Y")."')";
                    break;
                case "LAST_LOGIN_2":
                    $arSqlSearch[] = "U.LAST_LOGIN <= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"D.M.Y")." 23:59:59","d.m.Y")."')";
                    break;
                case "LAST_LOGIN":
                    if ($val === false)
                        $arSqlSearch[] = "U.LAST_LOGIN IS NULL";
                    break;
                case "!LAST_LOGIN":
                    if ($val === false)
                        $arSqlSearch[] = "U.LAST_LOGIN IS NOT NULL";
                    break;
                case "DATE_REGISTER_1":
                    $arSqlSearch[] = "U.DATE_REGISTER >= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"D.M.Y"),"d.m.Y")."')";
                    break;
                case "DATE_REGISTER_2":
                    $arSqlSearch[] = "U.DATE_REGISTER <= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"D.M.Y")." 23:59:59","d.m.Y")."')";
                    break;
                case "ACTIVE":
                    $arSqlSearch[] = ($val=="Y") ? "U.ACTIVE='Y'" : "U.ACTIVE='N'";
                    break;
                case "LOGIN_EQUAL":
                    $arSqlSearch[] = GetFilterQuery("U.LOGIN", $val, "N");
                    break;
                case "LOGIN":
                    $arSqlSearch[] = GetFilterQuery("U.LOGIN", $val);
                    break;
                case "EXTERNAL_AUTH_ID":
                    if($val <> '')
                        $arSqlSearch[] = "U.EXTERNAL_AUTH_ID='".$DB->ForSQL($val, 255)."'";
                    else
                        $arSqlSearch[] = "(U.EXTERNAL_AUTH_ID IS NULL OR U.EXTERNAL_AUTH_ID='')";
                    break;
                case "!EXTERNAL_AUTH_ID":
                    if (
                        is_array($val)
                        && count($val) > 0
                    )
                    {
                        $strTmp = "";
                        foreach($val as $authId)
                        {
                            if (strlen($authId) > 0)
                            {
                                $strTmp .= (strlen($strTmp) > 0 ? "," : "")."'".$DB->ForSQL($authId, 255)."'";
                            }
                        }
                        if (strlen($strTmp) > 0)
                        {
                            $arSqlSearch[] = "U.EXTERNAL_AUTH_ID NOT IN (".$strTmp.") OR U.EXTERNAL_AUTH_ID IS NULL";
                        }
                    }
                    elseif (!is_array($val))
                    {
                        if($val <> '')
                            $arSqlSearch[] = "U.EXTERNAL_AUTH_ID <> '".$DB->ForSql($val, 255)."' OR U.EXTERNAL_AUTH_ID IS NULL";
                        else
                            $arSqlSearch[] = "(U.EXTERNAL_AUTH_ID IS NOT NULL AND LENGTH(U.EXTERNAL_AUTH_ID) > 0)";
                    }
                    break;
                case "LOGIN_EQUAL_EXACT":
                    $arSqlSearch[] = "U.LOGIN='".$DB->ForSql($val)."'";
                    break;
                case "XML_ID":
                    $arSqlSearch[] = "U.XML_ID='".$DB->ForSql($val)."'";
                    break;
                case "CONFIRM_CODE":
                    if($val <> '')
                        $arSqlSearch[] = "U.CONFIRM_CODE='".$DB->ForSql($val)."'";
                    else
                        $arSqlSearch[] = "(U.CONFIRM_CODE IS NULL OR LENGTH(U.CONFIRM_CODE) <= 0)";
                    break;
                case "!CONFIRM_CODE":
                    if($val <> '')
                        $arSqlSearch[] = "U.CONFIRM_CODE <> '".$DB->ForSql($val)."'";
                    else
                        $arSqlSearch[] = "(U.CONFIRM_CODE IS NOT NULL AND LENGTH(U.CONFIRM_CODE) > 0)";
                    break;
                case "COUNTRY_ID":
                case "WORK_COUNTRY":
                    $arSqlSearch[] = "U.WORK_COUNTRY=".intval($val);
                    break;
                case "PERSONAL_COUNTRY":
                    $arSqlSearch[] = "U.PERSONAL_COUNTRY=".intval($val);
                    break;
                case "NAME":
                    $arSqlSearch[] = GetFilterQuery("U.NAME, U.LAST_NAME, U.SECOND_NAME", $val);
                    break;
                case "NAME_SEARCH":
                    $arSqlSearch[] = GetFilterQuery("U.NAME, U.LAST_NAME, U.SECOND_NAME, U.EMAIL, U.LOGIN", $val);
                    break;
                case "EMAIL":
                    $arSqlSearch[] = GetFilterQuery("U.EMAIL", $val, "Y", array("@","_",".","-"));
                    break;
                case "=EMAIL":
                    $arSqlSearch[] = "U.EMAIL = '".$DB->ForSQL(trim($val))."'";
                    break;
                case "GROUP_MULTI":
                case "GROUPS_ID":
                    if(is_numeric($val) && intval($val)>0)
                        $val = array($val);
                    if(is_array($val) && count($val)>0)
                    {
                        $ar = array();
                        foreach($val as $id)
                            $ar[intval($id)] = intval($id);
                        $strJoin .=
                            " INNER JOIN (SELECT DISTINCT UG.USER_ID FROM b_user_group UG
                            WHERE UG.GROUP_ID in (".implode(",", $ar).")
                                and (UG.DATE_ACTIVE_FROM is null or    UG.DATE_ACTIVE_FROM <= ".$DB->CurrentTimeFunction().")
                                and (UG.DATE_ACTIVE_TO is null or UG.DATE_ACTIVE_TO >= ".$DB->CurrentTimeFunction().")
                            ) UG ON UG.USER_ID=U.ID ";
                    }
                    break;
                case "PERSONAL_BIRTHDATE_1":
                    $arSqlSearch[] = "U.PERSONAL_BIRTHDATE>=".$DB->CharToDateFunction($val);
                    break;
                case "PERSONAL_BIRTHDATE_2":
                    $arSqlSearch[] = "U.PERSONAL_BIRTHDATE<=".$DB->CharToDateFunction($val." 23:59:59");
                    break;
                case "PERSONAL_BIRTHDAY_1":
                    $arSqlSearch[] = "U.PERSONAL_BIRTHDAY>=".$DB->CharToDateFunction($DB->ForSql($val), "SHORT");
                    break;
                case "PERSONAL_BIRTHDAY_2":
                    $arSqlSearch[] = "U.PERSONAL_BIRTHDAY<=".$DB->CharToDateFunction($DB->ForSql($val), "SHORT");
                    break;
                case "PERSONAL_BIRTHDAY_DATE":
                    $arSqlSearch[] = "DATE_FORMAT(U.PERSONAL_BIRTHDAY, '%m-%d') = '".$DB->ForSql($val)."'";
                    break;
                case "KEYWORDS":
                    $arSqlSearch[] = GetFilterQuery(implode(",",$arFields), $val);
                    break;
                case "CHECK_SUBORDINATE":
                    if(is_array($val))
                    {
                        $strSubord = "0";
                        foreach($val as $grp)
                            $strSubord .= ",".intval($grp);
                        if(intval($arFilter["CHECK_SUBORDINATE_AND_OWN"]) > 0)
                            $arSqlSearch[] = "(U.ID=".intval($arFilter["CHECK_SUBORDINATE_AND_OWN"])." OR NOT EXISTS(SELECT 'x' FROM b_user_group UGS WHERE UGS.USER_ID=U.ID AND UGS.GROUP_ID NOT IN (".$strSubord.")))";
                        else
                            $arSqlSearch[] = "NOT EXISTS(SELECT 'x' FROM b_user_group UGS WHERE UGS.USER_ID=U.ID AND UGS.GROUP_ID NOT IN (".$strSubord."))";
                    }
                    break;
                case "NOT_ADMIN":
                    if($val !== true)
                        break;
                    $arSqlSearch[] = "not exists (SELECT * FROM b_user_group UGNA WHERE UGNA.USER_ID=U.ID AND UGNA.GROUP_ID = 1)";
                    break;
                case "LAST_ACTIVITY":
                    if ($val === false)
                        $arSqlSearch[] = "U.LAST_ACTIVITY_DATE IS NULL";
                    elseif (intval($val)>0)
                        $arSqlSearch[] = "U.LAST_ACTIVITY_DATE > DATE_SUB(NOW(), INTERVAL ".intval($val)." SECOND)";
                    break;
                case "!LAST_ACTIVITY":
                    if ($val === false)
                        $arSqlSearch[] = "U.LAST_ACTIVITY_DATE IS NOT NULL";
                    break;
                case "INTRANET_USERS":
                    $arSqlSearch[] = "U.ACTIVE = 'Y' AND U.LAST_LOGIN IS NOT NULL AND EXISTS(SELECT 'x' FROM b_utm_user UF1, b_user_field F1 WHERE F1.ENTITY_ID = 'USER' AND F1.FIELD_NAME = 'UF_DEPARTMENT' AND UF1.FIELD_ID = F1.ID AND UF1.VALUE_ID = U.ID AND UF1.VALUE_INT IS NOT NULL AND UF1.VALUE_INT <> 0)";
                    break;
                case "IS_REAL_USER":
                    if($val === true || $val === 'Y')
                    {
                        $arSqlSearch[] = "U.EXTERNAL_AUTH_ID NOT IN ('".join("', '", CUser::GetExternalUserTypes())."') OR U.EXTERNAL_AUTH_ID IS NULL";
                    }
                    else
                    {
                        $arSqlSearch[] = "U.EXTERNAL_AUTH_ID IN ('".join("', '", CUser::GetExternalUserTypes())."')";
                    }
                    break;
                default:
                    if(in_array($key, $arFields))
                        $arSqlSearch[] = GetFilterQuery('U.'.$key, $val, ($arFilter[$key."_EXACT_MATCH"]=="Y" && $match_value_set? "N" : "Y"));
                }
            }
        }

        $arSqlOrder = array();
        foreach ($arOrder as $field => $dir)
        {
            $field = strtoupper($field);
            if(strtolower($dir) <> "asc")
            {
                $dir = "desc";
                if ($bSingleBy)
                    $order = "desc";
            }

            if($field == "CURRENT_BIRTHDAY")
            {
                $cur_year = intval(date('Y'));
                $arSqlOrder[$field] = "IF(ISNULL(U.PERSONAL_BIRTHDAY), '9999-99-99', IF (
                    DATE_FORMAT(U.PERSONAL_BIRTHDAY, '".$cur_year."-%m-%d') < DATE_FORMAT(DATE_ADD(".$DB->CurrentTimeFunction().", INTERVAL ".CTimeZone::GetOffset()." SECOND), '%Y-%m-%d'),
                    DATE_FORMAT(U.PERSONAL_BIRTHDAY, '".($cur_year + 1)."-%m-%d'),
                    DATE_FORMAT(U.PERSONAL_BIRTHDAY, '".$cur_year."-%m-%d')
                )) ".$dir;
            }
            elseif($field == "IS_ONLINE")
            {
                $arSelectFields[$field] = "IF(U.LAST_ACTIVITY_DATE > DATE_SUB(NOW(), INTERVAL ".$online_interval." SECOND), 'Y', 'N') IS_ONLINE";
                $arSqlOrder[$field] = "IS_ONLINE ".$dir;
            }
            elseif(in_array($field,$arFields_all))
            {
                $arSqlOrder[$field] = "U.".$field." ".$dir;
            }
            elseif($s = $obUserFieldsSql->GetOrder($field))
            {
                $arSqlOrder[$field] = strtoupper($s)." ".$dir;
            }
            elseif(preg_match('/^RATING_(\d+)$/i', $field, $matches))
            {
                $ratingId = intval($matches[1]);
                if ($ratingId > 0)
                {
                    $arSqlOrder[$field] = $field."_ISNULL ASC, ".$field." ".$dir;
                    $arParams['SELECT'][] = $field;
                }
                else
                {
                    $field = "TIMESTAMP_X";
                    $arSqlOrder[$field] = "U.".$field." ".$dir;
                    if ($bSingleBy)
                        $by = strtolower($field);
                }
            }
            elseif ($field == 'FULL_NAME')
            {
                $arSqlOrder[$field] = sprintf(
                    "IF(U.LAST_NAME IS NULL OR U.LAST_NAME = '', 1, 0) %1\$s,
                    IF(U.LAST_NAME IS NULL OR U.LAST_NAME = '', 1, U.LAST_NAME) %1\$s,
                    IF(U.NAME IS NULL OR U.NAME = '', 1, 0) %1\$s,
                    IF(U.NAME IS NULL OR U.NAME = '', 1, U.NAME) %1\$s,
                    IF(U.SECOND_NAME IS NULL OR U.SECOND_NAME = '', 1, 0) %1\$s,
                    IF(U.SECOND_NAME IS NULL OR U.SECOND_NAME = '', 1, U.SECOND_NAME) %1\$s,
                    U.LOGIN %1\$s", $dir
                );
            }elseif ($field == 'RAND')
            {
                $arSqlOrder[$field] = 'rand()';
            }
        }

        $userFieldsSelect = $obUserFieldsSql->GetSelect();
        $arSqlSearch[] = $obUserFieldsSql->GetFilter();
        $strSqlSearch = GetFilterSqlSearch($arSqlSearch);

        $sSelect = ($obUserFieldsSql->GetDistinct()? "DISTINCT " : "")
            .implode(', ',$arSelectFields)."
            ".$userFieldsSelect."
        ";

        if (is_array($arParams['SELECT']))
        {
            $arRatingInSelect = array();
            foreach ($arParams['SELECT'] as $column)
            {
                if(preg_match('/^RATING_(\d+)$/i', $column, $matches))
                {
                    $ratingId = intval($matches[1]);
                    if ($ratingId > 0 && !in_array($ratingId, $arRatingInSelect))
                    {
                        $sSelect .= ", RR".$ratingId.".CURRENT_POSITION IS NULL as RATING_".$ratingId."_ISNULL";
                        $sSelect .= ", RR".$ratingId.".CURRENT_VALUE as RATING_".$ratingId;
                        $sSelect .= ", RR".$ratingId.".CURRENT_VALUE as RATING_".$ratingId."_CURRENT_VALUE";
                        $sSelect .= ", RR".$ratingId.".PREVIOUS_VALUE as RATING_".$ratingId."_PREVIOUS_VALUE";
                        $sSelect .= ", RR".$ratingId.".CURRENT_POSITION as RATING_".$ratingId."_CURRENT_POSITION";
                        $sSelect .= ", RR".$ratingId.".PREVIOUS_POSITION as RATING_".$ratingId."_PREVIOUS_POSITION";
                        $strJoin .=    " LEFT JOIN b_rating_results RR".$ratingId."
                            ON RR".$ratingId.".RATING_ID=".$ratingId."
                            and RR".$ratingId.".ENTITY_TYPE_ID = 'USER'
                            and RR".$ratingId.".ENTITY_ID = U.ID ";
                        $arRatingInSelect[] = $ratingId;
                    }
                }
            }
        }
        $strFrom = "
            FROM
                b_user U
                ".$obUserFieldsSql->GetJoin("U.ID")."
                ".$strJoin."
            WHERE
                ".$strSqlSearch."
            ";

        $strSqlOrder = '';
        if (!empty($arSqlOrder))
            $strSqlOrder = 'ORDER BY '.implode(', ', $arSqlOrder);

        $strSql = "SELECT ".$sSelect.$strFrom.$strSqlOrder;

        if(array_key_exists("NAV_PARAMS", $arParams) && is_array($arParams["NAV_PARAMS"]))
        {
            $nTopCount = intval($arParams['NAV_PARAMS']['nTopCount']);
            if($nTopCount > 0)
            {
                $strSql = $DB->TopSql($strSql, $nTopCount);
                $res = $DB->Query($strSql, false, $err_mess.__LINE__);
                if($userFieldsSelect <> '')
                    $res->SetUserFields($USER_FIELD_MANAGER->GetUserFields("USER"));
            }
            else
            {
                $res_cnt = $DB->Query("SELECT COUNT(".($obUserFieldsSql->GetDistinct()? "DISTINCT ":"")."U.ID) as C ".$strFrom);
                $res_cnt = $res_cnt->Fetch();
                $res = new CDBResult();
                if($userFieldsSelect <> '')
                    $res->SetUserFields($USER_FIELD_MANAGER->GetUserFields("USER"));
                $res->NavQuery($strSql, $res_cnt["C"], $arParams["NAV_PARAMS"]);
            }
        }
        else
        {
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
            if($userFieldsSelect <> '')
                $res->SetUserFields($USER_FIELD_MANAGER->GetUserFields("USER"));
        }

        $res->is_filtered = IsFiltered($strSqlSearch);
        return $res;
    }
}
?>