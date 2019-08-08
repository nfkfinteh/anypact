<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
            "ELEMENT_ID"            => intval($arParams["ELEMENT_ID"]),
            "IBLOCK_ID_CONTRACT"    => intval($arParams["IBLOCK_ID_CONTRACT"]),
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
        $db_props       = CIBlockElement::GetProperty($id_iblok, $id_element, "sort", "asc", array());         
        $array_props    = array();        
        $array_img      = array();

        while($ar_props = $db_props->Fetch()){ 
            
            $array_props[$ar_props["CODE"]] = $ar_props ;
            
            if ($ar_props["CODE"] == "INPUT_FILES"){
                $file_path      = CFile::GetPath($ar_props["VALUE"]);
                $array_img[]    = array("URL" => $file_path, "PROPERTY" => $ar_props);
            }
        }
        
        $array_props["IMG_FILE"] = $array_img;      
        
        if(!empty($array_props["ID_DOGOVORA"]["VALUE"])){
            $this->ID_CONTRACT = $array_props["ID_DOGOVORA"]["VALUE"];            
        }

        return $array_props;
    }

    private function convertContent($Content){
        
        $regexp 		= "/%DATE%/ui";
        $replacement 	= date("d m Y") ;
        $Content = preg_replace($regexp, $replacement, $Content);

        $regexp 		= '/<recont fio.*recont>/ui';
        $replacement 	= 'Соловьёв Игорь Владимирович' ;
        $Content = preg_replace($regexp, $replacement, $Content);
        return $Content;
    }

    //Cвойства контракта
    private function getPropertyContract($id_infobloc_contract){        
        // объект
        $array_props    = array(); 
        $res            = CIBlockElement::GetByID($this->ID_CONTRACT);
        
        if($ar_res = $res->GetNext()){
            $array_props["CONTRACT"] = $ar_res;
        }        
        //подготовка текста
        $Contract_template_Text = $this->convertContent($array_props["CONTRACT"]["DETAIL_TEXT"]);

        $clear_text = new autoedittext();

        $Contract_template_Text                 = $clear_text->replaceTag($Contract_template_Text, $this->USER_PROPERTY);
        $array_props["CONTRACT"]["DETAIL_TEXT"] = str_replace("&nbsp;", "", $Contract_template_Text);
        
        // свойства контракта
        $db_props       = CIBlockElement::GetProperty($id_infobloc_contract, $this->ID_CONTRACT, "sort", "asc", array());
        
        while($ar_props = $db_props->Fetch()){ 
            $array_props["CONTRACT_PROPERTY"][] = $ar_props;
        }
        
        return $array_props;
    }

    private function getListCategory(){
        $items          = GetIBlockSectionList(5, 0, Array("sort"=>"asc"), 10);
        $arr_sections   = array();

        while($arResult = $items->GetNext()){       
            $arr_sections[] = array("NAME" => $arResult["NAME"], "ID" => $arResult["ID"]);
        }

        return $arr_sections;
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
            $VALUES[] = $ob['VALUE'];
        }
        return $VALUES;
    }

    function paramsUser($arParams){        
        $arResult["INFOBLOCK_ID"]       = $arParams["IBLOCK_ID"];
        $arResult["INFOBLOCK_C_ID"]     = $arParams["IBLOCK_ID_CONTRACT"];
        $arResult["SECTION_ID"]         = $arParams["SECTION_ID"];
        $arResult["ELEMENT_ID"]         = $arParams["ELEMENT_ID"];
        
        return $arResult;
    }    

    public function executeComponent()
    {
        if($this->startResultCache())
        {
            global $USER;
            $this->arResult                         = array_merge($this->arResult, $this->paramsUser($this->arParams));
            $this->arResult["USER_ID"]              = CUser::GetID();           
            // данные владельца сделки           
            $UserContractHolder                     = CUser::GetByID(CUser::GetID());
            $arrUserContractHolder                  = $UserContractHolder->Fetch();
            $this->USER_PROPERTY                    = $arrUserContractHolder;
            $this->arResult["USER_PROP"]            = $arrUserContractHolder;
            $this->arResult["USER_LOGIN"]           = CUser::GetLogin();

            $this->arResult["ELEMENT"]              = $this->getElement($this->arResult["ELEMENT_ID"]);
            $this->arResult["PROPERTY"]             = $this->getProperty($this->arResult["INFOBLOCK_ID"], $this->arResult["ELEMENT_ID"]);
            $this->arResult["LIST_CATEGORY"]        = $this->getListCategory();
            $this->arResult["CONTRACT_PROPERTY"]    = $this->getPropertyContract($this->arResult["INFOBLOCK_C_ID"]);
            $this->arResult["THREE_TEMPLATE"]       = $this->getTemplateContractCategote();

            if(!empty($this->ID_CONTRACT)){                
                $this->arResult["TEMPLATE_CONTENT"] = $this->getElement($this->ID_CONTRACT);
                $this->arResult["TEMPLATE_CONTENT_PROPERTY"]    = $this->getMultyProperty(5, $_GET["ID_TEMPLATE"]);
            }

            if(!empty($_GET["ID_TEMPLATE"])){                
                $this->arResult["TEMPLATE_CONTENT"] = $this->getElement($_GET["ID_TEMPLATE"]);
                $this->arResult["TEMPLATE_CONTENT_PROPERTY"]    = $this->getMultyProperty(5, $_GET["ID_TEMPLATE"]);
            }

            $this->includeComponentTemplate();
        }
        
        return $this->arResult;
    }
};

?>