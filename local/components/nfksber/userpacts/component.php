<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arDefaultUrlTemplates404 = array(
    "list" => "",
    "element" => "#ELEMENT_ID#/"
);

$arDefaultVariableAliases404 = array();
$arDefaultVariableAliases = array();
$arComponentVariables = array("ELEMENT_ID");

    $arVariables = array();

    $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases , $arParams["VARIABLE_ALIASES"]);
    CComponentEngine::InitComponentVariables(false , $arComponentVariables , $arVariableAliases ,  $arVariables);

    $componentPage = "";
    if (IntVal($arVariables["ELEMENT_ID"]) > 0){
        $componentPage = "detail";
    }elseif(IntVal($arVariables["ELEMENT_ID"]) > 0 && $arVariables["EDIT"]=="Y"){
        $componentPage = "edit_pact";
    }else{
        $componentPage = "list";
    }



$arResult = array(
    "FOLDER" => $SEF_FOLDER,
    "URL_TEMPLATES" => $arUrlTemplates,
    "VARIABLES" => $arVariables,
    "ALIASES" => $arVariableAliases
);


$this->includeComponentTemplate($componentPage);