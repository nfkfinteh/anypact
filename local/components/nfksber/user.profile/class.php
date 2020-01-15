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
            "IBLOCK_ID_COMPANY" => intval($arParams['IBLOCK_ID_COMPANY']),
            "ITEM_COUNT" => intval($arParams['ITEM_COUNT']),
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            'TYPE' => htmlspecialchars($arParams['TYPE'])
        );
        return $result;
    }

    public function getUserSdel($activeSdel, $arNavParams, $typeHolder){
        $arParams = $this->arParams;
        if(CModule::IncludeModule("iblock")) {
            if($typeHolder == 'user'){
                $arFilter = [
                    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                    'ACTIVE'=>$activeSdel,
                    'PROPERTY_PACT_USER'=>$arParams['USER_ID']
                ];
            }
            elseif($typeHolder == 'company') {
                $arFilter = [
                    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                    'ACTIVE'=>$activeSdel,
                    'PROPERTY_ID_COMPANY'=>$arParams['USER_ID']
                ];
            }

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

    public function getCntSdel($activeSdel, $typeHolder){
        if(CModule::IncludeModule("iblock")) {
            $arParams = $this->arParams;
            if($typeHolder == 'user'){
                $arFilter = [
                    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                    'ACTIVE'=>$activeSdel,
                    'PROPERTY_PACT_USER'=>$arParams['USER_ID']
                ];
            }
            elseif($typeHolder == 'company') {
                $arFilter = [
                    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                    'ACTIVE'=>$activeSdel,
                    'PROPERTY_ID_COMPANY'=>$arParams['USER_ID']
                ];
            }

            $res = CIBlockElement::GetList([], $arFilter);
            $result = $res->SelectedRowsCount();
        }
        return $result;
    }

    public function executeComponent()
    {
        if(empty($this->arParams['USER_ID'])){
            $this->arResult['ERROR'] = 'Профиль не найден';
            $this->includeComponentTemplate();
            return;
        }

        if($this->arParams['TYPE']=='company'){
            $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>$this->arParams['IBLOCK_ID_COMPANY'], 'ID'=>$this->arParams['USER_ID'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID']);
            if($obj = $res->SelectedRowsCount()==0){
                $this->arResult['ERROR'] = 'Профиль не найден';
                $this->includeComponentTemplate();
                return;
            }
        }
        else{
            $res = CUser::GetByID($this->arParams['USER_ID']);
            if($obj = $res->SelectedRowsCount()==0){
                $this->arResult['ERROR'] = 'Профиль не найден';
                $this->includeComponentTemplate();
                return;
            }
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
            if($this->arParams['TYPE']=='company'){
                $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>$this->arParams['IBLOCK_ID_COMPANY'], 'ID'=>$this->arParams['USER_ID'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_PICTURE']);
                if($obj = $res->GetNext(true, false)) $arCompany = $obj;

                $arResult['USER'] = $arCompany;
                $arResult["USER"]['IMG_URL'] = CFile::GetPath($arCompany['PREVIEW_PICTURE']);
                $arResult["USER"]["IN_NAME"] = substr($arCompany['NAME'], 0, 1);
                $arResult["ACTIVE_ITEMS"] = $this->getCntSdel('Y', 'company');
                $arResult["COMPLETED_ITEMS"] = $this->getCntSdel('N', 'company');
                $arResult["TYPE_HOLDER"] = 'company';

                $arItems  =  $this->getUserSdel($ajaxData, $arNavParams, 'company');
                // ошибка если нет записей поправка в шаблоне
                $arResult["ITEMS"] = $arItems['ITEMS'];
            }
            else{
                $res = CUser::GetByID($this->arParams['USER_ID']);
                $arUser = $res->GetNext();
                $arResult["USER"] = $arUser;
                $arResult["USER"]['IMG_URL'] = CFile::GetPath($arResult['USER']['PERSONAL_PHOTO']);
                $arResult["USER"]["IN_NAME"] = substr($arResult["USER"]['NAME'], 0, 1);
                $arResult["ACTIVE_ITEMS"] = $this->getCntSdel('Y', 'user');
                $arResult["COMPLETED_ITEMS"] = $this->getCntSdel('N', 'user');
                $arResult["TYPE_HOLDER"] = 'user';

                $arItems  =  $this->getUserSdel($ajaxData, $arNavParams, 'user');
                // ошибка если нет записей поправка в шаблоне
                $arResult["ITEMS"] = $arItems['ITEMS'];
            }


            if($ajaxData == 'Y'){
                $arResult['CURRENT_STATE'] = $ajaxData;
            }
            elseif($ajaxData == 'N'){
                $arResult['CURRENT_STATE'] = 'N';
            }
            else{
                $arResult['CURRENT_STATE'] = 'Y';
            }


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