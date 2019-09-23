<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CUserFilter extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "FILTER_NAME"=> $arParams["FILTER_NAME"],
        );
        return $result;
    }


    public function executeComponent()
    {
        global  $APPLICATION;
        $this->arResult['FORM_ACTION'] = $APPLICATION->GetCurPage();
        foreach ($_GET as $key=>$get){
            $postData[$key] = htmlspecialcharsEx($get);
        }

        $this->arResult['POST'] = $postData;
        $GLOBALS[$this->arParams['FILTER_NAME']] =  $postData;

        $this->includeComponentTemplate();
        return $this->arResult;
    }
};

?>