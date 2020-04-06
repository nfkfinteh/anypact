<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CDemoSqr extends CBitrixComponent
{
    //Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent
    //и применяет к ним функцию htmlspecialcharsex. В данном случае такая обработка избыточна.
    //Переопределяем.    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X" => intval($arParams["X"]),
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "SECTION_ID" => intval($arParams["SECTION_ID"]),
            "FILTER_NAME"=> $arParams["FILTER_NAME"],
            "NEWS_COUNT" => intval($arParams["NEWS_COUNT"]),
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"]
        );
        return $result;
    }

    public function listAllPacts($id_iblock, $section_id, $arNavParams) {
        $arPact = array();

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


            $arFilter = Array(
                "IBLOCK_ID"=>IntVal($id_iblock),
                "ACTIVE"=>"Y",
                ">=DATE_ACTIVE_TO" => new \Bitrix\Main\Type\DateTime(),
                "PROPERTY_MODERATION_VALUE" => 'Y'
            );

            if ($_GET['SECTION_ID'] > 0){
                // фильтр для отбора всех записей включая подкатегории
                $arFilter['SECTION_ID'] = $section_id;
                $arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
            }

            $res = CIBlockElement::GetList(Array(), array_merge($arFilter, $arrFilter), false, $arNavParams, $arSelect);
            // перебираем элементы
            while($ob = $res->GetNextElement())
            {
                $arFields   = $ob->GetFields();
                $id_element = $arFields["ID"];
                $arFields['PROPERTIES'] = $ob->GetProperties();

                $arFields['URL_IMG_PREVIEW'] = NULL;
                $arFields['URL_IMG_PREVIEW'] = CFile::ResizeImageGet($arFields["PROPERTIES"]['INPUT_FILES']['VALUE'][0], ['width'=>500, 'height'=>500], BX_RESIZE_IMAGE_PROPORTIONAL )['src'];
                $arPact[]   = $arFields;
                // добавим url img
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
        return $arPact;
    }

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["SECTION_ID"] = $arParams["SECTION_ID"];
        return $arResult;
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
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_ID"] = CUser::GetID();
            $this->arResult["USER_LOGIN"] =CUser::GetLogin();                                  
            $this->arResult["INFOBLOCK_LIST"] = $this->listAllPacts($this->arResult["INFOBLOCK_ID"], $this->arResult["SECTION_ID"], $arNavParams);
            $this->includeComponentTemplate();
        }

        $this->setTemplateCachedData($this->arResult["NAV_CACHED_DATA"]);
        
        return $this->arResult;
    }
};

?>