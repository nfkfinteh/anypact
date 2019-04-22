<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class navmenu extends CBitrixComponent
{
    //Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent
    //и применяет к ним функцию htmlspecialcharsex. В данном случае такая обработка избыточна.
    //Переопределяем.    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,            
            "ArURL_MENU" => $arParams["ArURL_MENU"],
        );        
        return $result;
    }

    public function paramsUser($arParams){        
        $arResult["ArURL_MENU"] = $arParams["ArURL_MENU"];
        return $arResult;
    }

    private function getNavMenu($arURLs){
        $arMenuItem     = array();
        $arRequestUrl   = array();
        $slashControl   = substr($_SERVER['REQUEST_URI'], 0, 2);        
        if($_SERVER['REQUEST_URI'] == '/' || $slashControl == '/?'){
            $RequestUrl = '/';            
        }else{
            $arRequestUrl   = explode('/', $_SERVER['REQUEST_URI']);
            $RequestUrl     = '/'.$arRequestUrl[1].'/';
        }
        
        $i = 0;
        foreach($arURLs as $key => $item){
            $arMenuItem[$i]["NAME"] = $item;
            $arMenuItem[$i]["URL"] = $key;            
            if($RequestUrl == $key){
                $arMenuItem[$i]["CLASS"] = 'nav-link-activ';
            }else {
                $arMenuItem[$i]["CLASS"] = '';
            }
            $i++;
        }
        return $arMenuItem;
    }

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));            
            $this->arResult["ARR_ITEM_MENU"] = $this->getNavMenu($this->arResult["ArURL_MENU"]);
            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>