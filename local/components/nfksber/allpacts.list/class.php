<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader,
    Bitrix\Iblock;

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
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            "PARENT_SECTION" => intval($arParams["PARENT_SECTION"]),
            "PARENT_SECTION_CODE" => $arParams["PARENT_SECTION_CODE"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "SET_BROWSER_TITLE" => $arParams["SET_BROWSER_TITLE"],
            "SET_META_KEYWORDS" => $arParams["SET_META_KEYWORDS"],
            "SET_META_DESCRIPTION" => $arParams["SET_META_DESCRIPTION"],
            "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
            "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
            "SECTION_URL" => $arParams["SECTION_URL"],
            "IBLOCK_URL" => $arParams["IBLOCK_URL"],
            "DETAIL_URL" => $arParams["DETAIL_URL"],
            "ADDITIONAL_FILTER" => $arParams["ADDITIONAL_FILTER"],
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

            //Доп фильтр
            if(strlen($this->arParams['ADDITIONAL_FILTER'])<=0)
            {
                $arrFilterN = array();
            }
            else
            {
                $arrFilterN = $GLOBALS[$this->arParams['ADDITIONAL_FILTER']];
                if(!is_array($arrFilterN))
                    $arrFilterN = array();
            }

            global $USER;

            $arFilter = Array(
                "IBLOCK_ID"=>IntVal($id_iblock),
                "ACTIVE"=>"Y",
                array(
                    "LOGIC" => "OR",
                    array("PROPERTY_INDEFINITELY" => 18),
                    array(">=DATE_ACTIVE_TO" => new \Bitrix\Main\Type\DateTime())
                ),
            );

            $PARENT_SECTION = CIBlockFindTools::GetSectionID(
                $this -> arParams["PARENT_SECTION"],
                $this -> arParams["PARENT_SECTION_CODE"],
                array(
                    "GLOBAL_ACTIVE" => "Y",
                    "IBLOCK_ID" => $id_iblock,
                )
            );

            $this -> arParams["PARENT_SECTION"] = $PARENT_SECTION;

            if($this -> arParams["PARENT_SECTION"]>0)
            {
                $arFilter["SECTION_ID"] = $this -> arParams["PARENT_SECTION"];
                $arFilter["INCLUDE_SUBSECTIONS"] = "Y";

                $this -> arResult["SECTION"]= array("PATH" => array());
                $rsPath = CIBlockSection::GetNavChain($this -> arResult["ID"], $this -> arParams["PARENT_SECTION"]);
                $rsPath->SetUrlTemplates("", $this -> arParams["SECTION_URL"], $this -> arParams["IBLOCK_URL"]);
                while($arPath = $rsPath->GetNext())
                {
                    $ipropValues = new Iblock\InheritedProperty\SectionValues($id_iblock, $arPath["ID"]);
                    $arPath["IPROPERTY_VALUES"] = $ipropValues->getValues();
                    $this -> arResult["SECTION"]["PATH"][] = $arPath;
                }

                $ipropValues = new Iblock\InheritedProperty\SectionValues($this -> arResult["ID"], $this -> arParams["PARENT_SECTION"]);
                $this -> arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();
            }
            else
            {
                $arResult["SECTION"]= false;
            }

            if($_SESSION['DEAL_SORT']){
                $arSort = $_SESSION['DEAL_SORT'];
            }else{
                $arSort = array('SORT' => 'asc', 'RAND' => 'asc');
            }

            $res = CIBlockElement::GetList($arSort, array_merge($arFilter, $arrFilter, $arrFilterN), false, $arNavParams, $arSelect);
            // перебираем элементы
            while($ob = $res->GetNextElement())
            {
                $arFields   = $ob->GetFields();
                $ipropValues = new Iblock\InheritedProperty\ElementValues($arFields["IBLOCK_ID"], $arFields["ID"]);
                $arFields["IPROPERTY_VALUES"] = $ipropValues->getValues();
                Iblock\Component\Tools::getFieldImageData(
                    $arFields,
                    array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
                    Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
                    'IPROPERTY_VALUES'
                );
                $id_element = $arFields["ID"];
                $arFields['PROPERTIES'] = $ob->GetProperties();

                $arFields['URL_IMG_PREVIEW'] = NULL;
                $arFields['URL_IMG_PREVIEW'] = CFile::ResizeImageGet($arFields["PROPERTIES"]['INPUT_FILES']['VALUE'][0], ['width'=>500, 'height'=>500], BX_RESIZE_IMAGE_PROPORTIONAL )['src'];
                $arPact[]   = $arFields;
                // добавим url img
            }

            $navComponentParameters = array();

            $res->nPageWindow = 3;

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
        if(!Loader::includeModule("iblock"))
        {
            $this->abortResultCache();
            ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return;
        }
        if(is_numeric($arParams["IBLOCK_ID"]))
        {
            $rsIBlock = CIBlock::GetList(array(), array(
                "ACTIVE" => "Y",
                "ID" => $arParams["IBLOCK_ID"],
            ));
        }
        else
        {
            $rsIBlock = CIBlock::GetList(array(), array(
                "ACTIVE" => "Y",
                "CODE" => $arParams["IBLOCK_ID"],
                "SITE_ID" => SITE_ID,
            ));
        }

        $arResult = $rsIBlock->GetNext();
        if (!$arResult)
        {
            $this->abortResultCache();
            Iblock\Component\Tools::process404(
                trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_NEWS_NA")
                ,true
                ,$arParams["SET_STATUS_404"] === "Y"
                ,$arParams["SHOW_404"] === "Y"
                ,$arParams["FILE_404"]
            );
            return;
        }
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

        global $USER, $APPLICATION;

        if($this->startResultCache(false, array($arrFilter, $arNavigation, $USER->GetID(), $_SESSION['DEAL_SORT'])))
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_ID"] = CUser::GetID();
            $this->arResult["USER_LOGIN"] =CUser::GetLogin();                                  
            $this->arResult["INFOBLOCK_LIST"] = $this->listAllPacts($this->arResult["INFOBLOCK_ID"], $this->arResult["SECTION_ID"], $arNavParams);
            $this->includeComponentTemplate();
        }

        $this->setTemplateCachedData($this->arResult["NAV_CACHED_DATA"]);
        
        if(isset($this->arResult["ID"]))
        {
            $arTitleOptions = null;
            if($USER->IsAuthorized())
            {
                if(
                    $APPLICATION->GetShowIncludeAreas()
                    || (is_object($GLOBALS["INTRANET_TOOLBAR"]) && $this->arParams["INTRANET_TOOLBAR"]!=="N")
                    || $this->arParams["SET_TITLE"]
                )
                {
                    if(Loader::includeModule("iblock"))
                    {
                        $arButtons = CIBlock::GetPanelButtons(
                            $this->arResult["ID"],
                            0,
                            $this->arParams["PARENT_SECTION"],
                            array("SECTION_BUTTONS"=>false)
                        );
        
                        if($APPLICATION->GetShowIncludeAreas())
                            $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
        
                        if(
                            is_array($arButtons["intranet"])
                            && is_object($INTRANET_TOOLBAR)
                            && $this->arParams["INTRANET_TOOLBAR"]!=="N"
                        )
                        {
                            $APPLICATION->AddHeadScript('/bitrix/js/main/utils.js');
                            foreach($arButtons["intranet"] as $arButton)
                                $INTRANET_TOOLBAR->AddButton($arButton);
                        }
        
                        if($this->arParams["SET_TITLE"])
                        {
                            $arTitleOptions = array(
                                'ADMIN_EDIT_LINK' => $arButtons["submenu"]["edit_iblock"]["ACTION"],
                                'PUBLIC_EDIT_LINK' => "",
                                'COMPONENT_NAME' => $this->getName(),
                            );
                        }
                    }
                }
            }

            if($this->arParams["SET_TITLE"])
            {
                if ($this->arResult["IPROPERTY_VALUES"] && $this->arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
                    $APPLICATION->SetTitle($this->arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arTitleOptions);
            }
        
            if ($this->arResult["IPROPERTY_VALUES"])
            {
                if ($this->arParams["SET_BROWSER_TITLE"] === 'Y' && $this->arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"] != "")
                    $APPLICATION->SetPageProperty("title", $this->arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"], $arTitleOptions);
        
                if ($this->arParams["SET_META_KEYWORDS"] === 'Y' && $this->arResult["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"] != "")
                    $APPLICATION->SetPageProperty("keywords", $this->arResult["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"], $arTitleOptions);
        
                if ($this->arParams["SET_META_DESCRIPTION"] === 'Y' && $this->arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"] != "")
                    $APPLICATION->SetPageProperty("description", $this->arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"], $arTitleOptions);
            }
        
            if($this->arParams["INCLUDE_IBLOCK_INTO_CHAIN"] && isset($this->arResult["NAME"]))
            {
                if($this->arParams["ADD_SECTIONS_CHAIN"] && is_array($this->arResult["SECTION"]))
                    $APPLICATION->AddChainItem(
                        $this->arResult["NAME"]
                        ,strlen($this->arParams["IBLOCK_URL"]) > 0? $this->arParams["IBLOCK_URL"]: $this->arResult["LIST_PAGE_URL"]
                    );
                else
                    $APPLICATION->AddChainItem($this->arResult["NAME"]);
            }
        
            if($this->arParams["ADD_SECTIONS_CHAIN"] && is_array($this->arResult["SECTION"]))
            {
                foreach($this->arResult["SECTION"]["PATH"] as $arPath)
                {
                    if ($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
                        $APPLICATION->AddChainItem($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arPath["~SECTION_PAGE_URL"]);
                    else
                        $APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
                }
            }
        
            if ($this->arParams["SET_LAST_MODIFIED"] && $this->arResult["ITEMS_TIMESTAMP_X"])
            {
                Context::getCurrent()->getResponse()->setLastModified($this->arResult["ITEMS_TIMESTAMP_X"]);
            }
        }

        return $this->arResult;
    }
};

?>