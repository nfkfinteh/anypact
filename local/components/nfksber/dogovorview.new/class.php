<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

/*
    Класс выводит информацию в карточку по сделке
*/
include_once 'autoedittext.php';

class CDemoSqr extends CBitrixComponent
{       
    public $ID_CONTRACT;
    public $USER_PROPERTY;
    private $ID_BLOCK_OFFERS = 3; // ID Инфоблока с объявлениями Клиентов
    private $ID_BLOCK_TEMPLATES_CONTRACTS = 5; // ID Инфоблока шаблона договоров
    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE"            => $arParams["CACHE_TYPE"],
            "CACHE_TIME"            => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "X"                     => intval($arParams["X"]),
            "IBLOCK_ID"             => intval($arParams["IBLOCK_ID"]),
            "SECTION_ID"            => intval($arParams["SECTION_ID"]),
            "IBLOCK_ID_CONTRACT"    => intval($arParams["IBLOCK_ID_CONTRACT"]),
            "TYPE_USER_PROF"        => intval($arParams["TYPE_USER_PROF"])
        );
        return $result;
    }

    private function getElement($id_element) {
        $arPact = array();        
        if(CModule::IncludeModule("iblock"))
            {
                $res = CIBlockElement::GetByID($id_element);
                if($ar_res = $res->GetNext()){
                    return $ar_res;
                }
                
            }        
    }

    // Все свойства элемента
    private function getProperty($id_iblok, $id_element){        
        $db_props           = CIBlockElement::GetProperty($id_iblok, $id_element, "sort", "asc", array());         
        $array_props        = array();        
        $array_img          = array();
        $array_unclude_file = array();

        while($ar_props = $db_props->Fetch()){ 
            
            $array_props[$ar_props["CODE"]] = $ar_props ;
            
            if ($ar_props["CODE"] == "INPUT_FILES"){
                $file_path      = CFile::GetPath($ar_props["VALUE"]);
                $array_img[]    = array("URL" => $file_path, "PROPERTY" => $ar_props);
            }

            if ($ar_props["CODE"] == "DOGOVOR_IMG"){
                if(!empty($ar_props["VALUE"])) {
                    $file_path = CFile::GetPath($ar_props["VALUE"]);
                    $array_img_dogovor[] = array("URL" => $file_path, "PROPERTY" => $ar_props);
                }
            }

            if ($ar_props["CODE"] == "MAIN_FILES"){
                if(!empty($ar_props["VALUE"])){
                    $file_path = CFile::GetPath($ar_props["VALUE"]);
                    $array_unclude_file[] = array("URL" => $file_path, "PROPERTY" => $ar_props);
                }
            }

        }
        
        $array_props["IMG_FILE"]        = $array_img;
        $array_props["DOGOVOR_IMG"]     = $array_img_dogovor;
        $array_props["INCLUDE_FILES"]   = $array_unclude_file;

        if(!empty($array_props["ID_DOGOVORA"]["VALUE"])){
            $this->ID_CONTRACT = $array_props["ID_DOGOVORA"]["VALUE"];            
        }

        return $array_props;
    }

    private function getTemplateContractCategote(){
        $IBLOCK_ID    = $this->ID_BLOCK_TEMPLATES_CONTRACTS;
        if(CModule::IncludeModule("iblock")){
            $arFilter    = Array(
                'IBLOCK_ID'=>$IBLOCK_ID,
                'GLOBAL_ACTIVE'=>'Y');
            $obSection    = CIBlockSection::GetTreeList($arFilter);
            $arThree = array();
            while($arResult = $obSection->GetNext()){
                $arThree[]= $arResult;
            }
            return $arThree;
        }
    }

    private function getMultyProperty($ID_IBLOCK, $ID_EL){
        $VALUES = array();
        $res = CIBlockElement::GetProperty($ID_IBLOCK, $ID_EL, "sort", "asc", array("CODE" => "STEPS"));
        while ($ob = $res->GetNext())
        {
            if(!empty($ob['VALUE'])){
                $VALUES[] = $ob['VALUE'];
            }
        }
        return $VALUES;
    }

    function paramsUser($arParams){        
        $arResult["INFOBLOCK_ID"]       = $arParams["IBLOCK_ID"];
        $arResult["SECTION_ID"]         = $arParams["SECTION_ID"];
        
        return $arResult;
    }

    private function getJsRequisit(){
        if(!empty($this->arResult['COMPANY_PROP'])){
            foreach ($this->arResult['COMPANY_PROP'] as $code=>$prop){
                if(!is_array($prop)){
                    if($code=='NAME'){
                        $this->arResult['JS_DATA']['USER'][$code] = [
                            'NAME'=>'Название компании',
                            'VALUE'=>$prop
                        ];
                    }
                }
                else{
                    $this->arResult['JS_DATA']['USER'][$code] = $prop;
                }
            }
        }
        else{
            $this->arResult['JS_DATA']['USER'] = [
                'NAME' => [
                    'NAME'=>'ФИО',
                    'VALUE'=> $this->arResult["USER_PROP"]["LAST_NAME"].' '.$this->arResult["USER_PROP"]["NAME"].' '.$this->arResult["USER_PROP"]["SECOND_NAME"],
                ],
                'PHONE' => [
                    'NAME'=>'Телефон',
                    'VALUE'=> $this->arResult["USER_PROP"]["PERSONAL_PHONE"],
                ],
                'PASSPORT' => [
                    'NAME'=>'Пасопрт',
                    'VALUE'=> $this->arResult["USER_PROP"]["UF_PASSPORT"].' '.$this->arResult["USER_PROP"]["UF_KEM_VPASSPORT"],
                ],
                'INDEX' => [
                    'NAME'=>'Индекс',
                    'VALUE'=> $this->arResult["USER_PROP"]["PERSONAL_ZIP"],
                ],
                'CITY' => [
                    'NAME'=>'Город',
                    'VALUE'=> $this->arResult["USER_PROP"]["PERSONAL_CITY"],
                ],
                'REGION' => [
                    'NAME'=>'Область',
                    'VALUE'=> $this->arResult["USER_PROP"]["UF_REGION"],
                ],
                'STREET' => [
                    'NAME'=>'Улица',
                    'VALUE'=> $this->arResult["USER_PROP"]["UF_STREET"],
                ],
                'HOUSE' => [
                    'NAME'=>'Дом',
                    'VALUE'=> $this->arResult["USER_PROP"]["UF_N_HOUSE"],
                ]
            ];
        }
    }

    private function getCompany($type){
        //если выбран профиль компании
        $resultDataProfile = [];
        if($type == 1){
            //при создании договора компания береться из сделки и сравниваеться с текущим пользователем
            if(
                !empty($this->arResult['PROPERTY']['ID_COMPANY']['VALUE']) &&
                $this->arResult['USER_PROP']['UF_CUR_COMPANY'] == $this->arResult['PROPERTY']['ID_COMPANY']['VALUE']
            ){
                $idCompany =  $this->arResult['USER_PROP']['UF_CUR_COMPANY'];
            }
        }
        elseif(!empty($this->arResult['USER_PROP']['UF_CUR_COMPANY'])){
            //полуение данных компании если выбран профиль "компания"
            $idCompany =  $this->arResult['USER_PROP']['UF_CUR_COMPANY'];
        }

        if(!empty($idCompany)){
            $res = CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID' => 8,
                    'ID' => $this->arResult['USER_PROP']['UF_CUR_COMPANY'],
                    'ACTIVE' => 'Y'
                ],
                false,
                false,
                ['IBLOCK_ID', 'ID', 'NAME']
            );
            if ($obj = $res->GetNextElement()) {
                $arCompany = $obj->GetFields();
                $arCompany['PROPERTY'] = $obj->GetProperties();
            }
            if (!empty($arCompany)) {
                $resultDataProfile = [
                    'IBLOCK_ID' => $arCompany['IBLOCK_ID'],
                    'ID' => $arCompany['ID'],
                    'NAME' => $arCompany['NAME']
                ];
                foreach ($arCompany['PROPERTY'] as $prop) {
                    $resultDataProfile[$prop['CODE']] = [
                        'NAME' => $prop['NAME'],
                        'VALUE' => $prop['VALUE']
                    ];
                }
            }
        }

        return $resultDataProfile;
    }

    public function executeComponent()
    {
        global $USER;
        $userId = CUser::GetID();
        $this->arResult["USER_ID"] = $userId;
        //if($this->startResultCache($this->arParams['CACHE_TIME'], $_GET['SECTION_ID'].$_GET['ELEMENT_ID'].$_GET['ID_TEMPLATE'].$userId))
        //{
            $this->arResult                         = array_merge($this->arResult, $this->paramsUser($this->arParams));
            // данные владельца сделки           
            $UserContractHolder                     = CUser::GetByID(CUser::GetID());
            $arrUserContractHolder                  = $UserContractHolder->Fetch();
            $this->USER_PROPERTY                    = $arrUserContractHolder;
            $this->arResult["USER_PROP"]            = $arrUserContractHolder;
            $this->arResult["USER_LOGIN"]           = CUser::GetLogin();
            $this->arResult["PROPERTY"]             = $this->getProperty($this->arResult["INFOBLOCK_ID"], $this->arResult["ELEMENT_ID"]);

            $this->arResult["COMPANY_PROP"]         = $this->getCompany($this->arParams['TYPE_USER_PROF']);
            $this->arResult["THREE_TEMPLATE"]       = $this->getTemplateContractCategote();

            //получаем данные по контракту
            //$this->arResult["CONTRACT_PROPERTY"]    = $this->getPropertyContract($this->arResult["INFOBLOCK_C_ID"]);

            if(!empty($_GET["ID_TEMPLATE"])){                
                $this->arResult["TEMPLATE_CONTENT"] = $this->getElement($_GET["ID_TEMPLATE"]);
                $this->arResult["TEMPLATE_CONTENT_PROPERTY"]    = $this->getMultyProperty(5, $_GET["ID_TEMPLATE"]);
            }

            #формирование масива для js (подстановка реквизитов)
            $this->getJsRequisit();


            //$this->EndResultCache();
        //}

        //записываем в сесию данные которые заполнили в форме
        if($_GET['form']){
            mb_parse_str (urldecode($_GET['form']), $arFormSdelka);
            $_SESSION['FORM_SDELKA'] = $arFormSdelka;
        }

        BXClearCache(false, "/dogovor_create_sdelka/");//подчищаем кеш с временными сохраненными догворми
        deleteTmpFile('/upload/tmp/dogovor_create_sdelka_img/', 1);//удаление временных картинок для договоров
        $this->includeComponentTemplate();
        
        //return $this->arResult;
    }
};

?>