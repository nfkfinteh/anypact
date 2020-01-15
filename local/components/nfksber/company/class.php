<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader,
    \Bitrix\Main\Application;

class CompanySber extends CBitrixComponent
{

    public function executeComponent()
    {
        global $APPLICATION;
        global $USER;

        if (!$this->arParams['IBLOCK_ID']) return false;
        if (!Loader::includeModule('iblock')) return false;
        if (!$USER) return false;

        $this->arResult = [];
        if($_REQUEST["ajax_result"] === "y"){

            #поиск сотрудника
            if($_REQUEST['staff_email']){
                $email = htmlspecialchars($_REQUEST['staff_email']);
                $arUsers = [];

                $rsUsers = CUser::GetList(($by = "email"), ($order = "asc"), ['EMAIL' => $email, 'ACTIVE' => 'Y'], []);
                while ($arUser = $rsUsers->Fetch()) {
                    $arUsers[] = $arUser;
                }

                if($arUsers){
                    $this->arResult['SEARCH'] = $arUsers;
                    $this->arResult['NAME'] = $email;
                    if($_REQUEST['staff_add']){
                        $arAdd = explode(',', $_REQUEST['staff_add']);
                        foreach($arAdd as &$item) $item = trim($item);
                        $this->arResult['ADD'] = $arAdd;
                    }
                }

                $APPLICATION->RestartBuffer();

                $this->IncludeComponentTemplate('ajax_result');

                CMain::FinalActions();

                die();
            }
            #добавление / редактирование компании
        }elseif($_REQUEST['DIRECTOR_ID'] && $_REQUEST['NAME']){
            #скрытые поля
            $addition_props = ['DIRECTOR_NAME', 'DIRECTOR_ID', 'STAFF'];
            $props_empty = [];
            foreach($_REQUEST as $key => $req){
                if(in_array($key, $this->arParams['PROPERTIES_NEED']) && empty($req)) $props_empty[] = $key;
                if(in_array($key, $this->arParams['PROPERTIES_NUMBER']) && !empty($req) && !ctype_digit($req)) $props_no_number[] = $key;
                if((!in_array($key, $this->arParams['PROPERTIES_SHOW']) && !in_array($key, $addition_props)) || empty($req)) continue;
                $arProps[$key] = $req;
            }

            if($props_empty){
                $output = implode(', ', $props_empty);
                LocalRedirect("/profile/company/?error=props_empty&props=".$output."&id=".$_REQUEST['ID_EXIST']);
            }
            if($props_no_number){
                $output = implode(', ', $props_no_number);
                LocalRedirect("/profile/company/?error=props_no_number&props=".$output."&id=".$_REQUEST['ID_EXIST']);
            }
            if($arProps){

                $el = new CIBlockElement;
                $arParams = array("replace_space" => "-", "replace_other" => "-");
                $code = CUtil::translit($_REQUEST['NAME'], "ru", $arParams);
                #проверка наличия компании с идиентичным символьным кодом
                if ($rsCompany = \CIBlockElement::GetList(
                    ['sort' => 'asc'],
                    ['=CODE' => $code],
                    false,
                    false,
                    ['ID', 'NAME', 'IBLOCK_ID', 'CODE']
                )
                ) {
                    if ($arElem = $rsCompany->GetNext()) {
                        $code = $code . '-' . rand(1, 100);
                    }
                }
                #массив ID сотрудников
                if ($arProps['STAFF']) {
                    $arAdd = explode(',', $arProps['STAFF']);
                    foreach ($arAdd as &$item) $item = trim($item);
                    $arProps['STAFF'] = $arAdd;
                }

                if(!$_REQUEST['ID_EXIST']) {
                    #добавление компании
                    $arEl = array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => intval($this->arParams['IBLOCK_ID']),
                        "NAME" => $_REQUEST['NAME'],
                        "PREVIEW_TEXT" => print_r($_FILES, true),
                        "CODE" => $code,
                        "PROPERTY_VALUES" => $arProps,
                    );
                    if ($arElm["ID"] = $el->Add($arEl, false, false, false)) {
                        #добавлена компания - редирект на неё
                        LocalRedirect("/profile/company/?id=" . $arElm["ID"]);
                    } else {
                        LocalRedirect("/profile/company/?error=".$el->LAST_ERROR);
                    }


                }else{
                    #обновление существующей компании

                    #проверка ID пользователя и ID директора компании
                    $arFilter['IBLOCK_ID'] = intval($this->arParams['IBLOCK_ID']);
                    $arFilter['ACTIVE'] = 'Y';
                    $arFilter['ID'] = intval($_REQUEST["ID_EXIST"]);
                    if ($rsCompany = \CIBlockElement::GetList(
                        ['sort' => 'asc'],
                        $arFilter,
                        false,
                        false,
                        ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_DIRECTOR_ID']
                    )
                    ) {
                        if ($arCompany = $rsCompany->GetNext(true, false)) {
                            if($arCompany['PROPERTY_DIRECTOR_ID_VALUE'] != $USER->GetID()){
                                LocalRedirect("/profile/company/");
                            }
                        }else{
                            LocalRedirect("/profile/company/");
                        }
                    }

                    $arEl = array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => intval($this->arParams['IBLOCK_ID']),
                        "NAME" => $_REQUEST['NAME'],
                        "CODE" => $code,
                        "PROPERTY_VALUES" => $arProps,
                    );
                    if($_FILES["PREVIEW_PICTURE"]) $arEl["PREVIEW_PICTURE"] = $_FILES["PREVIEW_PICTURE"];

                    if ($el->Update(intval($_REQUEST["ID_EXIST"]), $arEl)) {
                        LocalRedirect("/profile/company/?id=" . $_REQUEST["ID_EXIST"]);
                    } else {
                        LocalRedirect("/profile/company/?error=".$el->LAST_ERROR);
                    }

                }

            }
            #удалении компании
        }elseif($_REQUEST["id"] && $_REQUEST['remove'] == 'Y'){

            #проверка ID пользователя и ID директора
            $arFilter['IBLOCK_ID'] = intval($this->arParams['IBLOCK_ID']);
            $arFilter['ACTIVE'] = 'Y';
            $arFilter['ID'] = $_REQUEST["id"];

            if ($rsCompany = \CIBlockElement::GetList(
                ['sort' => 'asc'],
                $arFilter,
                false,
                false,
                ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_DIRECTOR_ID']
            )
            ) {
                if ($arCompany = $rsCompany->GetNext(true, false)) {
                    if($arCompany['PROPERTY_DIRECTOR_ID_VALUE'] != $USER->GetID()){
                        #не директор - редирект
                        LocalRedirect("/profile/company/");
                    }
                }else{
                    #не существует компании - на страницу создания
                    LocalRedirect("/profile/company/");
                }
            }

            global $DB;
            $el = new CIBlockElement();
            $DB->StartTransaction();
            if ( $el->Delete($arCompany["ID"]) ) $DB->Commit(); else $DB->Rollback();
            LocalRedirect("/profile/");

            #существующая компания
        }elseif($_REQUEST["id"]){

            #проверка ID пользователя и ID директора
            $arFilter['IBLOCK_ID'] = intval($this->arParams['IBLOCK_ID']);
            $arFilter['ACTIVE'] = 'Y';
            $arFilter['ID'] = $_REQUEST["id"];

            if ($rsCompany = \CIBlockElement::GetList(
                ['sort' => 'asc'],
                $arFilter,
                false,
                false,
                ['ID', 'NAME', 'IBLOCK_ID', 'PREVIEW_PICTURE']
            )
            ) {
                if ($arElem = $rsCompany->GetNextElement()) {
                    $arCompany = $arElem->GetFields();
                    $arCompany['PROPERTIES'] = $arElem->GetProperties();
                    if($arCompany['PREVIEW_PICTURE']) $arCompany['PREVIEW_PICTURE'] = CFile::GetPath($arCompany['PREVIEW_PICTURE']);
                    $this->arResult['COMPANY'] = $arCompany;
                    if($arCompany['PROPERTIES']['DIRECTOR_ID']['VALUE'] == $USER->GetID()){
                        $this->arResult['IS_DIRECTOR'] = 'Y';
                    }else{
                        #не директор
                        $this->arResult['IS_DIRECTOR'] = 'N';
                        //LocalRedirect("/profile/company/");
                    }
                }else{
                    #не существует компании - на страницу создания
                    LocalRedirect("/profile/company/");
                }
            }

            #список свойств компании для вывода
            $arProps = [];
            $res = CIBlock::GetProperties(intval($this->arParams['IBLOCK_ID']), ['SORT'=>'ASC'], ['ACTIVE' => 'Y']);
            while($res_arr = $res->Fetch())
                if(in_array($res_arr['CODE'], $this->arParams['PROPERTIES_SHOW'])) $arProps[$res_arr['CODE']] = $res_arr;

            $this->arResult['PROPERTIES'] = $arProps;

            #получение сотрудников по ID
            if($IDs = $arCompany['PROPERTIES']['STAFF']['VALUE']){
                $arUsers = [];
                $rsUsers = CUser::GetList(($by = "email"), ($order = "asc"), ['ID' => implode('|',$IDs), 'ACTIVE' => 'Y'], []);
                while ($arUser = $rsUsers->Fetch()) {
                    $arUsers[] = $arUser;
                }
                if($arUsers){
                    $this->arResult['STAFF'] = $arUsers;
                }
            }

            $APPLICATION->AddHeadScript($this->GetPath().'/script.js');
            CUtil::InitJSCore(array('ajax'));

            $this->includeComponentTemplate();

            #создание компании
        }else{

            #список свойств компании для вывода
            $arProps = [];
            $res = CIBlock::GetProperties(intval($this->arParams['IBLOCK_ID']), ['SORT'=>'ASC'], ['ACTIVE' => 'Y']);
            while($res_arr = $res->Fetch())
                if(in_array($res_arr['CODE'], $this->arParams['PROPERTIES_SHOW'])) $arProps[$res_arr['CODE']] = $res_arr;

            $this->arResult['PROPERTIES'] = $arProps;

            $APPLICATION->AddHeadScript($this->GetPath().'/script.js');
            CUtil::InitJSCore(array('ajax'));

            if (! is_null($this->getTemplateName()) ) {
                $this->includeComponentTemplate();
            }

        }

    }

}