<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader,
    \Bitrix\Main\Application,
    Bitrix\Iblock;

class CompanySber extends CBitrixComponent
{

    private function sendMessageAddStaff($idUser, $nameCompany){

            $rsUser = CUser::GetByID($idUser);
            if($obj = $rsUser->GetNext()){
                $arUser = $obj;
            }
            $send_data = Array(
               'EMAIL' => $arUser['EMAIL'],
               'NAME_COMPANY'=> htmlspecialchars($nameCompany)
            );

            CEvent::Send("ADD_STAFF", "s1", $send_data);

    }

    public function executeComponent()
    {
        global $APPLICATION;
        global $USER;

        if (!$this->arParams['IBLOCK_ID']) return false;
        if (!Loader::includeModule('iblock')) return false;
        if (!$USER) return false;

        $rsUserStart = CUser::GetByID($USER->GetID());
        $arUserStart = $rsUserStart->GetNext();
        if($arUserStart['UF_ESIA_AUT'] == 0){
            //return false;
            Iblock\Component\Tools::process404(
                '',
                true,
                true,
                true
            );
        }

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
                    if($_REQUEST['staff_add_no_active']){
                        $arAdd = explode(',', $_REQUEST['staff_add_no_active']);
                        foreach($arAdd as &$item) $item = trim($item);
                        $this->arResult['ADD_NO_ACTIVE'] = $arAdd;
                    }
                }

                $APPLICATION->RestartBuffer();

                $this->IncludeComponentTemplate('ajax_result');

                CMain::FinalActions();

