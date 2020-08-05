<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/local/class/CCustUser.php'))
    require_once $_SERVER['DOCUMENT_ROOT'].'/local/class/CCustUser.php';

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
            "FRIENDS_STATUS" => $arParams["FRIENDS_STATUS"],
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

            if(empty($arrFilter['NAME'])){
                $arrFilter['NAME'] = "_";
            }

            $by="RAND";

            if(!empty($this->arParams['FRIENDS_STATUS'])){
                if(empty($this->arResult["FRENDS"]) && $this->arParams['FRIENDS_STATUS'] != 'B')
                    return array();
                
                if($this->arParams['FRIENDS_STATUS'] == 'B'){
                    if(!empty($this->arResult["BLACKLIST"]['UF_USER_B']))
                        $arrFilter = array_merge($arrFilter, array("ID" => implode("|", $this->arResult["BLACKLIST"]['UF_USER_B'])));
                    else
                        return array();
                }else
                    $arrFilter = array_merge($arrFilter, array("ID" => implode("|", $this->arResult["FRENDS"])));
                    
                
                $by = array("LAST_NAME" => "asc", "NAME" => "asc", "SECOND_NAME" => "asc");
            }

            $res = CCustUser::GetList($by, $order="desc", $arrFilter, ['SELECT'=>['UF_*'] ]);
            $res->NavStart($arNavParams['nPageSize']);
            while($obj = $res->NavNext(true)) {
                $arUser[] = $obj;
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
        return $arUser;
    }

    public function getFrends(){
        global $USER;
        $current_user = $USER->GetID();
        if(CModule::IncludeModule("highloadblock"))
        {
            $filter_a = array("UF_USER_A" => $current_user);
            $filter_b = array("UF_USER_B" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y);

            switch($this->arParams['FRIENDS_STATUS']){
                case "Y":
                    $filter_a = array("UF_USER_A" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y);
                    $filter_b = array("UF_USER_B" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y);
                    break;
                case "O":
                    $filter_a = array("UF_USER_A" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_A);
                    $filter_b = array();
                    break;
                case "N":
                    $filter_a = array("UF_USER_A" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_N);
                    $filter_b = array();
                    break;
                case "I":
                    $filter_a = array("UF_USER_B" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_A);
                    $filter_b = array();
                    break;
                case "S":
                    $filter_a = array();
                    $filter_b = array("UF_USER_B" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_N);
                    break;
            }

            $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(14)->fetch();
            $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_USER_A", "UF_USER_B"),
                "order" => array("ID" => "ASC"),
                "filter" => array(array(
                    "LOGIC" => "OR",
                    $filter_a,
                    $filter_b,
                ))
            ));
            while($arData = $rsData->Fetch()){
                $result[] = $arData["UF_USER_A"];
                $result[] = $arData["UF_USER_B"];
            }
        }

        if(empty($result)){
            $result = [];
        }

        $result = array_unique($result);

        if(isset($result[array_search($current_user, $result)]))
            unset($result[array_search($current_user, $result)]);

        return $result;
    }

    public function getBlackList(){
        global $USER;
        $current_user = $USER->GetID();
        if(CModule::IncludeModule("highloadblock"))
        {
            $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(15)->fetch();
            $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array("ID" => "ASC"),
                "filter" => array(array(
                    "LOGIC" => "OR",
                    array("UF_USER_A" => $current_user),
                    array("UF_USER_B" => $current_user)
                ))
            ));
            while($arData = $rsData->Fetch()){
                $result['UF_USER_B'][] = $arData["UF_USER_B"];
                $result['UF_USER_A'][] = $arData["UF_USER_A"];
            }
        }
        if(empty($result['UF_USER_B']))
            $result['UF_USER_B'] = [];
        if(empty($result['UF_USER_A']))
            $result['UF_USER_A'] = [];
        
        $result['UF_USER_B'] = array_unique($result['UF_USER_B']);
        if(isset($result['UF_USER_B'][array_search($current_user, $result['UF_USER_B'])]))
            unset($result['UF_USER_B'][array_search($current_user, $result['UF_USER_B'])]);

        $result['UF_USER_A'] = array_unique($result['UF_USER_A']);
        if(isset($result['UF_USER_A'][array_search($current_user, $result['UF_USER_A'])]))
            unset($result['UF_USER_A'][array_search($current_user, $result['UF_USER_A'])]);
        

        return $result;
    }


    public function executeComponent()
    {
        global $APPLICATION;
        $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];

        $arNavParams = array(
            "nPageSize" => $this->arParams["NEWS_COUNT"],
        );
        $arNavigation = CDBResult::GetNavParams($arNavParams);
        //if($arNavigation["PAGEN"]==0)
        if(empty($arParams["CACHE_TIME"]))
            $arParams["CACHE_TIME"] = 86400;

        $this->arResult["FRENDS"] = $this->getFrends();
        $this->arResult["BLACKLIST"] = $this->getBlackList();

        if($this->startResultCache(false, array($arrFilter, $arNavigation, $this->arResult["FRENDS"], $this->arResult["BLACKLIST"])))
        {
            if($_REQUEST["ajax_result"] === "y"){
                $APPLICATION->RestartBuffer();
                $this->IncludeComponentTemplate('ajax_result');
                CMain::FinalActions();
                die();
            }
            else{
               
                $this->arResult["USER"] = $this->listAllUser($arNavParams);
                $this->includeComponentTemplate();
            }
        }

        $this->setTemplateCachedData($this->arResult["NAV_CACHED_DATA"]);
        
        return $this->arResult;
    }
};

?>