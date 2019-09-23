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
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"]
        );
        return $result;
    }

    public function listAllUser($arNavParams) {

        $arUser = [];

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

            $res = CUser::GetList($by="personal_country", $order="desc", $arrFilter);
            $res->NavStart($arNavParams['nPageSize']);
            while($obj = $res->NavNext(true)) {
                $arUser[] = $obj;
            }

            $navComponentParameters = array();

            $this->arResult["NAV_STRING"] = $res->GetPageNavStringEx(
                $navComponentObject,
                '',
                $this->arParams["PAGER_TEMPLATE"],
                false,
                $this,
                $navComponentParameters
            );
            $this->arResult["NAV_CACHED_DATA"] = null;
            $this->arResult["NAV_RESULT"] = $res;
            $this->arResult["NAV_PARAM"] = $navComponentParameters;

        }
        return $arUser;
    }


    public function executeComponent()
    {
        $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];

        $arNavParams = array(
            "nPageSize" => $this->arParams["NEWS_COUNT"],
        );
        $arNavigation = CDBResult::GetNavParams($arNavParams);
        if($arNavigation["PAGEN"]==0)
            $arParams["CACHE_TIME"] = 36000;

        if($this->startResultCache(false, array($arrFilter, $arNavigation)))
        {
            $this->arResult["USER"] = $this->listAllUser($arNavParams);
            $this->includeComponentTemplate();
        }

        $this->setTemplateCachedData($this->arResult["NAV_CACHED_DATA"]);
        
        return $this->arResult;
    }
};

?>