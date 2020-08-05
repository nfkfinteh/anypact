<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
    Класс выводит информацию в карточку по сделке
*/
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class CDemoSqr extends CBitrixComponent
{       
    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "USER_ID" => intval($arParams['USER_ID']),
            'CURRENT_USER'=>intval($arParams['CURRENT_USER']),
            "IBLOCK_ID" => intval($arParams['IBLOCK_ID']),
            "IBLOCK_ID_COMPANY" => intval($arParams['IBLOCK_ID_COMPANY']),
            "IBLOCK_ID_DEAL" => intval($arParams['IBLOCK_ID_DEAL']),
            "ITEM_COUNT" => intval($arParams['ITEM_COUNT']),
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            'TYPE' => htmlspecialchars($arParams['TYPE'])
        );
        return $result;
    }

    private function getIDCompletSdel($UserID, $typeHolder){
        CModule::IncludeModule("highloadblock");
        CModule::IncludeModule("iblock");

        if($typeHolder == 'user'){
            $arFilter = Array(
                Array(
                    "UF_STATUS" => 2,
                    "UF_ID_USER_A"=> $UserID
                )
            );
        }
        elseif($typeHolder == 'company') {
            $arFilter = Array(
                Array(
                    "UF_STATUS" => 2,
                    "UF_ID_COMPANY_A"=> $UserID
                )
            );
        }

        $arSend_Contract = [];

        // получить все подписанные сделки
        $ID_hl_send_contract = 3;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order"  => array("ID" => "ASC"),
            "filter" => $arFilter
        ));

        while($arData = $rsData->Fetch()){
            $arrID_Info_Contract[] =  $arData['UF_ID_CONTRACT'];
        }

        #если нет подписаных договоров
        if(empty($arrID_Info_Contract)) return $arSend_Contract;

        foreach($arrID_Info_Contract as $i=>$value ){
            $res = CIBlockElement::GetList(
                array(),
                array("IBLOCK_ID" => $this->arParams['IBLOCK_ID'], "PROPERTY_ID_DOGOVORA" => $value),
                false,
                false,
                array("IBLOCK_ID", "ID")
            );
            while($ob = $res->GetNext(true, false)){
                $result[] = $ob['ID'];
            }
        }

        $result = array_unique($result);

        return $result;
    }

    public function getUserSdel($activeSdel, $arNavParams, $typeHolder){
        $arParams = $this->arParams;
        if(CModule::IncludeModule("iblock")) {
            //фильтр для активных сделок
            if($typeHolder == 'user'){
                $arFilter = [
                    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                    'PROPERTY_PACT_USER'=>$arParams['USER_ID'],
                    'PROPERTY_ID_COMPANY'=>false,
                    'PROPERTY_MODERATION_VALUE'=>'Y',
                ];
            }
            elseif($typeHolder == 'company') {
                $arFilter = [
                    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                    'PROPERTY_ID_COMPANY'=>$arParams['USER_ID'],
                    'PROPERTY_MODERATION_VALUE'=>'Y',
                ];
            }

            //фильтр для завершенных сделок
            if($activeSdel == 'N'){
                $arIdSdelk = $this->getIDCompletSdel($arParams['USER_ID'], $typeHolder);

                //если нет заключенных сделок
                if(empty($arIdSdelk)) return 0;

                $arFilter['=ID'] = $arIdSdelk;
            }
            elseif($activeSdel == 'Y') {
                $arFilter['ACTIVE'] = 'Y';
                $arFilter['>=DATE_ACTIVE_TO'] = ConvertTimeStamp(time(), "SHORT");
            };

            global $USER;

            $arFilter = array_merge($arFilter, array(
            array(
                'LOGIC' => 'OR',
                array("!=PROPERTY_PRIVATE_VALUE" => "Y"),
                array(
                    "PROPERTY_PRIVATE_VALUE" => "Y",
                    "=PROPERTY_ACCESS_USER" => empty( $USER->GetID() ) ? 0 : $USER->GetID()
                ),
                array(
                    "PROPERTY_PRIVATE_VALUE" => "Y",
                    "=CREATED_BY" => empty( $USER->GetID() ) ? 0 : $USER->GetID()
                ),
            )));
            
            $res = CIBlockElement::GetList([], $arFilter, false, $arNavParams);
            while ($obj = $res->GetNextElement()) {
                $arFields = $obj->GetFields();
                $arProperty = $obj->GetProperties();
                $arFields = array_merge($arFields, $arProperty);
                $arResult['ITEMS'][] = $arFields;
            }


            $navComponentParameters = array();

            $res->nPageWindow = 3;

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
            //фильтр для активных сделок
            if($typeHolder == 'user'){
                $arFilter = [
                    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                    'PROPERTY_PACT_USER'=>$arParams['USER_ID'],
                    'PROPERTY_ID_COMPANY'=>false,
                    'PROPERTY_MODERATION_VALUE'=>'Y',
                ];
            }
            elseif($typeHolder == 'company') {
                $arFilter = [
                    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
                    'PROPERTY_ID_COMPANY'=>$arParams['USER_ID'],
                    'PROPERTY_MODERATION_VALUE'=>'Y',
                ];
            }
            //фильтр для завершенных сделок
            if($activeSdel == 'N'){
                $arIdSdelk = $this->getIDCompletSdel($arParams['USER_ID'], $typeHolder);

                //если нет заключенных сделок
                if(empty($arIdSdelk)) return 0;

                $arFilter['=ID'] = $arIdSdelk;
            }
            elseif($activeSdel == 'Y') {
                $arFilter['ACTIVE'] = 'Y';
                $arFilter['>=DATE_ACTIVE_TO'] = ConvertTimeStamp(time(), "SHORT");
            };

            $res = CIBlockElement::GetList([], $arFilter);
            $result = $res->SelectedRowsCount();
        }
        return $result;
    }

    public function getFrends(){
        global $USER;
        $current_user = $USER->GetID();
        if(CModule::IncludeModule("highloadblock"))
        {
            $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(14)->fetch();
            $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_USER_A", "UF_USER_B"),
                "order" => array("ID" => "ASC"),
                "filter" => array(array(
                    "LOGIC" => "OR",
                    array("UF_USER_A" => $current_user),
                    array("UF_USER_B" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y),
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
        $hlblock = HL\HighloadBlockTable::getById(15)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array(array(
                "LOGIC" => "OR",
                array("UF_USER_A" => $current_user, "UF_USER_B" => $this->arParams['USER_ID']),
                array("UF_USER_A" => $this->arParams['USER_ID'], "UF_USER_B" => $current_user),
            ))
        ));
        while($arData = $rsData->Fetch()){
            if($arData['UF_USER_A'] == $current_user){
                $result['CLOSE'] = true;
            }elseif($arData['UF_USER_B'] == $current_user){
                $result['CLOSED'] = true;
            }
        }

        if(empty($result)){
            $result = [];
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
        if($this->arParams['TYPE']!='company'){
            $arFrends = $this->getFrends();
            $arBlackList = $this->getBlackList();
        }

        $res = CUser::GetByID($this->arParams['USER_ID']);
        $arUser = $res->GetNext();

        /*if($this->startResultCache($this->arParams['CACHE_TIME'], [$ajaxData, $arNavigation, $arFrends, $arUser, $arBlackList]))
        {*/
            if($this->arParams['TYPE']=='company'){
                $res = CIBlockElement::GetList(
                    [],
                    ['IBLOCK_ID'=>$this->arParams['IBLOCK_ID_COMPANY'], 'ID'=>$this->arParams['USER_ID'], 'ACTIVE'=>'Y'],
                    false,
                    false,
                    ['IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT']
                );
                if($obj = $res->GetNextElement()){
                    $arCompany = $obj->GetFields();
                    $arCompanyProp = $obj->GetProperties();
                }

                $arResult['USER'] = $arCompany;
                $arResult['USER']['PROPERTY'] = $arCompanyProp;
                $arResult["USER"]['IMG_URL'] = CFile::GetPath($arCompany['PREVIEW_PICTURE']);
                $arResult["USER"]["IN_NAME"] = substr($arCompany['NAME'], 0, 1);
                $arResult["ACTIVE_ITEMS"] = $this->getCntSdel('Y', 'company');
                $arResult["COMPLETED_ITEMS"] = $this->getCntSdel('N', 'company');
                $arResult["TYPE_HOLDER"] = 'company';

                $arIdStaff = [];
                $arIdStaff[] = $arResult['USER']['PROPERTY']['DIRECTOR_ID']['VALUE'];
                if(!empty($arResult['USER']['PROPERTY']['STAFF']['VALUE'])){
                    $arIdStaff = array_merge($arIdStaff, $arResult['USER']['PROPERTY']['STAFF']['VALUE']);
                }

                if(!empty($arIdStaff)){
                    foreach ($arIdStaff as $id){
                        $rsUser = CUser::GetByID($id);
                        if ($obj = $rsUser->GetNext()){
                            $arResult['STAFF'][] = [
                                'ID'=>$obj['ID'],
                                'NAME'=>$obj['NAME'],
                                'LAST_NAME'=>$obj['LAST_NAME']
                            ];
                        }
                    }
                }
                unset($arIdStaff);

                $arItems  =  $this->getUserSdel($ajaxData, $arNavParams, 'company');
                // ошибка если нет записей поправка в шаблоне
                $arResult["ITEMS"] = $arItems['ITEMS'];
            }
            else{
               /* $res = CUser::GetByID($this->arParams['USER_ID']);
                $arUser = $res->GetNext();*/
                $arResult["USER"] = $arUser;
                $arResult["USER"]['IMG_URL'] = CFile::GetPath($arResult['USER']['PERSONAL_PHOTO']);
                $arResult["USER"]["IN_NAME"] = substr($arResult["USER"]['NAME'], 0, 1);
                $arResult["ACTIVE_ITEMS"] = $this->getCntSdel('Y', 'user');
                $arResult["COMPLETED_ITEMS"] = $this->getCntSdel('N', 'user');
                $arResult["TYPE_HOLDER"] = 'user';

                $arItems  =  $this->getUserSdel($ajaxData, $arNavParams, 'user');
                $arResult["ITEMS"] = $arItems['ITEMS'];
                //$arResult["FRENDS"] = $this->getFrends();
                $arResult["FRENDS"] =$arFrends;
                $arResult["BLACK_LIST"] = $arBlackList;

                $res = CIBlockElement::GetList(
                    [],
                    [
                        'IBLOCK_ID'=>$this->arParams['IBLOCK_ID_COMPANY'],
                        'ACTIVE'=>'Y',
                        [
                            'LOGIC'=> 'OR',
                            ['PROPERTY_DIRECTOR_ID'=>$this->arParams['USER_ID']],
                            ['PROPERTY_STAFF'=>$this->arParams['USER_ID']]
                        ]
                    ],
                    false,
                    false,
                    ['IBLOCK_ID', 'ID', 'NAME']
                );
                while($obj = $res->GetNext()){
                    $arCompany[] = $obj;
                }
                $arResult['COMPANY'] = $arCompany;
                unset($arCompany);

                //добавление в представители компании
                //только пользователей подтвердивших на гос услуга
                if($arResult['USER']['UF_ESIA_AUT']==1){
                    $res = CIBlockElement::GetList(
                        [],
                        [
                            'IBLOCK_ID'=>$this->arParams['IBLOCK_ID_COMPANY'],
                            'ACTIVE'=>'Y',
                            'PROPERTY_DIRECTOR_ID'=>$this->arParams['CURRENT_USER'],
                        ],
                        false,
                        false,
                        ['IBLOCK_ID', 'ID', 'NAME']
                    );
                    while($obj = $res->GetNextElement()){
                        $arr = $obj->GetFields();
                        $arr['PROPERTY_STAFF'] = $obj->GetProperty('STAFF')['VALUE'];
                        $arr['PROPERTY_STAFF_NO_ACTIVE'] = $obj->GetProperty('STAFF_NO_ACTIVE')['VALUE'];

                        if(!empty($arr['PROPERTY_STAFF']) && in_array($this->arParams['USER_ID'], $arr['PROPERTY_STAFF'])){
                            $arr['STAFF'] = true;
                        }

                        if(!empty($arr['PROPERTY_STAFF_NO_ACTIVE']) && in_array($this->arParams['USER_ID'], $arr['PROPERTY_STAFF_NO_ACTIVE'])){
                            $arr['STAFF_NO_ACTIVE'] = true;
                        }

                        $arResult['COMPANY_CURRENT_USER'][] = $arr;
                    }
                    $res = CIBlockElement::GetList(
                        [],
                        [
                            'IBLOCK_ID'=>$this->arParams['IBLOCK_ID_DEAL'],
                            'ACTIVE'=>'Y',
                            'CREATED_BY'=>$this->arParams['CURRENT_USER'],
                            'PROPERTY_MODERATION' => 7,
                            'PROPERTY_PRIVATE' => 10,
                        ],
                        false,
                        false,
                        ['IBLOCK_ID', 'ID', 'NAME']
                    );
                    while($obj = $res->GetNextElement()){
                        $arr = $obj->GetFields();
                        $arr['PROPERTY_ACCESS_USER'] = $obj->GetProperty('ACCESS_USER')['VALUE'];

                        if(!empty($arr['PROPERTY_ACCESS_USER']) && in_array($this->arParams['USER_ID'], $arr['PROPERTY_ACCESS_USER'])){
                            $arr['ACCESS'] = true;
                        }

                        $arResult['DEAL_CURRENT_USER'][] = $arr;
                    }
                }
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
        //}

        $this->setTemplateCachedData($this->arResult["NAV_CACHED_DATA"]);
        
        return $this->arResult;
    }
};

?>