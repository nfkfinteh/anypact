<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Iblock;
/*
    Класс выводит информацию в карточку по сделке
*/

class CDemoSqr extends CBitrixComponent
{       
    
    public function onPrepareComponentParams($arParams)
    {
        if(!Loader::includeModule("iblock"))
        {
            $this->abortResultCache();
            ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return;
        }
        if($arParams["ELEMENT_ID"] <= 0)
		    $arParams["ELEMENT_ID"] = CIBlockFindTools::GetElementID(
			$arParams["ELEMENT_ID"],
			$arParams["ELEMENT_CODE"],
			$arParams["STRICT_SECTION_CHECK"]? $arParams["SECTION_ID"]: false,
			$arParams["STRICT_SECTION_CHECK"]? $arParams["~SECTION_CODE"]: false,
			$arFilter
		);
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X" => intval($arParams["X"]),
            "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
            "SECTION_ID" => intval($arParams["SECTION_ID"]),
            "ELEMENT_ID" => intval($arParams["ELEMENT_ID"]),
            "LOCATION" => htmlspecialcharsEx($arParams["LOCATION"]),
            "FORM_SDELKA"=>$arParams["FORM_SDELKA"],
            "DOGOVOR"=>$arParams["DOGOVOR"],
            "DETAIL_URL"=>$arParams["DETAIL_URL"],
            "SECTION_URL"=>$arParams["SECTION_URL"],
            "SET_CANONICAL_URL" => $arParams["SET_CANONICAL_URL"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "SET_BROWSER_TITLE" => $arParams["SET_BROWSER_TITLE"],
            "SET_META_KEYWORDS" => $arParams["SET_META_KEYWORDS"],
            "SET_META_DESCRIPTION" => $arParams["SET_META_DESCRIPTION"],
            "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
            "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
            "ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
            "ADDITIONAL_FILTER" => $arParams["ADDITIONAL_FILTER"],
        );
        return $result;
    }

