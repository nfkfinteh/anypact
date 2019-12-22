<?php define("PREFIX_PATH_404", "/404.php");
include_once 'function.php';

AddEventHandler("main", "OnAfterEpilog", "Prefix_FunctionName");
//AddEventHandler("main", "OnAfterUserLogin", "OnAfterUserLoginHandler");

function Prefix_FunctionName() {
    global $APPLICATION;

    // Check if we need to show the content of the 404 page
    if (!defined('ERROR_404') || ERROR_404 != 'Y') {
        return;
    }

    // Display the 404 page unless it is already being displayed
    if ($APPLICATION->GetCurPage() != PREFIX_PATH_404) {
        header('X-Accel-Redirect: '.PREFIX_PATH_404);
        exit();
    }
}

/*function OnAfterUserLoginHandler(&$fields){
    if(\CModule::IncludeModule('iblock') && !empty($fields['USER_ID'])){
        //получаем компании для пол
        $arFilter = [
            'IBLOCK_ID'=>8,
            'ACTIVE'=>'Y',
            [
                "LOGIC" => "OR",
                ["PROPERTY_DIRECTOR_ID" => $fields['USER_ID']],
                ["PROPERTY_STAFF" => $fields['USER_ID']],
            ],
        ];
        $res = \CIBlockElement::GetList([], $arFilter, false, false, ['IBLOCK_ID', 'ID', 'PROPERTY_DIRECTOR_ID', 'PROPERTY_STAFF']);
        $cntCompany = $res->SelectedRowsCount();

        if($cntCompany>0){
            LocalRedirect("/profile/select_company/");
        }

    }
}*/