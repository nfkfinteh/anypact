<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CDemoSqr extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "FILTER_NAME"=> $arParams["FILTER_NAME"],
            "NEWS_COUNT" => intval($arParams["NEWS_COUNT"]),
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            "IBLOCK_COMPANY" => intval($arParams["IBLOCK_COMPANY"])
        );
        return $result;
    }

    public function listAllCompany($arNavParams) {

        $arCompany = [];

        if(CModule::IncludeModule("iblock"))
        {
            //внешняя фильтрация
            if(strlen($this->arParams['FILTER_NAME'])<=0)
            {
                $arrFilter = array();
            }
            else
            {
                $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];
                if(!is_array($arrFilter))
                    $arrFilter = array();
            }

            //удаляем фильтры для пользователя
            if(!empty($arrFilter['PERSONAL_CITY'])) $arrFilter['PROPERTY_CITY'] = $arrFilter['PERSONAL_CITY'];
            unset($arrFilter['!ID'], $arrFilter['UF_HIDE_PROFILE'], $arrFilter['PERSONAL_CITY'], $arrFilter['ACTION']);
            $arrFilter['IBLOCK_ID'] = $this->arParams['IBLOCK_COMPANY'];
            $arrFilter['ACTIVE'] = 'Y';

            $arSelect = [
                'IBLOCK_ID',
                'ID',
                'NAME',
                'PREVIEW_PICTURE'
            ];

            $res = CIBlockElement::GetList(['rand' => 'asc'], $arrFilter, false, $arNavParams, $arSelect);
            while($obj = $res->GetNext(true, false)) {
                if(!empty($obj['PREVIEW_PICTURE'])){
                    $obj['PREVIEW_PICTURE'] = CFile::GetPath( $obj['PREVIEW_PICTURE']);
                }
                $arCompany[] = $obj;
            }
            $navComponentParameters = array();
            $res->nPageWindow = 3;
            $this->arResult["NAV_STRING_COMPANY"] = $res->GetPageNavStringEx(
                $navComponentObject,
                '',
                $this->arParams["PAGER_TEMPLATE"],
                false,
                $this,
                $navComponentParameters
            );
            $this->arResult["NAV_CACHED_DATA_COMPANY"] = null;
            $this->arResult["NAV_RESULT_COMPANY"] = $res;
            $this->arResult["NAV_PARAM_COMPANY"] = $navComponentParameters;

        }
        return $arCompany;
    }

    public function getFrends(){
        global $USER;
        $current_user = $USER->GetID();
        $arFilter = array("ID" => $current_user);
        $arParams["SELECT"] = array("ID", "UF_FRENDS");
        $res = CUser::GetList($by ="timestamp_x", $order = "desc", $arFilter, $arParams);
        $result = [];
        if($obj=$res->GetNext()){
            if(!empty($obj['UF_FRENDS'])){
               $result = json_decode($obj['~UF_FRENDS']);
            }
        }

        if(empty($result)){
            $result = [];
        }

        return $result;
    }

    public function getBlackList(){
        global $USER;
        $current_user = $USER->GetID();
        $arFilter = array("ID" => $current_user);
        $arParams["SELECT"] = array("ID", "UF_BLACKLIST");
        $res = CUser::GetList($by ="timestamp_x", $order = "desc", $arFilter, $arParams);
        $result = [];
        if($obj=$res->GetNext()){
            if(!empty($obj['UF_BLACKLIST'])){
                $result = json_decode($obj['~UF_BLACKLIST']);
            }
        }

        if(empty($result)){
            $result = [];
        }

        return $result;
    }


    public function executeComponent()
    {
        $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];

        $arNavParams = array(
            "nPageSize" => $this->arParams["NEWS_COUNT"],
        );
        $arNavigation = CDBResult::GetNavParams($arNavParams);
        /*if($arNavigation["PAGEN"]==0)
            $arParams["CACHE_TIME"] = 36000;

        if($this->startResultCache(false, array($arrFilter, $arNavigation)))
        {*/
            $this->arResult["FRENDS"] = $this->getFrends();
            $this->arResult["BLACKLIST"] = $this->getBlackList();
            $this->arResult["COMPANY"] = $this->listAllCompany($arNavParams);
            $this->includeComponentTemplate();
        //}

        //$this->setTemplateCachedData($this->arResult["NAV_CACHED_DATA"]);
        
        return $this->arResult;
    }
};

?>