    private function getElement($id_element) {
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

                $arFilter = array_merge(Array(
                    "ID" => $id_element,
                ), $arrFilter, $arrFilterN);
                if($_REQUEST['ACTION']!='ADD' && $_REQUEST['ACTION']!='EDIT'){
                    $arFilter = array_merge($arFilter, array("ACTIVE"=>"Y",
                    array(
                        "LOGIC" => "OR",
                        array("PROPERTY_INDEFINITELY" => 18),
                        array(">=DATE_ACTIVE_TO" => new \Bitrix\Main\Type\DateTime())
                    ),
                    ), $arrFilter);
                }
                $res = CIBlockElement::GetList(array(), $arFilter);
                $res->SetUrlTemplates($this -> arParams["DETAIL_URL"], "", $this -> arParams["IBLOCK_URL"]);
                if($ar_res = $res->GetNext()){
                    $ipropValues = new Iblock\InheritedProperty\ElementValues($ar_res["IBLOCK_ID"], $ar_res["ID"]);
                    $this->arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

                    Iblock\Component\Tools::getFieldImageData(
                        $ar_res,
                        array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
                        Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
                        'IPROPERTY_VALUES'
                    );
                }

                $this->arResult["IBLOCK"] = GetIBlock($ar_res["IBLOCK_ID"], $ar_res["IBLOCK_TYPE"]);

                $this -> arResult["SECTION"] = array("PATH" => array());
                $this -> arResult["SECTION_URL"] = "";
                if($this -> arParams["ADD_SECTIONS_CHAIN"] && $ar_res["IBLOCK_SECTION_ID"] > 0)
                {
                    $rsPath = CIBlockSection::GetNavChain(
                        $ar_res["IBLOCK_ID"],
                        $ar_res["IBLOCK_SECTION_ID"],
                        array(
                            "ID", "CODE", "XML_ID", "EXTERNAL_ID", "IBLOCK_ID",
                            "IBLOCK_SECTION_ID", "SORT", "NAME", "ACTIVE",
                            "DEPTH_LEVEL", "SECTION_PAGE_URL"
                        )
                    );
                    $rsPath->SetUrlTemplates("", $this -> arParams["SECTION_URL"]);
                    while($arPath = $rsPath->GetNext())
                    {
                        $ipropValues = new Iblock\InheritedProperty\SectionValues($ar_res["IBLOCK_ID"], $arPath["ID"]);
                        $arPath["IPROPERTY_VALUES"] = $ipropValues->getValues();
                        $this -> arResult["SECTION"]["PATH"][] = $arPath;
                        $this -> arResult["SECTION_URL"] = $arPath["~SECTION_PAGE_URL"];
                    }
                }

                if (
                    $this->arParams["SET_TITLE"]
                    || $this->arParams["ADD_ELEMENT_CHAIN"]
                    || $this->arParams["SET_BROWSER_TITLE"] === 'Y'
                    || $this->arParams["SET_META_KEYWORDS"] === 'Y'
                    || $this->arParams["SET_META_DESCRIPTION"] === 'Y'
                )
                {
                    $this->arResult["META_TAGS"] = array();
                    $resultCacheKeys[] = "META_TAGS";
        
                    if ($this->arParams["SET_TITLE"])
                    {
                        $this->arResult["META_TAGS"]["TITLE"] = (
                            $this->arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ""
                            ? $this->arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
                            : $ar_res["NAME"]
                        );
                    }
        
                    if ($this->arParams["ADD_ELEMENT_CHAIN"])
                    {
                        $this->arResult["META_TAGS"]["ELEMENT_CHAIN"] = (
                            $this->arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ""
                            ? $this->arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
                            : $ar_res["NAME"]
                        );
                    }
        
                    if ($this->arParams["SET_BROWSER_TITLE"] === 'Y')
                    {
                        $browserTitle = \Bitrix\Main\Type\Collection::firstNotEmpty(
                            $this->arResult["PROPERTIES"], array($this->arParams["BROWSER_TITLE"], "VALUE")
                            ,$this->arResult, $this->arParams["BROWSER_TITLE"]
                            ,$this->arResult["IPROPERTY_VALUES"], "ELEMENT_META_TITLE"
                        );
                        $this->arResult["META_TAGS"]["BROWSER_TITLE"] = (
                            is_array($browserTitle)
                            ? implode(" ", $browserTitle)
                            : $browserTitle
                        );
                        unset($browserTitle);
                    }
                    if ($this->arParams["SET_META_KEYWORDS"] === 'Y')
                    {
                        $metaKeywords = \Bitrix\Main\Type\Collection::firstNotEmpty(
                            $this->arResult["PROPERTIES"], array($this->arParams["META_KEYWORDS"], "VALUE")
                            ,$this->arResult["IPROPERTY_VALUES"], "ELEMENT_META_KEYWORDS"
                        );
                        $this->arResult["META_TAGS"]["KEYWORDS"] = (
                            is_array($metaKeywords)
                            ? implode(" ", $metaKeywords)
                            : $metaKeywords
                        );
                        unset($metaKeywords);
                    }
                    if ($this->arParams["SET_META_DESCRIPTION"] === 'Y')
                    {
                        $metaDescription = \Bitrix\Main\Type\Collection::firstNotEmpty(
                            $this->arResult["PROPERTIES"], array($this->arParams["META_DESCRIPTION"], "VALUE")
                            ,$this->arResult["IPROPERTY_VALUES"], "ELEMENT_META_DESCRIPTION"
                        );
                        $this->arResult["META_TAGS"]["DESCRIPTION"] = (
                            is_array($metaDescription)
                            ? implode(" ", $metaDescription)
                            : $metaDescription
                        );
                        unset($metaDescription);
                    }
                }
                return $ar_res;
            }        
    }

    // Все свойства элемента
    private function getProperty($id_iblok, $id_element){        
        $db_props = CIBlockElement::GetProperty($id_iblok, $id_element, "sort", "asc", array());         
        $array_props = array();        
        $array_img = array();
        while($ar_props = $db_props->Fetch()){ 
            
            $array_props[$ar_props["CODE"]] = $ar_props ;
            
            if ($ar_props["CODE"] == "INPUT_FILES"){
                $file_path = CFile::GetPath($ar_props["VALUE"]);
                if(!empty($file_path)){
                    $array_img[] = array("URL" => $file_path, "PROPERTY" => $ar_props);
                }
            }
            if ($ar_props["CODE"] == "MAIN_FILES"){
                $file_path = CFile::GetFileArray($ar_props["VALUE"]);
                if(!empty($file_path)){
                    $array_incl[] = array("URL" => $file_path['SRC'], "ID" => $ar_props["PROPERTY_VALUE_ID"], "ID_FILE" => $ar_props["VALUE"], 'NAME'=>$file_path['ORIGINAL_NAME']);
                }
            }
        }

        $array_props["IMG_FILE"] = $array_img;
        $array_props["UNCLUDE_FILE"] = $array_incl;
        return $array_props;
    }    

    function paramsUser($arParams){
        $arResult["INFOBLOCK_ID"] = $arParams["IBLOCK_ID"];
        $arResult["SECTION_ID"] = $arParams["SECTION_ID"];
        $arResult["ELEMENT_ID"] = $arParams["ELEMENT_ID"];
        return $arResult;
    }

    public function listSection($id_iblock, $section_id) {
        $arPact = array();            
        if(CModule::IncludeModule("iblock"))
            {
                // если $ID не задан или это не число, тогда 
                // $ID будет =0, выбираем корневые разделы
                $ID =  $section_id; //false;
                // выберем папки из информационного блока $BID и раздела $ID
                $items = GetIBlockSectionList($id_iblock, $ID, Array("sort"=>"asc"), 10);
                $arr_section_value['PROP_ONE_ITEM'] = 'Y';
                //
                // для отображения всех элементов в подкаталогах получим их ид
                if ($_GET['SECTION_ID'] == 0){
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock));
                }
                // фильтр для отбора всех записей включая подкатегории                 
                while($arItem = $items->GetNext())
                {                  
                    $arFilter = Array("IBLOCK_ID"=>IntVal($id_iblock), "SECTION_ID"=> $arItem['ID'], "INCLUDE_SUBSECTIONS" => "Y" );                    
                    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);                    
                    $arr_Count_Iten = array();
                    // перебераем категории и считаем сколько там элементов
                    while($ob = $res->GetNextElement())
                    {
                        $arFields = $ob->GetFields();
                        $arr_Count_Iten[]['ID'] = $arFields['ID'];                     
                    }                    
                    $arItem['COUNT_IN_ITEM'] = count($arr_Count_Iten);                    
                    //
                    $arr_section_value['SECTION_LIST'][] = $arItem;                    
                    $arr_section_value['PROP_ONE_ITEM'] = 'N';
                }
                           
                if ($arr_section_value['PROP_ONE_ITEM'] == 'Y'){                    
                    $arr_section_value['ARR_ONE_ITEM'] = GetIBlockSection($ID);
                }                
            }
        return $arr_section_value;
    }

    public function getTreeCategory($ID_INF){
        $tree = CIBlockSection::GetTreeList(
            $arFilter=Array('IBLOCK_ID' => $ID_INF),
            $arSelect=Array()
        );
        while($section = $tree->GetNext()) {
            $arTree[] = $section;
        }
        //print_r($arTree);
        return $arTree;
    }

    public function getCountDogovor($idUser){
        $hlbl = 3;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
            "filter" => array(
                'LOGIC' => 'OR',
                array(
                    'UF_ID_USER_A'=>$idUser
                ),
                array(
                    'UF_ID_USER_B'=>$idUser
                )
            )
        ));

        $result = $rsData->getSelectedRowsCount();

        return $result;
    }

    public function getListCity(){
        $arFilter = [
            'IBLOCK_ID'=>7,
            'ACTIVE'=>'Y',
        ];
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'NAME'
        ];
        $res = CIBlockElement::GetList(['NAME'=>'ASC'], $arFilter, false, false, $arSelect);
        while ($obj = $res->GetNext(true, false)){
            $result[] = $obj['NAME'];
        }
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
                "filter" => array("UF_USER_A" => $this->arResult["PROPERTY"]["PACT_USER"]["VALUE"], "UF_USER_B" => $this->arResult["USER_ID"])
            ));
            while($arData = $rsData->Fetch()){
                $result = true;
            }
        }
        if(empty($result)){
            $result = false;
        }

        return $result;
    }

    #функция получения данных владельца договора (компании или физ лица)
    public function getContractHolder(){
        if(empty($this->arResult["PROPERTY"]["ID_COMPANY"]["VALUE"])){
            $UserContractHolder = CUser::GetByID($this->arResult["PROPERTY"]["PACT_USER"]["VALUE"]);
            $arrUserContractHolder = $UserContractHolder->Fetch();
            $USER_CONTRACT_HOLDER = array(
                "ID"    => $arrUserContractHolder["ID"],
                "NAME"  => $arrUserContractHolder["NAME"],
                "LAST_NAME" => $arrUserContractHolder["LAST_NAME"],
                "LOGIN" => $arrUserContractHolder["LOGIN"],
                "CITY"  => $arrUserContractHolder["PERSONAL_CITY"],
                "PERSONAL_PHOTO" => CFile::GetPath($arrUserContractHolder["PERSONAL_PHOTO"]),
                "TYPE" => 'user',
                "UF_BLACKLIST" => $arrUserContractHolder['UF_BLACKLIST']
            );
        }
        else{
            $CompanyContractHolder = CIBlockElement::GetList([], ['IBLOCK_ID'=>8, 'ID'=>$this->arResult["PROPERTY"]["ID_COMPANY"]["VALUE"]], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_CITY', 'PREVIEW_PICTURE']);
            if ($obj = $CompanyContractHolder->GetNext(true, false)){
                $arrCompanyContractHolder = $obj;
            }
            $USER_CONTRACT_HOLDER = array(
                "ID"    => $arrCompanyContractHolder["ID"],
                "NAME"  => $arrCompanyContractHolder["NAME"],
                "CITY"  => $arrCompanyContractHolder["PROPERTY_CITY_VALUE"],
                "PERSONAL_PHOTO" => CFile::GetPath($arrCompanyContractHolder["PREVIEW_PICTURE"]),
                "TYPE" => 'company'
            );
        }

        return $USER_CONTRACT_HOLDER;
    }

    public function executeComponent()
    {
        /*if($this->startResultCache())
        {*/
            global $USER, $APPLICATION;
            $this->arResult = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_ID"] = CUser::GetID();
            $rsUser = CUser::GetByID($this->arResult["USER_ID"]);
            $this->arResult['USER'] = $rsUser->GetNext();
            $this->arResult['CUR_COMPANY_ID'] = $this->arResult['USER']['UF_CUR_COMPANY'];
            //404 при добавлении сделки если вользователь не подтвердил ругистрацию в гос услугах
            if($this->arResult['USER']['UF_ESIA_AUT']==0 && $_REQUEST['ACTION']=='ADD')
            {
                Iblock\Component\Tools::process404(
                    '',
                    true,
                    true,
                    true
                );
            }

            $this->arResult["USER_LOGIN"] =$this->arResult['USER']['LOGIN'];
            $this->arResult["ELEMENT"] = $this->getElement($this->arResult["ELEMENT_ID"]);

            if($_REQUEST['ACTION']!='ADD' && $_REQUEST['ACTION']!='EDIT' && empty($this->arResult["ELEMENT"])){
                Iblock\Component\Tools::process404(
                    '',
                    true,
                    true,
                    true
                );
            }

            $this->arResult["PROPERTY"] = $this->getProperty($this->arResult["INFOBLOCK_ID"], $this->arResult["ELEMENT_ID"]);

            $arDataDisplay = [
                'UF_ESIA_AUT'=>$this->arResult['USER']['UF_ESIA_AUT'],
                'ID_COMPANY_ELEMENT'=>$this->arResult['PROPERTY']['ID_COMPANY']['VALUE'],
                'ID_COMPANY_USER'=>$this->arResult['USER']['UF_CUR_COMPANY'],
                'ID_USER_ELEMENT'=>$this->arResult['PROPERTY']['PACT_USER']['VALUE'],
                'ID_USER'=>$this->arResult["USER_ID"],
            ];

            //404 проверка на отображение сделки под выбранным профилем
            if($_REQUEST['ACTION']=='EDIT' && !isDisplayElement($arDataDisplay))
            {
                Iblock\Component\Tools::process404(
                    '',
                    true,
                    true,
                    true
                );
            }



            $this->arResult["INFOBLOCK_SECTION_LIST"] = $this->getTreeCategory($this->arResult["INFOBLOCK_ID"]);
            $this->arResult['DOGOVOR']['CNT'] =  $this->getCountDogovor($this->arResult["USER_ID"]);
            $this->arResult['LIST_CITY'] = $this->getListCity();
            $this->arResult["CONTRACT_HOLDER"] = $this->getContractHolder();

            //данные заполненния формы и договор
            $this->arResult['FORM_SDELKA'] = $this->arParams['FORM_SDELKA'];
            if($this->arParams['DOGOVOR']){
                $cacheName = $this->arParams['DOGOVOR'];
            }
            $cache = \Bitrix\Main\Data\Cache::createInstance();
            $cacheInitDir = 'dogovor_create_sdelka';

            if ($cache->initCache(600, $cacheName, $cacheInitDir)){
                $this->arResult['DOGOVOR'] = $cache->getVars();
            }
            else{
                $this->arResult['DOGOVOR'] = '';
            }

            if(!empty($this->arResult['DOGOVOR'])){
                $this->arResult['DOGOVOR_KEY_CASHE'] = $cacheName;
            }

            $this->arResult["BLACKLIST"] = $this->getBlackList();

            if($_REQUEST['ACTION']!='ADD' && $_REQUEST['ACTION']!='EDIT' && isset($this->arResult["ELEMENT_ID"]))
            {
                $arTitleOptions = null;
                if(Loader::includeModule("iblock"))
                {
                    CIBlockElement::CounterInc($this->arResult["ID"]);

                    if($USER->IsAuthorized())
                    {
                        if(
                            $APPLICATION->GetShowIncludeAreas()
                            || $this->arParams["SET_TITLE"]
                            || isset($this->arResult[$this->arParams["BROWSER_TITLE"]])
                        )
                        {
                            $arReturnUrl = array(
                                "add_element" => CIBlock::GetArrayByID($this->arResult["IBLOCK_ID"], "DETAIL_PAGE_URL"),
                                "delete_element" => (
                                    empty($this->arResult["SECTION_URL"])?
                                    $this->arResult["LIST_PAGE_URL"]:
                                    $this->arResult["SECTION_URL"]
                                ),
                            );

                            $arButtons = CIBlock::GetPanelButtons(
                                $this->arResult["IBLOCK_ID"],
                                $this->arResult["ID"],
                                $this->arResult["IBLOCK_SECTION_ID"],
                                Array(
                                    "RETURN_URL" => $arReturnUrl,
                                    "SECTION_BUTTONS" => false,
                                )
                            );

                            if($APPLICATION->GetShowIncludeAreas())
                                $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));

                            if($this->arParams["SET_TITLE"] || isset($this->arResult[$this->arParams["BROWSER_TITLE"]]))
                            {
                                $arTitleOptions = array(
                                    'ADMIN_EDIT_LINK' => $arButtons["submenu"]["edit_element"]["ACTION"],
                                    'PUBLIC_EDIT_LINK' => $arButtons["edit"]["edit_element"]["ACTION"],
                                    'COMPONENT_NAME' => $this->getName(),
                                );
                            }
                        }
                    }
                }

                if ($this->arParams['SET_CANONICAL_URL'] === 'Y' && $this->arResult["CANONICAL_PAGE_URL"])
                {
                    $APPLICATION->SetPageProperty('canonical', $this->arResult["CANONICAL_PAGE_URL"]);
                }

                if($this->arParams["SET_TITLE"])
                    $APPLICATION->SetTitle($this->arResult["META_TAGS"]["TITLE"], $arTitleOptions);

                if ($this->arParams["SET_BROWSER_TITLE"] === 'Y')
                {
                    if ($this->arResult["META_TAGS"]["BROWSER_TITLE"] !== '')
                        $APPLICATION->SetPageProperty("title", $this->arResult["META_TAGS"]["BROWSER_TITLE"], $arTitleOptions);
                }

                if ($this->arParams["SET_META_KEYWORDS"] === 'Y')
                {
                    if ($this->arResult["META_TAGS"]["KEYWORDS"] !== '')
                        $APPLICATION->SetPageProperty("keywords", $this->arResult["META_TAGS"]["KEYWORDS"], $arTitleOptions);
                }

                if ($this->arParams["SET_META_DESCRIPTION"] === 'Y')
                {
                    if ($this->arResult["META_TAGS"]["DESCRIPTION"] !== '')
                        $APPLICATION->SetPageProperty("description", $this->arResult["META_TAGS"]["DESCRIPTION"], $arTitleOptions);
                }

                if($this->arParams["INCLUDE_IBLOCK_INTO_CHAIN"] && isset($this->arResult["IBLOCK"]["NAME"]))
                {
                    $APPLICATION->AddChainItem($this->arResult["IBLOCK"]["NAME"], $this->arResult["ELEMENT"]["~LIST_PAGE_URL"]);
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
                if ($this->arParams["ADD_ELEMENT_CHAIN"])
                    $APPLICATION->AddChainItem($this->arResult["META_TAGS"]["ELEMENT_CHAIN"]);

                if ($this->arParams["SET_LAST_MODIFIED"] && $this->arResult["TIMESTAMP_X"])
                {
                    Context::getCurrent()->getResponse()->setLastModified(DateTime::createFromUserTime($this->arResult["TIMESTAMP_X"]));
                }

            }
            /*$GLOBALS['CACHE_MANAGER']->StartTagCache("/".SITE_ID.$this->GetRelativePath());
            $GLOBALS['CACHE_MANAGER']->RegisterTag('iblock_id_4');//Кеш будет зависить от изменений инфоблока 9
            $GLOBALS['CACHE_MANAGER']->EndTagCache();*/
            $this->includeComponentTemplate();
        //}
        
        //return $this->arResult;
    }
};

?>