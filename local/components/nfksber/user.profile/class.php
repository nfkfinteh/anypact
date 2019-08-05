<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
    Класс выводит информацию в карточку по сделке
*/

class CDemoSqr extends CBitrixComponent
{       
    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "USER_ID" => intval($arParams['USER_ID']),
            "IBLOCK_ID" => intval($arParams['IBLOCK_ID']),
            "ITEM_COUNT" => intval($arParams['ITEM_COUNT']),
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
        );
        return $result;
    }

    public function getUserSdel($activeSdel, $arNavParams){
        $arParams = $this->arParams;
        $arFilter = [
            'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
            'ACTIVE'=>$activeSdel,
            'PROPERTY_PACT_USER'=>$arParams['USER_ID']
        ];

        if(CModule::IncludeModule("iblock")) {
            $res = CIBlockElement::GetList([], $arFilter, false, $arNavParams);
            while ($obj = $res->GetNextElement()) {
                $arFields = $obj->GetFields();
                $arProperty = $obj->GetProperties();
                $arFields = array_merge($arFields, $arProperty);
                $arResult['ITEMS'][] = $arFields;
            }

            $navComponentParameters = array();

            $arResult["NAV_STRING"] = $res->GetPageNavStringEx(
                $navComponentObject,
                '',
                $arParams["PAGER_TEMPLATE"],
                false,
                $this,
                $navComponentParameters
            );
            $arResult["NAV_CACHED_DATA"] = null;
            $arResult["NAV_RESULT"] = $res;
            $arResult["NAV_PARAM"] = $navComponentParameters;
        }
        return $arResult;
    }

    public function getCntSdel($activeSdel){
        if(CModule::IncludeModule("iblock")) {
            $arParams = $this->arParams;
            $arFilter = [
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'ACTIVE' => $activeSdel,
                'PROPERTY_PACT_USER' => $arParams['USER_ID']
            ];

            $res = CIBlockElement::GetList([], $arFilter);
            $result = $res->SelectedRowsCount();
        }
        return $result;
    }

    public function executeComponent()
    {
        if(empty($this->arParams['USER_ID'])){
            $this->arResult['ERROR'] = 'Не найден пользователь';
            $this->includeComponentTemplate();
            return $this->arResult;
        }

        if(!empty($_REQUEST['STATE_SDEL'])){
            $ajaxData = $_REQUEST['STATE_SDEL'];
        }
        else{
            $ajaxData = 'Y';
        }

        $arNavParams = array(
            "nPageSize" => $this->arParams["ITEM_COUNT"],
        );
        $arNavigation = CDBResult::GetNavParams($arNavParams);

        if($this->startResultCache($this->arParams['CACHE_TIME'], [$ajaxData, $arNavigation]))
        {
            $res = CUser::GetByID($this->arParams['USER_ID']);
            $arUser = $res->GetNext();
            $arResult["USER"] = $arUser;
            $arResult["USER"]['IMG_URL'] = CFile::GetPath($arResult['USER']['PERSONAL_PHOTO']);
            $arResult["USER"]["IN_NAME"] = substr($arResult["USER"]['NAME'], 0, 1);
            $arResult["ACTIVE_ITEMS"] = $this->getCntSdel('Y');
            $arResult["COMPLETED_ITEMS"] = $this->getCntSdel('N');

            if($ajaxData == 'Y'){
                $arResult['CURRENT_STATE'] = $ajaxData;
            }
            elseif($ajaxData == 'N'){
                $arResult['CURRENT_STATE'] = 'N';
            }
            else{
                $arResult['CURRENT_STATE'] = 'Y';
            }


            $arItems  =  $this->getUserSdel($ajaxData, $arNavParams);
            $arResult["ITEMS"] = $arItems['ITEMS'];
            $arResult["NAV_STRING"] = $arItems["NAV_STRING"];
            $arResult["NAV_CACHED_DATA"] = $arItems["NAV_CACHED_DATA"];
            $arResult["NAV_RESULT"] = $arItems["NAV_RESULT"];
            $arResult["NAV_PARAM"] = $arItems["NAV_PARAM"];

            $this->arResult = $arResult;
            $this->includeComponentTemplate();
        }

        $this->setTemplateCachedData($this->arResult["NAV_CACHED_DATA"]);
        
        return $this->arResult;
    }
};

?>