                die();
            }
        // добавление / редактирование компании и ИП
        }elseif($_REQUEST['DIRECTOR_ID'] && $_REQUEST['NAME']){
            #скрытые поля
            $addition_props = ['DIRECTOR_NAME', 'DIRECTOR_ID', 'STAFF', 'STAFF_NO_ACTIVE', 'TYPE'];
            $props_empty = [];

            foreach($_REQUEST as $key => $req){
                if(in_array($key, $this->arParams['PROPERTIES_NEED']) && empty($req)) $props_empty[] = $key;
                if(in_array($key, $this->arParams['PROPERTIES_NUMBER']) && !empty($req) && !ctype_digit($req)) $props_no_number[] = $key;
                if((!in_array($key, $this->arParams['PROPERTIES_SHOW']) && !in_array($key, $addition_props)) || empty($req)) continue;
                $arProps[$key] = $req;
            }

            if($props_empty){
                $output = implode(', ', $props_empty);
                LocalRedirect("?error=props_empty&props=".$output."&id=".$_REQUEST['ID_EXIST']);
            }
            if($props_no_number){
                $output = implode(', ', $props_no_number);
                LocalRedirect("?error=props_no_number&props=".$output."&id=".$_REQUEST['ID_EXIST']);
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
                if ($arProps['STAFF_NO_ACTIVE']) {
                    $arAdd = explode(',', $arProps['STAFF_NO_ACTIVE']);
                    foreach ($arAdd as &$item) $item = trim($item);
                    $arProps['STAFF_NO_ACTIVE'] = $arAdd;
                }
                if(!$_REQUEST['ID_EXIST']) {
                    #добавление компании
                    $arEl = array(
                        "ACTIVE" => "N",
                        "IBLOCK_ID" => intval($this->arParams['IBLOCK_ID']),
                        "NAME" => $_REQUEST['NAME'],
                        "CODE" => $code,
                        "PREVIEW_TEXT"=> htmlspecialcharsEx($_REQUEST['PREVIEW_TEXT']),
                        "PROPERTY_VALUES" => $arProps,
                    );
                    if($_REQUEST["PREVIEW_PICTURE"]) $arEl["PREVIEW_PICTURE"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$_REQUEST["PREVIEW_PICTURE"]);

                    if ($arElm["ID"] = $el->Add($arEl, false, false, false)) {
                        //подчищаем пакпку со временными фалами
                        deleteTmpFile('/upload/tmp/company_profile/', 1);
                        #отправка письма добавленным сотрудникам
                        if(!empty($arProps['STAFF_NO_ACTIVE'])) {
                            foreach ($arProps['STAFF_NO_ACTIVE'] as $idUser){
                                if(!empty($idUser)){
                                    $this->sendMessageAddStaff($idUser, $_REQUEST['NAME']);
                                }
                            }
                        }

                        //Отправка письма о модерации
                        CEvent::Send("NEW_COMPANY_IP", "s1", array("NAME" => $_REQUEST['NAME'], "ID" => $arElm["ID"]));

                        #добавлена компания - редирект на неё
                        if($arProps['TYPE'] == 9){
                            LocalRedirect("/profile/infopage/?typepage=new_ip");
                        }else{
                            LocalRedirect("/profile/infopage/?typepage=new_company");
                        }
                        
                        //LocalRedirect("/profile/company/?id=" . $arElm["ID"]);
                    } else {
                        LocalRedirect("?error=".$el->LAST_ERROR);
                    }


                }else{
                    #обновление существующей компании

                    #проверка ID пользователя и ID директора компании
                    $arFilter['IBLOCK_ID'] = intval($this->arParams['IBLOCK_ID']);
                    $arFilter['ACTIVE'] = 'Y';
                    $arFilter['ID'] = intval($_REQUEST["ID_EXIST"]);
                    $rsCompany = \CIBlockElement::GetList(
                        ['sort' => 'asc'],
                        $arFilter,
                        false,
                        false,
                        ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_DIRECTOR_ID', 'PROPERTY_STAFF', 'PROPERTY_STAFF_NO_ACTIVE']
                    );
                    if ($obj = $rsCompany->GetNextElement()) {
                        $arCompany = $obj->GetFields();
                        $arCompany['PROPERTIES'] = $obj->GetProperties();
                    }

                    if ($arCompany['PROPERTIES']['DIRECTOR_ID']['VALUE']!=$USER->GetID()) {
                        LocalRedirect("");
                    }

                    $arEl = array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => intval($this->arParams['IBLOCK_ID']),
                        "NAME" => $_REQUEST['NAME'],
                        "CODE" => $code,
                        "PREVIEW_TEXT"=>htmlspecialcharsEx($_REQUEST['PREVIEW_TEXT'])
                    );
                    if($_REQUEST["PREVIEW_PICTURE"]) $arEl["PREVIEW_PICTURE"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$_REQUEST["PREVIEW_PICTURE"]);


                    if ($el->Update(intval($_REQUEST["ID_EXIST"]), $arEl)) {

                        $el->SetPropertyValuesEx(intval($_REQUEST["ID_EXIST"]), intval($this->arParams['IBLOCK_ID']), $arProps);

                        //подчищаем пакпку со временными фалами
                        deleteTmpFile('/upload/tmp/company_profile/', 1);

                        #отправка письма добавленным сотрудникам
                        if(!empty($arProps['STAFF_NO_ACTIVE'])) {
                            foreach ($arProps['STAFF_NO_ACTIVE'] as $idUser){
                                if(!in_array($idUser, $arCompany['PROPERTIES']['STAFF_NO_ACTIVE']['VALUE']) && !empty($idUser)){
                                    $this->sendMessageAddStaff($idUser, $_REQUEST['NAME']);
                                }
                            }
                        }

                        LocalRedirect("?id=" . $_REQUEST["ID_EXIST"]);
                    } else {
                        LocalRedirect("?error=".$el->LAST_ERROR);
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
                        LocalRedirect("");
                    }
                }else{
                    #не существует компании - на страницу создания
                    LocalRedirect("");
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
                ['ID', 'NAME', 'IBLOCK_ID', 'PREVIEW_PICTURE', 'PREVIEW_TEXT']
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
                    LocalRedirect("");
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

            #получение сотрудников не потвердившие свое участие
            if($IDs = $arCompany['PROPERTIES']['STAFF_NO_ACTIVE']['VALUE']){
                $arUsers = [];
                $rsUsers = CUser::GetList(($by = "email"), ($order = "asc"), ['ID' => implode('|',$IDs), 'ACTIVE' => 'Y'], []);
                while ($arUser = $rsUsers->Fetch()) {
                    $arUsers[] = $arUser;
                }
                if($arUsers){
                    $this->arResult['STAFF_NO_ACTIVE'] = $arUsers;
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
            $this->arResult['IS_DIRECTOR'] = 'Y';
            //Получаем данные пользователя для ИП
            $rsUser = CUser::GetList($by="ID", $order="asc", array("ID" => $USER->GetID()));
            $this->arResult['USER'] = $rsUser -> GetNext();

            $res = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 8, "=PROPERTY_DIRECTOR_ID" => $this->arResult['USER']['ID'], "=PROPERTY_TYPE" => 9), false, false, array("ID", "ACTIVE"));
            if($ob = $res -> GetNext()){
                $this->arResult['IP_ID'] = $ob['ID'];
                $this->arResult['ACTIVE'] = $ob['ACTIVE'];
            }

            $APPLICATION->AddHeadScript($this->GetPath().'/script.js');
            CUtil::InitJSCore(array('ajax'));

            if (! is_null($this->getTemplateName()) ) {
                $this->includeComponentTemplate();
            }

        }

    }

}